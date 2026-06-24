<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kabupaten', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kabupaten');
            $table->string('kode_kabupaten', 10)->unique();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kabupaten');
    }
};