<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OTPLoginController extends Controller
{
    public function showOTPForm()
    {
        return inertia('Auth/OTP');
    }

    public function showPhoneOTPForm()
    {
        $phone = session('phone');
        return inertia('Auth/PhoneOTP', ['phone' => $phone]);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::find(session('otp_user_id'));

        if (! $user || $user->otp_code !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'الرمز المدخل غير صحيح أو غير صالح.']);
        }

        // Clear OTP fields
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->phone_verified_at = now();
        $user->save();

        // Log in and regenerate session (like Breeze)
        Auth::login($user);
        $request->session()->regenerate();

        session()->forget('otp_user_id');
        session()->forget('phone');

        return redirect()->intended(route('dashboard', absolute: false));
    }

   

    
}
