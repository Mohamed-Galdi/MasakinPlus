<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['wallet_id', 'amount', 'status', 'payout_method', 'payout_details', 'admin_notes'];

    protected $casts = [
        'payout_details' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'source');
    }
}
