<?php

namespace App\Http\Controllers;
use App\Models\Delivery;

use Illuminate\Http\Request;

class getLocation extends Controller
{
    public function showID($id){

            return view('admin.delivery');
    }

    public function getLocation($id){
        return Delivery::where('delivery_id',$id)->get();
    }



}
