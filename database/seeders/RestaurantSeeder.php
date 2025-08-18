<?php

namespace Database\Seeders;

use App\Enums\WorkerDesignation;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'filwilliano@gmail.com';

        $owner = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Demo Owner',
                'password' => Hash::make(Str::random(12)),
                'is_active' => true,
                'is_onboarded' => true,
            ]
        );

        $restaurantRole = Role::firstOrCreate(['name' => WorkerDesignation::RESTAURANT->value]);

        if (! $owner->hasRole(WorkerDesignation::RESTAURANT->value)) {
            $owner->assignRole($restaurantRole);
        }

        $restaurant = Restaurant::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Demo Restaurant',
                'address' => '123 Demo Street',
                'location' => 'Nairobi',
                'phone_number' => '0700000000',
                'whatsapp_number' => '0712345678',
                'email' => $email,
                'owner_name' => $owner->name,
                'owner_whatsapp' => '0712345678',
                'manager_name' => 'Demo Manager',
                'manager_whatsapp' => '0722333444',
                'cashier_name' => 'Demo Cashier',
                'cashier_whatsapp' => '0733221100',
                'supervisor_name' => 'Demo Supervisor',
                'supervisor_whatsapp' => '0744556677',
                'website' => 'https://demo-restaurant.com',
                'admin_email' => json_encode([$email]),
                'description' => 'This is a seeded demo restaurant with all fields populated.',
                'logo' => null,
                'picture' => json_encode([]),
                'video' => null,
                'allow_place_order' => true,
                'allow_call_owner' => true,
                'allow_call_manager' => true,
                'allow_call_cashier' => true,
                'allow_call_supervisor' => true,
                'currency_symbol' => 'KSh',
                'rewards_per_dollar' => 1.5,
                'google_maps_link' => 'https://maps.google.com/demo',
                'google_review_link' => 'https://g.page/demo',
                'opening_hours' => "Mon–Fri: 9AM–9PM\nSat–Sun: 10AM–10PM",
                'notify_customer' => true,
            ]
        );

        $owner->update([
            'restaurant_id' => $restaurant->id,
        ]);
    }
}
