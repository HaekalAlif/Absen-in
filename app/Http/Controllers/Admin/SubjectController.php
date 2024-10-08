<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(){
        return view('admin.subject.create');
    }

    public function manage_subject(){
        return view('admin.subject.manage');
    }
}
