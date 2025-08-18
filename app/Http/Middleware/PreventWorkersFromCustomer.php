<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventWorkersFromCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->isStaff() || $user->isAdmin() || $user->isRestaurant()) {
                return redirect()->route('dashboard')
                    ->with('error', 'You are not authorized to access customer pages.');
            }
        }

        return $next($request);
    }
}
