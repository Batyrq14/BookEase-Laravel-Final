<?php

declare(strict_types=1);

use App\Enums\AppointmentStatus;
use App\Exceptions\SlotUnavailableException;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->service = Service::factory()->create(['duration_minutes' => 60]);
    $this->client = User::factory()->create(['role' => 'client']);

    $this->appointmentService = app(AppointmentService::class);
});

// ─────────────────────────────────────────────────────
//  Happy-path bookings
// ─────────────────────────────────────────────────────

it('records an appointment when the slot is free', function () {
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $appointment = $this->appointmentService->book(
        userId: $this->client->id,
        serviceId: $this->service->id,
        scheduledAt: $scheduledAt,
    );

    expect($appointment)
        ->toBeInstanceOf(Appointment::class)
        ->status->toBe(AppointmentStatus::Booked->value)
        ->scheduled_at->toDateTimeString()->toBe($scheduledAt->toDateTimeString())
        ->ends_at->toDateTimeString()->toBe($scheduledAt->copy()->addMinutes(60)->toDateTimeString());

    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'user_id' => $this->client->id,
        'service_id' => $this->service->id,
        'status' => 'booked',
    ]);
});

it('auto-calculates ends_at based on service duration', function () {
    $shortService = Service::factory()->create(['duration_minutes' => 30]);
    $scheduledAt = Carbon::tomorrow()->setTime(14, 0);

    $appointment = $this->appointmentService->book(
        userId: $this->client->id,
        serviceId: $shortService->id,
        scheduledAt: $scheduledAt,
    );

    expect($appointment->ends_at->toDateTimeString())
        ->toBe($scheduledAt->copy()->addMinutes(30)->toDateTimeString());
});

it('allows back-to-back bookings on the same service', function () {
    $start1 = Carbon::tomorrow()->setTime(10, 0);
    $start2 = Carbon::tomorrow()->setTime(11, 0); // starts exactly when the first ends

    $this->appointmentService->book($this->client->id, $this->service->id, $start1);
    $second = $this->appointmentService->book($this->client->id, $this->service->id, $start2);

    expect($second)->toBeInstanceOf(Appointment::class);
    $this->assertDatabaseCount('appointments', 2);
});

it('allows the same time slot for two different services', function () {
    $otherService = Service::factory()->create(['duration_minutes' => 60]);
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $this->appointmentService->book($this->client->id, $this->service->id, $scheduledAt);
    $second = $this->appointmentService->book($this->client->id, $otherService->id, $scheduledAt);

    expect($second)->toBeInstanceOf(Appointment::class);
    $this->assertDatabaseCount('appointments', 2);
});

it('frees a slot after its appointment is cancelled', function () {
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $appointment = $this->appointmentService->book($this->client->id, $this->service->id, $scheduledAt);
    $this->appointmentService->cancel($appointment);

    $newBooking = $this->appointmentService->book($this->client->id, $this->service->id, $scheduledAt);

    expect($newBooking)
        ->toBeInstanceOf(Appointment::class)
        ->status->toBe(AppointmentStatus::Booked->value);
});

// ─────────────────────────────────────────────────────
//  Overlap rejections
// ─────────────────────────────────────────────────────

it('rejects an exact duplicate booking', function () {
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $this->appointmentService->book($this->client->id, $this->service->id, $scheduledAt);
    $this->appointmentService->book($this->client->id, $this->service->id, $scheduledAt);
})->throws(SlotUnavailableException::class);

it('rejects a booking that starts before and overlaps an existing one', function () {
    // Existing: 10:00 – 11:00
    // New:       9:30 – 10:30  ← overlaps by 30 min
    $this->appointmentService->book(
        $this->client->id, $this->service->id, Carbon::tomorrow()->setTime(10, 0),
    );

    $this->appointmentService->book(
        $this->client->id, $this->service->id, Carbon::tomorrow()->setTime(9, 30),
    );
})->throws(SlotUnavailableException::class);

it('rejects a booking that starts inside and overflows an existing one', function () {
    // Existing: 10:00 – 11:00
    // New:      10:30 – 11:30  ← overlaps by 30 min
    $this->appointmentService->book(
        $this->client->id, $this->service->id, Carbon::tomorrow()->setTime(10, 0),
    );

    $this->appointmentService->book(
        $this->client->id, $this->service->id, Carbon::tomorrow()->setTime(10, 30),
    );
})->throws(SlotUnavailableException::class);

it('rejects a booking that fully wraps an existing one', function () {
    $longService = Service::factory()->create(['duration_minutes' => 180]);

    // Existing: 10:00 – 13:00 (180 min)
    $this->appointmentService->book(
        $this->client->id, $longService->id, Carbon::tomorrow()->setTime(10, 0),
    );

    // New: 09:00 – 12:00 (180 min) — wraps the existing slot
    $this->appointmentService->book(
        $this->client->id, $longService->id, Carbon::tomorrow()->setTime(9, 0),
    );
})->throws(SlotUnavailableException::class);

// ─────────────────────────────────────────────────────
//  Reschedule
// ─────────────────────────────────────────────────────

it('reschedules an appointment to a free slot', function () {
    $appointment = $this->appointmentService->book(
        $this->client->id, $this->service->id, Carbon::tomorrow()->setTime(10, 0),
    );

    $newTime = Carbon::tomorrow()->setTime(14, 0);
    $result = $this->appointmentService->reschedule($appointment, $newTime);

    expect($result)->toBeTrue();

    $appointment->refresh();
    expect($appointment->scheduled_at->toDateTimeString())->toBe($newTime->toDateTimeString());
    expect($appointment->ends_at->toDateTimeString())->toBe($newTime->copy()->addMinutes(60)->toDateTimeString());
});

it('rejects rescheduling to an already-occupied slot', function () {
    $this->appointmentService->book(
        $this->client->id, $this->service->id, Carbon::tomorrow()->setTime(10, 0),
    );

    $second = $this->appointmentService->book(
        $this->client->id, $this->service->id, Carbon::tomorrow()->setTime(14, 0),
    );

    $this->appointmentService->reschedule($second, Carbon::tomorrow()->setTime(10, 0));
})->throws(SlotUnavailableException::class);

it('allows rescheduling to the same time without a self-conflict', function () {
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $appointment = $this->appointmentService->book(
        $this->client->id, $this->service->id, $scheduledAt,
    );

    $result = $this->appointmentService->reschedule($appointment, $scheduledAt->copy());

    expect($result)->toBeTrue();
});

// ─────────────────────────────────────────────────────
//  API conflict response
// ─────────────────────────────────────────────────────

it('returns HTTP 409 with slot_unavailable when the API slot is taken', function () {
    $scheduledAt = Carbon::now()->next(Carbon::MONDAY)->setTime(10, 0);

    $this->appointmentService->book($this->client->id, $this->service->id, $scheduledAt);

    $response = $this->actingAs($this->client)
        ->postJson('/api/appointments', [
            'service_id' => $this->service->id,
            'scheduled_at' => $scheduledAt->toDateTimeString(),
        ]);

    $response->assertStatus(409)
        ->assertJsonPath('error', 'slot_unavailable');
});
