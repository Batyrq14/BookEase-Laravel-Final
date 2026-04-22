<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }

    public function allForUser(int $userId): Collection
    {
        return Appointment::with('service')
            ->where('user_id', $userId)
            ->latest('scheduled_at')
            ->get();
    }

    public function bookedForServiceOnDate(int $serviceId, Carbon $date): Collection
    {
        return Appointment::with('service')
            ->where('service_id', $serviceId)
            ->where('status', AppointmentStatus::Booked->value)
            ->whereDate('scheduled_at', $date->toDateString())
            ->get();
    }

    public function createForUser(int $userId, array $data): Appointment
    {
        /** @var Appointment */
        return Appointment::create([...$data, 'user_id' => $userId]);
    }
}
