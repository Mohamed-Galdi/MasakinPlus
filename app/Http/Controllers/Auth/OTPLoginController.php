<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OTPLoginController extends Controller
{
    public function showOTPForm()
    {
        return inertia('Auth/OTP');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::find(session('otp_user_id'));

        if (!$user || $user->otp_code !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'الرمز المدخل غير صحيح أو غير صالح.']);
        }

        // Clear OTP fields
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Log in and regenerate session (like Breeze)
        Auth::login($user);
        $request->session()->regenerate();

        session()->forget('otp_user_id');

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
