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
            // Only add columns if they do not already exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'restaurant', 'waiter', 'cashier', 'cook'])
                    ->default('restaurant')
                    ->after('password');
                $table->index('role');
            }
            if (!Schema::hasColumn('users', 'is_first_user')) {
                $table->boolean('is_first_user')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'invited_by')) {
                $table->unsignedBigInteger('invited_by')->nullable()->after('is_first_user');
                $table->foreign('invited_by')
                    ->references('id')->on('users')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            }
            if (!Schema::hasColumn('users', 'invitation_token')) {
                $table->string('invitation_token')->nullable()->after('invited_by');
                $table->index('invitation_token');
            }
            if (!Schema::hasColumn('users', 'invitation_expires_at')) {
                $table->timestamp('invitation_expires_at')->nullable()->after('invitation_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['invited_by']);
            $table->dropIndex('users_role_index');
            $table->dropIndex('users_invitation_token_index');
            $table->dropColumn([
                'role',
                'is_first_user',
                'invited_by',
                'invitation_token',
                'invitation_expires_at',
            ]);
        });
    }
};
