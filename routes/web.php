<?php

use App\Http\Controllers\Admin\AdminMainController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dosen\AbsensiDosenController;
use App\Http\Controllers\Dosen\DosenMainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Mahasiswa\MahasiswaMainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RekapAbsensiController;
use App\Http\Controllers\Mahasiswa\AbsensiMahasiswaController;

Route::get('/', function () {
    return view('auth.login');
}); 

// Mahasiswa routes
Route::middleware(['auth', 'verified', 'rolemanager:mahasiswa'])->group(function () {
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [MahasiswaMainController::class, 'index'])->name('mahasiswa');
        Route::get('/settings', [MahasiswaMainController::class, 'setting'])->name('mahasiswa.settings');
        Route::get('/faq', [MahasiswaMainController::class, 'faq'])->name('mahasiswa.faq');
        Route::get('/jadwal', [MahasiswaMainController::class, 'jadwal'])->name('mahasiswa.jadwal'); 
        
        Route::get('/absensi', [AbsensiMahasiswaController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/subject/{id}', [AbsensiMahasiswaController::class, 'show'])->name('mahasiswa.absensi');

        Route::get('/absensi/qr/{absenId}/{subjectId}/{classroomId}', [AbsensiMahasiswaController::class, 'showQrScanner'])->name('mahasiswa.absensi.qr'); 
        Route::post('/absensi/qr-submit', [AbsensiMahasiswaController::class, 'submitQr'])->name('absensi.qr.submit');

        Route::get('/absensi/qr', [AbsensiMahasiswaController::class, 'showQrScannerWithoutId'])->name('mahasiswa.absensi.qr.test');
        
        Route::post('/absensi/check-time', [AbsensiMahasiswaController::class, 'checkAbsensiTime'])->name('mahasiswa.absensi.check-time');

        Route::post('/absensi/qr/validate', [AbsensiMahasiswaController::class, 'validateQr'])->name('absensi.qr.validate');

        // Route untuk profle
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profile/upload-image', [ProfileController::class, 'uploadImage'])->name('profile.upload_image');
    });
});


//admin routes
Route::middleware(['auth', 'verified', 'rolemanager:admin'])->group(function () {
    Route::prefix('admin')->group(function(){
         // Grup route yang menggunakan JWT
        Route::middleware('jwt.token')->controller(AdminMainController::class)->group(function(){
            Route::get('/dashboard', 'index')->name('admin');
            Route::get('/settings', 'setting')->name('admin.settings');
            Route::get('/admin/settings/backup', [AdminMainController::class, 'backupDatabase'])->name('admin.settings.backup');
            Route::post('/admin/settings/restore', [AdminMainController::class, 'restoreDatabase'])->name('admin.settings.restore');
            Route::post('settings/send-otp', [AdminMainController::class, 'sendOtp'])->name('admin.settings.sendOtp');
            Route::post('settings/verify-otp', [AdminMainController::class, 'verifyOtp'])->name('admin.settings.verifyOtp');
        });
        
        Route::controller(ClassController::class)->group(function(){
            Route::get('/class/create', 'index')->name('class.create');
            Route::post('/class/store', 'store')->name('class.store'); 
            Route::get('/class/manage', 'manage_class')->name('class.manage');
            Route::get('/class/edit/{id}', 'edit')->name('class.edit'); 
            Route::put('/class/update/{id}', 'update')->name('class.update'); 
            Route::delete('/class/delete/{id}', 'destroy')->name('class.destroy'); 
        });

        Route::controller(SubjectController::class)->group(function() {
            Route::get('/subject/create', 'index')->name('subject.create');
            Route::post('/subject/store', 'store')->name('subject.store');
            Route::get('/subject/manage', 'manage_subject')->name('subject.manage');
            Route::get('/subject/{id}/edit', 'edit')->name('subject.edit'); 
            Route::put('/subject/{id}',     'update')->name('subject.update'); 
            Route::delete('/subject/{id}', 'destroy')->name('subject.destroy'); 
        });

        Route::controller(UserController::class)->group(function(){
            Route::get('user/create', 'index')->name('user.create');
            Route::post('user/store', 'store')->name('user.store');
            Route::get('user/manage','manage_user')->name('user.manage');
            Route::get('user/edit/{id}',  'edit')->name('user.edit');
            Route::put('user/update/{id}',  'update')->name('user.update');
            Route::delete('user/destroy/{id}', 'destroy')->name('user.destroy');   
            Route::get('user/qr-code/{id}','showQrCode')->name('user.qr_code'); 
            Route::get('/users',  'index')->name('user.index');
            Route::get('/user/{id}/download-qr', 'downloadQrCode')->name('user.download.qr');

        });
    });
});

//route dosen
Route::middleware(['auth', 'verified', 'rolemanager:dosen'])->group(function () {
    Route::prefix('dosen')->group(function(){
        Route::controller(DosenMainController::class)->group(function(){
            Route::get('/dashboard', 'index')->name('dosen');
            Route::get('/settings', 'setting')->name('dosen.settings');
            Route::get('/jadwal', 'jadwal')->name('dosen.jadwal');
        });
    });
});

// routes absensi dosen
Route::middleware(['auth', 'verified', 'rolemanager:dosen'])->group(function () {
    Route::prefix('dosen')->group(function () {
        Route::get('/absensi/create', [AbsensiDosenController::class, 'create'])->name('absensi.create');
        Route::post('/absensi/store', [AbsensiDosenController::class, 'store'])->name('absensi.store');
        Route::get('/absensi', [AbsensiDosenController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/subject/{id}', [AbsensiDosenController::class, 'show'])->name('dosen.absensi'); 
        Route::get('/absensi/detail/{id}', [AbsensiDosenController::class, 'detail'])->name('dosen.absensi.detail');
        Route::get('/absensi/{id}/edit', [AbsensiDosenController::class, 'edit'])->name('absensi.edit');
        Route::put('/absensi/{id}', [AbsensiDosenController::class, 'update'])->name('absensi.update');
        });
        Route::get('/absensi/{id}', [AbsensiDosenController::class, 'show'])->name('absensi.show');
        Route::put('/absensi/{id}/update', [AbsensiDosenController::class, 'updateStatus'])->name('absensi.updateStatus');
        Route::put('/dosen/absensi/updateAll', [AbsensiDosenController::class, 'updateAll'])->name('absensi.updateAll');
        Route::get('/absensi/detail/{absenId}/{subject_id}/{classroom_id}', [AbsensiDosenController::class, 'detail'])->name('absensi.detail');
        Route::put('/absensi/update-all/{absenId}/{subject_id}/{classroom_id}/{user_id}', [AbsensiDosenController::class, 'updateAllAbsensi'])->name('absensi.updateAll');
        Route::delete('absensi/{id}', [AbsensiDosenController::class, 'destroy'])->name('absensi.destroy');
});
    
// //route profile
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::get('/mahasiswa/profile', [ProfileController::class, 'edit'])->name('profile');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//     Route::post('/profile/upload-image', [ProfileController::class, 'uploadImage'])->name('profile.upload_image');
// });

require __DIR__.'/auth.php';
