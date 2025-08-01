<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waste_type',
        'quantity',
        'pickup_date',
        'pickup_time',
        'address',
        'description',
        'status',
        'priority',
        'special_instructions'
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'pickup_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collection()
    {
        return $this->hasOne(WasteCollection::class);
    }
}
