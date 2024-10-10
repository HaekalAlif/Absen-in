<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom; // Import model Classroom
use Illuminate\Http\Request; 

class ClassController extends Controller
{
    // Menampilkan form untuk membuat kelas baru
    public function index()
    {
        return view('admin.class.create');
    }

    // Menampilkan daftar kelas yang ada
    public function manage_class()
    {
        $classes = Classroom::all(); // Mengambil semua data kelas dari database
        return view('admin.class.manage', compact('classes'));
    }

    // Menyimpan kelas baru ke dalam database
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'name' => 'required|string|max:255',
            'batch_year' => 'required|integer',
        ]);

        // Menyimpan data kelas baru
        Classroom::create([
            'name' => $request->name,
            'batch_year' => $request->batch_year,
        ]);

        // Redirect ke halaman manajemen kelas dengan pesan sukses
        return redirect()->route('class.manage')->with('success', 'Kelas berhasil ditambahkan');
    }


    // Menampilkan form untuk mengedit kelas
    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id); // Mencari kelas berdasarkan id
        return view('admin.class.edit', compact('classroom'));
    }

    // Memperbarui data kelas
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'batch_year' => 'required|integer', // Ganti year dengan batch_year
        ]);

        // Temukan kelas dan update datanya
        $classroom = Classroom::findOrFail($id);
        $classroom->update([
            'name' => $request->name,
            'batch_year' => $request->batch_year, // Ganti year dengan batch_year
        ]);

        // Redirect ke halaman manage dengan pesan sukses
        return redirect()->route('class.manage')->with('success', 'Kelas berhasil diperbarui');
    }

    // Menghapus kelas dari database
    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        // Redirect ke halaman manage dengan pesan sukses
        return redirect()->route('class.manage')->with('success', 'Kelas berhasil dihapus');
    }


}
