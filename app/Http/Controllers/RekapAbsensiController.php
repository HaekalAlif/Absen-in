<?php

namespace App\Http\Controllers;

use App\Models\RekapAbsensi;
use App\Models\Subject; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapAbsensiController extends Controller
{
    public function show($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);

        $classroom = $subject->classroom;

        $students = $classroom->users()->where('role', 2)->get();

        $attendanceRecords = RekapAbsensi::where('subject_id', $subjectId)->get();

        return view('dosen.absen.detail', compact('students', 'subject', 'attendanceRecords', 'classroom'));
    }

    public function updateAll(Request $request)
    {
        $attendanceStatuses = $request->input('attendance_statuses', []);
        
        foreach ($attendanceStatuses as $attendanceId => $status) {
            RekapAbsensi::where('id', $attendanceId)->update(['attendance_status' => $status]);
        }

        $newAttendanceStatuses = $request->input('new_attendance_statuses', []);
        
        foreach ($newAttendanceStatuses as $studentId => $status) {
            RekapAbsensi::create([
                'user_id' => $studentId,
                'classroom_id' => $request->input('classroom_id'), 
                'subject_id' => $request->input('subject_id'),
                'date' => now()->toDateString(),
                'attendance_status' => $status,
            ]);
        }

        return redirect()->route('absensi.detail', ['subjectId' => $request->subject_id])
                        ->with('success', 'Status absensi berhasil diperbarui');
    }

}
