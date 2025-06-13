<?php

namespace App\Http\Controllers\Common;

use App\Enums\DepositStatus;
use App\Enums\TxStatus;
use App\Enums\TxType;
use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $wallet = Auth::user()->wallet;

        // Simulated callback from Moyasar
        if ($request->has('id') && $request->has('status')) {
            // Simulated callback from Moyasar
            $status = $request->input('status');
            $message = $request->input('message');

            // Only process if payment is successful
            if ($status === 'paid' && $message === 'APPROVED') {
                $data = [
                    'gateway_reference' => $request->input('id'),
                    'amount' => $request->input('amount') / 100,
                    'payment_gateway' => 'moyasar',
                ];


                $walletService = new WalletService();

                // Create successful deposit
                $walletService->deposit(
                    $wallet,
                    $data['amount'],
                    [
                        'gateway_reference' => $data['gateway_reference'],
                        'status' => DepositStatus::Succeeded->value, // Always completed since we validated
                        'payment_gateway' => $data['payment_gateway'],
                    ],
                    "إداع في المحفظة عبر بطاقة بنكية"
                );

                return redirect()->route('wallet.index')->with('success', 'تم شحن المحفظة بنجاح');
            } else {
                // Handle failed payment - you might want to log this or create a failed deposit record
                return redirect()->route('wallet.index')->with('error', 'فشل في شحن المحفظة');
            }
        }

        // Get paginated transactions
        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Adjust per page as needed

        $transactions->getCollection()->transform(function ($transaction) {
            $transaction->type_label = TxType::from($transaction->type)->label();
            $transaction->type_icon = TxType::from($transaction->type)->icon();
            $transaction->type_color = TxType::from($transaction->type)->color();
            $transaction->type_sign = TxType::from($transaction->type)->sign();
            $transaction->status_label = TxStatus::from($transaction->status)->label();
            $transaction->status_severity = TxStatus::from($transaction->status)->severity();
            return $transaction;
        });

        // Calculate money in and money out
        $moneyIn = $wallet->transactions()
            ->whereIn('type', [TxType::Deposit->value])
            ->where('status', TxStatus::Completed->value)
            ->sum('amount');

        $moneyOut = $wallet->transactions()
            ->whereIn('type', [TxType::Withdrawal->value, TxType::Investment->value])
            ->where('status', TxStatus::Completed->value)
            ->sum(DB::raw('ABS(amount)')); 

        return inertia('Common/wallet', [
            'wallet' => $wallet,
            'transactions' => $transactions,
            'money_in' => $moneyIn,
            'money_out' => $moneyOut,

        ]);
    }
}
