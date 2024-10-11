<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Classroom;
use App\Models\Absensi; // Ganti Schedule dengan Absensi
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class AbsenController extends Controller
{
    public function create()
    {
        $classrooms = Classroom::all(); // Ambil semua kelas
        $users = User::all(); // Ambil semua pengguna (mahasiswa)
        return view('absen.create', compact('classrooms', 'users')); // Sertakan pengguna
    }

    public function store(Request $request)
    {
        // Validasi dan simpan data absensi
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id' => 'required|exists:users,id', // Menambahkan validasi untuk user_id
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Simpan data absensi baru
        Absensi::create([ // Ubah Schedule menjadi Absensi
            'classroom_id' => $request->classroom_id,
            'user_id' => $request->user_id, // Menyimpan user_id
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'attendance_status' => 'pending', // Misalkan status kehadiran awalnya "pending"
        ]);

        return redirect()->route('dosen.absensi.index')->with('success', 'Absensi berhasil dibuat.');
    }

    public function index()
    {
        // Ambil data absensi untuk ditampilkan
        $classrooms = Classroom::all();
        $absensi = Absensi::with('user', 'classroom')->get(); // Ambil data absensi dengan relasi user dan classroom

        return view('absen.absensi', compact('classrooms', 'absensi'));
    }
}
