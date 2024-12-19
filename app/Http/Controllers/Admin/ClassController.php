<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom; 
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
        $classes = Classroom::all(); 
        return view('admin.class.manage', compact('classes'));
    }

    // Menyimpan kelas baru ke dalam database
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'name' => 'required|string|max:255',
            'batch_year' => 'required|integer',
            'tahun' => 'required|string|max:255',  
        ]);

        // Menyimpan data kelas baru
        Classroom::create([
            'name' => $request->name,
            'batch_year' => $request->batch_year,
            'tahun' => $request->tahun,  
        ]);

        return redirect()->route('class.manage')->with('success', 'Kelas berhasil ditambahkan');
    }



    // Menampilkan form untuk mengedit kelas
    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id); 
        return view('admin.class.edit', compact('classroom'));
    }

    // Memperbarui data kelas
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'batch_year' => 'required|integer',
            'tahun' => 'required|string|max:255',  
        ]);

        // Temukan kelas dan update datanya
        $classroom = Classroom::findOrFail($id);
        $classroom->update([
            'name' => $request->name,
            'batch_year' => $request->batch_year,
            'tahun' => $request->tahun,  
        ]);

        return redirect()->route('class.manage')->with('success', 'Kelas berhasil diperbarui');
    }

    // Menghapus kelas dari database
    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        return redirect()->route('class.manage')->with('success', 'Kelas berhasil dihapus');
    }
}
