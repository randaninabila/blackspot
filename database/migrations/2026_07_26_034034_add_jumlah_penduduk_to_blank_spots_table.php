<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            if (!Schema::hasColumn('blank_spots', 'jumlah_penduduk')) {
                $table->string('jumlah_penduduk')->nullable()->after('kondisi_geografis');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            $table->dropColumn('jumlah_penduduk');
        });
    }
};