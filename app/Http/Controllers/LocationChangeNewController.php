<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserLocationUpdated;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;

class LocationChangeNewController extends Controller
{
    public function updateLocation(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $userId = Auth::user()->user_id;

        event(new UserLocationUpdated($latitude, $longitude, $userId));

        Location::create([
            'delivery_id' => $request->id,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return response()->json(['status' => 'Location updated successfully']);
    }
}
