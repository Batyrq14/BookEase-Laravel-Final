<?php

use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        $stats = [
            'total_services' => Service::count(),
            'total_appointments' => Appointment::count(),
            'today' => Appointment::whereDate('scheduled_at', today())
                ->where('status', 'booked')->count(),
            'upcoming_week' => Appointment::whereBetween('scheduled_at', [now(), now()->endOfWeek()])
                ->where('status', 'booked')->count(),
        ];
    } else {
        $next = Appointment::with('service')
            ->where('user_id', $user->id)
            ->where('status', 'booked')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->first();

        $stats = [
            'next' => $next,
            'upcoming' => Appointment::where('user_id', $user->id)->where('status', 'booked')->where('scheduled_at', '>', now())->count(),
            'completed' => Appointment::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total' => Appointment::where('user_id', $user->id)->count(),
        ];
    }

    return view('dashboard', compact('stats'));
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
    Route::get('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
    Route::patch('/appointments/{appointment}/reschedule', [AppointmentController::class, 'performReschedule'])->name('appointments.reschedule.update');
});

require __DIR__.'/auth.php';
