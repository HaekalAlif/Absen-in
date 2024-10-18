<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'absensi';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'classroom_id',
        'subject_id',
        'date',
        'start_time',
        'end_time',
        'attendance_status',
    ];

    /**
     * Relasi ke model User
     * Setiap Absensi dimiliki oleh satu User (Mahasiswa)
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Relasi ke model Classroom
     * Setiap Absensi terkait dengan satu Classroom
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
}
