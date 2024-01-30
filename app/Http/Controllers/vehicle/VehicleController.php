<?php

namespace App\Http\Controllers\vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function store(Request $req)
    {
        $req->validate([
            'name'=>['required'],
            'plate_no'=>['required','unique:vehicles'],
            'vehicle_type'=>['required'],
        ]);

        Vehicle::create([
            'name' => strtoupper($req->name),
            'plate_no' => strtoupper($req->plate_no),
            'vehicle_type' => strtoupper($req->vehicle_type),
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
    }

    public function show()
    {
        return Vehicle::all();
    }

    public function edit($id)
    {
        return $data = Vehicle::where('vehicle_id',$id)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        $req->validate([
            'vehicle_id'=>['required'],
            'name'=>['required'],
            'vehicle_type'=>['required'],
            'plate_no'=>['required','unique:vehicles,plate_no,'.$req->vehicle_id.',vehicle_id'], 
        ]);

        $user = Vehicle::where('vehicle_id',$req->vehicle_id)
        ->update([
            'name' =>  strtoupper($req->name),
            'vehicle_type' =>  strtoupper($req->vehicle_type),
            'plate_no' =>  strtoupper($req->plate_no),
        ]);
        return response()->json([
            'status' => 'saved'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Vehicle::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
