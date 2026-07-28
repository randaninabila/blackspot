<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            if (!Schema::hasColumn('blank_spots', 'nama_lokasi')) {
                $table->string('nama_lokasi')->nullable()->after('desa_id');
            }
            if (!Schema::hasColumn('blank_spots', 'radius')) {
                $table->decimal('radius', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('blank_spots', 'status_jaringan')) {
                $table->string('status_jaringan')->nullable();
            }
            if (!Schema::hasColumn('blank_spots', 'prioritas')) {
                $table->integer('prioritas')->nullable();
            }
            if (!Schema::hasColumn('blank_spots', 'foto')) {
                $table->string('foto')->nullable();
            }
            if (!Schema::hasColumn('blank_spots', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable();
            }
            if (!Schema::hasColumn('blank_spots', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blank_spots', function (Blueprint $table) {
            //
        });
    }
};
