<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Delivery;
use App\Models\Vehicle;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboard(){
        return $dashboard =[
        'totalCustomer' => Customer::count(),
        'totalDriver' => Driver::count(),
        'totalDelivery' => Delivery::count(),
        'totalVehicle' => Vehicle::count(),
        'availableDriver' => Driver::where('is_available',1)->count(),
        'availableVehicle' => Vehicle::where('is_available',1)->count(),
        'unavailableDriver' => Driver::where('is_available',0)->count(),
        'pendingDelivery' => Delivery::where('is_delivered',0)->count(),
        'deliveringDelivery' => Delivery::where('is_delivered',1)
                                      ->orWhere('is_delivered',2)
                                      ->count(),
        'deliveredDelivery' => Delivery::where('is_delivered',3)->count(),
        'unavailableVehicle' => Vehicle::where('is_available',0)->count(),
        ];

    }
}
