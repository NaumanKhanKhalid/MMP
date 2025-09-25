<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SecurityMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            // force password change
            if ($user->force_password_change && !$request->is('change-password')) {
                return redirect()->route('change.password.get');
            }

            // check 2FA
            if ($user->two_factor_enabled
                && !$request->session()->get('two_factor_verified')
                && !$request->is('twofactor')) {
                return redirect()->route('twofactor.get');
            }
        }

        return $next($request);
    }
}
