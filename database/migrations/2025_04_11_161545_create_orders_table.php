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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('group_number')->nullable();
            $table->text('remark')->nullable();
            $table->string('status')->default('Editable');
            $table->integer('quantity');
            $table->string('price')->default('0');
            $table->string('paid_status')->default('0');
            $table->string('style')->nullable();
            $table->string('cookie_code')->nullable();
            $table->string('ip_address');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('prepared_by')->nullable();
            $table->unsignedBigInteger('delivered_by')->nullable();
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->foreign('item_id')->references('id')->on('food_items')->onSoftDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')->onSoftDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
