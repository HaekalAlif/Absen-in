<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view('admin.user.create');
    }

    public function manage_user(){
        return view('admin.user.manage');
    }
}
