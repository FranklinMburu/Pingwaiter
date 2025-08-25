<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class SubscriptionController extends Controller
{
    protected $provider;

    public function __construct()
    {
            $paypalConfig = config('paypal');
            $sandboxId = $paypalConfig['sandbox']['client_id'] ?? null;
            $liveId = $paypalConfig['live']['client_id'] ?? null;
            // Only initialize PayPal if config is present to avoid errors in non-subscription environments
            if (!empty($sandboxId) || !empty($liveId)) {
                $this->provider = new PayPalClient;
                $this->provider->setApiCredentials($paypalConfig);
                $this->provider->getAccessToken();
            } else {
                $this->provider = null;
            }

    }

    public function index()
    {
        $subscription = Auth::user()->subscription;

        return view('pricing.index', [
            'subscription' => $subscription,
        ]);
    }

    public function create(Request $request)
    {
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();

        $planId = config('paypal.plans.premium');

        if (empty($planId)) {
            \Log::error('PayPal plan_id is missing in configuration');

            return back()->with('error', 'Subscription plan configuration is missing.');
        }

        try {
            $response = $this->provider->createSubscription([
                'plan_id' => $planId,
                'start_time' => now()->addMinutes(5)->toIso8601String(),
                'quantity' => '1',
                'subscriber' => [
                    'name' => ['given_name' => Auth::user()->name ?? 'Test'],
                    'email_address' => Auth::user()->email ?? 'buyer@example.com',
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'locale' => 'en-US',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'return_url' => route('subscription.approve', [], true),
                    'cancel_url' => route('subscription.cancel', [], true),
                ],
            ]);

            foreach ($response['links'] ?? [] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }

            \Log::error('Malformed PayPal subscription response', $response);

            return back()->with('error', 'Subscription request malformed.');

        } catch (\Exception $e) {
            \Log::error('PayPal subscription create error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'PayPal API call failed.');
        }
    }

    public function approve(Request $request)
    {
        $subscriptionId = $request->get('subscription_id');

        $details = $this->provider->showSubscriptionDetails($subscriptionId);

        if (! isset($details['status']) || $details['status'] !== 'ACTIVE') {
            return redirect()->route('pricing.index')->with('error', 'Subscription not active.');
        }

        Subscription::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'paypal_subscription_id' => $subscriptionId,
                'plan_id' => $details['plan_id'],
                'plan_name' => $details['plan_id'] === config('paypal.plans.premium') ? 'Premium' : 'Free',
                'status' => $details['status'],
                'expires_at' => Carbon::parse($details['billing_info']['next_billing_time'] ?? null),
            ]
        );

        return redirect()->route('pricing.index')->with('success', 'Subscription activated.');
    }

    public function cancel()
    {
        return redirect()->route('pricing.index')->with('info', 'You canceled the subscription.');
    }
}
