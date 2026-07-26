<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE blank_spots
            MODIFY status_jaringan
            ENUM(
                'Zero Blankspot',
                'Sinyal Sangat Lemah',
                'Sinyal Lemah',
                '2G',
                '3G',
                '4G Tidak Stabil',
                '5G Belum Tersedia'
            )
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE blank_spots
            MODIFY status_jaringan
            ENUM(
                'Tidak Ada Sinyal',
                'Sinyal Lemah',
                'Tidak Stabil'
            )
        ");
    }
};