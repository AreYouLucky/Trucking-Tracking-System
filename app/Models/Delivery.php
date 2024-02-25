<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $table = 'deliveries';
    protected $primaryKey = 'delivery_id';
    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'driver_id',
        'is_delivered',
        'from_lat',
        'from_long',
        'to_lat',
        'to_long',
        'reciever_name',
        'reciever_no',
        'date',
        'time',
        'proof_path'
    ];

    public function driver(){
        return $this->hasOne(Driver::class,'driver_id','driver_id');
    }
    public function vehicle(){
        return $this->hasOne(Vehicle::class,'driver_id','driver_id');
    }
}
