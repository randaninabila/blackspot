<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlankSpot extends Model
{
    use HasFactory;

    // Kunci nama tabel secara manual
    protected $table = 'blank_spots';

    protected $fillable = [
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'latitude',
        'longitude',
        'tahun',
        'keterangan',
        'status_validasi',
        'created_by',
        'validated_by',
        'validated_at',
    ];

    // Casts untuk memastikan format data waktu validasi & tahun sesuai tipenya di PHP
    protected $casts = [
        'validated_at' => 'datetime',
        'tahun' => 'integer',
    ];

    // Relasi ke Kabupaten
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    // Relasi ke Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    // Relasi ke Desa
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    // Relasi ke User pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke User validator
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}