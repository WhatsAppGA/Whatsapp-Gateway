<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->two_factor_enabled && !$request->session()->get('2fa_verified', false)) {
            if ($request->route()->getName() !== '2fa.verify') {
                return redirect()->route('2fa.verify');
            }
        }

        return $next($request);
    }
}
