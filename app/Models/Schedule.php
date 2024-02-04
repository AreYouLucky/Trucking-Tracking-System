<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'driver_id', 'schedule_date'
    ];
}
