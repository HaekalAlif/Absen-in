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
        $user = Auth::user();

        $taughtSubjects = Subject::where('user_id', $user->id)->get();

        $subjectNames = [];
        $studentsCount = [];
        $classNames = [];
        
        foreach ($taughtSubjects as $subject) {
            $studentsCount[] = $subject->classroom->users()->where('role', 2)->count();

            $subjectNames[] = $subject->name;

            $classNames[] = $subject->classroom->name;
        }

        return view('dosen.dashboard', compact('subjectNames', 'studentsCount', 'classNames'));
    }




    public function setting(){
        return view('dosen.settings');
    }

    public function jadwal(){
        return view('dosen.jadwal');
    }

}
