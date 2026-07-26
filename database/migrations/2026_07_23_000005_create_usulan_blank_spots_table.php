<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('usulan_blank_spots')) {
            Schema::create('usulan_blank_spots', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kabupaten_id')->constrained('kabupaten')->cascadeOnDelete();
                $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
                $table->foreignId('desa_id')->nullable()->constrained('desa')->nullOnDelete();
                $table->string('nama_lokasi');
                $table->decimal('latitude', 10, 8);
                $table->decimal('longitude', 11, 8);
                $table->decimal('radius', 8, 2)->nullable();
                $table->text('keterangan')->nullable();
                $table->string('foto')->nullable();
                $table->string('status_usulan')->default('pending');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('usulan_blank_spots');
    }
};
