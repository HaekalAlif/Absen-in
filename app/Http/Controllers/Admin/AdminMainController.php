<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom; // Model untuk Kelas
use App\Models\Subject;   // Model untuk Mata Kuliah
use App\Models\User;      // Model untuk User
use Illuminate\Support\Facades\Auth;

class AdminMainController extends Controller
{
    public function index()
    {
        // Mendapatkan jumlah kelas yang ada
        $total_classes = Classroom::count();

        // Mendapatkan jumlah mata kuliah
        $total_subjects = Subject::count();

        // Mendapatkan jumlah dosen (role = 1)
        $total_lecturers = User::where('role', 1)->count();

        // Mendapatkan jumlah mahasiswa (role = 2)
        $total_students = User::where('role', 2)->count();

        // Ambil data untuk grafik (jumlah mahasiswa per kelas, dengan filter role = 2 untuk mahasiswa)
        $classroomData = Classroom::withCount(['users' => function($query) {
            $query->where('role', 2); // Pastikan hanya menghitung mahasiswa dengan role = 2
        }])->get();

        // Data untuk chart (jumlah mahasiswa per kelas)
        $classroomNames = $classroomData->pluck('name');
        $studentsCount = $classroomData->pluck('users_count'); // Menyesuaikan dengan alias 'users_count'

        // Kirim data ke view
        return view('admin.admin', compact(
            'total_classes', 
            'total_subjects', 
            'total_lecturers', 
            'total_students', 
            'classroomData',
            'classroomNames', // Kirim nama-nama kelas
            'studentsCount'   // Kirim jumlah mahasiswa per kelas
        ));
    }

    public function setting()
    {
        return view('admin.settings');
    }
}
