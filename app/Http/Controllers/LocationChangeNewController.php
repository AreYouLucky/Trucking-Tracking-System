<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserLocationUpdated;
use App\Models\Location;

class LocationChangeNewController extends Controller
{
    public function updateLocation(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $id = $request->input('id');

        $userId = auth()->user()->id;

        broadcast(new UserLocationUpdated($latitude, $longitude, $userId));

        Location::create([
            'delivery_id' => $id,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return response()->json(['status' => 'Location updated successfully']);
    }
}
