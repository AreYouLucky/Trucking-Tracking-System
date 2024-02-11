<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'location';
    protected $primarykey = 'location_id';
    protected $fillable = [
        'delivery_id',
        'latitude',
        'longitude'
    ];

}
