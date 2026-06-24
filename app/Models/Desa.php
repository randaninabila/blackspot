<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    // Kunci nama tabel secara manual agar tidak terbaca sebagai 'desas'
    protected $table = 'desa';

    protected $fillable = [
        'kecamatan_id',
        'nama_desa',
    ];

    // Relasi Kebalikan (Belongs To): Desa ini milik dari Kecamatan mana
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    // Relasi One-to-Many: Satu Desa memiliki banyak titik Blank Spot
    public function blankSpots()
    {
        return $this->hasMany(BlankSpot::class, 'desa_id');
    }
}