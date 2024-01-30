<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\Delivery;

class DriverDashboardController extends Controller
{
    public function getDriver(){
        return Auth::user()->driver()->first();
    }
    public function getLocation(){
        $driver_id = Auth::user()->driver()->first();
        return Delivery::where('driver_id',$driver_id->driver_id)
        ->where('is_delivered','!=','2')
        ->latest()
        ->first();
    }
    public function startDelivery($id){
        Driver::where('delivery_id',$id)
        ->update([
            'is_delivered' => 2
        ]);
    }
}
