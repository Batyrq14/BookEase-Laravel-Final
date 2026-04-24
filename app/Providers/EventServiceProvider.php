<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\AppointmentBooked;
use App\Listeners\SendAppointmentConfirmation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AppointmentBooked::class => [
            SendAppointmentConfirmation::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
