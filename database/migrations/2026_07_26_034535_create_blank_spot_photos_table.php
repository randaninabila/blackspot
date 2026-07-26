<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blank_spot_photos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('blank_spot_id')
                  ->constrained('blank_spots')
                  ->cascadeOnDelete();

            $table->string('foto');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blank_spot_photos');
    }
};