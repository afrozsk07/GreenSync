<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'handling_instructions',
        'is_hazardous',
        'segregation_requirements'
    ];

    public function wasteCollections()
    {
        return $this->hasMany(WasteCollection::class);
    }


} 