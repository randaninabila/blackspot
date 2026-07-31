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
        Schema::create('kepala_dinas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('nomenklatur_dinas')->nullable();
            $table->string('nama_kepala_dinas')->nullable();
            $table->string('pangkat_golongan')->nullable();
            $table->string('nip')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('kabupaten_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepala_dinas');
    }
};
