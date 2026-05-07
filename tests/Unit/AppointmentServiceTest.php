<?php

declare(strict_types=1);

use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Enums\AppointmentStatus;
use App\Exceptions\SlotUnavailableException;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

it('calculates end time correctly', function () {
    $service = Mockery::mock(AppointmentRepositoryInterface::class);
    $appointmentService = new AppointmentService($service);

    $start = Carbon::parse('2026-05-01 10:00:00');
    $end = $appointmentService->calculateEndTime($start, 45);

    expect($end->toDateTimeString())->toBe('2026-05-01 10:45:00');
});

it('throws SlotUnavailableException when overlap is detected', function () {
    $mockRepo = Mockery::mock(AppointmentRepositoryInterface::class);

    $mockRepo->shouldReceive('hasOverlappingAppointment')
        ->once()
        ->andReturn(true);

    $appointmentService = new AppointmentService($mockRepo);

    // We need a real Service in the DB for findOrFail
    $service = Service::factory()->create(['duration_minutes' => 60]);

    $appointmentService->book(
        userId: 1,
        serviceId: $service->id,
        scheduledAt: Carbon::parse('2026-05-01 10:00:00'),
    );
})->throws(SlotUnavailableException::class);

it('passes ends_at to repository when booking', function () {
    Event::fake();

    $service = Service::factory()->create(['duration_minutes' => 90]);

    $scheduledAt = Carbon::parse('2026-05-01 10:00:00');
    $expectedEnd = Carbon::parse('2026-05-01 11:30:00');

    $mockRepo = Mockery::mock(AppointmentRepositoryInterface::class);

    $mockRepo->shouldReceive('hasOverlappingAppointment')
        ->once()
        ->withArgs(function (int $serviceId, Carbon $start, Carbon $end) use ($service, $scheduledAt, $expectedEnd) {
            return $serviceId === $service->id
                && $start->eq($scheduledAt)
                && $end->eq($expectedEnd);
        })
        ->andReturn(false);

    $fakeUser = User::factory()->create();

    $fakeAppointment = clone new Appointment([
        'user_id' => $fakeUser->id,
        'service_id' => $service->id,
        'scheduled_at' => $scheduledAt,
        'ends_at' => $expectedEnd,
        'status' => AppointmentStatus::Booked->value,
    ]);

    // Setting an ID manually prevents missing model errors during relationship loading / event serialization
    $fakeAppointment->setAttribute('id', 999);
    $fakeAppointment->exists = true;

    $mockRepo->shouldReceive('createForUser')
        ->once()
        ->withArgs(function (int $userId, array $data) use ($fakeUser, $service, $expectedEnd) {
            return $userId === $fakeUser->id
                && $data['service_id'] === $service->id
                && $data['ends_at']->eq($expectedEnd)
                && $data['status'] === AppointmentStatus::Booked->value;
        })
        ->andReturn($fakeAppointment);

    $appointmentService = new AppointmentService($mockRepo);

    $result = $appointmentService->book(
        userId: $fakeUser->id,
        serviceId: $service->id,
        scheduledAt: $scheduledAt,
    );

    expect($result->ends_at->toDateTimeString())->toBe('2026-05-01 11:30:00');
});

it('delegates cancel to repository', function () {
    $mockRepo = Mockery::mock(AppointmentRepositoryInterface::class);

    $appointment = new Appointment(['status' => AppointmentStatus::Booked->value]);

    $mockRepo->shouldReceive('update')
        ->once()
        ->with($appointment, ['status' => AppointmentStatus::Cancelled->value])
        ->andReturn(true);

    $appointmentService = new AppointmentService($mockRepo);

    expect($appointmentService->cancel($appointment))->toBeTrue();
});
