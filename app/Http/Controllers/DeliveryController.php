<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Driver;

class DeliveryController extends Controller
{
    public function store(Request $req)
    {
        $req->validate([
            'customer_id' => ['required'],
            'vehicle_id' => ['required'],
            'driver_id' => ['required'],
            'fromLat' => ['required', 'numeric'],
            'fromLng' => ['required', 'numeric'],
            'toLat' => ['required', 'numeric'],
            'toLng' => ['required', 'numeric'],
            'reciever' => ['required'],
            'date' => ['required'],
            'time' => ['required'],
            'reciever_no' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'to_province' => ['required'],
            'to_city' => ['required'],
            'to_barangay' => ['required'],
            'to_street' => ['required'],
            'from_province' => ['required'],
            'from_city' => ['required'],
            'from_barangay' => ['required'],
            'from_street' => ['required'],
            'delivery_info'=> ['required'],
            'delivery_weight'=> ['required','numeric']

        ]);

        Delivery::create([
            'customer_id' => $req->customer_id,
            'vehicle_id' => $req->vehicle_id,
            'driver_id' => $req->driver_id,
            'from_lat' => $req->fromLat,
            'from_long' => $req->fromLng,
            'to_lat' => $req->toLat,
            'to_long' => $req->toLng,
            'reciever_name' => $req->reciever,
            'reciever_no' => $req->reciever_no,
            'date' => $req->date,
            'time' => $req->time,
            'delivery_info' => $req->delivery_info,
            'delivery_weight' => $req->delivery_weight,

            'to_province' => $req->to_province,
            'to_city' => $req->to_city,
            'to_barangay' => $req->to_barangay,
            'to_street' => $req->to_street,

            'from_province' => $req->from_province,
            'from_city' => $req->from_city,
            'from_barangay' => $req->from_barangay,
            'from_street' => $req->from_street,
        ]);

        Driver::where('driver_id', $req->driver_id)
            ->update([
                'is_available' => 0
            ]);

        Vehicle::where('vehicle_id', $req->vehicle_id)
            ->update([
                'is_available' => 0,
            ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function loadDrivers()
    {
        return DB::select("
            SELECT
                u.username,
                d.driver_id
            FROM
                users u
            INNER JOIN
                drivers d ON u.user_id = d.user_id
            WHERE
                d.is_available = 1 AND u.role = 'DRIVER'
        ");
    }


    public function loadCustomers()
    {
        return DB::select("
            SELECT
                u.username,
                c.customer_id
            FROM
                users u
            INNER JOIN
                customers c ON u.user_id = c.user_id
            WHERE
                u.role = 'CUSTOMER'
        ");
    }

    public function loadVehicles()
    {
        return Vehicle::where('is_available', 1)->get();
    }
    public function loadDeliveries()
    {
        return DB::select("
        SELECT
            d.delivery_id,
            d.is_delivered,
            dr.lname,
            u.username,
            d.reciever_name,
            d.reciever_no,
            d.time,
            d.date
        FROM
            deliveries d
        LEFT JOIN
            drivers dr ON dr.driver_id = d.driver_id
        LEFT JOIN
            customers c ON c.customer_id = d.customer_id
        LEFT JOIN
            users u ON u.user_id = c.user_id;

        ");
    }

    public function editDelivery($id)
    {
        $delivery = Delivery::where('delivery_id', $id)->first();
        $driver_id = Delivery::where('delivery_id', $id)->pluck('driver_id');
        $vehicle_id = Delivery::where('delivery_id', $id)->pluck('vehicle_id');

        Driver::where('driver_id', $driver_id)->update([
            'is_available' => 1,
        ]);
        Vehicle::where('vehicle_id', $vehicle_id)->update([
            'is_available' => 1,
        ]);
        return view('Admin.editDelivery')
            ->with('delivery', $delivery);
    }
    public function getDelivery($id)
    {
        return Delivery::where('delivery_id', $id)->first();
    }

    public function updateDelivery(Request $req)
    {
        $req->validate([
            'customer_id' => ['required'],
            'vehicle_id' => ['required'],
            'driver_id' => ['required'],
            'fromLat' => ['required', 'numeric'],
            'fromLng' => ['required', 'numeric'],
            'toLat' => ['required', 'numeric'],
            'toLng' => ['required', 'numeric'],
            'reciever' => ['required'],
            'date' => ['required'],
            'time' => ['required'],
            'reciever_no' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'delivery_info'=> ['required'],
            'delivery_weight'=> ['required', 'numeric']
        ]);

        Delivery::where('delivery_id',$req->delivery_id)
        ->update([
            'customer_id' => $req->customer_id,
            'vehicle_id' => $req->vehicle_id,
            'driver_id' => $req->driver_id,
            'from_lat' => $req->fromLat,
            'from_long' => $req->fromLng,
            'to_lat' => $req->toLat,
            'to_long' => $req->toLng,
            'reciever_name' => $req->reciever,
            'reciever_no' => $req->reciever_no,
            'date' => $req->date,
            'time' => $req->time,
            'delivery_info' => $req->delivery_info,
            'delivery_weight' => $req->delivery_weight,
        ]);

        Driver::where('driver_id', $req->driver_id)
            ->update([
                'is_available' => 0
            ]);

        Vehicle::where('vehicle_id', $req->vehicle_id)
            ->update([
                'is_available' => 0,
            ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
    public function deleteDelivery(Request $req)
    {
        foreach ($req->all() as $key => $id) {
            $driver_id = Delivery::where('delivery_id', $id)->pluck('driver_id');
            $vehicle_id = Delivery::where('delivery_id', $id)->pluck('vehicle_id');

            Driver::where('driver_id', $driver_id)->update([
                'is_available' => 1,
            ]);
            Vehicle::where('vehicle_id', $vehicle_id)->update([
                'is_available' => 1,
            ]);
            Delivery::destroy($id);
        }
        return response()->json([
            'status' => 'deleted'
        ], 200);

    }
    public function getDriver($id){
        return Driver::where('driver_id',$id)->first();
    }
    public function getVehicle($id){
        return Vehicle::where('vehicle_id',$id)->first();
    }
}
