<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    // Kunci nama tabel secara manual agar tidak terbaca sebagai 'kecamatans'
    protected $table = 'kecamatan';

    protected $fillable = [
        'kabupaten_id',
        'nama_kecamatan',
    ];

    // Relasi Kebalikan (Belongs To): Kecamatan ini milik dari Kabupaten mana
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    // Relasi One-to-Many: Satu Kecamatan memiliki banyak Desa
    public function desa()
    {
        return $this->hasMany(Desa::class, 'kecamatan_id');
    }

    // Relasi One-to-Many: Satu Kecamatan memiliki banyak titik Blank Spot
    public function blankSpots()
    {
        return $this->hasMany(BlankSpot::class, 'kecamatan_id');
    }
}