<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireAccountTypeSelection
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // Bypass for guests, demo users, or completed profiles
        if (!$user || $user->is_demo || $user->account_type) {
            return $next($request);
        }
        // AJAX requests: return JSON error
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'error' => 'Account type selection required.'
            ], 403);
        }
        // Redirect to account type selection page
        return redirect()->route('account.type.select');
    }
}
