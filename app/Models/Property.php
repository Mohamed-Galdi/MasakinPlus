<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'type',
        'city',
        'address',
        'area',
        'bedrooms',
        'bathrooms',
        'is_furnished',
        'daily_rent_price',
        'status',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_property', 'property_id', 'amenity_id')
            ->withTimestamps();
    }
}
