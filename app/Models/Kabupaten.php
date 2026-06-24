<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;

    // Kunci nama tabel secara manual agar tidak terbaca sebagai 'kabupatens'
    protected $table = 'kabupaten';

    // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'nama_kabupaten',
        'kode_kabupaten',
    ];

    // Relasi One-to-Many: Satu Kabupaten memiliki banyak Kecamatan
    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'kabupaten_id');
    }

    // Relasi One-to-Many: Satu Kabupaten memiliki banyak titik Blank Spot
    public function blankSpots()
    {
        return $this->hasMany(BlankSpot::class, 'kabupaten_id');
    }

    // Relasi One-to-Many: Satu Kabupaten terkait dengan banyak User (Operator Kabupaten)
    public function users()
    {
        return $this->hasMany(User::class, 'kabupaten_id');
    }
}