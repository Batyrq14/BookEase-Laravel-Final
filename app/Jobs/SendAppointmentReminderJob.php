<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
    ) {}

    public function handle(): void
    {
        $this->appointment->loadMissing(['user', 'service']);

        Log::info('Appointment reminder queued for delivery.', [
            'appointment_id' => $this->appointment->id,
            'client_email' => $this->appointment->user?->email,
            'service_name' => $this->appointment->service?->name,
            'scheduled_at' => $this->appointment->scheduled_at?->toDateTimeString(),
        ]);
    }
}
