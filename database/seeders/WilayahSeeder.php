<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kabupaten;
use App\Models\Kecamatan;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data lama agar tidak duplikat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kecamatan')->truncate();
        DB::table('kabupaten')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $filePath = base_path("database/seeders/csv/Daftar_Kabupaten_Kecamatan_Sumut.csv");

        if (!file_exists($filePath)) {
            $this->command->error("File CSV tidak ditemukan!");
            return;
        }

        // Taktik membaca file CSV baris demi baris menggunakan `fopen` agar handle Newline di dalam tanda kutip (") bekerja 100%
        $file = fopen($filePath, "r");
        $isHeader = true;

        $this->command->info("Memproses pemecahan ratusan data kecamatan se-Sumut...");

        while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
            // Lewati baris header (No, Kabupaten/Kota, Kecamatan)
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            $no = trim($data[0]);
            $namaKabupaten = trim($data[1]);
            $rawKecamatan = isset($data[2]) ? trim($data[2]) : '';

            if (empty($namaKabupaten)) {
                continue;
            }

            // 2. Masukkan Kabupaten ke database
            $kabupaten = Kabupaten::create([
                'kode_kabupaten' => $no,
                'nama_kabupaten' => $namaKabupaten,
            ]);

            // 3. PROSES PEMECAHAN UTAMA: Memecah teks kecamatan yang menumpuk di dalam sel
            if (!empty($rawKecamatan)) {
                // Menggunakan kombinasi regex untuk memecah enter baik format Windows (\r\n) maupun Linux (\n)
                $listKecamatan = preg_split("/\r\n|\n|\r/", $rawKecamatan);

                foreach ($listKecamatan as $kec) {
                    $namaKecamatanClean = trim($kec);
                    
                    // Pastikan bukan baris kosong yang di-input
                    if (!empty($namaKecamatanClean)) {
                        Kecamatan::create([
                            'kabupaten_id' => $kabupaten->id,
                            'nama_kecamatan' => $namaKecamatanClean,
                        ]);
                    }
                }
            }
        }

        fclose($file);
        $this->command->info("BERHASIL! Ratusan data kecamatan sekarang sudah terpisah secara mandiri.");
    }
}