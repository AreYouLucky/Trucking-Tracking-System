<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Vehicle;

class DriverDashboardController extends Controller
{
    public function getDriver(){
        return Auth::user()->driver()->first();
    }
    public function getLocation(){
        $driver_id = Auth::user()->driver()->first();
        return Delivery::where('driver_id',$driver_id->driver_id)
        ->where('is_delivered','!=','3')
        ->latest()
        ->first();
    }
    public function startDelivery($id){
        Delivery::where('delivery_id',$id)
        ->update([
            'is_delivered' => 1
        ]);
        return response()->json([
            'status' => 'updated'
        ], 200);
    }
    public function midDelivery($id){
        Delivery::where('delivery_id',$id)
        ->update([
            'is_delivered' => 2
        ]);
        return response()->json([
            'status' => 'updated'
        ], 200);
    }
    public function finDelivery($id){
        Delivery::where('delivery_id',$id)
        ->update([
            'is_delivered' => 3
        ]);
        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function storeProof(Request $req){
        $req->validate([
            'vehicle_id'=>['required'],
            'driver_id'=>['required'],
            'id'=>['required'],
            'image'=>['required','mimes:jpeg,jpg,png,gif']
        ]);
        $file = $req->file('image');

        $file_location = '';

        if($file){
            $filePath = $file->store('public/proofs'); //get path of the file
            $filePathArray = explode('/', $filePath); //split into array using /
            $file_location = $filePathArray[2];
        }
        Delivery::where('delivery_id',$req->id)
        ->update([
            'proof_path' => $file_location,
        ]);

        Driver::where('driver_id',$req->driver_id)
        ->update([
            'is_available'=> 1
        ]);
        Vehicle::where('vehicle_id',$req->vehicle_id)
        ->update([
            'is_available'=> 1
        ]);

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function updateDriverloc(Request $req){
        $req->validate([
            'latitude'=>'required',
            'longitude'=>'required'
        ]);
        Delivery::where('delivery_id',$req->id)
        ->update([
            'driver_lat' => $req->latitude,
            'driver_long' => $req->longitude,
        ]);
        return response()->json([
            'status' => 'driver location updated!'
        ], 200);
    }

}
