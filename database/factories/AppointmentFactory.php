<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<Appointment> */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $service = Service::factory();
        $scheduledAt = Carbon::instance(fake()->dateTimeBetween('+1 day', '+30 days'))
            ->setTime(fake()->numberBetween(9, 17), 0);

        return [
            'user_id' => User::factory(),
            'service_id' => $service,
            'scheduled_at' => $scheduledAt,
            'ends_at' => $scheduledAt->copy()->addMinutes(60), // Default; overridden by service in tests
            'status' => AppointmentStatus::Booked->value,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /** Create an appointment with ends_at auto-calculated from the service duration. */
    public function forService(Service $service): static
    {
        return $this->state(fn (array $attributes) => [
            'service_id' => $service->id,
            'ends_at' => Carbon::parse($attributes['scheduled_at'])
                ->addMinutes($service->duration_minutes),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => AppointmentStatus::Cancelled->value,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => AppointmentStatus::Completed->value,
        ]);
    }
}
