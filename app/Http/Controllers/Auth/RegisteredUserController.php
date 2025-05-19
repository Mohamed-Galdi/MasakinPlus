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
            'email' => 'required|string|lowercase|email|max:255|min:3|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            Rule::in(array_column(UserType::casesExcluding(UserType::Admin), 'value')),
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->accountType,
        ]);

        Auth::login($user);

        // this will send the email verification email
        // event(new Registered($user));

        return redirect(route('dashboard'));
    }
}
