<?php

use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('can:admin')->group(function () {
        Route::resource('services', ServiceController::class);
        Route::get('/admin/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments.index');
        Route::patch('/admin/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('admin.appointments.complete');
    });

    Route::get('/api/appointments/calendar', [AppointmentApiController::class, 'calendar'])->name('appointments.calendar');
    Route::resource('appointments', AppointmentController::class);
});

require __DIR__.'/auth.php';
