<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        // Data Kabupaten
        $kabupatens = [  // ← PASTIKAN PAKAI 's' (JAMAK)
            ['nama_kabupaten' => 'Nias', 'kode_kabupaten' => '1'],
            ['nama_kabupaten' => 'Mandailing Natal', 'kode_kabupaten' => '2'],
            ['nama_kabupaten' => 'Tapanuli Selatan', 'kode_kabupaten' => '3'],
            ['nama_kabupaten' => 'Tapanuli Tengah', 'kode_kabupaten' => '4'],
            ['nama_kabupaten' => 'Tapanuli Utara', 'kode_kabupaten' => '5'],
            ['nama_kabupaten' => 'Toba Samosir', 'kode_kabupaten' => '6'],
            ['nama_kabupaten' => 'Labuhan Batu', 'kode_kabupaten' => '7'],
            ['nama_kabupaten' => 'Asahan', 'kode_kabupaten' => '8'],
            ['nama_kabupaten' => 'Simalungun', 'kode_kabupaten' => '9'],
            ['nama_kabupaten' => 'Dairi', 'kode_kabupaten' => '10'],
            ['nama_kabupaten' => 'Karo', 'kode_kabupaten' => '11'],
            ['nama_kabupaten' => 'Deli Serdang', 'kode_kabupaten' => '12'],
            ['nama_kabupaten' => 'Langkat', 'kode_kabupaten' => '13'],
            ['nama_kabupaten' => 'Nias Selatan', 'kode_kabupaten' => '14'],
            ['nama_kabupaten' => 'Humbang Hasundutan', 'kode_kabupaten' => '15'],
            ['nama_kabupaten' => 'Pakpak Bharat', 'kode_kabupaten' => '16'],
            ['nama_kabupaten' => 'Samosir', 'kode_kabupaten' => '17'],
            ['nama_kabupaten' => 'Serdang Bedagai', 'kode_kabupaten' => '18'],
            ['nama_kabupaten' => 'Batu Bara', 'kode_kabupaten' => '19'],
            ['nama_kabupaten' => 'Padang Lawas Utara', 'kode_kabupaten' => '20'],
            ['nama_kabupaten' => 'Padang Lawas', 'kode_kabupaten' => '21'],
            ['nama_kabupaten' => 'Labuhan Batu Selatan', 'kode_kabupaten' => '22'],
            ['nama_kabupaten' => 'Labuhan Batu Utara', 'kode_kabupaten' => '23'],
            ['nama_kabupaten' => 'Nias Utara', 'kode_kabupaten' => '24'],
            ['nama_kabupaten' => 'Nias Barat', 'kode_kabupaten' => '25'],
            ['nama_kabupaten' => 'Kota Sibolga', 'kode_kabupaten' => '26'],
            ['nama_kabupaten' => 'Kota Tanjung Balai', 'kode_kabupaten' => '27'],
            ['nama_kabupaten' => 'Kota Pematang Siantar', 'kode_kabupaten' => '28'],
            ['nama_kabupaten' => 'Kota Tebing Tinggi', 'kode_kabupaten' => '29'],
            ['nama_kabupaten' => 'Kota Medan', 'kode_kabupaten' => '30'],
            ['nama_kabupaten' => 'Kota Binjai', 'kode_kabupaten' => '31'],
            ['nama_kabupaten' => 'Kota Padangsidimpuan', 'kode_kabupaten' => '32'],
            ['nama_kabupaten' => 'Kota Gunungsitoli', 'kode_kabupaten' => '33'],
        ];

        foreach ($kabupatens as $data) {  // ← PASTIKAN $kabupatens (dengan 's')
            Kabupaten::create($data);
        }
    }
}