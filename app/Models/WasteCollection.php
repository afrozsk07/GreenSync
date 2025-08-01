<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCollection extends Model
{
    use HasFactory;

    protected $table = 'collections';

    protected $fillable = [
        'user_id',
        'request_id',
        'waste_type',
        'quantity',
        'pickup_date',
        'pickup_time',
        'address',
        'status',
        'vehicle_id',
        'driver_id',
        'collection_notes',
        'actual_pickup_time',
        'completion_time'
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'pickup_time' => 'datetime',
        'actual_pickup_time' => 'datetime',
        'completion_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function request()
    {
        return $this->belongsTo(WasteRequest::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function transportation()
    {
        return $this->hasOne(Transportation::class, 'collection_id');
    }
} 