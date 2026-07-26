<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {

            $table->decimal('jarak_ibukota',8,2)
                  ->after('jumlah_penduduk');

        });
    }

    public function down(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            $table->dropColumn('jarak_ibukota');
        });
    }
};