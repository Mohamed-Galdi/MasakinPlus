<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function index()
    {
        return inertia('Investor/Investments/index');
    }

    public function invest(Request $request, InvestmentService $investmentService)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'property_id' => 'required|uuid|exists:properties,id',
        ]);

        $user = Auth::user();

        try {
            $investment = $investmentService->createInvestment(
                user: $user,
                propertyId: $validated['property_id'],
                amount: $validated['amount']
            );

            return redirect()->route('dashboard')->with('success', 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['amount' => $e->getMessage()]);
        }
    }
}
