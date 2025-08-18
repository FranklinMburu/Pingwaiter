<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('currency_symbol', 10)->nullable();
            $table->decimal('rewards_per_dollar', 8, 2)->default(0);
            $table->string('google_maps_link')->nullable();
            $table->string('google_review_link')->nullable();
            $table->text('opening_hours')->nullable();
            $table->boolean('notify_customer')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('currency_symbol');
            $table->dropColumn('rewards_per_dollar');
            $table->dropColumn('google_maps_link');
            $table->dropColumn('google_review_link');
            $table->dropColumn('opening_hours');
            $table->dropColumn('notify_customer');
        });
    }
};
