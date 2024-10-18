<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Classroom;
use App\Models\Absensi; 
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class AbsenController extends Controller
{
    public function create()
    {
        $classrooms = Classroom::all(); // Ambil semua kelas
        $subjects = Subject::all(); // Ambil semua pengguna (mahasiswa)
        return view('dosen.absen.create', compact('classrooms', 'subjects')); // Sertakan pengguna
    }

    public function store(Request $request)
    {
        // Validasi dan simpan data absensi
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id', // Menambahkan validasi untuk user_id
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Simpan data absensi baru
        Absensi::create([ // Ubah Schedule menjadi Absensi
            'classroom_id' => $request->classroom_id,
            'subject_id' => $request->subject_id, // Menyimpan user_id
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'attendance_status' => 'pending', // Misalkan status kehadiran awalnya "pending"
        ]);

        return redirect()->route('absen.absensi')->with('success', 'Absensi berhasil dibuat.');
    }

    public function index()
    {
        // Ambil data absensi untuk ditampilkan
        $classrooms = Classroom::all();
        $absensi = Absensi::with('subject', 'classroom')->get(); // Ambil data absensi dengan relasi user dan classroom

        return view('dosen.absen.absensi', compact('classrooms', 'absensi'));
    }
}
