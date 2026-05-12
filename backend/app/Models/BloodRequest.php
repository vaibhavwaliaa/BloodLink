<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BloodRequest extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bloodrequests';

    protected $fillable = [
        'requester_id',
        'patient_name',
        'blood_type',
        'hospital_name',
        'city',
        'units_required',
        'urgency_level',
        'contact_number',
        'latitude',
        'longitude',
        'location',
        'status',
    ];

    protected $casts = [
        'units_required' => 'integer',
    ];

    protected $attributes = [
        'status' => 'PENDING',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
}
