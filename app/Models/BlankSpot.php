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
        'prioritas',
        'nama_lokasi',
        'status_jaringan',
        'kondisi_geografis',
        'jumlah_penduduk',
        'jarak_ibukota',
        'tahun',
        'semester',
        'status_validasi',
        'catatan_revisi',
        'alasan_penolakan',
        'created_by',
        'updated_by',
        'validated_by',
        'validated_at',
        'verifikator_id',
        'tanggal_verifikasi',
        'hasil_verifikasi',
        'catatan_verifikasi',
    ];

    public const STATUS_JARINGAN_TO_PRIORITAS = [
        'Zero Blankspot'      => 1,
        'Blank Spot Total'    => 1,
        'Sinyal Sangat Lemah' => 2,
        'Sinyal Lemah'        => 3,
        '2G'                  => 4,
        '3G'                  => 5,
        '4G Tidak Stabil'     => 6,
        '5G Belum Tersedia'   => 7,
    ];

    public static function getPrioritasFromStatusJaringan(?string $status): int
    {
        if (!$status) return 1;
        $trimmed = trim($status);

        if (preg_match('/^P([1-9]|10)$/i', $trimmed, $matches)) {
            return (int) $matches[1];
        }

        if (isset(self::STATUS_JARINGAN_TO_PRIORITAS[$trimmed])) {
            return self::STATUS_JARINGAN_TO_PRIORITAS[$trimmed];
        }
        foreach (self::STATUS_JARINGAN_TO_PRIORITAS as $key => $val) {
            if (strcasecmp($key, $trimmed) === 0) {
                return $val;
            }
        }
        return 6;
    }

    public const PRIORITAS_LABELS = [
        1  => 'Prioritas P1 (Sangat Mendesak)',
        2  => 'Prioritas P2 (Sangat Tinggi)',
        3  => 'Prioritas P3 (Tinggi)',
        4  => 'Prioritas P4 (Cukup Tinggi)',
        5  => 'Prioritas P5 (Sedang)',
        6  => 'Prioritas P6 (Menengah)',
        7  => 'Prioritas P7 (Rendah)',
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

public function getFotoUrlAttribute(): ?string
{
    $firstPhoto = $this->photos->first();
    if ($firstPhoto) {
        return $firstPhoto->url;
    }
    return null;
}

public function getFotoAttribute(): ?string
{
    $firstPhoto = $this->photos->first();
    if ($firstPhoto) {
        return $firstPhoto->path;
    }
    return null;
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

    protected static function booted()
    {
        static::deleting(function ($blankSpot) {
            foreach ($blankSpot->photos as $photo) {
                $photo->delete();
            }
        });
    }
}