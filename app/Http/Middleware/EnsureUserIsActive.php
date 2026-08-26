<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu cuenta ha sido desactivada.'
                ], 401);
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta ha sido desactivada por un administrador.'
            ]);
        }

        return $next($request);
    }
}