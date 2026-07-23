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

            $table->string('nama_lokasi')->after('desa_id');

            $table->decimal('radius',8,2)->nullable();

            $table->enum('status_jaringan',[
                'Tidak Ada Sinyal',
                'Sinyal Lemah',
                'Tidak Stabil'
            ]);

            $table->enum('prioritas',[
                'P1','P2','P3','P4','P5',
                'P6','P7','P8','P9','P10'
            ]);

            $table->string('foto')->nullable();

            $table->text('alasan_penolakan')->nullable();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

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
