<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relationship with properties
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'amenity_property', 'amenity_id', 'property_id')
            ->withTimestamps();
    }
}
