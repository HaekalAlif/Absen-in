<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    use HasFactory;

    protected $table = 'rekap_absensi';

    protected $fillable = [
        'user_id',
        'classroom_id',
        'subject_id',
        'date',
        'attendance_status',
        'qr_code',
        'absen_id' // Tambahkan ini
    ];

    // Relasi ke tabel absensi
    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absen_id');
    }

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Relasi ke tabel subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
