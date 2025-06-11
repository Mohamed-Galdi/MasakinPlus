<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasUuids;

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'type',
        'status',
        'city',
        'address',
        'area',
        'bedrooms',
        'bathrooms',
        'latitude',
        'longitude',
        'nightly_rent',
        'investment_required',
        'property_valuation',
        'owner_share',
        'investor_share',
        'platform_fee_share',
        'monthly_operating_cost',
    ];

    protected $casts = [
        'status' => PropertyStatus::class,
        'latitude' => 'float',
        'longitude' => 'float',
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

    public function investmentRequest()
    {
        return $this->hasOne(InvestmentRequest::class);
    }

    public function investmentOffer()
    {
        return $this->hasOne(InvestmentOffer::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
