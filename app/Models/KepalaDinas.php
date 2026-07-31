<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaDinas extends Model
{
    use HasFactory;

    protected $table = 'kepala_dinas';

    protected $fillable = [
        'tanggal',
        'lokasi',
        'nomenklatur_dinas',
        'nama_kepala_dinas',
        'pangkat_golongan',
        'nip',
        'user_id',
        'kabupaten_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
}
