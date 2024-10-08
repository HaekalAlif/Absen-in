<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(){
        return view('admin.class.create');
    }

    public function manage_class(){
        return view('admin.class.manage');
    }
}
