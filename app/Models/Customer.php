<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'user_id',
        'lname',
        'fname',
        'mname',
        'suffix',
        'sex',
        'contact_no',
        'email',
        'province',
        'city',
        'barangay',
        'street'
    ];
}
