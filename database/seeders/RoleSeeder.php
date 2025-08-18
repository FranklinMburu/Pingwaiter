<?php

namespace Database\Seeders;

use App\Enums\WorkerDesignation;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (WorkerDesignation::cases() as $designation) {
            Role::firstOrCreate(['name' => $designation->value]);
        }
    }
}
