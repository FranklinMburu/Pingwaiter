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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'restaurant', 'waiter', 'cashier', 'cook', 'staff', 'customer'])->default('customer')->change();
            $table->boolean('is_first_user')->default(false)->after('role');
            $table->unsignedBigInteger('invited_by')->nullable()->after('is_first_user');
            $table->string('invitation_token')->nullable()->after('invited_by');
            $table->timestamp('invitation_expires_at')->nullable()->after('invitation_token');

            $table->index('role');
            $table->index('invitation_token');

            $table->foreign('invited_by')
                ->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['invited_by']);
            $table->dropIndex(['role']);
            $table->dropIndex(['invitation_token']);
            $table->dropColumn(['is_first_user', 'invited_by', 'invitation_token', 'invitation_expires_at']);
            // Optionally revert role column to previous state if needed
        });
    }
};
