<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsulanBlankSpot extends Model
{
    protected $table = 'usulan_blank_spots';

    protected $fillable = [
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius',
        'keterangan',
        'foto',
        'status_usulan',
        'created_by',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius' => 'float',
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
