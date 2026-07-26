<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlankSpotHistory extends Model
{
    protected $table = 'blank_spot_histories';
    
    public $timestamps = false;

    protected $fillable = [
        'blank_spot_id',
        'user_id',
        'role',
        'old_data',
        'new_data',
        'created_at',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
    ];

    public function blankSpot()
    {
        return $this->belongsTo(BlankSpot::class, 'blank_spot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
