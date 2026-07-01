<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';
    
    protected $fillable = ['nama_kabupaten', 'kode_kabupaten'];

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'kabupaten_id');
    }

    public function blankSpots()
    {
        return $this->hasMany(BlankSpot::class, 'kabupaten_id');
    }
}