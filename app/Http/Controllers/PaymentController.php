<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\User;

class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        return view('payment');
    }


    public function processPayment(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $charge = Charge::create([
                'amount' => $request->amount * 100, // Stripe uses cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Monthly Subscription',
            ]);

            $user = Auth()->user();
            $user->subscription_status = 'active';
            $user->subscription_start_date = Carbon::now();
            $user->subscription_end_date = Carbon::now()->addMonth();
            $user->subscription_amount = $request->amount;
            $user->save();

            return redirect()->route('dashboard')->with('success', 'Subscription successful!');
        } catch (\Exception $e) {
            return redirect()->route('payment.form')->with('error', $e->getMessage());
        }
    }
}
