<?php

namespace Database\Seeders;

use App\Enums\WorkerDesignation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@pingwaiter.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'is_onboarded' => true,
            ]
        );

        Role::firstOrCreate(['name' => WorkerDesignation::ADMIN->value]);
        $user->assignRole(WorkerDesignation::ADMIN->value);
    }
}
