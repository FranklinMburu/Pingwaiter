<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DemoUserSeeder extends Seeder
{
    public function run()
    {
        $email = env('DEMO_USER_EMAIL', 'demo@restaurant.com');
        $password = env('DEMO_USER_PASSWORD', 'password');
        $role = env('DEMO_USER_ROLE', 'staff');
        $name = env('DEMO_USER_NAME', 'Demo User');

        if (!User::where('email', $email)->exists()) {
            User::create([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($password),
                'role' => $role,
            ]);
        }
    }
}
