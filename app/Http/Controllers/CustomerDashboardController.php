<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function getCustomer(){
        return Customer::where('user_id', Auth::user()->user_id)->first();
    }

    public function customerDeliveries()
    {
        $id =Customer::select('customer_id')->where('user_id',Auth::user()->user_id)->first();
    
        return DB::table('deliveries as d')
            ->leftJoin('drivers as dr', 'dr.driver_id', '=', 'd.driver_id')
            ->select(
                'd.delivery_id',
                'd.is_delivered',
                'dr.lname',
                'dr.fname',
                'dr.contact_no',
                'd.reciever_name',
                'd.reciever_no',
                'd.time',
                'd.date',
                'd.driver_id',
                'd.updated_at'
            )
            ->where('d.customer_id', $id->customer_id)
            ->get();
    }
    
}
