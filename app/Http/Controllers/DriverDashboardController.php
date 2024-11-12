<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Location;

class DriverDashboardController extends Controller
{
    public function getDriver()
    {
        return Auth::user()->driver()->first();
    }
    public function getLocation()
    {
        $driver_id = Auth::user()->driver()->first();
        $delivery = Delivery::where('driver_id', $driver_id->driver_id)
            ->where('is_delivered', '!=', '3')
            ->latest()
            ->first();

        $to = DB::select('
        SELECT
        d.`to_street`,
        p.`provDesc`,
        b.`brgyDesc`,
        cit.`citymunDesc`

        FROM deliveries d
        INNER JOIN provinces p ON d.to_province = p.provCode
        LEFT JOIN barangays b ON d.to_barangay = b.brgyCode
        LEFT JOIN cities cit ON d.to_city = cit.citymunCode
        where d.delivery_id = ' . $delivery->delivery_id . '
       ;');

        $from = DB::select('
        SELECT
        d.`from_street`,
        p.`provDesc`,
        b.`brgyDesc`,
        cit.`citymunDesc`

        FROM deliveries d
        INNER JOIN provinces p ON d.from_province = p.provCode
        LEFT JOIN barangays b ON d.from_barangay = b.brgyCode
        LEFT JOIN cities cit ON d.from_city = cit.citymunCode
        where d.delivery_id = ' . $delivery->delivery_id . '
       ;');

        return [
            'delivery' => $delivery,
            'to' => $to,
            'from' => $from
        ];
    }
    public function startDelivery($id)
    {
        Delivery::where('delivery_id', $id)
            ->update([
                'is_delivered' => 1
            ]);
        return response()->json([
            'status' => 'updated'
        ], 200);
    }
    public function midDelivery($id)
    {
        Delivery::where('delivery_id', $id)
            ->update([
                'is_delivered' => 2
            ]);
        return response()->json([
            'status' => 'updated'
        ], 200);
    }
    public function finDelivery($id)
    {
        Cache::flush();
        Delivery::where('delivery_id', $id)
            ->update([
                'is_delivered' => 3
            ]);

        return response()->json([
            'status' => 'updated'
        ], 200);
    }
    public function confirmDelivery($id)
    {
        Delivery::where('delivery_id', $id)
            ->update([
                'is_delivered' => 4
            ]);
        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function storeProof(Request $req)
    {
        Driver::where('driver_id', $req->driver_id)
            ->update([
                'is_available' => 1
            ]);
        Vehicle::where('vehicle_id', $req->vehicle_id)
            ->update([
                'is_available' => 1
            ]);
        $req->validate([
            'vehicle_id' => ['required'],
            'driver_id' => ['required'],
            'id' => ['required'],
            'image' => ['required', 'mimes:jpeg,jpg,png,gif']
        ]);

        $file = $req->file('image');

        $file_location = '';

        if ($file) {
            $filePath = $file->store('public/proofs'); //get path of the file
            $filePathArray = explode('/', $filePath); //split into array using /
            $file_location = $filePathArray[2];
        }
        Delivery::where('delivery_id', $req->id)
            ->update([
                'proof_path' => $file_location,
            ]);

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function updateDriverloc(Request $req)
    {
        $req->validate([
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        Delivery::where('delivery_id', $req->id)
            ->update([
                'driver_lat' => $req->latitude,
                'driver_long' => $req->longitude,
            ]);
        return response()->json([
            'status' => 'driver location updated!'
        ], 200);
    }

    public function cacheDelivery(Request $req)
    {
        $req->validate([
            'latitude' => 'required',
            'longitude' => 'required'
        ]);


        $latitude = Cache::get('latitude');

        if (!is_array($latitude) && $latitude !== null) {

            $latitude = [$latitude];
        } elseif ($latitude === null) {
            $latitude = [];
        }

        $latitude[] = $req->latitude;
        Cache::forever('latitude', $latitude);


        $longitude = Cache::get('longitude');
        if (!is_array($longitude) && $longitude !== null) {
            $longitude = [$longitude];
        } elseif ($longitude === null) {
            $longitude = [];
        }
        $longitude[] = $req->longitude;
        Cache::forever('longitude', $longitude);


        return response()->json([
            'status' => 'Location cached successfully'
        ], 200);
    }
    public function saveRoute($id)
    {
        $latitude = Cache::get('latitude');
        $longitude = Cache::get('longitude');

        $count = count($latitude);
        for ($i = 0; $i < $count; $i++) {
            Location::create([
                'delivery_id' => $id,
                'latitude' => $latitude[$i],
                'longitude' => $longitude[$i]
            ]);
        }
        return response()->json([
            'status' => 'Route Successfully Saved!'
        ], 200);
    }

    public function driverDeliveries()
    {
        $driver_id = Auth::user()->driver()->first();
        return DB::select("
        SELECT
            d.delivery_id,
            d.is_delivered,
            c.lname,
            c.fname,
            d.reciever_name,
            d.reciever_no,
            d.time,
            d.date,
            d.delivery_weight,
            d.delivery_info
        FROM
            deliveries d
        LEFT JOIN
            customers c ON c.customer_id = d.customer_id
        WHERE
            d.driver_id = $driver_id->driver_id
        ");
    }
}
