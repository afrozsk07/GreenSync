<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_number',
        'type',
        'capacity',
        'current_status',
        'last_maintenance_date',
        'next_maintenance_due',
        'fuel_efficiency',
        'registration_number',
        'insurance_expiry'
    ];

    public function transportations()
    {
        return $this->hasMany(Transportation::class);
    }
} 