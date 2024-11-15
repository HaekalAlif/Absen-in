<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'classroom_id', // Foreign key ke Classroom
        'user_id',      // Foreign key ke User (dosen)
        'semester'      // Semester subject
    ];

    // Relasi ke model Classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Relasi ke model User (dosen yang mengajar subject ini)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi untuk mahasiswa yang terdaftar di subject ini
    // Subject.php
    public function students()
    {
        // Menambahkan filter untuk mengambil mahasiswa berdasarkan kelas yang terdaftar di mata kuliah
        return $this->belongsToMany(User::class, 'subject_user', 'subject_id', 'user_id')
                    ->where('role', 2) // Pastikan hanya mahasiswa (role = 2)
                    ->whereHas('classroom', function($query) {
                        $query->where('id', $this->classroom_id); // Memastikan mahasiswa ada di kelas yang sama
                    });
    }

    // Model Subject.php
    public function attendances()
    {
        return $this->hasMany(Absensi::class);
    }
}
