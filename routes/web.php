<?php
use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Route;

Route::get('/kliping-isu', [BeritaController::class, 'index'])->name('kliping.index');
Route::post('/kliping-isu/store', [BeritaController::class, 'store'])->name('kliping.store');
Route::put('/kliping-isu/update/{id}', [BeritaController::class, 'updateNote'])->name('kliping.update');
Route::delete('/kliping-isu/delete/{id}', [BeritaController::class, 'destroy'])->name('kliping.destroy');
Route::get('/kliping-isu/cetak-pdf/{id}', [BeritaController::class, 'cetakPdf'])->name('kliping.cetakPdf');
Route::get('/kliping-isu/cetak-pdf/{id}', [BeritaController::class, 'cetakPdf'])->name('kliping.cetakPdf');
Route::get('/kliping-isu/cetak-semua', [BeritaController::class, 'cetakSemuaPdf'])->name('kliping.cetakSemua');