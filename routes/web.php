<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MataKuliahController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/master-mk', [MataKuliahController::class, 'index'])->name('master-mk.index');
Route::post('/master-mk', [MataKuliahController::class, 'store'])->name('master-mk.store');
Route::put('/master-mk/{id}', [MataKuliahController::class, 'update'])->name('master-mk.update');
Route::delete('/master-mk/{id}', [MataKuliahController::class, 'destroy'])->name('master-mk.destroy');
Route::get('/master-mk-download', [MataKuliahController::class, 'downloadPdf'])->name('master-mk.pdf');