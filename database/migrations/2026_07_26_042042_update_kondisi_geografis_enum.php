<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE blank_spots
            MODIFY kondisi_geografis ENUM(
                'Pegunungan',
                'Pantai',
                'Sungai',
                'Dataran Rendah',
                'Perkebunan',
                'Danau',
                'Perbukitan',
                'Hutan',
                'Pesisir',
                'Lainnya'
            )
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE blank_spots
            MODIFY kondisi_geografis ENUM(
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
            )
        ");
    }
};