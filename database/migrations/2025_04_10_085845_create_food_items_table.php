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
        Schema::create('food_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->bigInteger('category_id'); // e.g., Breakfast, Lunch, Dessert
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('picture')->nullable(); // Store as a comma-separated string or handle multiple via another table
            $table->string('video')->nullable();    // File path or URL
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onSoftDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_items');
    }
};