<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isClient() || $user->isProvider();
    }

    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->isClient()) {
            return $user->id === $appointment->user_id;
        }

        return $user->isProvider()
            && $appointment->belongsToProvider($user)
            && $appointment->status === AppointmentStatus::Booked->value
            && $appointment->scheduled_at->isFuture();
    }

    public function create(User $user): bool
    {
        return $user->isClient();
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->user_id
            && $appointment->status === AppointmentStatus::Booked->value;
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $user->isClient() && $user->id === $appointment->user_id;
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return false;
    }

    public function complete(User $user, Appointment $appointment): bool
    {
        return $user->isProvider()
            && $appointment->belongsToProvider($user)
            && $appointment->status === AppointmentStatus::Booked->value;
    }
}
