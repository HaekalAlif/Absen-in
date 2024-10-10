<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject; // Import model Subject
use App\Models\Classroom; // Import model Classroom
use App\Models\User; // Import model User
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Menampilkan form untuk membuat subject
    public function index()
    {
        // Ambil semua kelas
        $classrooms = Classroom::all();

        // Ambil semua dosen dengan role = 1
        $users = User::where('role', 1)->get();

        return view('admin.subject.create', compact('classrooms', 'users'));
    }

    // Menyimpan subject baru ke dalam database
   public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'name' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
            'semester' => 'required|integer', // Validasi untuk semester
        ]);

        // Menyimpan data subject baru
        Subject::create($request->all());

        // Debugging: Lihat isi request
        \Log::info($request->all()); // Tambahkan ini untuk log isi request

        // Redirect ke halaman manajemen subject dengan pesan sukses
        return redirect()->route('subject.manage')->with('success', 'Subject berhasil ditambahkan');
    }




    // Menampilkan daftar subject yang ada
    public function manage_subject()
    {
        // Ambil semua data subject dengan relasi ke kelas dan dosen
        $subjects = Subject::with('classroom', 'user')->get();

        return view('admin.subject.manage', compact('subjects'));
    }

    public function edit($id)
    {
        // Ambil data subject berdasarkan ID
        $subject = Subject::findOrFail($id);
        
        // Ambil semua dosen dengan role = 1
        $users = User::where('role', 1)->get();

        // Ambil semua kelas yang ada
        $classrooms = Classroom::all();

        return view('admin.subject.edit', compact('subject', 'users', 'classrooms'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input form
        $request->validate([
            'name' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
            'semester' => 'required|integer', // Tambahkan validasi untuk semester
        ]);

        // Update subject
        $subject = Subject::findOrFail($id);
        $subject->update([
            'name' => $request->name,
            'classroom_id' => $request->classroom_id,
            'user_id' => $request->user_id,
            'semester' => $request->semester, // Tambahkan semester secara eksplisit
        ]);

        // Redirect ke halaman manajemen subject dengan pesan sukses
        return redirect()->route('subject.manage')->with('success', 'Subject berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Temukan subject berdasarkan ID
        $subject = Subject::findOrFail($id);

        // Hapus subject
        $subject->delete();

        // Redirect ke halaman manajemen subject dengan pesan sukses
        return redirect()->route('subject.manage')->with('success', 'Subject berhasil dihapus');
    }
}
