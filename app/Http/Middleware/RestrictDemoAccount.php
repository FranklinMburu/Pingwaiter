<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RestrictDemoAccount
{
    /**
     * Handle an incoming request.
     *
     * Prevents the demo user from performing destructive operations on restricted routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow all GET requests
        if ($request->isMethod('get')) {
            return $next($request);
        }

        $user = Auth::user();
        $demoEmail = env('DEMO_USER_EMAIL', 'demo@restaurant.com');
        // Allow configuration of restricted methods/keywords via config or .env
        $restrictedMethods = config('demo.restrict_methods', explode(',', env('DEMO_RESTRICT_METHODS', 'post,put,patch,delete')));
        $restrictedKeywords = config('demo.restrict_keywords', explode(',', env('DEMO_RESTRICT_KEYWORDS', 'delete,destroy,settings,admin')));

        if ($user && $user->email === $demoEmail) {
            $method = strtolower($request->method());
            if (in_array($method, $restrictedMethods, true)) {
                foreach ($restrictedKeywords as $keyword) {
                    $keyword = trim($keyword);
                    if ($keyword && Str::contains(strtolower($request->path()), strtolower($keyword))) {
                        $errorMsg = __('Demo account is not allowed to perform destructive operations.');
                        // Always use expectsJson for modern Laravel
                        if ($request->expectsJson()) {
                            return response()->json(['error' => $errorMsg], 403);
                        }
                        // Use session flashing for error, fallback to home if no referer
                        return redirect()->back(fallback: route('dashboard'))->withErrors([$errorMsg]);
                    }
                }
            }
        }

        return $next($request);
    }
}
