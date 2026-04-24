<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentBooked
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Appointment $appointment,
    ) {}
}
