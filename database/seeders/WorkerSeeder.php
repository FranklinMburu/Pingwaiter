<?php

namespace Database\Seeders;

use App\Enums\WorkerDesignation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            WorkerDesignation::COOK,
            WorkerDesignation::WAITER,
            WorkerDesignation::CASHIER,
            WorkerDesignation::RESTAURANT,
        ];

        $owner = User::role(WorkerDesignation::RESTAURANT->value)->first();
        $restaurantId = $owner?->restaurant_id ?? 1;

        foreach ($roles as $roleEnum) {
            $roleName = $roleEnum->value;

            // Create role if it doesn't exist (Spatie)
            Role::firstOrCreate(['name' => $roleName]);

            // Create user with role
            $user = User::updateOrCreate(
                ['email' => "{$roleName}@pingwaiter.com"],
                [
                    'name' => ucfirst($roleName),
                    'password' => Hash::make('password123'),
                    'role' => $roleEnum,
                    'is_active' => true,
                    'is_onboarded' => true,
                    'restaurant_id' => $restaurantId,
                ]
            );

            $user->assignRole($roleName);
        }
    }
}
