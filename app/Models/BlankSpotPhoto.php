<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlankSpotPhoto extends Model
{
    protected $table = 'blank_spot_photos';

    protected $fillable = [
        'blank_spot_id',
        'filename',
        'path',
        'latitude',
        'longitude',
        'uploaded_by',
    ];

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
}
