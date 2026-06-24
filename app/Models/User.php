<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Sesuaikan fillable dengan kolom rancangan database kalian
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'kabupaten_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi Kebalikan (Belongs To): Jika role 'operator_kabupaten', dia terikat pada satu Kabupaten
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    // Relasi One-to-Many: Dokumen blank spot yang dibuat oleh user ini
    public function createdBlankSpots()
    {
        return $this->hasMany(BlankSpot::class, 'created_by');
    }

    // Relasi One-to-Many: Dokumen blank spot yang divalidasi oleh user ini (Admin Diskominfo)
    public function validatedBlankSpots()
    {
        return $this->hasMany(BlankSpot::class, 'validated_by');
    }
}