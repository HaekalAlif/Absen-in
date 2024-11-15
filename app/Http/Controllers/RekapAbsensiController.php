<?php

namespace App\Http\Controllers;

use App\Models\RekapAbsensi;
use App\Models\Subject; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapAbsensiController extends Controller
{
    // Fungsi untuk menampilkan detail absensi
    public function show($subjectId)
    {
        // Ambil data mata kuliah berdasarkan ID
        $subject = Subject::findOrFail($subjectId);

        // Ambil data kelas yang terkait dengan mata kuliah
        $classroom = $subject->classroom;

        // Ambil semua mahasiswa yang terdaftar di kelas ini
        $students = $classroom->users()->where('role', 2)->get();

        // Ambil semua data absensi yang terkait dengan mata kuliah
        $attendanceRecords = RekapAbsensi::where('subject_id', $subjectId)->get();

        // Tampilkan view detail absensi
        return view('dosen.absen.detail', compact('students', 'subject', 'attendanceRecords', 'classroom'));
    }

    // Fungsi untuk memperbarui status absensi
    public function updateAll(Request $request)
    {
        // Ambil data absensi yang sudah ada
        $attendanceStatuses = $request->input('attendance_statuses', []);
        
        // Update absensi yang sudah ada
        foreach ($attendanceStatuses as $attendanceId => $status) {
            RekapAbsensi::where('id', $attendanceId)->update(['attendance_status' => $status]);
        }

        // Ambil data absensi baru
        $newAttendanceStatuses = $request->input('new_attendance_statuses', []);
        
        // Simpan absensi baru
        foreach ($newAttendanceStatuses as $studentId => $status) {
            RekapAbsensi::create([
                'user_id' => $studentId,
                'classroom_id' => $request->input('classroom_id'), // Pastikan classroom_id diisi
                'subject_id' => $request->input('subject_id'),
                'date' => now()->toDateString(),
                'attendance_status' => $status,
            ]);
        }

        return redirect()->route('absensi.detail', ['subjectId' => $request->subject_id])
                        ->with('success', 'Status absensi berhasil diperbarui');
    }

}
