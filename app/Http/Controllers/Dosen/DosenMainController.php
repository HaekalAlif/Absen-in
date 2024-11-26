<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RekapAbsensi;
use App\Models\Classroom;
use Carbon\Carbon;



class DosenMainController extends Controller
{

    public function index()
    {
        // Ambil data dosen yang sedang login
        $user = Auth::user();

        // Ambil semua mata kuliah yang diajarkan oleh dosen ini
        $taughtSubjects = Subject::where('user_id', $user->id)->get();

        $subjectNames = [];
        $studentsCount = [];
        $classNames = [];
        
        foreach ($taughtSubjects as $subject) {
            // Ambil jumlah mahasiswa yang terdaftar pada mata kuliah ini (role = 2)
            $studentsCount[] = $subject->classroom->users()->where('role', 2)->count();

            // Simpan nama mata kuliah
            $subjectNames[] = $subject->name;

            // Simpan nama kelas terkait dengan mata kuliah ini
            $classNames[] = $subject->classroom->name;
        }

        // Kirim data ke view
        return view('dosen.dashboard', compact('subjectNames', 'studentsCount', 'classNames'));
    }




    public function setting(){
        return view('dosen.settings');
    }

    public function jadwal(){
        return view('dosen.jadwal');
    }

}
