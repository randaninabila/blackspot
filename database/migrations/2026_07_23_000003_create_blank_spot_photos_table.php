<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('blank_spot_photos')) {
            Schema::create('blank_spot_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('blank_spot_id')->constrained('blank_spots')->cascadeOnDelete();
                $table->string('filename');
                $table->string('path');
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blank_spot_photos');
    }
};
