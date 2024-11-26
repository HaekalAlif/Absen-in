<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\RekapAbsensi;

class MahasiswaMainController extends Controller
{
    public function index()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();
        
        // Cek apakah user memiliki relasi classroom
        $classroom = $user->classroom;
        
        // Ambil mata kuliah jika classroom ada, jika tidak, set ke koleksi kosong
        $subjects = $classroom ? $classroom->subjects : collect();

        // Ambil absensi berdasarkan subject yang diambil oleh dosen
        $absensi = Absensi::whereIn('subject_id', $subjects->pluck('id'))
                        ->where('classroom_id', $classroom->id)
                        ->get();
        
        // Ambil rekap absensi untuk mengecek apakah user sudah hadir
        $rekapAbsensi = RekapAbsensi::whereIn('subject_id', $subjects->pluck('id'))
                                    ->where('classroom_id', $classroom->id)
                                    ->where('user_id', $user->id)
                                    ->get();
        
        // Perhitungan persentase kehadiran
        $totalPertemuan = $absensi->count();
        $totalHadirs = 0;

        // Menambahkan status absensi untuk setiap mata kuliah
        foreach ($absensi as $attendance) {
            // Cek apakah ada rekap absensi untuk user_id dan absen_id yang sesuai
            $rekap = $rekapAbsensi->where('absen_id', $attendance->id)->first();

            // Jika ada rekap absensi, berarti user sudah hadir
            if ($rekap && $rekap->attendance_status == 'hadir') {
                $totalHadirs++;
            }
        }

        $persentaseKehadiran = $totalPertemuan > 0 ? ($totalHadirs / $totalPertemuan) * 100 : 0;

        // Kirim data ke view
        return view('mahasiswa.dashboard', compact('user', 'classroom', 'subjects', 'absensi', 'rekapAbsensi', 'persentaseKehadiran'));
    }

    public function faq()
    {
        return view('mahasiswa.faq');
    }

    public function setting()
    {
        return view('mahasiswa.settings');
    }

    public function jadwal()
    {
        return view('mahasiswa.jadwal');
    }

}
