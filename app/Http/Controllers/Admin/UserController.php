<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Classroom; 
use SimpleSoftwareIO\QrCode\Facades\QrCode; 

class UserController extends Controller
{
    // Menampilkan form untuk membuat user baru
    public function index()
    {
        $classrooms = Classroom::all(); 
        return view('admin.user.create', compact('classrooms')); 
    }

    // Menampilkan daftar user yang telah dibuat
     public function manage_user(Request $request)
    {
        $query = User::with('classroom');

        if ($request->has('role') && $request->role !== null) {
            $query->where('role', $request->role);
        }

        if ($request->has('class_id') && $request->class_id !== null) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('batch_year') && $request->batch_year !== null) {
            $query->where('batch_year', $request->batch_year);
        }

        $users = $query->get();
        $classrooms = Classroom::all();

        return view('admin.user.manage', compact('users', 'classrooms'));
    }


        // Menyimpan user baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer|in:1,2', // 1 = dosen, 2 = mahasiswa
            'class_id' => 'nullable|exists:classrooms,id',
        ]);

        // Buat user baru
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->class_id = $request->class_id;

        if ($request->class_id) {
            $classroom = Classroom::findOrFail($request->class_id);
            $user->batch_year = $classroom->batch_year; 
        }

        $user->status = 'active'; 

        // Generate QR Code untuk mahasiswa
        if ($request->role == 2) {
            $qrCodePath = 'qr-codes/' . Str::random(10) . '.png'; // Simpan di public/qr-codes/
            QrCode::format('png')->size(300)->generate($user->email, public_path($qrCodePath));
            $user->qr_code = $qrCodePath; 
        }
        
        $user->save(); // Simpan ke database

        return redirect()->route('user.manage')->with('success', 'User berhasil ditambahkan!');
    }


    // Menampilkan form untuk mengedit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $classrooms = Classroom::all(); 

        return view('admin.user.edit', compact('user', 'classrooms')); 
    }
    
    // Mengupdate data user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|integer|in:1,2',
            'class_id' => 'nullable|exists:classrooms,id',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->role = $request->role;
        $user->class_id = $request->class_id;
        $user->status = 'active';

        $user->save(); 

        return redirect()->route('user.manage')->with('success', 'User berhasil diperbarui!');
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus data terkait di tabel rekap_absensi
        $user->rekap_absensi()->delete(); // Hapus semua data rekap_absensi terkait dengan user

        $user->delete();

        return redirect()->route('user.manage')->with('success', 'User beserta data terkait berhasil dihapus!');
    }


    // Menampilkan QR code pengguna
    public function showQrCode($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.qr_code', compact('user'));
    }

    public function downloadQrCode($id)
    {
        $user = User::findOrFail($id);

        // Pastikan QR code tersedia
        if (!$user->qr_code || !file_exists(public_path($user->qr_code))) {
            return redirect()->route('user.manage')->with('error', 'QR Code tidak ditemukan.');
        }

        $filePath = public_path($user->qr_code);
        $fileName = $user->name . '_QR_Code.png';

        return response()->download($filePath, $fileName);
    }

}
