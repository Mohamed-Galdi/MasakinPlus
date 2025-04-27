<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentRequest extends Model
{
    protected $fillable = [
        'property_id',
        'owner_id',
        'suggested_investment_amount',
        'suggested_daily_rent_price',
        'owner_note',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'status' => \App\Enums\InvestmentRequestStatus::class,
    ];

    // Relationships

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function owner()
    {
        return $this->hasOneThrough(
            User::class,         // Final model
            Property::class,     // Intermediate model
            'id',                // Foreign key on Property (property.id)
            'id',                // Foreign key on User (users.id)
            'property_id',       // Local key on InvestmentRequest
            'owner_id'           // Local key on Property
        );
    }
}
