<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Classroom;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MahasiswaMainController extends Controller
{
    public function index(){
        return view('mahasiswa.dashboard');
    } 

    public function faq(){
        return view('mahasiswa.faq');
    }
    
    public function setting(){
        return view('mahasiswa.settings');
    }

    public function jadwal(){
        return view('mahasiswa.jadwal');
    }
}
