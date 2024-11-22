<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',         // Nama kelas
        'batch_year',   // Angkatan (tahun batch)
        'user_id',      // Foreign key ke User (dosen yang memiliki kelas ini)
        'tahun'
    ];

    // Relasi ke model User (mahasiswa dalam kelas ini)
    public function users()
    {
        return $this->hasMany(User::class, 'class_id'); // Satu kelas banyak user
    }

    // Relasi ke model User (dosen yang memiliki kelas ini)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'user_id'); // Menggunakan user_id untuk dosen
    }

    // Definisikan relasi dengan mata kuliah
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
