<?php

use App\Http\Controllers\MahasiswaAuthController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MatakuliahController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman utama - redirect jika sudah login sebagai dosen
Route::get('/', function () {
    // Jika dosen sudah login, redirect ke dashboard dosen
    if (Auth::check() && Auth::user()->role === 'dosen') {
        return redirect()->route('dosen.dashboard');
    }
    
    // Jika mahasiswa sudah login (pakai session mahasiswa)
    if (session()->has('mahasiswa')) {
        return redirect()->route('mahasiswa.dashboard');
    }
    
    // Jika belum login, tampilkan welcome page
    return view('welcome');
})->name('welcome');

// ========== ROUTE MAHASISWA ==========
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Guest routes
    Route::middleware(['guest'])->group(function () {
        Route::get('login', [MahasiswaAuthController::class, 'showLoginForm'])->name('login.form');
        Route::post('login', [MahasiswaAuthController::class, 'login'])->name('login');
        Route::get('register', [MahasiswaAuthController::class, 'showRegisterForm'])->name('register.form');
        Route::post('register', [MahasiswaAuthController::class, 'register'])->name('register');
    });
    
    Route::post('logout', [MahasiswaAuthController::class, 'logout'])->name('logout');
    
    // Auth routes
    Route::middleware(['mahasiswa.auth'])->group(function () {
        Route::get('dashboard', [KrsController::class, 'dashboard'])->name('dashboard');
        Route::post('update-semester', [KrsController::class, 'updateSemester'])->name('update-semester');
        Route::post('store', [KrsController::class, 'store'])->name('store');
        Route::get('cetak-krs', [KrsController::class, 'cetakKrs'])->name('cetak-krs');
    });
});

// ========== ROUTE DOSEN ==========
Route::middleware(['auth'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
    Route::get('krs/{id}/detail', [DosenController::class, 'detailKrs'])->name('krs.detail');
    Route::post('krs/{id}/approve', [DosenController::class, 'approveKrs'])->name('krs.approve');
    Route::post('krs/{id}/reject', [DosenController::class, 'rejectKrs'])->name('krs.reject');
    Route::put('mahasiswa/{id}', [DosenController::class, 'updateMahasiswa'])->name('mahasiswa.update');
});

// ========== ROUTE MATA KULIAH ==========
Route::middleware(['auth'])->prefix('matakuliah')->name('matakuliah.')->group(function () {
    Route::get('/', [MatakuliahController::class, 'index'])->name('index');
    Route::get('create', [MatakuliahController::class, 'create'])->name('create');
    Route::post('/', [MatakuliahController::class, 'store'])->name('store');
    Route::get('{id}/edit', [MatakuliahController::class, 'edit'])->name('edit');
    Route::put('{id}', [MatakuliahController::class, 'update'])->name('update');
    Route::delete('{id}', [MatakuliahController::class, 'destroy'])->name('destroy');
});

// Auth routes untuk Breeze
require __DIR__.'/auth.php';