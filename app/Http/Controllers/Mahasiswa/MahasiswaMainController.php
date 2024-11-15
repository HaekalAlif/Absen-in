<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class MahasiswaMainController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('classroom.subjects');
        $classroom = $user->classroom;
        $subjects = $classroom ? $classroom->subjects : collect();

        return view('mahasiswa.dashboard', compact('user', 'classroom', 'subjects'));
    }

    public function faq()
    {
        return view('mahasiswa.faq');
    }

    public function setting()
    {
        return view('mahasiswa.settings');
    }

    public function jadwal()
    {
        return view('mahasiswa.jadwal');
    }

}
