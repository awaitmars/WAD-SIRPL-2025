<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Redirect Halaman Utama ke Dashboard Jadwal
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