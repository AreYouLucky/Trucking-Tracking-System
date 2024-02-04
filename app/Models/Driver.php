<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';
    protected $primarykey = 'driver_id';
    protected $fillable = [
        'user_id',
        'lname',
        'fname',
        'mname',
        'suffix',
        'sex',
        'contact_no',
        'license_id',
        'province',
        'city',
        'barangay',
        'street',
        'is_available'
    ];

    public function schedule(){
        return $this->hasMany(Schedule::class,'driver_id','driver_id');
    }

}
