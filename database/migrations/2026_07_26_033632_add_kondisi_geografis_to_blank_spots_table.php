<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {

            $table->enum('kondisi_geografis', [
                'Pegunungan',
                'Daerah Pantai',
                'Daerah Sungai',
                'Dataran Rendah',
                'Perkebunan',
                'Danau',
                'Perbukitan',
                'Hutan',
                'Pesisir',
                'Lainnya'
            ])->after('status_jaringan');

        });
    }

    public function down(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            $table->dropColumn('kondisi_geografis');
        });
    }
};