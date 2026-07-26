<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            if (!Schema::hasColumn('blank_spots', 'semester')) {
                $table->unsignedTinyInteger('semester')->default(1)->after('tahun');
            }
            if (!Schema::hasColumn('blank_spots', 'verifikator_id')) {
                $table->foreignId('verifikator_id')->nullable()->after('validated_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('blank_spots', 'tanggal_verifikasi')) {
                $table->timestamp('tanggal_verifikasi')->nullable()->after('verifikator_id');
            }
            if (!Schema::hasColumn('blank_spots', 'hasil_verifikasi')) {
                $table->string('hasil_verifikasi')->nullable()->after('tanggal_verifikasi');
            }
            if (!Schema::hasColumn('blank_spots', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('hasil_verifikasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            $columns = [];
            foreach (['semester', 'verifikator_id', 'tanggal_verifikasi', 'hasil_verifikasi', 'catatan_verifikasi'] as $col) {
                if (Schema::hasColumn('blank_spots', $col)) {
                    $columns[] = $col;
                }
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
