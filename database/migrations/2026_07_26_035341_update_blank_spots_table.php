<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        // Schema::table('blank_spots', function (Blueprint $table) {
        //     $table->string('nama_lokasi')->after('desa_id');

        //     $table->decimal('radius',8,2)
        //           ->nullable()
        //           ->after('nama_lokasi');


        //     $table->enum('status_jaringan',[
        //         'Zero Blankspot',
        //         'Sinyal Sangat Lemah',
        //         'Sinyal Lemah',
        //         '2G',
        //         '3G',
        //         '4G Tidak Stabil',
        //         '5G Belum Tersedia'
        //     ])->after('longitude');

        //     $table->tinyInteger('prioritas')
        //           ->after('status_jaringan');

        //     $table->enum('kondisi_geografis',[
        //         'Pegunungan',
        //         'Pantai',
        //         'Sungai',
        //         'Dataran Rendah',
        //         'Perkebunan',
        //         'Danau',
        //         'Perbukitan',
        //         'Hutan',
        //         'Pesisir',
        //         'Lainnya'
        //     ])->after('prioritas');

        //     $table->string('jumlah_penduduk')
        //           ->after('kondisi_geografis');

        //     $table->decimal('jarak_ibukota',8,2)
        //           ->after('jumlah_penduduk');

        //     $table->string('foto_blankspot')
        //           ->nullable()
        //           ->after('jarak_ibukota');

        //     $table->string('foto_geografis')
        //           ->nullable()
        //           ->after('foto_blankspot');

        //     $table->text('alasan_penolakan')
        //           ->nullable()
        //           ->after('status_validasi');

        // });
    }

    public function down(): void
    {
        // Schema::table('blank_spots', function (Blueprint $table) {

        //     $table->dropColumn([
        //         'nama_lokasi',
        //         'radius',
        //         'status_jaringan',
        //         'prioritas',
        //         'kondisi_geografis',
        //         'jumlah_penduduk',
        //         'jarak_ibukota',
        //         'foto_blankspot',
        //         'foto_geografis',
        //         'alasan_penolakan'
        //     ]);

        // });
    }
};