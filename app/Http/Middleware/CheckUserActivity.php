<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;

class CheckUserActivity
{
    protected $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            $lastActivity = $this->session->get('last_activity');

            if (!is_null($lastActivity) && time() - $lastActivity > 60) {
                // 120 seconds = 2 minutes
                Auth::logout();
                $this->session->flush(); // Clear session data
                return redirect()->route('login')->with('message', 'You have been logged out due to inactivity.');
            }

            $this->session->put('last_activity', time());
        }
        return $next($request);
    }
}
