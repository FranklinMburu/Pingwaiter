<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Drop indexes related to IP banning first
            if (Schema::hasColumn('customers', 'banned_ip')) {
                $table->dropIndex(['banned_ip']); // If index exists
                $table->dropColumn('banned_ip');
            }
            if (Schema::hasColumn('customers', 'ip_ban_expires_at')) {
                $table->dropIndex(['ip_ban_expires_at']); // If index exists
                $table->dropColumn('ip_ban_expires_at');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('banned_ip')->nullable()->after('ban_reason');
            $table->timestamp('ip_ban_expires_at')->nullable()->after('banned_ip');
            $table->index('banned_ip');
            $table->index('ip_ban_expires_at');
        });
    }
};
