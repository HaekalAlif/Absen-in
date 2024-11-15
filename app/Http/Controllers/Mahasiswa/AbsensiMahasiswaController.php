<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RekapAbsensi;
use App\Models\Classroom;
use Carbon\Carbon;

class AbsensiMahasiswaController extends Controller
{
    public function show($id)
{
    // Ambil data user yang sedang login
    $user = Auth::user();

    // Ambil mata kuliah berdasarkan ID
    $subject = Subject::where('id', $id)->firstOrFail();

    // Ambil semua absensi yang terkait dengan mata kuliah ini
    $absensi = Absensi::where('subject_id', $id)->get();

    // Loop melalui setiap absensi dan cek apakah user sudah absen
    foreach ($absensi as $item) {
        $alreadyAttended = RekapAbsensi::where('user_id', $user->id)
            ->where('absen_id', $item->id)
            ->where('subject_id', $subject->id)
            ->where('classroom_id', $user->classroom->id)
            ->exists();

        // Menambahkan atribut baru untuk menentukan status kehadiran
        $item->already_attended = $alreadyAttended;
    }

    return view('mahasiswa.absen.absensi', compact('subject', 'absensi', 'user'));
}


    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil mata kuliah yang dimiliki oleh kelas mahasiswa yang sedang login
        $subjects = $user->classroom ? $user->classroom->subjects : collect();

        // Ambil absensi yang sudah ada
        $absensi = collect();

        // Jika ada mata kuliah yang dipilih, tampilkan absensi
        if ($request->has('subject_id')) {
            $absensi = Absensi::where('subject_id', $request->subject_id)
                              ->get();
        }

        return view('mahasiswa.absen.absensi', compact('subjects', 'absensi'));
    }

    // Menampilkan halaman QR Scanner
    public function showQrScanner($absenId, $subjectId, $classroomId)
    {
        try {
            $subject = Subject::findOrFail($subjectId);
            $classroom = Classroom::findOrFail($classroomId);
            $absensi = Absensi::findOrFail($absenId);

            return view('mahasiswa.absen.qr-scanner', compact('subject', 'classroom', 'absensi'));
        } catch (\Exception $e) {
            return abort(404, 'Data tidak ditemukan');
        }
    }

   public function submitQr(Request $request)
    {
        try {
            // Ambil data dari request
            $subjectId = $request->json('subject_id');
            $classroomId = $request->json('classroom_id');
            $qrCode = $request->json('qr_code');
            $userId = auth()->user()->id; // Mendapatkan ID pengguna yang sedang login
            $date = now(); // Mendapatkan tanggal dan waktu saat ini

            // Dapatkan absen berdasarkan `subject_id` dan `classroom_id`
            $absen = Absensi::where('subject_id', $subjectId)
                ->where('classroom_id', $classroomId)
                ->where('id', $request->json('absen_id')) // Pastikan absen_id diambil dari request
                ->first();

            if (!$absen) {
                return response()->json(['error' => 'Data absen tidak ditemukan'], 404);
            }

            // Ambil `absen_id` yang spesifik
            $absenId = $absen->id;

            // Cek apakah pengguna sudah pernah absen dengan `absen_id` ini saja
            $rekapAbsensi = RekapAbsensi::where('user_id', $userId)
                ->where('absen_id', $absenId) // Hanya periksa berdasarkan `absen_id`
                ->first();

            // Jika rekap absensi sudah ada untuk absen_id ini, berikan respons error
            if ($rekapAbsensi) {
                return response()->json(['error' => 'Anda sudah absen untuk absen ini'], 400);
            }

            // Jika rekap absensi belum ada, buat data baru
            RekapAbsensi::create([
                'user_id' => $userId,
                'absen_id' => $absenId,  // Simpan absen_id yang diambil dari tabel absensi
                'subject_id' => $subjectId,
                'classroom_id' => $classroomId,
                'qr_code' => $qrCode,
                'attendance_status' => 'hadir', // Status hadir
                'date' => $date, // Menambahkan tanggal absensi
            ]);

            return response()->json(['message' => 'Absensi berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            // Jika ada error, tangkap dan kembalikan pesan error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showQrScannerWithoutId()
    {
        return view('mahasiswa.absen.qr-scanner');
    }

    public function checkAbsensiTime(Request $request)
    {
        $absensiId = $request->absensi_id;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $absensiDate = $request->date;

        // Mengonversi waktu mulai dan waktu akhir ke format waktu yang valid
        $startTime = Carbon::createFromFormat('H:i', $startTime);
        $endTime = Carbon::createFromFormat('H:i', $endTime);
        $absensiDate = Carbon::createFromFormat('Y-m-d', $absensiDate);

        // Gabungkan tanggal dan waktu menjadi datetime penuh
        $startDateTime = $absensiDate->setTime($startTime->hour, $startTime->minute);
        $endDateTime = $absensiDate->setTime($endTime->hour, $endTime->minute);

        // Ambil waktu server sekarang
        $now = Carbon::now();

        // Cek apakah waktu sekarang di luar rentang waktu absensi
        if ($now->lt($startDateTime) || $now->gt($endDateTime)) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Waktu Absen Telah Berakhir',
            ]);
        }

        // Jika waktu absensi valid, arahkan ke URL absen
        return response()->json([
            'status' => 'valid',
            'url' => route('mahasiswa.absensi.qr', [
                'absenId' => $absensiId,
                'subjectId' => $request->subject_id,
                'classroomId' => $request->classroom_id
            ])
        ]);
    }

}
