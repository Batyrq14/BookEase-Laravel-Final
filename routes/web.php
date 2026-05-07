<?php

use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
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
    } elseif ($user->isProvider()) {
        $providerAppointments = Appointment::query()
            ->where('status', 'booked')
            ->whereHas('service', fn ($query) => $query->where('provider_id', $user->id));

        $stats = [
            'assigned_services' => Service::where('provider_id', $user->id)->count(),
            'upcoming' => (clone $providerAppointments)->where('scheduled_at', '>=', now())->count(),
            'today' => (clone $providerAppointments)->whereDate('scheduled_at', today())->count(),
            'completed' => Appointment::query()
                ->where('status', 'completed')
                ->whereHas('service', fn ($query) => $query->where('provider_id', $user->id))
                ->count(),
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

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/browse', [ServiceController::class, 'browse'])->name('services.browse');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/provider/appointments', [AppointmentController::class, 'providerIndex'])
        ->middleware('can:provider')
        ->name('provider.appointments.index');
    Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])
        ->name('appointments.complete');

    Route::middleware('can:admin')->group(function () {
        Route::get('/admin/providers', [ProviderController::class, 'index'])->name('providers.index');
        Route::post('/admin/providers', [ProviderController::class, 'store'])->name('providers.store');
        Route::get('/admin/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments.index');
        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/admin/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/api/appointments/calendar', [AppointmentApiController::class, 'calendar'])->name('appointments.calendar');
    Route::middleware('can:client')->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
        Route::get('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
        Route::patch('/appointments/{appointment}/reschedule', [AppointmentController::class, 'performReschedule'])->name('appointments.reschedule.update');
    });
});

require __DIR__.'/auth.php';
