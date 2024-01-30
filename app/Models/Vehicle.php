<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $table = 'vehicles';
    protected $primaryKey = 'vehicle_id';
    protected $fillable = [
        'name',
        'vehicle_type',
        'plate_no',
        'is_available'
    ];
}
