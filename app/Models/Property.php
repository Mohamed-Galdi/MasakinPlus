<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Property extends Model
{
    use HasUuids;
    
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
        'latitude',
        'longitude',
        'daily_rent_price',
        'status',
    ];

    protected $casts = [
        'status' => PropertyStatus::class,
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
