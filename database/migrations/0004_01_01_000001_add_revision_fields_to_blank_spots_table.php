<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            if (!Schema::hasColumn('blank_spots', 'catatan_revisi')) {
                $table->text('catatan_revisi')->nullable()->after('status_validasi');
            }
            if (!Schema::hasColumn('blank_spots', 'radius')) {
                $table->decimal('radius', 8, 2)->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('blank_spots', 'foto')) {
                $table->string('foto')->nullable()->after('radius');
            }
            if (!Schema::hasColumn('blank_spots', 'prioritas')) {
                $table->unsignedTinyInteger('prioritas')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('blank_spots', 'nama_lokasi')) {
                $table->string('nama_lokasi')->nullable()->after('prioritas');
            }
            if (!Schema::hasColumn('blank_spots', 'status_jaringan')) {
                $table->string('status_jaringan')->nullable()->after('nama_lokasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['catatan_revisi', 'radius', 'foto', 'prioritas', 'nama_lokasi', 'status_jaringan'] as $col) {
                if (Schema::hasColumn('blank_spots', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};