<?php

use App\Http\Controllers\BudgetController;

Route::get('/', [BudgetController::class, 'index']); // Halaman awal
Route::get('/anggaran', [BudgetController::class, 'index'])->name('budget.index');
Route::post('/anggaran', [BudgetController::class, 'store'])->name('budget.store');
Route::put('/anggaran/{id}', [BudgetController::class, 'update'])->name('budget.update'); // Route Update
Route::delete('/anggaran/{id}', [BudgetController::class, 'destroy'])->name('budget.destroy'); // Route Delete
Route::get('/anggaran/cetak', [BudgetController::class, 'cetakPdf'])->name('budget.cetak');