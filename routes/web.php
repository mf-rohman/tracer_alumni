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


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == HALAMAN PUBLIK & ALUMNI ==

// Halaman utama (Landing Page) dengan form cek NPM
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/cek-npm', [LandingController::class, 'cekNpm'])->name('cek.npm');

// Dashboard bawaan Breeze (untuk alumni setelah login)
Route::get('/dashboard', function () {
    // Di sini nanti bisa diarahkan ke controller khusus dashboard alumni
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
Route::middleware(['auth', 'role:superadmin,bak,admin_prodi'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Utama Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Mengelola data alumni (CRUD)
    Route::resource('alumni', AlumniController::class);

    // Route khusus untuk fitur upload Excel
    Route::get('alumni-import', [AlumniController::class, 'showImportForm'])->name('alumni.import.show');
    Route::post('alumni-import', [AlumniController::class, 'handleImport'])->name('alumni.import.handle');
});

// Grup KHUSUS untuk Super Admin
// Middleware 'role:superadmin' memastikan hanya superadmin yang bisa akses
Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {

    // Mengelola data user (Admin Prodi, BAK, dll)
    Route::resource('users', UserController::class);

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AlumniDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [AlumniDashboardController::class, 'store'])->name('dashboard.store');
    // ... rute profile lainnya
});


// Memanggil route otentikasi dari Breeze (login, register, dll)
require __DIR__.'/auth.php';
