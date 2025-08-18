<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\FoodStyle;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['register', 'store', 'demoLogin']);
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phoneNumber' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'restaurantName' => 'required|string|max:255',
            'currencySymbol' => 'required|string|max:10',
            'rewardsPoints' => 'required|numeric|min:0',
            'googleMapsLink' => 'nullable|url',
            'googleReviewLink' => 'nullable|url',
            'openingHours' => 'nullable|string',
            'notifyCustomer' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $request->fullName,
            'email' => $request->email,
            'phone' => $request->phoneNumber,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }

    /**
     * Handle the demo login request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function demoLogin()
    {
        $randomEmail = 'demo'.Str::random(8).'@pingwaiter.com';

        $restaurantRole = Role::where('name', 'restaurant')->first();

        $user = User::create([
            'name' => 'Demo User',
            'email' => $randomEmail,
            'phone' => '0700000000',
            'password' => bcrypt(Str::random(12)),
            'is_demo' => true,
        ]);

        $user->assignRole($restaurantRole);

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Sample Restaurant',
            'address' => '123 Demo Street',
            'phone_number' => '0700000000',
            'email' => $user->email,
            'owner_name' => $user->name,
            'description' => 'Welcome to our sample restaurant!',
            'currency_symbol' => 'KSh',
            'rewards_per_dollar' => 1.5,
            'google_maps_link' => 'https://maps.google.com/sample',
            'google_review_link' => 'https://g.page/sample',
            'opening_hours' => "Mon–Fri: 9AM–9PM\nSat–Sun: 10AM–10PM",
            'notify_customer' => true,
            'allow_place_order' => true,
            'allow_call_owner' => true,
        ]);

        $user->update([
            'restaurant_id' => $restaurant->id,
            'is_onboarded' => true,
        ]);

        $styles = ['Italian Cuisine', 'Fast Food', 'Desserts'];
        foreach ($styles as $styleName) {
            FoodStyle::create([
                'name' => $styleName,
                'restaurant_id' => $restaurant->id,
            ]);
        }

        $categories = ['Breakfast', 'Lunch', 'Dessert'];
        $categoryIds = [];

        foreach ($categories as $categoryName) {
            $category = FoodCategory::create([
                'name' => $categoryName,
                'restaurant_id' => $restaurant->id,
            ]);
            $categoryIds[] = $category->id;
        }

        $styleIds = FoodStyle::where('restaurant_id', $restaurant->id)->pluck('id');
        $sampleItems = [
            [
                'item_code' => 'BRK001_'.Str::random(4),
                'name' => 'Breakfast Combo',
                'description' => 'Delicious breakfast with eggs, bacon, and toast',
                'price' => 12.99,
            ],
            [
                'item_code' => 'LCH001_'.Str::random(4),
                'name' => 'Lunch Special',
                'description' => 'Sandwich with fries and a drink',
                'price' => 15.99,
            ],
            [
                'item_code' => 'DST001_'.Str::random(4),
                'name' => 'Chocolate Cake',
                'description' => 'Rich chocolate cake with vanilla ice cream',
                'price' => 8.99,
            ],
        ];

        $lastFoodItem = null;
        foreach ($sampleItems as $index => $itemData) {
            $foodItem = FoodItem::create([
                'item_code' => $itemData['item_code'],
                'category_id' => $categoryIds[$index],
                'name' => $itemData['name'],
                'description' => $itemData['description'],
                'price' => $itemData['price'],
                'restaurant_id' => $restaurant->id,
            ]);

            $foodItem->styles()->attach($styleIds);
            $lastFoodItem = $foodItem;
        }

        $random = strtoupper(Str::random(4));

        $tables = [
            [
                'table_code' => 'T001_'.$random,
                'size' => 4,
                'location' => 'Indoor',
                'description' => 'Window side table',
                'status' => true,
                'restaurant_id' => $restaurant->id,
            ],
            [
                'table_code' => 'T002_'.$random,
                'size' => 6,
                'location' => 'Outdoor',
                'description' => 'Garden view table',
                'status' => true,
                'restaurant_id' => $restaurant->id,
            ],
        ];

        foreach ($tables as $tableData) {
            Table::create($tableData);
        }

        $styles = ['Regular', 'Spicy', 'Extra Cheese'];
        for ($i = 0; $i < 10; $i++) {
            $date = Carbon::now()->subDays($i);

            Order::create([
                'item_id' => $lastFoodItem->id,
                'user_id' => $user->id,
                'table_id' => rand(1, 2),
                'remark' => 'Sample order #'.($i + 1),
                'quantity' => rand(1, 5),
                'price' => $lastFoodItem->price,
                'paid_status' => 1,
                'style' => $styles[array_rand($styles)],
                'cookie_code' => str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                'ip_address' => request()->ip(),
                'is_banned' => false,
                'restaurant_id' => $restaurant->id,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
