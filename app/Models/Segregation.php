<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segregation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waste_type',
        'category_id',
        'quantity',
        'description',
        'accuracy',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(WasteCategory::class);
    }
} 