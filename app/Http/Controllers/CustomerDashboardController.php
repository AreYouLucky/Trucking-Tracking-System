<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function getCustomer(){
        return Auth::user()->customer()->first();
    }

    public function customerDeliveries(){
        $customer_id = Auth::user()->customer()->first();
        return DB::select("
        SELECT 
            d.delivery_id,
            d.is_delivered,
            dr.lname,
            dr.fname,
            dr.contact_no,
            d.reciever_name,
            d.reciever_no,
            d.time,
            d.date,
            d.driver_id,
            d.updated_at
        FROM 
            deliveries d
        LEFT JOIN 
            drivers dr ON dr.driver_id = d.driver_id
        WHERE
            d.customer_id = $customer_id->customer_id
        ");
    }
}
