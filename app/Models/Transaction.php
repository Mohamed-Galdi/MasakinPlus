<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['wallet_id', 'type', 'amount', 'status', 'transactionable_type', 'transactionable_id', 'description'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactionable()
    {
        return $this->morphTo();
    }
}
