<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || is_null($request->user()->phone_verified_at)) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Phone number must be verified.'], 403)
                : redirect()->route('home');
        }

        return $next($request);
    }
}
