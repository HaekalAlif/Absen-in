<?php

use App\Http\Controllers\Admin\AdminMainController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'rolemanager:mahasiswa'])->name('dashboard');

//admin routes

Route::middleware(['auth', 'verified', 'rolemanager:admin'])->group(function () {
    Route::prefix('admin')->group(function(){
        Route::controller(AdminMainController::class)->group(function(){
            Route::get('/dashboard', 'index')->name('admin');
            Route::get('/settings', 'setting')->name('admin.settings');

        });

        Route::controller(ClassController::class)->group(function(){
            Route::get('/class/create', 'index')->name('class.create');
            Route::get('/class/manage', 'manage_class')->name('class.manage');
        });

        Route::controller(SubjectController::class)->group(function(){
            Route::get('/subject/create', 'index')->name('subject.create');
            Route::get('/subject/manage', 'manage_subject')->name('subject.manage');
        });

        Route::controller(UserController::class)->group(function(){
            Route::get('/user/create', 'index')->name('user.create');
            Route::get('/user/manage', 'manage_user')->name('user.manage');
        });
    });
});

Route::get('/dosen/dashboard', function () {
    return view('dosen');
})->middleware(['auth', 'verified', 'rolemanager:dosen'])->name('dosen');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
