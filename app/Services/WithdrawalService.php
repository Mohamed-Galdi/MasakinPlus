<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WithdrawalService
{
    public function requestWithdrawal(User $user, float $amount, string $method, array $details = []): WithdrawalRequest
    {
        $wallet = $user->wallet;

        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'Amount must be greater than zero.',
            ]);
        }

        if ($wallet->balance < $amount) {
            throw ValidationException::withMessages([
                'amount' => 'Insufficient wallet balance.',
            ]);
        }

        return DB::transaction(function () use ($user, $wallet, $amount, $method, $details) {
            $wallet->decrement('balance', $amount);

            return WithdrawalRequest::create([
                'user_id'   => $user->id,
                'wallet_id' => $wallet->id,
                'amount'    => $amount,
                'method'    => $method,
                'details'   => $details,
                'status'    => 'pending',
            ]);
        });
    }

    public function markAsProcessed(WithdrawalRequest $request, string $status = 'completed', ?string $note = null): void
    {
        $request->update([
            'status' => $status,
            'processed_at' => now(),
            'admin_note' => $note,
        ]);
    }
}
