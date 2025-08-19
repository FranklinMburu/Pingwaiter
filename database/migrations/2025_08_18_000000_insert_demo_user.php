<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $exists = DB::table('users')->where('email', 'demo@restaurant.com')->exists();
        if (! $exists) {
            DB::table('users')->insert([
                'name' => 'Demo User',
                'email' => 'demo@restaurant.com',
                'password' => Hash::make('password'),
                'role' => 'restaurant',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'demo@restaurant.com')->delete();
    }
};
