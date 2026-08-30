<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionNotLocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && session('is_locked', false)) {
            if (!$request->routeIs('lockscreen.show') && 
                !$request->routeIs('lockscreen.unlock') && 
                !$request->routeIs('logout')) {
                return redirect()->route('lockscreen.show');
            }
        }

        $response = $next($request);

        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                         ->header('Pragma', 'no-cache')
                         ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}