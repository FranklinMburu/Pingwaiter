<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckCustomerBan
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // Only check for restaurant role
        if (!$user || $user->role !== 'restaurant') {
            return $next($request);
        }
        $customer = $user->customer;
        if ($customer && $customer->isBanned()) {
            $banReason = $customer->ban_reason ?: 'You have been banned.';
            $contact = config('app.contact_email', 'support@example.com');
            $message = $banReason . ' Please contact support: ' . $contact;
            Log::info('Banned restaurant attempted access', ['user_id' => $user->id, 'reason' => $banReason]);
            Auth::logout();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Banned',
                    'message' => $message,
                ], 403);
            }
            return redirect()->route('login')->withErrors(['email' => $message]);
        }
        return $next($request);
    }
}
