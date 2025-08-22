<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Restaurant;

class AttachDemoRestaurantSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', env('DEMO_USER_EMAIL', 'demo@restaurant.com'))->first();
        if ($user) {
            $restaurant = Restaurant::firstOrCreate(
                ['name' => 'Demo Restaurant'],
                ['email' => $user->email]
            );
            $user->restaurant_id = $restaurant->id;
            $user->save();
        }
    }
}
