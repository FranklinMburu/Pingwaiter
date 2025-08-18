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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('location')->nullable(); // GPS coordinates or area name
            $table->string('phone_number')->nullable(); // multiple phone numbers
            $table->string('whatsapp_number')->nullable(); // multiple WhatsApp numbers
            $table->string('email')->nullable(); // general emails
            $table->string('owner_name')->nullable();
            $table->string('owner_whatsapp')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('manager_whatsapp')->nullable();
            $table->string('cashier_name')->nullable();
            $table->string('cashier_whatsapp')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_whatsapp')->nullable();
            $table->string('website')->nullable();
            $table->json('admin_email')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable(); // store path or URL
            $table->json('picture')->nullable(); // array of picture paths
            $table->string('video')->nullable(); // video path or URL

            // Permissions for contact
            $table->boolean('allow_place_order')->default(false);
            $table->boolean('allow_call_owner')->default(false);
            $table->boolean('allow_call_manager')->default(false);
            $table->boolean('allow_call_cashier')->default(false);
            $table->boolean('allow_call_supervisor')->default(false);

            $table->string('stripe_customer_id')->nullable(); // To store Stripe customer ID
            $table->string('stripe_subscription_id')->nullable(); // To store Stripe subscription ID
            $table->decimal('subscription_amount', 10, 2)->nullable(); // Subscription amount if applicable
            $table->enum('subscription_status', ['active', 'inactive', 'paused'])->default('inactive'); // Subscription status
            $table->timestamp('subscription_start_date')->nullable(); // Subscription start date
            $table->timestamp('subscription_end_date')->nullable(); // Subscription end date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
