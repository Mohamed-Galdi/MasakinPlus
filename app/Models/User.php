<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the user's image or default image if null.
     */
    public function getImageAttribute($value)
    {
        return $value ?? '/storage/users_images/default-user-image.webp';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
        'image',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ownedProperties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function investmentRequests()
    {
        return $this->hasManyThrough(
            InvestmentRequest::class,
            Property::class,
            'owner_id',        // Foreign key on Property
            'property_id',     // Foreign key on InvestmentRequest
            'id',              // Local key on User
            'id'               // Local key on Property
        );
    }

    /**
     * Properties the user has invested in (for investors).
     */
    // public function investedProperties()
    // {
    //     return $this->belongsToMany(Property::class, 'property_investor', 'investor_id', 'property_id')
    //         ->withPivot('investment_amount', 'share_percentage')
    //         ->withTimestamps();
    // }

    /**
     * Properties the user is renting (for tenants).
     */
    // public function rentedProperties()
    // {
    //     return $this->belongsToMany(Property::class, 'property_tenant', 'tenant_id', 'property_id')
    //         ->withPivot('lease_start', 'lease_end', 'monthly_rent')
    //         ->withTimestamps();
    // }
}
