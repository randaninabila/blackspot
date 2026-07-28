<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BlankSpotPhoto extends Model
{
    protected $table = 'blank_spot_photos';

    protected $fillable = [
        'blank_spot_id',
        'jenis_foto',
        'filename',
        'path',
        'latitude',
        'longitude',
        'uploaded_by',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): ?string
    {
        return $this->path ? asset('storage/' . $this->path) : null;
    }

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function blankSpot()
    {
        return $this->belongsTo(BlankSpot::class, 'blank_spot_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    protected static function booted()
    {
        static::deleting(function ($photo) {
            if ($photo->path && Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }
        });
    }
}
