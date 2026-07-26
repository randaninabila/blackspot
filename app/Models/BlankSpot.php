<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlankSpot extends Model
{
    protected $table = 'blank_spots';
    
    protected $fillable = [
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'latitude',
        'longitude',
        'radius',
        'foto',
        'prioritas',
        'nama_lokasi',
        'status_jaringan',
        'tahun',
        'keterangan',
        'status_validasi',
        'catatan_revisi',
        'created_by',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius' => 'float',
        'prioritas' => 'integer',
        'tahun' => 'integer',
        'validated_at' => 'datetime',
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

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revisi' => 'Perlu Revisi',
            'perlu_revisi' => 'Perlu Revisi',
        ][$this->status_validasi] ?? ucfirst($this->status_validasi);
    }

    public function getStatusBadgeAttribute()
    {
        return [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
            'revisi' => 'bg-orange-100 text-orange-700',
            'perlu_revisi' => 'bg-orange-100 text-orange-700',
        ][$this->status_validasi] ?? 'bg-gray-100 text-gray-700';
    }

    public function scopeApproved($query)
    {
        return $query->where('status_validasi', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status_validasi', 'pending');
    }

    public function scopeForKabupaten($query, $kabupatenId)
    {
        if ($kabupatenId) {
            return $query->where('kabupaten_id', $kabupatenId);
        }
        return $query;
    }
}