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
        Schema::create('food_item_food_style', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('food_item_id');
            $table->unsignedBigInteger('food_style_id');
            $table->timestamps();

            $table->foreign('food_item_id')
                  ->references('id')
                  ->on('food_items')
                  ->onDelete('cascade');

            $table->foreign('food_style_id')
                  ->references('id')
                  ->on('food_styles')
                  ->onDelete('cascade');

            // Prevent duplicate combinations
            $table->unique(['food_item_id', 'food_style_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_item_food_style');
    }
};