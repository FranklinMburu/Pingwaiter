<?php

namespace App\Http\Middleware;

use App\Models\TableAccessToken;
use Closure;
use Illuminate\Http\Request;

class ValidateTableAccess
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->route('token');
        $tableId = $request->route('id');

        $accessToken = TableAccessToken::where('table_id', $tableId)
            ->where('token', $token)
            ->first();

        if (!$accessToken || $accessToken->isExpired()) {
            return redirect()->route('expired.link');
        }

        return $next($request);
    }
}