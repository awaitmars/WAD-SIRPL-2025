<?php

use App\Http\Controllers\AcademicCalendarController;

Route::resource('academic-calendar', AcademicCalendarController::class)->names([
    'index' => 'academic.index',
    'store' => 'academic.store',
    'update' => 'academic.update',
    'destroy' => 'academic.destroy',
]);