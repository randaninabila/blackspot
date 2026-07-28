<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            if (!Schema::hasColumn('blank_spots', 'jarak_ibukota')) {
                $table->decimal('jarak_ibukota', 10, 2)->nullable()->after('jumlah_penduduk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            $table->dropColumn('jarak_ibukota');
        });
    }
};