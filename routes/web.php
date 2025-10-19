<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// --- Import Controller untuk Fitur Utama ---
use App\Http\Controllers\LandingController;

use App\Http\Controllers\AlumniDashboardController;
// --- Import Controller untuk Panel Admin ---
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AlumniCategoryController;
use App\Http\Controllers\Admin\InstansiController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\RespondenController;
use App\Http\Controllers\Auth\InstansiLoginController;
use App\Http\Controllers\Instansi\InstansiDashboardController;
use App\Http\Controllers\Instansi\ProfileController as InstansiProfileController;
use Illuminate\Support\Facades\Log;
use App\Models\Instansi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == HALAMAN PUBLIK & ALUMNI ==

// Halaman utama (Landing Page) dengan form cek NPM
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/cek-npm', [LandingController::class, 'cekNpm'])->name('alumni.login.cek');
Route::get('/verify/{npm}', [LandingController::class, 'showVerifyForm'])->name('alumni.login.show_verify');
Route::post('/login-verify', [LandingController::class, 'verifyLogin'])->name('alumni.login.verify');

// Dashboard bawaan Breeze (untuk alumni setelah login)
Route::get('/dashboard',[AlumniDashboardController::class, 'index'] 
)->middleware(['auth', 'verified'])->name('dashboard');

// Route Profile bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =====================================================================
// == GRUP ROUTE UNTUK PANEL ADMIN ==
// =====================================================================

// Grup untuk semua role admin (Superadmin, BAK, Admin Prodi)
// Menggunakan prefix 'admin' dan nama 'admin.'
Route::middleware(['auth', 'role:superadmin,bak,admin_prodi'])->prefix(env('ADMIN_PATH', 'admin'))->name('admin.')->group(function () {

    // Dashboard Utama Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/responden', [RespondenController::class, 'index'])->name('responden.index');

    // Mengelola data alumni (CRUD)
    Route::resource('alumni', AlumniController::class);

    // Route khusus untuk fitur upload Excel
    Route::get('alumni-import', [AlumniController::class, 'showImportForm'])->name('alumni.import.show');
    Route::post('alumni-import', [AlumniController::class, 'handleImport'])->name('alumni.import.handle');
    Route::get('alumni-template-download', [AlumniController::class, 'downloadTemplate'])->name('alumni.template.download');

    Route::get('kategori-alumni', [AlumniCategoryController::class, 'index'])->name('alumni.kategori');
});

// Grup KHUSUS untuk Super Admin
// Middleware 'role:superadmin' memastikan hanya superadmin yang bisa akses
Route::middleware(['auth', 'role:superadmin'])->prefix(env('ADMIN_PATH', 'admin'))->name('admin.')->group(function () {

    // Mengelola data user (Admin Prodi, BAK, dll)
    Route::resource('users', UserController::class);
    Route::resource('instansi', InstansiController::class);

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/{tahun}', [AlumniDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/{tahun}', [AlumniDashboardController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard/copy', [AlumniDashboardController::class, 'copyAnswers'])->name('dashboard.copy');
    // ... rute profile lainnya
});


Route::prefix('penilaian')->name('instansi.')->group(function() {
    // Rute untuk menampilkan halaman login
    Route::get('/login-instansi', [InstansiLoginController::class, 'showLoginForm'])->name('login.show');
    // Rute untuk memproses data dari form login
    Route::post('/login-instansi', [InstansiLoginController::class, 'login'])->name('login.submit');
});

// Contoh rute untuk dashboard instansi (diberi middleware agar aman)
// Ini adalah halaman yang akan dilihat instansi setelah berhasil login
Route::middleware(['auth', 'role:instansi'])->prefix(env('INSTASI_PATH', 'instansi'))->name('instansi.')->group(function() {
    Route::get('/dashboard', [InstansiDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/data-alumni', [InstansiDashboardController::class, 'dataAlumni'])->name('data_alumni');

    // Rute untuk form penilaian
    Route::get('/alumni/{alumnus}/nilai', [InstansiDashboardController::class, 'showPenilaianForm'])->name('penilaian.show');
    Route::post('/alumni/{alumnus}/nilai', [InstansiDashboardController::class, 'storePenilaian'])->name('penilaian.store');
    
    // RUTE BARU UNTUK PENGATURAN AKUN
    Route::get('/instansi-profile', [InstansiProfileController::class, 'edit'])->name('profile.edit');
    // Rute untuk memperbarui profil (nama & foto)
    Route::put('/instansi-profile', [InstansiProfileController::class, 'updateProfile'])->name('profile.update');
    // Rute untuk memperbarui password
    Route::put('/instansi-password', [InstansiProfileController::class, 'updatePassword'])->name('password.update');
});

// Route::get('/test-log', function() {
//     Log::info('Log test berjalan!');
//     return 'cek storage/logs/laravel.log';
// });

// Memanggil route otentikasi dari Breeze (login, register, dll)
require __DIR__.'/auth.php';
