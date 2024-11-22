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
        'class_id',      // Kolom class_id untuk menghubungkan ke Classroom
        'batch_year',    // Kolom batch_year
        'status',        // Kolom status
        'qr_code',       // Kolom untuk menyimpan path QR code
        'role',          // Kolom role untuk menyimpan apakah user mahasiswa atau dosen
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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

}
