<?php

use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\Api\ServiceApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/services', [ServiceApiController::class, 'index']);

Route::middleware(['auth:sanctum', 'can:client'])->group(function () {
    Route::get('/appointments', [AppointmentApiController::class, 'index']);
    Route::post('/appointments', [AppointmentApiController::class, 'store']);
    Route::delete('/appointments/{appointment}', [AppointmentApiController::class, 'destroy']);
});
