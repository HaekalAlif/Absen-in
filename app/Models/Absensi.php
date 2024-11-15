<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id', 
        'classroom_id',
        'subject_id',
        'date',
        'start_time',
        'end_time',
        'attendance_status'
    ];

    // Relasi ke tabel classrooms
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Relasi ke tabel subjects
     public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id'); // Pastikan nama model Subject benar
    }

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
