<?php

use App\Http\Controllers\Admin\AdminMainController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dosen\AbsenController;
use App\Http\Controllers\Dosen\DosenMainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Mahasiswa\MahasiswaMainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//mahasiswa routes
Route::middleware(['auth', 'verified', 'rolemanager:mahasiswa'])->group(function () {
    Route::prefix('mahasiswa')->group(function(){
        Route::controller(MahasiswaMainController::class)->group(function(){
            Route::get('/dashboard', 'index')->name('mahasiswa');
            Route::get('/settings', 'setting')->name('mahasiswa.settings');
            Route::get('/faq', 'faq')->name('mahasiswa.faq');
            Route::get('/jadwal', 'jadwal')->name('mahasiswa.jadwal');
        });
    });
});

//admin routes
Route::middleware(['auth', 'verified', 'rolemanager:admin'])->group(function () {
    Route::prefix('admin')->group(function(){
        Route::controller(AdminMainController::class)->group(function(){
            Route::get('/dashboard', 'index')->name('admin');
            Route::get('/settings', 'setting')->name('admin.settings');
        });

        Route::controller(ClassController::class)->group(function(){
            Route::get('/class/create', 'index')->name('class.create');
            Route::post('/class/store', 'store')->name('class.store'); // Route untuk menyimpan kelas
            Route::get('/class/manage', 'manage_class')->name('class.manage');
            Route::get('/class/edit/{id}', 'edit')->name('class.edit'); // Route untuk form edit
            Route::put('/class/update/{id}', 'update')->name('class.update'); // Route untuk update kelas
            Route::delete('/class/delete/{id}', 'destroy')->name('class.destroy'); // Route untuk menghapus kelas
        });

        Route::controller(SubjectController::class)->group(function() {
            Route::get('/subject/create', 'index')->name('subject.create');
            Route::post('/subject/store', 'store')->name('subject.store');
            Route::get('/subject/manage', 'manage_subject')->name('subject.manage');
            Route::get('/subject/{id}/edit', 'edit')->name('subject.edit'); // Route untuk edit subject
            Route::put('/subject/{id}',     'update')->name('subject.update'); // Route untuk update subject
            Route::delete('/subject/{id}', 'destroy')->name('subject.destroy'); // Route untuk menghapus subject
        });

        Route::controller(UserController::class)->group(function(){
            Route::get('user/create', 'index')->name('user.create');
            Route::post('user/store', 'store')->name('user.store');
            Route::get('user/manage','manage_user')->name('user.manage');
            Route::get('user/edit/{id}',  'edit')->name('user.edit');
            Route::put('user/update/{id}',  'update')->name('user.update');
            Route::delete('user/destroy/{id}', 'destroy')->name('user.destroy');   
            Route::get('user/qr-code/{id}','showQrCode')->name('user.qr_code');     
        });
    });
});

//dosen routes
Route::middleware(['auth', 'verified', 'rolemanager:dosen'])->group(function () {
    Route::prefix('dosen')->group(function(){
        Route::controller(DosenMainController::class)->group(function(){
            Route::get('/dashboard', 'index')->name('dosen');
            Route::get('/settings', 'setting')->name('dosen.settings');
            Route::get('/jadwal', 'jadwal')->name('dosen.jadwal');
        });

        Route::controller(AbsenController::class)->group(function(){
            Route::get('/absen/create', 'create')->name('absen.create');
            Route::post('/absen/store','store')->name('absen.store');
            Route::get('/absen', 'index')->name('absen.absensi');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-image', [ProfileController::class, 'uploadImage'])->name('profile.upload_image');
});

require __DIR__.'/auth.php';
