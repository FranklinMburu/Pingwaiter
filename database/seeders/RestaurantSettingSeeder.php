<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantSetting;

class RestaurantSettingSeeder extends Seeder
{
    public function run()
    {
        RestaurantSetting::firstOrCreate(
            ['name' => 'Default Restaurant'],
            [
                'logo' => null,
                'address' => '123 Main St, City, Country',
                'phone' => '+1234567890',
                'email' => 'info@restaurant.com',
                'timezone' => 'UTC',
            ]
        );
    }
}
