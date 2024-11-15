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
    // AbsensiDosenController.php
    public function show($id)
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Ambil mata kuliah berdasarkan ID dan pastikan mata kuliah ini milik dosen yang sedang login
        $subject = Subject::where('id', $id)
                        ->where('user_id', $user->id)  // Pastikan mata kuliah ini milik dosen yang login
                        ->firstOrFail(); // Jika tidak ada, akan menghasilkan 404

        // Ambil semua data kelas untuk dropdown
        $classrooms = Classroom::all();

        // Ambil semua absensi yang terkait dengan mata kuliah ini (dosen yang sama)
        $absensi = Absensi::where('subject_id', $id)
                        ->where('user_id', $user->id) // Filter absensi berdasarkan dosen yang login
                        ->get();

        // Simpan subject_id ke dalam session untuk digunakan di store method
        session(['subject_id' => $subject->id]);

        // Kembalikan view dengan data yang diperlukan
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

        // Ambil subjects yang diajarkan oleh dosen ini
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
    // Ambil data dosen yang sedang login
    $user = Auth::user();

    // Ambil mata kuliah berdasarkan ID dan pastikan mata kuliah ini milik dosen yang sedang login
    $subject = Subject::where('id', $subject_id)
                      ->where('user_id', $user->id)  // Memastikan mata kuliah milik dosen yang login
                      ->firstOrFail();

    // Ambil kelas yang terhubung dengan mata kuliah
    $classroom = Classroom::findOrFail($classroom_id);

    // Ambil semua absensi yang ada di kelas ini berdasarkan mata kuliah dan kelas, dan hanya untuk absen_id tertentu
    $attendanceRecords = Absensi::where('subject_id', $subject_id)
                                ->where('classroom_id', $classroom_id)
                                ->where('id', $id)  // Filter hanya untuk absen_id yang sesuai
                                ->get();

    // Ambil semua mahasiswa yang terdaftar di kelas ini (role 2 sebagai mahasiswa)
    $students = $classroom->users()->where('role', 2)->get();

    // Ambil data rekap absensi yang hanya terkait dengan absen_id, subject_id, dan classroom_id yang sesuai
    $rekapAbsensi = RekapAbsensi::where('subject_id', $subject_id)
                                ->where('classroom_id', $classroom_id)
                                ->whereIn('absen_id', [$id]) // Filter berdasarkan absen_id
                                ->get();

    // Kirim data ke view
    return view('dosen.absen.detail', compact('students', 'subject', 'attendanceRecords', 'rekapAbsensi', 'classroom', 'id'));
}


    // Menampilkan halaman edit absensi
    public function edit($id)
    {
        $user = Auth::user();
        
        // Cari absensi berdasarkan ID dan pastikan absensi tersebut milik dosen yang sedang login
        $absensi = Absensi::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Ambil mata kuliah yang terkait dengan absensi ini
        $subject = $absensi->subject;

        // Ambil semua data kelas untuk dropdown jika diperlukan
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

        // Cari absensi yang akan diupdate
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
        // Cari absensi berdasarkan ID dan pastikan absensi tersebut milik dosen yang sedang login
        $absensi = Absensi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Hapus absensi
        $absensi->delete();

        return redirect()->route('dosen.absensi', ['id' => $absensi->subject_id])->with('success', 'Absensi berhasil dihapus!');
    }


    // Controller updateStatus method
    public function updateStatus(Request $request, $id)
    {
        try {
            // Cari absensi berdasarkan ID
            $attendance = Absensi::findOrFail($id);

            // Update status absensi dengan nilai yang dikirimkan
            $attendance->attendance_status = $request->attendance_status;
            $attendance->save();

            // Mengembalikan response sukses
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tangani dan kirimkan response gagal
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status absensi.']);
        }
    }




    // Menampilkan mahasiswa berdasarkan mata kuliah dan kelas
    public function showMahasiswa($id)
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Ambil mata kuliah berdasarkan ID dan pastikan mata kuliah ini milik dosen yang sedang login
        $subject = Subject::where('id', $id)
                        ->where('user_id', $user->id)  // Pastikan mata kuliah ini milik dosen yang login
                        ->firstOrFail(); // Jika tidak ada, akan menghasilkan 404

        // Ambil kelas yang terhubung dengan mata kuliah
        $classroom = $subject->classroom; // Sesuaikan dengan relasi antara subject dan classroom

        // Ambil semua mahasiswa yang terdaftar di kelas ini (role 2 untuk mahasiswa)
        $students = $classroom->users()->where('role', 2)->get(); // Menggunakan role 2 untuk mahasiswa

        return view('dosen.absen.showMahasiswa', compact('students', 'subject', 'classroom'));
    }

   public function updateAllAbsensi(Request $request, $absenId, $subject_id, $classroom_id, $user_id)
    {
        try {
            // Ambil data status absensi dari request
            $attendanceStatuses = $request->input('attendance_statuses'); // ID absensi yang sudah ada
            
            // Update status absensi yang sudah ada
            if ($attendanceStatuses) {
                foreach ($attendanceStatuses as $rekapId => $status) {
                    // Jika id yang dikirimkan diawali dengan 'new_', berarti data tersebut baru
                    if (strpos($rekapId, 'new_') === false) {
                        // Cari rekapan absensi yang sesuai dengan ID
                        $rekapAbsensi = RekapAbsensi::findOrFail($rekapId);

                        // Update status absensi
                        $rekapAbsensi->attendance_status = $status;
                        $rekapAbsensi->save(); // Simpan perubahan
                    } else {
                        // ID baru, buat entry baru di rekap_absensi
                        $parts = explode('_', $rekapId);
                        $studentId = $parts[1]; // Ambil ID mahasiswa dari nama yang baru

                        RekapAbsensi::create([
                            'user_id' => $studentId,
                            'absen_id' => $absenId, // Harus sesuai dengan ID absensi yang valid
                            'subject_id' => $subject_id,
                            'classroom_id' => $classroom_id,
                            'attendance_status' => $status,
                            'date' => now(),
                        ]);
                    }
                }
            }

            // Redirect kembali ke halaman detail dengan pesan sukses
            return redirect()->route('absensi.detail', [
                'id' => $absenId,
                'subject_id' => $subject_id,
                'classroom_id' => $classroom_id,
            ])->with('success', 'Absensi berhasil diperbarui.');
            
        } catch (\Exception $e) {
            // Tangani error jika ada
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateAttendanceStatus(Request $request)
    {
        $user = Auth::user();
        $subjectId = $request->subject_id;
        $classroomId = $request->classroom_id;
        $attendanceStatuses = $request->attendance_statuses; // Status absensi yang dipilih dosen

        // Loop melalui setiap status absensi yang telah diubah
        foreach ($attendanceStatuses as $rekapId => $status) {
            // Cari data rekap absensi berdasarkan id rekap_absensi dan user_id
            $rekapAbsensi = RekapAbsensi::find($rekapId);

            // Pastikan hanya mengupdate data yang sesuai dengan mata kuliah dan kelas yang benar
            if ($rekapAbsensi && $rekapAbsensi->subject_id == $subjectId && $rekapAbsensi->classroom_id == $classroomId) {
                $rekapAbsensi->attendance_status = $status;
                $rekapAbsensi->save();
            }
        }

        return redirect()->route('absensi.detail', ['id' => $user->id, 'subject_id' => $subjectId, 'classroom_id' => $classroomId])
                        ->with('success', 'Status absensi berhasil diperbarui');
    }

    
}
