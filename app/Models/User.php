<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'class_id',
        'batch_year',
        'status',
        'qr_code',
        'role',
        'otp',              // Kolom untuk menyimpan kode OTP
        'otp_expired_at',   // Kolom untuk menyimpan waktu kedaluwarsa OTP
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_user', 'user_id', 'subject_id');
    }

    // Relasi ke model Classroom untuk dosen (dosen memiliki banyak kelas)
    public function classroomsAsDosen()
    {
        return $this->hasMany(Classroom::class, 'dosen_id');
    }

    // app/Models/User.php
    public function taughtSubjects()
    {
        return $this->hasMany(Subject::class, 'user_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }

    public function rekap_absensi()
    {
        return $this->hasMany(RekapAbsensi::class, 'user_id');
    }

    /**
     * Menyediakan ID yang akan digunakan dalam token JWT.
     *
     * @return string
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Menyediakan klaim tambahan untuk token JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role, // Contoh menambahkan klaim tambahan (role)
        ];
    }
}
