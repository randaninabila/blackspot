<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE blank_spots
            MODIFY prioritas TINYINT NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE blank_spots
            MODIFY prioritas
            ENUM('P1','P2','P3','P4','P5','P6')
        ");
    }
};