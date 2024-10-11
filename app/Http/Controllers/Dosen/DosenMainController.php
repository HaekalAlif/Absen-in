<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Classroom;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DosenMainController extends Controller
{
    public function index(){
        return view('dosen.dashboard');
    } 

    public function setting(){
        return view('dosen.settings');
    }

    public function jadwal(){
        return view('dosen.jadwal');
    }

}
