<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $userTypes = array_values(UserType::optionsExcluding(UserType::Admin));

        return Inertia::render('Auth/Register', compact('userTypes'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|max:255|min:3',
            'phone' => 'required|string|max:255|min:3',
            'email' => 'required|string|lowercase|email|max:255|min:3|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'accountType' => Rule::in(array_column(UserType::casesExcluding(UserType::Admin), 'value')),
        ]);

        $user = User::create([
            'name' => $request->username,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->accountType,
        ]);

        // Auth::login($user);

        // this will send the email verification email
        // event(new Registered($user));

        // Mask phone number - show only last 4 digits
        $maskedPhone = str_repeat('*', strlen($request->phone) - 4) . substr($request->phone, -4);
        // save phone on session
        session(['phone' => $maskedPhone]);

        // send OTP to phone
        $user->otp_code = rand(100000, 999999);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        // notify(new OTP($user->otp_code))->to($user->email)->send();

        return redirect(route('phone.otp'));
    }
}
