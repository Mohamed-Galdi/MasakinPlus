<?php

namespace App\Http\Controllers\Common;

use App\Enums\DepositStatus;
use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WalletController extends Controller
{
    public function index(Request $request)
    {
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

                $wallet = Auth::user()->wallet;
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

        return inertia('Common/wallet');
    }
}
