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
        User::create([
            'nama' => 'Admin Diskominfo Sumut',
            'email' => 'admin@sumutprov.go.id',
            'password' => Hash::make('AdminKominfo2026!'),
            'role' => 'admin',
            'kabupaten_id' => null,
        ]);

        $kabupatens = Kabupaten::all();

        foreach ($kabupatens as $kabupaten) {

            $emailNama = strtolower($kabupaten->nama_kabupaten);

            $emailNama = str_replace(['Kota ', 'Kabupaten '], '', $emailNama);

            $emailNama = str_replace(' ', '.', $emailNama);

            $passwordNama = str_replace(['Kota ', 'Kabupaten '], '', $kabupaten->nama_kabupaten);

            $passwordNama = str_replace(' ', '', $passwordNama);

            User::create([
                'nama' => 'Operator ' . $kabupaten->nama_kabupaten,
                'email' => 'op.' . $emailNama . '@sumutprov.go.id',
                'password' => Hash::make($passwordNama . '2026!'),
                'role' => 'operator_kabupaten',
                'kabupaten_id' => $kabupaten->id,
            ]);
        }
    }
}