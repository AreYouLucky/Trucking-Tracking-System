<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserLocationUpdated;
use App\Models\Location;

class LocationChangeNewController extends Controller
{
    public function updateLocation(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $userId = $request->id;

        broadcast(new UserLocationUpdated($latitude, $longitude, $userId));

        Location::create([
            'delivery_id' => $request->input('id'),
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return response()->json(['status' => 'Location updated successfully']);
    }
}
