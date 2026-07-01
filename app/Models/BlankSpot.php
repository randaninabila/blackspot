<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlankSpot extends Model
{
    protected $table = 'blank_spots';
    
    protected $fillable = [
        'kabupaten_id', 'kecamatan_id', 'desa_id',
        'latitude', 'longitude', 'tahun', 'keterangan',
        'status_validasi', 'created_by', 'validated_by', 'validated_at'
    ];

    /**
     * Relasi ke kabupaten
     */
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    /**
     * Relasi ke kecamatan
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    /**
     * Relasi ke desa
     */
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    /**
     * Relasi ke user (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user (validator)
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Attribute untuk status label
     */
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ][$this->status_validasi] ?? $this->status_validasi;
    }

    /**
     * Attribute untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        return [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700'
        ][$this->status_validasi] ?? 'bg-gray-100 text-gray-700';
    }
}