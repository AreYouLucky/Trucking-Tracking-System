<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Vehicle;
use App\Models\Driver;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function smsCustomer($id){
        $fetch_data = Delivery::where('delivery_id',$id)->first();
        $vehicle = Vehicle::where('vehicle_id',$fetch_data->vehicle_id)->first();
        $customer = Customer::where('customer_id',$fetch_data->customer_id)->first();
        $driver = Driver::where('driver_id',$fetch_data->driver_id)->first();
        $ch = curl_init();
        $apiKey =  env('SMS');
        $parameters = array(
            'apikey' => $apiKey,
            'number' => $customer->contact_no,
            'message' => 'Hello Mr/Mrs. '.$customer->lname.'! A '.$vehicle->name.' is coming to pickup your package. Driver: '.$driver->lname.' '.$driver->contact_no,
            'sendername' => 'LARATSYS'
        );
        curl_setopt( $ch, CURLOPT_URL,'https://semaphore.co/api/v4/priority' );
        curl_setopt( $ch, CURLOPT_POST, 1 );

        //Send the parameters set above with the request
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );

        // Receive response from server
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $output = curl_exec( $ch );
        curl_close ($ch);

        return $output;
    }
    
    public function smsReciever($id){
        $fetch_data = Delivery::where('delivery_id',$id)->first();
        $vehicle = Vehicle::where('vehicle_id',$fetch_data->vehicle_id)->first();
        $customer = Customer::where('customer_id',$fetch_data->customer_id)->first();
        $driver = Driver::where('driver_id',$fetch_data->driver_id)->first();
        $ch = curl_init();
        $apiKey =  env('SMS');
        $parameters = array(
            'apikey' => $apiKey,
            'number' => $fetch_data->reciever_no,
            'message' => 'Hello Mr/Mrs. '.$fetch_data->reciever_name.'! A '.$vehicle->name.' is coming to deliver a package from '.'Mr/Mrs. '.$customer->lname.'. Driver: '.$driver->lname.' '.$driver->contact_no,
            'sendername' => 'LARATSYS'
        );
        curl_setopt( $ch, CURLOPT_URL,'https://semaphore.co/api/v4/priority' );
        curl_setopt( $ch, CURLOPT_POST, 1 );

        //Send the parameters set above with the request
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );

        // Receive response from server
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $output = curl_exec( $ch );
        curl_close ($ch);

        return $output;
    }
}
