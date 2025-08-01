<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'collection_id',
        'vehicle_id',
        'driver_id',
        'destination_id',
        'estimated_departure',
        'estimated_arrival',
        'actual_departure',
        'actual_arrival',
        'current_location',
        'latitude',
        'longitude',
        'status',
        'last_updated'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collection()
    {
        return $this->belongsTo(WasteCollection::class, 'collection_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function destination()
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }
} 