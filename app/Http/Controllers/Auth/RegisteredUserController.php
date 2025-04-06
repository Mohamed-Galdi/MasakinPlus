<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        return Inertia::render('Auth/Register');
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
            'accountType' => 'required|string|max:255|in:tenant,investor,owner',
        ]);


        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->accountType,
            'image' => '/storage/users_images/default-user-image.jpg'
        ]);

        Auth::login($user);
        
        // this will send the email verification email
        event(new Registered($user));

        return redirect(route('dashboard'));
    }
}
