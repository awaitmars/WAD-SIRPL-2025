<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Redirect Halaman Utama ke Dashboard Jadwal


use App\Http\Controllers\AcademicCalendarController;

Route::resource('academic-calendar', AcademicCalendarController::class)->names([
    'index' => 'academic.index',
    'store' => 'academic.store',
    'update' => 'academic.update',
    'destroy' => 'academic.destroy',
]);

use App\Http\Controllers\MataKuliahController;

Route::get('/', function () {
    return redirect()->route('jadwal.index');
});


// 2. Custom Route untuk Export PDF (TARUH DISINI SEBELUM RESOURCE)
Route::get('/jadwal/export-pdf', [JadwalController::class, 'exportPdf'])->name('jadwal.exportPdf');

// 3. Custom Route untuk DELETE
Route::delete('/jadwal/{type}/{id}', [JadwalController::class, 'destroy'])
    ->name('jadwal.destroy');

// 4. Resource Route Utama
Route::resource('jadwal', JadwalController::class)
    ->except(['destroy', 'create', 'edit', 'show']);

Route::get('/master-mk', [MataKuliahController::class, 'index'])->name('master-mk.index');
Route::post('/master-mk', [MataKuliahController::class, 'store'])->name('master-mk.store');
Route::put('/master-mk/{id}', [MataKuliahController::class, 'update'])->name('master-mk.update');
Route::delete('/master-mk/{id}', [MataKuliahController::class, 'destroy'])->name('master-mk.destroy');
Route::get('/master-mk-download', [MataKuliahController::class, 'downloadPdf'])->name('master-mk.pdf');

