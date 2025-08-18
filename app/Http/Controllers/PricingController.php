<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PricingController extends Controller
{
    public function index()
    {
        $subscription = Auth::user()->subscription;

        return view('pricing.index',
            [
                'subscription' => $subscription,
            ]
        );
    }
}
