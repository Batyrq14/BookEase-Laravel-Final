<?php

declare(strict_types=1);

use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Enums\AppointmentStatus;
use App\Exceptions\SlotUnavailableException;
use App\Jobs\SendAppointmentReminderJob;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

it('calculates ends_at correctly from scheduled_at and duration', function () {
    $mockRepo = Mockery::mock(AppointmentRepositoryInterface::class);
    $appointmentService = new AppointmentService($mockRepo);

    $start = Carbon::parse('2026-05-01 10:00:00');
    $end = $appointmentService->calculateEndTime($start, 45);

    expect($end->toDateTimeString())->toBe('2026-05-01 10:45:00');
});

it('throws SlotUnavailableException when the repository reports an overlap', function () {
    $mockRepo = Mockery::mock(AppointmentRepositoryInterface::class);

    $mockRepo->shouldReceive('hasOverlappingAppointment')
        ->once()
        ->andReturn(true);

    $appointmentService = new AppointmentService($mockRepo);

    $service = Service::factory()->create(['duration_minutes' => 60]);

    $appointmentService->book(
        userId: 1,
        serviceId: $service->id,
        scheduledAt: Carbon::parse('2026-05-01 10:00:00'),
    );
})->throws(SlotUnavailableException::class);

it('passes the correct ends_at to the repository when booking', function () {
    Event::fake();
    Queue::fake();

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

    $client = User::factory()->create(['role' => 'client']);

    $fakeAppointment = clone new Appointment([
        'user_id' => $client->id,
        'service_id' => $service->id,
        'scheduled_at' => $scheduledAt,
        'ends_at' => $expectedEnd,
        'status' => AppointmentStatus::Booked->value,
    ]);
    $fakeAppointment->setAttribute('id', 999);
    $fakeAppointment->exists = true;

    $mockRepo->shouldReceive('createForUser')
        ->once()
        ->withArgs(function (int $userId, array $data) use ($client, $service, $expectedEnd) {
            return $userId === $client->id
                && $data['service_id'] === $service->id
                && $data['ends_at']->eq($expectedEnd)
                && $data['status'] === AppointmentStatus::Booked->value;
        })
        ->andReturn($fakeAppointment);

    $appointmentService = new AppointmentService($mockRepo);

    $result = $appointmentService->book(
        userId: $client->id,
        serviceId: $service->id,
        scheduledAt: $scheduledAt,
    );

    expect($result->ends_at->toDateTimeString())->toBe('2026-05-01 11:30:00');

    Queue::assertPushed(SendAppointmentReminderJob::class, function (SendAppointmentReminderJob $job) use ($fakeAppointment) {
        return $job->appointment->is($fakeAppointment);
    });
});

it('delegates cancellation to the repository', function () {
    $mockRepo = Mockery::mock(AppointmentRepositoryInterface::class);
    $appointment = new Appointment(['status' => AppointmentStatus::Booked->value]);

    $mockRepo->shouldReceive('update')
        ->once()
        ->with($appointment, ['status' => AppointmentStatus::Cancelled->value])
        ->andReturn(true);

    $appointmentService = new AppointmentService($mockRepo);

    expect($appointmentService->cancel($appointment))->toBeTrue();
});

it('syncs service providers through the pivot relationship', function () {
    $service = Service::factory()->create();
    $providers = User::factory()->count(2)->provider()->create();

    $service->providers()->sync($providers->pluck('id')->all());

    $syncedProviderIds = $service->load('providers')->providers->pluck('id')->all();

    expect($syncedProviderIds)->toHaveCount(2)
        ->and($syncedProviderIds)->toEqualCanonicalizing($providers->pluck('id')->all());
});
