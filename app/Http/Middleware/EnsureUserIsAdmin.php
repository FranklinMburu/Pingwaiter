<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            // Unauthenticated: redirect to login or return JSON error
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')->with('error', 'You must be logged in to access admin features.');
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        // Not admin: redirect to access denied page or return JSON error
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Forbidden. Admins only.'], 403);
        }
        return redirect()->route('access.denied')->with('error', 'Access denied: Admins only.');
    }
}
