<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Models\Appointment;
use App\Models\User;
use App\Policies\AppointmentPolicy;
use App\Repositories\AppointmentRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AppointmentRepositoryInterface::class,
            AppointmentRepository::class,
        );
    }

    public function boot(): void
    {
        Gate::define('admin', fn (User $user) => $user->isAdmin());
        Gate::define('client', fn (User $user) => $user->isClient());

        Gate::policy(Appointment::class, AppointmentPolicy::class);
    }
}
