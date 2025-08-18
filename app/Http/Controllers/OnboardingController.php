<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
    {
        $currencies = currency()->getCurrencies();

        return view('onboarding.index', [
            'currencies' => $currencies,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'restaurantName' => 'required|string|max:255',
            // 'currency_symbol' => 'required|string|max:10',
            // 'rewards_points' => 'required|numeric|min:0',
            // 'googleMapsLink' => 'nullable|url',
            // 'googleReviewLink' => 'nullable|url',
            // 'openingHours' => 'nullable|string',
            // 'notifyCustomer' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => $request->restaurantName,
            'currency_symbol' => $request->currency_symbol,
            'rewards_per_dollar' => $request->rewards_points ?? 0,
            'google_maps_link' => $request->googleMapsLink,
            'google_review_link' => $request->googleReviewLink,
            'opening_hours' => $request->openingHours,
            'notify_customer' => $request->has('notifyCustomer'),
            'allow_place_order' => true,
        ]);

        $user->update([
            'restaurant_id' => $restaurant->id,
            'is_onboarded' => true,
        ]);

        return redirect()->route('restaurants.index')->with('success', 'Welcome aboard! You have successfully created your restaurant. You can continue updating your restaurant details.');
    }
}
