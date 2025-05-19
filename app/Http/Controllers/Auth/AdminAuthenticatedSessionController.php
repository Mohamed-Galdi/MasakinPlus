<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AdminAuthenticatedSessionController extends Controller
{
    public function adminLogin()
    {
        return Inertia::render('Auth/AdminLogin');
    }

    public function adminOTP()
    {
        return Inertia::render('Auth/AdminOTP');
    }
}
