<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;

class InvestmentController extends Controller
{
    public function index()
    {
        return inertia('Investor/Investments/index');
    }
}
