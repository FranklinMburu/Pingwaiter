<?php

// app/Http/Controllers/Auth/WorkerLoginController.php

namespace App\Http\Controllers\Auth;

use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;

class WorkerLoginController extends Controller
{
    // Redirect to Google OAuth
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback()
    {
        try {
            $googleWorker = Socialite::driver('google')->user();
            $email = $googleWorker->getEmail();
            $worker = Worker::where('email', $email)->first();
            if ($worker) {
                // Log the worker in using the worker guard
                Auth::guard('worker')->login($worker, true);

                return redirect()->route('worker_dashboard'); // Redirect to the dashboard or home page
            } else {
                return redirect()->route('signup')->with('error', 'Unkown Email.');
            }
        } catch (\Exception $e) {
            return redirect()->route('signup')->with('error', 'Failed to login with Google.');
        }
    }
}
