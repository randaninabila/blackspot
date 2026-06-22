<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blank_spots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kabupaten_id')
                ->constrained('kabupaten')
                ->cascadeOnDelete();

            $table->foreignId('kecamatan_id')
                ->constrained('kecamatan')
                ->cascadeOnDelete();

            $table->foreignId('desa_id')
                ->constrained('desa')
                ->cascadeOnDelete();

            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            $table->year('tahun');

            $table->text('keterangan')->nullable();

            $table->enum('status_validasi', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('validated_at')->nullable();

            $table->timestamps();

            $table->index('kabupaten_id');
            $table->index('kecamatan_id');
            $table->index('desa_id');
            $table->index('tahun');
            $table->index('status_validasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blank_spots');
    }
};