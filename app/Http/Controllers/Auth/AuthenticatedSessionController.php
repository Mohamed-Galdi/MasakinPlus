<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OTP;
use App\Models\User;
use App\Notifications\PhoneVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);

        // phone login
        // return Inertia::render('Auth/PhoneLogin', [
        //     'canResetPassword' => Route::has('password.request'),
        //     'status' => session('status'),
        // ]);
    }

    // ==========> Direct login
    public function store(LoginRequest $request): RedirectResponse
    {
        $formType = $request->formType; // 'login' or 'adminLogin'

        // Fetch user with type and is_active status
        $user = User::where('email', $request->email)
            ->select('email', 'type', 'is_active')
            ->first();

        if (! $user) {
            return back()->withErrors(['email' => __('auth.failed')]);
        }

        // Check if the user account is active
        if (! $user->is_active) {
            return back()->withErrors(['email' => __('auth.failed')]);
        }

        // Prevent normal users from logging in through the admin form
        if ($formType === 'adminLogin' && $user->type !== UserType::Admin->value) {
            return back()->withErrors(['email' => __('auth.failed')]);
        }

        // Prevent admins from logging in through the normal user form
        if ($formType === 'login' && $user->type === UserType::Admin->value) {
            return back()->withErrors(['email' => __('auth.failed')]);
        }

        // Proceed with login
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    // ==========> login with EMAIL otp
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     // Get the authenticated user
    //     $user = User::where('email', $request->email)->first();

    //     // Generate and save OTP
    //     $otp = rand(100000, 999999);
    //     $user->otp_code = $otp;
    //     $user->otp_expires_at = now()->addMinutes(5);
    //     $user->save();

    //     // Send the OTP email
    //     Mail::to($user->email)->send(new OTP($otp));

    //     // Store the user ID in session for OTP verification
    //     Session::put('otp_user_id', $user->id);

    //     // Log the user out temporarily — we only finalize login after OTP verification
    //     auth()->logout();

    //     if ($user->type === UserType::Admin->value) {
    //         return redirect()->route('admin.otp');
    //     }

    //     return redirect()->route('otp');
    // }


    // ==========> login with SMS otp
    // public function store(Request $request): RedirectResponse
    // {
    //     // rate limit
    //     $key = 'otp-send:' . $request->ip();

    //     if (RateLimiter::tooManyAttempts($key, 5)) {
    //         return back()->withErrors(['phone' => 'محاولات كثيرة. يُرجى المحاولة مرة أخرى بعد ٥ دقائق.']);
    //     }

    //     RateLimiter::hit($key, 60 * 5); // lock the IP for 5 minutes
        
    //     $request->validate([
    //         'phone' => 'required|string|max:255|min:3',
    //     ]);

    //     // Get the authenticated user
    //     $user = User::where('phone', $request->phone)->first();

    //     if (! $user || $user->phone_verified_at === null) {
    //         return back()->withErrors(['phone' => __('auth.failed')]);
    //     }

    //     // Generate and save OTP
    //     $otp = rand(100000, 999999);
    //     $user->otp_code = $otp;
    //     $user->otp_expires_at = now()->addMinutes(5);
    //     $user->save();

    //     // Mask phone number - show only last 4 digits and save it on session
    //     $maskedPhone = str_repeat('*', strlen($request->phone) - 4) . substr($request->phone, -4);
    //     session(['phone' => $maskedPhone]);

    //     // Send the OTP sms
    //     // $user->notify(new PhoneVerification($user->otp_code));

    //     // Store the user ID in session for OTP verification
    //     Session::put('otp_user_id', $user->id);

    //     // Log the user out temporarily — we only finalize login after OTP verification
    //     auth()->logout();

    //     if ($user->type === UserType::Admin->value) {
    //         return redirect()->route('admin.otp');
    //     }

    //     return redirect()->route('phone.otp');
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
