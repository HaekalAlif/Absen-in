<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'class_id',      // Kolom class_id
        'batch_year',    // Kolom batch_year
        'status',        // Kolom status
        'qr_code',       // Kolom untuk menyimpan path QR code
        'role',          // Kolom untuk menyimpan role user (mahasiswa atau dosen)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi dengan model Classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
