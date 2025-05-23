<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentOffer extends Model
{
    protected $fillable = [
        'property_id',
        'suggested_valuation',
        'suggested_investment_amount',
        'suggested_monthly_operating_cost',
        'suggested_nightly_rent',
        'owner_share',
        'investor_share',
        'platform_fee_share',
        'admin_note'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    
}
