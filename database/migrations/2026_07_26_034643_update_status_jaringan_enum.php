<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('blank_spots')->where('status_jaringan', 'Blank Spot Total')->orWhere('status_jaringan', 'Tidak Ada Sinyal')->update(['status_jaringan' => 'Zero Blankspot']);
        DB::table('blank_spots')->where('status_jaringan', 'Tidak Stabil')->update(['status_jaringan' => '4G Tidak Stabil']);
        
        try {
            DB::statement("ALTER TABLE blank_spots MODIFY status_jaringan VARCHAR(255) NULL");
        } catch (\Throwable $e) {
            // Ignore
        }
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