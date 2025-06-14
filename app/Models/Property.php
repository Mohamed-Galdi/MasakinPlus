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

    protected $appends = [
        'total_funded',
        'remaining_investment',
        'expected_monthly_income',
        'investors_monthly_share',
        'percentage_funded',
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
    
    // Total funded amount from investments
    public function getTotalFundedAttribute(): float
    {
        return (float) $this->investments()->sum('amount');
    }

    // Remaining amount to reach required investment
    public function getRemainingInvestmentAttribute(): float
    {
        return max(0, $this->investment_required - $this->total_funded);
    }

    public function getPercentageFundedAttribute(): float
    {
        return ($this->total_funded / $this->investment_required) * 100;
    }

    // Expected monthly rental income (24–29 nights * nightly rent)
    public function getExpectedMonthlyIncomeAttribute(): float
    {
        return $this->nightly_rent ? $this->nightly_rent * $this->expected_occupancy : 0;
    }

    // Investors' expected monthly share from rent
    public function getInvestorsMonthlyShareAttribute(): float
    {
        return round($this->expected_monthly_income * $this->investor_share, 2);
    }
}
