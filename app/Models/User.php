<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is operator kabupaten
     */
    public function isOperator(): bool
    {
        return $this->role === 'operator_kabupaten';
    }

    /**
     * Relasi ke Kabupaten (untuk operator)
     */
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    /**
     * Data blank spot yang dibuat oleh user ini
     */
    public function blankSpots()
    {
        return $this->hasMany(BlankSpot::class, 'created_by');
    }

    /**
     * Data blank spot yang divalidasi oleh user ini (Admin)
     */
    public function validatedBlankSpots()
    {
        return $this->hasMany(BlankSpot::class, 'validated_by');
    }
}