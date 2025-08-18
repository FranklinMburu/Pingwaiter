<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCustomerIpBan
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->isCustomer() && $user->isBanned()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'You have been banned from placing orders.',
                    'is_banned' => true,
                ], 403);
            }

            return redirect()->route('banned.notice');
        }

        return $next($request);
    }
}
