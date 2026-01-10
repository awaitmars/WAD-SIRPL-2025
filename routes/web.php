<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE PUBLIC / GUEST (Bisa diakses tanpa login)
// ==========================================
Route::middleware('guest')->group(function () {
    // Halaman Login
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
    
    // Halaman Register (Publik)
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
});

// Logout (Hanya bisa diakses jika sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// 2. ROUTE PROTECTED (Wajib Login untuk akses fitur ini)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Redirect halaman utama ke dashboard jadwal
    Route::get('/', function () {
        return redirect()->route('jadwal.index');
    });

    // --- FITUR JADWAL ---
    // Route Export PDF (PENTING: Ditaruh sebelum resource agar tidak dianggap ID)
    Route::get('/jadwal/export-pdf', [JadwalController::class, 'exportPdf'])->name('jadwal.exportPdf');
    
    // Custom Delete (karena butuh parameter {type})
    Route::delete('/jadwal/{type}/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    
    // Resource Utama Jadwal (CRUD)
    Route::resource('jadwal', JadwalController::class)
        ->except(['destroy', 'create', 'edit', 'show']);

    // --- FITUR KALENDER AKADEMIK ---
    Route::resource('academic-calendar', AcademicCalendarController::class)->names([
        'index'   => 'academic.index',
        'store'   => 'academic.store',
        'update'  => 'academic.update',
        'destroy' => 'academic.destroy',
    ]);

    // --- FITUR MASTER MK ---
    Route::get('/master-mk', [MataKuliahController::class, 'index'])->name('master-mk.index');
    Route::post('/master-mk', [MataKuliahController::class, 'store'])->name('master-mk.store');
    Route::put('/master-mk/{id}', [MataKuliahController::class, 'update'])->name('master-mk.update');
    Route::delete('/master-mk/{id}', [MataKuliahController::class, 'destroy'])->name('master-mk.destroy');
    Route::get('/master-mk-download', [MataKuliahController::class, 'downloadPdf'])->name('master-mk.pdf');

});