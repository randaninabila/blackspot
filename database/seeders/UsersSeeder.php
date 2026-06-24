<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kabupaten;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin Diskominfo Sumut (Tidak terikat kabupaten_id)
        User::create([
            'nama' => 'Admin Diskominfo Sumut',
            'email' => 'admin@sumutprov.go.id',
            'password' => Hash::make('AdminKominfo2026!'),
            'role' => 'admin',
            'kabupaten_id' => null,
        ]);

        // Ambil data kabupaten dari DB untuk relasi akun operator
        $medan = Kabupaten::where('nama_kabupaten', 'LIKE', '%Medan%')->first();
        $deliSerdang = Kabupaten::where('nama_kabupaten', 'LIKE', '%Deli Serdang%')->first();
        $langkat = Kabupaten::where('nama_kabupaten', 'LIKE', '%Langkat%')->first();

        // 2. Buat Akun Operator Kota Medan
        if ($medan) {
            User::create([
                'nama' => 'Operator Kota Medan',
                'email' => 'op.medan@sumutprov.go.id',
                'password' => Hash::make('MedanBlankspot2026!'),
                'role' => 'operator_kabupaten',
                'kabupaten_id' => $medan->id,
            ]);
        }

        // 3. Buat Akun Operator Kabupaten Deli Serdang
        if ($deliSerdang) {
            User::create([
                'nama' => 'Operator Deli Serdang',
                'email' => 'op.deliserdang@sumutprov.go.id',
                'password' => Hash::make('DeliSerdang2026!'),
                'role' => 'operator_kabupaten',
                'kabupaten_id' => $deliSerdang->id,
            ]);
        }

        // 4. Buat Akun Operator Kabupaten Langkat
        if ($langkat) {
            User::create([
                'nama' => 'Operator Kabupaten Langkat',
                'email' => 'op.langkat@sumutprov.go.id',
                'password' => Hash::make('Langkat2026!'),
                'role' => 'operator_kabupaten',
                'kabupaten_id' => $langkat->id,
            ]);
        }
    }
}