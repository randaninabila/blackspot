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
        'semester',
        'keterangan',
        'status_validasi',
        'catatan_revisi',
        'created_by',
        'validated_by',
        'validated_at',
        'verifikator_id',
        'tanggal_verifikasi',
        'hasil_verifikasi',
        'catatan_verifikasi',
    ];

    public const PRIORITAS_LABELS = [
        1  => 'Prioritas paling tinggi (sangat mendesak)',
        2  => 'Sangat tinggi',
        3  => 'Tinggi',
        4  => 'Cukup tinggi',
        5  => 'Sedang',
        6  => 'Menengah',
        7  => 'Rendah',
        8  => 'Rendah',
        9  => 'Sangat rendah',
        10 => 'Prioritas paling rendah',
    ];

    public const STATUS_JARINGAN_GUIDE = [
        'Blank Spot Total'    => 'Tidak terdapat layanan jaringan sama sekali.',
        'Sinyal Sangat Lemah' => 'Jaringan tersedia tetapi sangat sulit digunakan.',
        'Sinyal Lemah'        => 'Dapat digunakan namun sering terputus.',
        '2G'                  => 'Hanya tersedia jaringan 2G.',
        '3G'                  => 'Hanya tersedia jaringan 3G.',
        '4G Tidak Stabil'     => '4G tersedia tetapi tidak stabil.',
        '5G Belum Tersedia'   => 'Wilayah belum memiliki layanan 5G.',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius' => 'float',
        'prioritas' => 'integer',
        'tahun' => 'integer',
        'semester' => 'integer',
        'validated_at' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

<<<<<<< HEAD
=======
    public function getPrioritasLabelAttribute()
    {
        return isset($this->prioritas) ? "P{$this->prioritas}" : '-';
    }

    public function getPrioritasKeteranganAttribute()
    {
        return self::PRIORITAS_LABELS[$this->prioritas] ?? '-';
    }

    /**
     * Relasi ke kabupaten
     */
>>>>>>> 0e156c1 (feat: improve blankspot validation workflow and UI logic)
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

<<<<<<< HEAD
=======
    /**
     * Relasi ke user (verifikator)
     */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    /**
     * Multiple foto pendukung
     */
    public function photos()
    {
        return $this->hasMany(BlankSpotPhoto::class, 'blank_spot_id');
    }

    /**
     * History riwayat perubahan data
     */
    public function histories()
    {
        return $this->hasMany(BlankSpotHistory::class, 'blank_spot_id')->orderBy('created_at', 'desc');
    }

    /**
     * Attribute untuk status label
     */
>>>>>>> 0e156c1 (feat: improve blankspot validation workflow and UI logic)
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