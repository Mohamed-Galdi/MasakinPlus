<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class WalletController extends Controller
{
    public function index()
    {
        return inertia('Common/wallet');
    }
}
