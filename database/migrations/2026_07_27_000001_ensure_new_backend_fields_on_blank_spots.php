<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            if (!Schema::hasColumn('blank_spots', 'kondisi_geografis')) {
                $table->string('kondisi_geografis')->nullable()->after('status_jaringan');
            }
            if (!Schema::hasColumn('blank_spots', 'jumlah_penduduk')) {
                $table->bigInteger('jumlah_penduduk')->nullable()->after('kondisi_geografis');
            }
            if (!Schema::hasColumn('blank_spots', 'jarak_ibukota')) {
                $table->decimal('jarak_ibukota', 10, 2)->nullable()->after('jumlah_penduduk');
            }
            if (!Schema::hasColumn('blank_spots', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('status_validasi');
            }
        });

        // Safe alter for kondisi_geografis to VARCHAR(255) if it was restricted ENUM
        if (Schema::hasColumn('blank_spots', 'kondisi_geografis')) {
            try {
                DB::statement("ALTER TABLE blank_spots MODIFY kondisi_geografis VARCHAR(255) NULL");
            } catch (\Throwable $e) {
                // Ignore if driver doesn't support raw ALTER
            }
        }
    }

    public function down(): void
    {
        // Non-destructive fallback: leave columns intact or drop only if strictly needed
    }
};
