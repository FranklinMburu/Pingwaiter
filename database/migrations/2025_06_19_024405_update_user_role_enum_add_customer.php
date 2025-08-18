<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE users
                MODIFY role ENUM('admin','restaurant','cashier','cook','waiter','customer')
                NOT NULL DEFAULT 'restaurant'
            ");
        } else if ($driver === 'sqlite') {
            // SQLite does not support MODIFY or ENUM, so skip or handle as needed
            // You may want to update validation logic in your models/forms for allowed roles
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE users
                MODIFY role ENUM('admin','restaurant','cashier','cook','waiter')
                NOT NULL
            ");
        } else if ($driver === 'sqlite') {
            // SQLite does not support MODIFY or ENUM, so skip or handle as needed
        }
    }
};
