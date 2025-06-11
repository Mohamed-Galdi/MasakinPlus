<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = ['user_id', 'wallet_id', 'property_id', 'amount', 'status', 'invested_at'];

    protected $dates = ['invested_at'];

    protected $casts = [
        'amount'      => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'source');
    }
}
