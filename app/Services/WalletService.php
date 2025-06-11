<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function deposit(Wallet $wallet, float $amount, $source = null, string $description = ''): Transaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        return DB::transaction(function () use ($wallet, $amount, $source, $description) {
            $wallet->increment('balance', $amount);

            return $wallet->transactions()->create([
                'type' => 'deposit',
                'amount' => $amount,
                'status' => 'completed',
                'description' => $description,
                'source_type' => optional($source)->getMorphClass(),
                'source_id' => optional($source)->id,
            ]);
        });
    }

    public function withdraw(Wallet $wallet, float $amount, $source = null, string $description = ''): Transaction
    {
        if ($wallet->balance < $amount) {
            throw new \Exception("Insufficient balance");
        }

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        return DB::transaction(function () use ($wallet, $amount, $source, $description) {
            $wallet->decrement('balance', $amount);

            return $wallet->transactions()->create([
                'type' => 'withdrawal',
                'amount' => -$amount,
                'status' => 'completed',
                'description' => $description,
                'source_type' => optional($source)->getMorphClass(),
                'source_id' => optional($source)->id,
            ]);
        });
    }

    public function credit(Wallet $wallet, float $amount, $type = 'income_share', $source = null, string $description = ''): Transaction
    {
        return $this->deposit($wallet, $amount, $source, $description);
    }

    public function debit(Wallet $wallet, float $amount, $type = 'investment', $source = null, string $description = ''): Transaction
    {
        return DB::transaction(function () use ($wallet, $amount, $type, $source, $description) {
            if ($wallet->balance < $amount) {
                throw new \Exception("Insufficient balance");
            }

            $wallet->decrement('balance', $amount);

            return $wallet->transactions()->create([
                'type' => $type,
                'amount' => -$amount,
                'status' => 'completed',
                'description' => $description,
                'source_type' => optional($source)->getMorphClass(),
                'source_id' => optional($source)->id,
            ]);
        });
    }
}
