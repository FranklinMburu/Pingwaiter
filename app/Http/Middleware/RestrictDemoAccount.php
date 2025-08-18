<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictDemoAccount
{
    /**
     * Handle an incoming request.
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

        if ($user && $user->email === $demoEmail) {
            $restricted = false;
            $restrictedMethods = ['post', 'put', 'patch', 'delete'];
            $restrictedKeywords = ['delete', 'destroy', 'settings', 'admin'];

            if (in_array(strtolower($request->method()), $restrictedMethods)) {
                foreach ($restrictedKeywords as $keyword) {
                    if (stripos($request->path(), $keyword) !== false) {
                        $restricted = true;
                        break;
                    }
                }
            }

            if ($restricted) {
                $errorMsg = 'Demo account is not allowed to perform destructive operations.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['error' => $errorMsg], 403);
                } else {
                    return redirect()->back()->withErrors([$errorMsg]);
                }
            }
        }

        return $next($request);
    }
}
