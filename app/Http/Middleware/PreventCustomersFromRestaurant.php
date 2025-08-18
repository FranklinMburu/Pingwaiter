<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventCustomersFromRestaurant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isCustomer()) {
            $tableId = session('table_id');

            if ($tableId) {
                return redirect()->route('table.menu', ['table' => $tableId])
                    ->with('error', 'You are not authorized to access restaurant routes.');
            }

            return redirect()->route('home')
                ->with('error', 'Table information not found. Please scan the QR again.');
        }

        return $next($request);
    }
}
