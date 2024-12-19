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
        $user = Auth::user();
        
        $classroom = $user->classroom;
        
        $subjects = $classroom ? $classroom->subjects : collect();

        $absensi = Absensi::whereIn('subject_id', $subjects->pluck('id'))
                        ->where('classroom_id', $classroom->id)
                        ->get();

        $rekapAbsensi = RekapAbsensi::whereIn('subject_id', $subjects->pluck('id'))
                                    ->where('classroom_id', $classroom->id)
                                    ->where('user_id', $user->id)
                                    ->get();

        $totalPertemuan = $absensi->count();
        $totalHadirs = 0;

        foreach ($absensi as $attendance) {
            $rekap = $rekapAbsensi->where('absen_id', $attendance->id)->first();

            if ($rekap && $rekap->attendance_status == 'hadir') {
                $totalHadirs++;
            }
        }

        $persentaseKehadiran = $totalPertemuan > 0 ? ($totalHadirs / $totalPertemuan) * 100 :  0;

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
