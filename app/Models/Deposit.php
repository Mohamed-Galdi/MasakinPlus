<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = ['wallet_id', 'amount', 'status', 'payment_gateway', 'gateway_reference'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
