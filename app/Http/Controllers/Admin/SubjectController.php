<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject; 
use App\Models\Classroom; 
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    // Menampilkan form untuk membuat subject
    public function index()
    {
        $classrooms = Classroom::all();

        $users = User::where('role', 1)->get();

        if (Auth::user()->role == 1) {
            $subjects = Subject::where('user_id', Auth::id())->get();
        } else {
            $subjects = collect(); 
        }

        return view('admin.subject.create', compact('classrooms', 'users', 'subjects'));
    }

    // Menyimpan subject baru ke dalam database
   public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'name' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
            'semester' => 'required|integer', 
        ]);

        Subject::create($request->all());

        \Log::info($request->all()); 

        return redirect()->route('subject.manage')->with('success', 'Subject berhasil ditambahkan');
    }

    // Menampilkan daftar subject yang ada
    public function manage_subject()
    {
        $subjects = Subject::with('classroom', 'user')->get();

        return view('admin.subject.manage', compact('subjects'));
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        
        $users = User::where('role', 1)->get();

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
            'semester' => 'required|integer', 
        ]);

        // Update subject
        $subject = Subject::findOrFail($id);
        $subject->update([
            'name' => $request->name,
            'classroom_id' => $request->classroom_id,
            'user_id' => $request->user_id,
            'semester' => $request->semester, 
        ]);

        return redirect()->route('subject.manage')->with('success', 'Subject berhasil diperbarui');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        $subject->delete();

        return redirect()->route('subject.manage')->with('success', 'Subject berhasil dihapus');
    }
}
