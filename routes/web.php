<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BeritaController;

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

    Route::get('/', function () {
        return redirect()->route('login');
    });
});


// ==========================================
// 2. ROUTE PROTECTED (Wajib Login untuk akses fitur ini)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Redirect halaman utama ke dashboard jadwal
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });

    // --- FITUR JADWAL ---
    // Route Export PDF 
    Route::get('/jadwal/export-pdf', [JadwalController::class, 'exportPdf'])->name('jadwal.exportPdf');
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
    Route::prefix('master-mk')->name('master-mk.')->group(function () {
        Route::get('/', [MataKuliahController::class, 'index'])->name('index');
        Route::post('/', [MataKuliahController::class, 'store'])->name('store');
        Route::put('/{id}', [MataKuliahController::class, 'update'])->name('update');
        Route::delete('/{id}', [MataKuliahController::class, 'destroy'])->name('destroy');
        Route::get('/download', [MataKuliahController::class, 'downloadPdf'])->name('pdf');
    });

        //Anggaran
    Route::prefix('anggaran')->name('budget.')->group(function () {
        Route::get('/', [BudgetController::class, 'index'])->name('index');
        Route::post('/', [BudgetController::class, 'store'])->name('store');
        Route::put('/{id}', [BudgetController::class, 'update'])->name('update'); // Route Update
        Route::delete('/{id}', [BudgetController::class, 'destroy'])->name('destroy'); // Route Delete
        Route::get('/cetak', [BudgetController::class, 'cetakPdf'])->name('cetak');
    });

    Route::prefix('kliping')->name('kliping.')->group(function () {
        Route::get('/', [BeritaController::class, 'index'])->name('index');
        Route::post('/', [BeritaController::class, 'store'])->name('store');
        Route::put('/{id}', [BeritaController::class, 'update'])->name('update');
        Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('destroy');

        Route::get('/cetak-semua', [BeritaController::class, 'cetakSemuaPdf'])->name('cetakSemua');
        Route::get('/cetak-pdf/{id}', [BeritaController::class, 'cetakPdf'])->name('cetakPdf');
    });
});