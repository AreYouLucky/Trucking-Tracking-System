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
    public function store(Request $req){
        $req->validate([
            'customer_id'=>['required'],
            'vehicle_id'=>['required'],
            'driver_id'=>['required'],
            'fromLat'=>['required','numeric'],
            'fromLng'=>['required','numeric'],
            'toLat'=>['required','numeric'],
            'toLng'=>['required','numeric'],
            'reciever'=>['required'],
            'date'=>['required'],
            'time'=>['required'],
            'reciever_no'=>['required','regex:/^(09|\+639)\d{9}$/']
        ]);

        Delivery::create([
            'customer_id'=> $req->customer_id,
            'vehicle_id'=> $req->vehicle_id,
            'driver_id'=> $req->driver_id,
            'from_lat'=> $req->fromLat,
            'from_long'=> $req->fromLng,
            'to_lat'=> $req->toLat,
            'to_long'=> $req->toLng,
            'reciever_name'=>$req->reciever,
            'reciever_no'=>$req->reciever_no,
            'date'=>$req->date,
            'time'=>$req->time
        ]);

        Driver::where('driver_id',$req->driver_id)
        ->update([
            'is_available'=>0
        ]);

        Vehicle::where('vehicle_id',$req->vehicle_id)
        ->update([
            'is_available'=>0,
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
        
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


    public function loadCustomers(){
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

    public function loadVehicles(){
        return Vehicle::where('is_available',1)->get();
    }
    public function loadDeliveries(){
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


}
