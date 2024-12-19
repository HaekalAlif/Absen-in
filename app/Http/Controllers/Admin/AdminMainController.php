<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom; // Model untuk Kelas
use App\Models\Subject;   // Model untuk Mata Kuliah
use App\Models\User;      // Model untuk User
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;        // Import Carbon untuk manipulasi tanggal
use Illuminate\Support\Facades\Mail; // Import Mail untuk mengirim email
use App\Mail\OtpMail; 

class AdminMainController extends Controller
{
    public function index()
    {
        // Mengambil jumlah data yang diperlukan
        $total_classes = Classroom::count();
        $total_subjects = Subject::count();
        $total_lecturers = User::where('role', 1)->count();
        $total_students = User::where('role', 2)->count();

        // Mengambil data kelas dan jumlah siswa per kelas
        $classroomData = Classroom::withCount(['users' => function ($query) {
            $query->where('role', 2);
        }])->get();

        // Mengambil nama kelas dan jumlah siswa di tiap kelas
        $classroomNames = $classroomData->pluck('name');
        $studentsCount = $classroomData->pluck('users_count');

        // Kirim data ke view
        return view('admin.admin', compact(
            'total_classes',
            'total_subjects',
            'total_lecturers',
            'total_students',
            'classroomData',
            'classroomNames',
            'studentsCount'
        ));
    }

    public function setting()
    {
        return view('admin.settings');
    }

    public function backupDatabase()
    {
        // Konfigurasi database dari file .env
        $host = env('DB_HOST', '127.0.0.1');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', 'Haekal030505');
        $database = env('DB_DATABASE', 'qr_attendance');

        // Tentukan path file backup
        $backupPath = storage_path('app/backup_' . date('Y-m-d_H-i-s') . '.sql');

        // Perintah mysqldump untuk backup seluruh database
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s --routines --add-drop-database --add-drop-table --complete-insert %s > %s',
            $host,
            $username,
            $password,
            $database,
            escapeshellarg($backupPath)
        );

        try {
            // Eksekusi perintah mysqldump
            exec($command, $output, $status);

            if ($status === 0) {
                // Jika berhasil, kembalikan file untuk diunduh
                return response()->download($backupPath)->deleteFileAfterSend(true);
            } else {
                // Jika gagal, tampilkan pesan error
                return redirect()->back()->with('error', 'Gagal membuat backup database. Pastikan konfigurasi database benar.');
            }
        } catch (\Exception $e) {
            // Tangani error lainnya
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function restoreDatabase(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'sql_file' => 'required|file|mimes:sql',
        ]);

        // Ambil file SQL yang diunggah
        $file = $request->file('sql_file');

        // Konfigurasi database dari file .env
        $host = env('DB_HOST', '127.0.0.1');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', 'Haekal030505');
        $database = env('DB_DATABASE', 'qr_attendance');

        // Path ke file SQL
        $filePath = $file->getRealPath();

        // Perintah mysql untuk restore database
        $command = sprintf(
            'mysql -h %s -u %s -p%s %s < %s',
            $host,
            $username,
            $password,
            $database,
            escapeshellarg($filePath)
        );

        try {
            // Eksekusi perintah mysql
            exec($command . ' 2>&1', $output, $status);

            // Cek apakah restore berhasil
            if ($status === 0) {
                // Setelah restore, periksa dan sesuaikan autoincrement ID jika perlu
                $tables = ['users', 'another_table']; // Tambahkan nama tabel yang sesuai
                foreach ($tables as $table) {
                    $resetAutoIncrementCommand = sprintf(
                        'mysql -h %s -u %s -p%s -e "SELECT MAX(id) FROM %s;" %s',
                        $host,
                        $username,
                        $password,
                        $table,
                        $database
                    );
                    exec($resetAutoIncrementCommand, $resetOutput, $resetStatus);

                    if ($resetStatus === 0) {
                        // Jika perlu, set AUTO_INCREMENT ke ID maksimal + 1
                        $maxId = (int)trim(implode('', $resetOutput));
                        $resetAutoIncrementCommand = sprintf(
                            'mysql -h %s -u %s -p%s -e "ALTER TABLE %s AUTO_INCREMENT = %d;" %s',
                            $host,
                            $username,
                            $password,
                            $table,
                            $maxId + 1,
                            $database
                        );
                        exec($resetAutoIncrementCommand, $resetOutput, $resetStatus);
                    }
                }

                return redirect()->back()->with('success', 'Database berhasil di-restore dan ID autoincrement telah disesuaikan.');
            } else {
                return redirect()->back()->with('error', 'Gagal restore database. Pastikan file SQL valid. Error: ' . implode("\n", $output));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        // Generate OTP secara acak
        $otp = rand(100000, 999999);

        // Simpan OTP ke dalam session
        session(['otp' => $otp]);
        session(['otp_verified' => false]); // Set status OTP verified ke false

        // Kirim OTP ke email pengguna
        Mail::to($user->email)->send(new OtpMail($otp));

        // Berikan response
        return redirect()->route('admin.settings')->with('success', 'OTP telah dikirim ke email Anda.');
    }

    public function verifyOtp(Request $request)
    {
        // Cek apakah OTP yang dimasukkan sesuai dengan yang ada di session
        if ($request->otp == session('otp')) {
            // Set status OTP verified ke true
            session(['otp_verified' => true]);
            return redirect()->route('admin.settings')->with('success', 'OTP berhasil diverifikasi.');
        }

        return redirect()->route('admin.settings')->with('error', 'OTP yang Anda masukkan salah.');
    }
}
