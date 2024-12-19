<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RekapAbsensi;

class AbsensiDosenController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();

        $subject = Subject::where('id', $id)
                        ->where('user_id', $user->id)  
                        ->firstOrFail(); 

        $classrooms = Classroom::all();

        $absensi = Absensi::where('subject_id', $id)
                        ->where('user_id', $user->id) 
                        ->get();

        session(['subject_id' => $subject->id]);

        return view('dosen.absen.absensi', compact('subject', 'classrooms', 'absensi'));
    }

    // Menyimpan absensi baru
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Ambil subject_id dari session
        $subjectId = session('subject_id');

        if (!$subjectId) {
            return redirect()->back()->with('error', 'Subject ID tidak ditemukan. Harap kembali dan coba lagi.');
        }

        // Buat absensi baru
        Absensi::create([
            'user_id' => Auth::id(),
            'classroom_id' => $request->classroom_id,
            'subject_id' => $subjectId,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'attendance_status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil dibuat!');
    }

    // Menampilkan daftar absensi berdasarkan mata kuliah yang dipilih
    public function index(Request $request)
    {
        $user = Auth::user();

        $subjects = Subject::where('user_id', $user->id)->get();
        $absensi = [];

        if ($request->subject_id) {
            $absensi = Absensi::where('subject_id', $request->subject_id)
                ->where('user_id', $user->id)
                ->get();
        }

        return view('dosen.absen.absensi', compact('absensi', 'subjects'));
    }

    public function detail($id, $subject_id, $classroom_id)
    {
        $user = Auth::user();

        $subject = Subject::where('id', $subject_id)
                        ->where('user_id', $user->id)  
                        ->firstOrFail();

        $classroom = Classroom::findOrFail($classroom_id);

        // Ambil semua absensi yang ada di kelas ini berdasarkan mata kuliah dan kelas, dan hanya untuk absen_id tertentu
        $attendanceRecords = Absensi::where('subject_id', $subject_id)
                                    ->where('classroom_id', $classroom_id)
                                    ->where('id', $id) 
                                    ->get();

        $students = $classroom->users()->where('role', 2)->get();

        // Ambil data rekap absensi yang hanya terkait dengan absen_id, subject_id, dan classroom_id yang sesuai
        $rekapAbsensi = RekapAbsensi::where('subject_id', $subject_id)
                                    ->where('classroom_id', $classroom_id)
                                    ->whereIn('absen_id', [$id]) 
                                    ->get();

        // Kirim data ke view
        return view('dosen.absen.detail', compact('students', 'subject', 'attendanceRecords', 'rekapAbsensi', 'classroom', 'id'));
    }


    // Menampilkan halaman edit absensi
    public function edit($id)
    {
        $user = Auth::user();

        $absensi = Absensi::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $subject = $absensi->subject;

        $classrooms = Classroom::all();

        return view('dosen.absen.edit', compact('absensi', 'subject', 'classrooms'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'classroom_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $absensi = Absensi::findOrFail($id);

        // Perbarui absensi
        $absensi->update([
            'classroom_id' => $request->classroom_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('dosen.absensi', ['id' => $absensi->subject_id])->with('success', 'Absensi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $absensi = Absensi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $absensi->delete();

        return redirect()->route('dosen.absensi', ['id' => $absensi->subject_id])->with('success', 'Absensi berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $attendance = Absensi::findOrFail($id);

            $attendance->attendance_status = $request->attendance_status;
            $attendance->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status absensi.']);
        }
    }

    // Menampilkan mahasiswa berdasarkan mata kuliah dan kelas
    public function showMahasiswa($id)
    {
        $user = Auth::user();

        $subject = Subject::where('id', $id)
                        ->where('user_id', $user->id)  
                        ->firstOrFail(); 

        $classroom = $subject->classroom; 

        $students = $classroom->users()->where('role', 2)->get(); 

        return view('dosen.absen.showMahasiswa', compact('students', 'subject', 'classroom'));
    }

   public function updateAllAbsensi(Request $request, $absenId, $subject_id, $classroom_id, $user_id)
    {
        try {
            $attendanceStatuses = $request->input('attendance_statuses'); 
            
            // Update status absensi yang sudah ada
            if ($attendanceStatuses) {
                foreach ($attendanceStatuses as $rekapId => $status) {
                    if (strpos($rekapId, 'new_') === false) {
                        $rekapAbsensi = RekapAbsensi::findOrFail($rekapId);

                        $rekapAbsensi->attendance_status = $status;
                        $rekapAbsensi->save(); 
                    } else {
                        $parts = explode('_', $rekapId);
                        $studentId = $parts[1]; 

                        RekapAbsensi::create([
                            'user_id' => $studentId,
                            'absen_id' => $absenId, 
                            'subject_id' => $subject_id,
                            'classroom_id' => $classroom_id,
                            'attendance_status' => $status,
                            'date' => now(),
                        ]);
                    }
                }
            }

            return redirect()->route('absensi.detail', [
                'id' => $absenId,
                'subject_id' => $subject_id,
                'classroom_id' => $classroom_id,
            ])->with('success', 'Absensi berhasil diperbarui.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateAttendanceStatus(Request $request)
    {
        $user = Auth::user();
        $subjectId = $request->subject_id;
        $classroomId = $request->classroom_id;
        $attendanceStatuses = $request->attendance_statuses; 

        // Loop melalui setiap status absensi yang telah diubah
        foreach ($attendanceStatuses as $rekapId => $status) {
            $rekapAbsensi = RekapAbsensi::find($rekapId);

            if ($rekapAbsensi && $rekapAbsensi->subject_id == $subjectId && $rekapAbsensi->classroom_id == $classroomId) {
                $rekapAbsensi->attendance_status = $status;
                $rekapAbsensi->save();
            }
        }

        return redirect()->route('absensi.detail', ['id' => $user->id, 'subject_id' => $subjectId, 'classroom_id' => $classroomId])
                        ->with('success', 'Status absensi berhasil diperbarui');
    }

    
}
