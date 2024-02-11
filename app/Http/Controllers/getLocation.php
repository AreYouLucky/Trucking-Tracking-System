<?php

namespace App\Http\Controllers;
use App\Models\Delivery;
use App\Models\Location;
use Illuminate\Http\Request;

class getLocation extends Controller
{
    public function showID($id){

            return view('Admin.delivery');
    }

    public function getLocation($id){
        return Delivery::where('delivery_id',$id)->get();
    }

    public function getDriverLocation($id){

        return view('Admin.driverlocation');
    }
    public function getDriverLocationData($id){
        return Delivery::where('driver_id',$id)
        ->latest()
        ->first();
    }
    public function proof($id){

        return view('Admin.proof');
    }
    public function getProof($id){
        return Delivery::where('delivery_id',$id)
        ->first();
    }

    public function route($id){

        return view('Admin.route');
    }
    public function getRoute($id){
        return Location::where('delivery_id',$id)->get();
    }




}
