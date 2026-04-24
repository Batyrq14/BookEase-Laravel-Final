<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\AppointmentBooked;
use App\Mail\AppointmentConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAppointmentConfirmation implements ShouldQueue
{
    public function handle(AppointmentBooked $event): void
    {
        $appointment = $event->appointment;

        // Лог (для проверки)
        Log::info('Queue: Sending appointment confirmation', [
            'user_email' => $appointment->user->email,
            'service' => $appointment->service->name,
            'scheduled_at' => $appointment->scheduled_at,
        ]);

        // Email (пока через log/mailtrap)
        Mail::to($appointment->user->email)
            ->send(new AppointmentConfirmationMail($appointment));
    }
}
