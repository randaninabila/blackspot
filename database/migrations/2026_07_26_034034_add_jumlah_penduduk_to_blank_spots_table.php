<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {

            $table->enum('jumlah_penduduk', [
                '1-10',
                '11-50',
                '51-100',
                '101-200',
                '201-500',
                '501-1000',
                '1001-5000',
                '5001-10000',
                '10001-50000',
                '>50000'
            ])->after('kondisi_geografis');

        });
    }

    public function down(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            $table->dropColumn('jumlah_penduduk');
        });
    }
};