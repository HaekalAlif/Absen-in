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
        $user = Auth::user();

        $subject = Subject::where('id', $id)->firstOrFail();

        $absensi = Absensi::where('subject_id', $id)->get();

        foreach ($absensi as $item) {
            $alreadyAttended = RekapAbsensi::where('user_id', $user->id)
                ->where('absen_id', $item->id)
                ->where('subject_id', $subject->id)
                ->where('classroom_id', $user->classroom->id)
                ->exists();

            $item->already_attended = $alreadyAttended;
        }

        return view('mahasiswa.absen.absensi', compact('subject', 'absensi', 'user'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $subjects = $user->classroom ? $user->classroom->subjects : collect();

        $absensi = collect();

        if ($request->has('subject_id')) {
            $absensi = Absensi::where('subject_id', $request->subject_id)
                              ->get();
        }

        return view('mahasiswa.absen.absensi', compact('subjects', 'absensi'));
    }

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

    public function validateQr(Request $request)
    {
        try {
            $qrCode = $request->json('qr_code');
            $userEmail = auth()->user()->email; 

            if (!filter_var($qrCode, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['error' => 'Format QR Code tidak valid'], 400);
            }

            if ($qrCode !== $userEmail) {
                return response()->json(['error' => 'QR Code tidak valid untuk pengguna ini'], 400);
            }

            return response()->json(['message' => 'QR Code valid'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   public function submitQr(Request $request)
    {
        try {
            $qrValidation = $this->validateQr($request);
            if ($qrValidation->status() !== 200) {
                return $qrValidation; 
            }

            $subjectId = $request->json('subject_id');
            $classroomId = $request->json('classroom_id');
            $qrCode = $request->json('qr_code');
            $userId = auth()->user()->id; 
            $date = now(); 

            $absen = Absensi::where('subject_id', $subjectId)
                ->where('classroom_id', $classroomId)
                ->where('id', $request->json('absen_id')) 
                ->first();

            if (!$absen) {
                return response()->json(['error' => 'Data absen tidak ditemukan'], 404);
            }

            $absenId = $absen->id;

            $rekapAbsensi = RekapAbsensi::where('user_id', $userId)
                ->where('absen_id', $absenId)
                ->first();

            if ($rekapAbsensi) {
                return response()->json(['error' => 'Anda sudah absen untuk absen ini'], 400);
            }

            RekapAbsensi::create([
                'user_id' => $userId,
                'absen_id' => $absenId,  
                'subject_id' => $subjectId,
                'classroom_id' => $classroomId,
                'qr_code' => $qrCode,
                'attendance_status' => 'hadir', 
                'date' => $date, 
            ]);

            return response()->json(['message' => 'Absensi berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showQrScannerWithoutId()
    {
        return view('mahasiswa.absen.qr-scanner');
    }

    public function checkAbsensiTime(Request $request)
    {
        $absensi = Absensi::find($request->absensi_id);

        if (!$absensi) {
            return response()->json(['status' => 'invalid']);
        }

        $currentTime = now();
        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);

        if ($currentTime->between($startTime, $endTime)) {
            return response()->json(['status' => 'valid']);
        }

        return response()->json(['status' => 'invalid']);
    }

}
