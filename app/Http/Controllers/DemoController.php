<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class DemoController extends Controller
{
    public function login(Request $request)
    {
        $key = 'demo-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->route('login')->withErrors(['Too many demo login attempts. Try again in ' . $seconds . ' seconds.']);
        }
        RateLimiter::hit($key, 60);

        $user = User::where('email', env('DEMO_USER_EMAIL', 'demo@restaurant.com'))->first();
        if (!$user) {
            return redirect()->route('login')->withErrors(['Demo user does not exist.']);
        }
        Auth::login($user);
        return redirect()->route('dashboard')->with('status', 'Logged in as demo user!');
    }
}
