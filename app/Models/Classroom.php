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
    ];

    // Relasi ke model User
    public function users()
    {
        return $this->hasMany(User::class, 'class_id'); // Relasi satu ke banyak
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class); // Relasi ke model Subject
    }
}
