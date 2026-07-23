<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('audit_logs') && !Schema::hasColumn('audit_logs', 'ip_address')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->string('ip_address', 45)->nullable()->after('aktivitas');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('audit_logs') && Schema::hasColumn('audit_logs', 'ip_address')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropColumn('ip_address');
            });
        }
    }
};
