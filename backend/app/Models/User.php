<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class User extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'blood_group',
        'city',
        'contact_number',
        'is_donor',
        'is_available',
        'last_donation_date',
        'location',
    ];

    protected $casts = [
        'is_donor' => 'boolean',
        'is_available' => 'boolean',
        'last_donation_date' => 'datetime',
    ];

    protected $attributes = [
        'is_donor' => false,
        'is_available' => true,
    ];
}
