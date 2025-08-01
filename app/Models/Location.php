<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'latitude',
        'longitude',
        'location_type',
        'contact_person',
        'contact_phone',
        'collection_frequency'
    ];

    public function wasteCollections()
    {
        return $this->hasMany(WasteCollection::class);
    }
} 