<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectCustomerToLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            $tableCode = $request->route('table');
            session(['table_id' => $tableCode]);

            $redirectUrl = route('table.menu', ['table' => $tableCode]);

            return redirect()->route('customer.login', ['redirect' => $redirectUrl, 'table' => $tableCode]);
        }

        return $next($request);
    }
}
