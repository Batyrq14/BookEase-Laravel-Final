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
    $this->service = Service::factory()->create([
        'name' => 'Haircut',
        'duration_minutes' => 60,
        'price' => 25.00,
    ]);

    $this->user = User::factory()->create(['role' => 'client']);

    $this->appointmentService = app(AppointmentService::class);
});

// ─────────────────────────────────────────────────────
//  Happy-path bookings
// ─────────────────────────────────────────────────────

it('books an appointment when the slot is free', function () {
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $appointment = $this->appointmentService->book(
        userId: $this->user->id,
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
        'user_id' => $this->user->id,
        'service_id' => $this->service->id,
        'status' => 'booked',
    ]);
});

it('auto-calculates ends_at based on service duration', function () {
    $shortService = Service::factory()->create(['duration_minutes' => 30]);
    $scheduledAt = Carbon::tomorrow()->setTime(14, 0);

    $appointment = $this->appointmentService->book(
        userId: $this->user->id,
        serviceId: $shortService->id,
        scheduledAt: $scheduledAt,
    );

    expect($appointment->ends_at->toDateTimeString())
        ->toBe($scheduledAt->copy()->addMinutes(30)->toDateTimeString());
});

it('allows booking adjacent (back-to-back) slots', function () {
    $start1 = Carbon::tomorrow()->setTime(10, 0);
    $start2 = Carbon::tomorrow()->setTime(11, 0); // starts exactly when first ends

    $this->appointmentService->book($this->user->id, $this->service->id, $start1);
    $second = $this->appointmentService->book($this->user->id, $this->service->id, $start2);

    expect($second)->toBeInstanceOf(Appointment::class);
    $this->assertDatabaseCount('appointments', 2);
});

it('allows the same time for different services', function () {
    $otherService = Service::factory()->create(['duration_minutes' => 60]);
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $this->appointmentService->book($this->user->id, $this->service->id, $scheduledAt);
    $second = $this->appointmentService->book($this->user->id, $otherService->id, $scheduledAt);

    expect($second)->toBeInstanceOf(Appointment::class);
    $this->assertDatabaseCount('appointments', 2);
});

it('allows booking a slot previously held by a cancelled appointment', function () {
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $appointment = $this->appointmentService->book($this->user->id, $this->service->id, $scheduledAt);
    $this->appointmentService->cancel($appointment);

    $newBooking = $this->appointmentService->book($this->user->id, $this->service->id, $scheduledAt);

    expect($newBooking)
        ->toBeInstanceOf(Appointment::class)
        ->status->toBe(AppointmentStatus::Booked->value);
});

// ─────────────────────────────────────────────────────
//  Overlap rejections (the core of the engine)
// ─────────────────────────────────────────────────────

it('rejects exact duplicate booking', function () {
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $this->appointmentService->book($this->user->id, $this->service->id, $scheduledAt);

    $this->appointmentService->book($this->user->id, $this->service->id, $scheduledAt);
})->throws(SlotUnavailableException::class);

it('rejects partial overlap at the start', function () {
    // Existing: 10:00 – 11:00
    // New:       9:30 – 10:30 ← overlaps by 30 min
    $this->appointmentService->book(
        $this->user->id,
        $this->service->id,
        Carbon::tomorrow()->setTime(10, 0),
    );

    $this->appointmentService->book(
        $this->user->id,
        $this->service->id,
        Carbon::tomorrow()->setTime(9, 30),
    );
})->throws(SlotUnavailableException::class);

it('rejects partial overlap at the end', function () {
    // Existing: 10:00 – 11:00
    // New:      10:30 – 11:30 ← overlaps by 30 min
    $this->appointmentService->book(
        $this->user->id,
        $this->service->id,
        Carbon::tomorrow()->setTime(10, 0),
    );

    $this->appointmentService->book(
        $this->user->id,
        $this->service->id,
        Carbon::tomorrow()->setTime(10, 30),
    );
})->throws(SlotUnavailableException::class);

it('rejects an enclosing booking that encompasses an existing one', function () {
    // Use a long-duration service so the new booking fully wraps the existing one.
    $longService = Service::factory()->create(['duration_minutes' => 180]);

    // Existing: 10:00 – 13:00 (180 min)
    $this->appointmentService->book(
        $this->user->id,
        $longService->id,
        Carbon::tomorrow()->setTime(10, 0),
    );

    // New: 9:00 – 12:00 (180 min) — overlaps with existing 10:00-13:00
    $this->appointmentService->book(
        $this->user->id,
        $longService->id,
        Carbon::tomorrow()->setTime(9, 0),
    );
})->throws(SlotUnavailableException::class);

// ─────────────────────────────────────────────────────
//  Reschedule
// ─────────────────────────────────────────────────────

it('reschedules an appointment to a free slot', function () {
    $appointment = $this->appointmentService->book(
        $this->user->id,
        $this->service->id,
        Carbon::tomorrow()->setTime(10, 0),
    );

    $newTime = Carbon::tomorrow()->setTime(14, 0);
    $result = $this->appointmentService->reschedule($appointment, $newTime);

    expect($result)->toBeTrue();

    $appointment->refresh();
    expect($appointment->scheduled_at->toDateTimeString())->toBe($newTime->toDateTimeString());
    expect($appointment->ends_at->toDateTimeString())->toBe($newTime->copy()->addMinutes(60)->toDateTimeString());
});

it('rejects rescheduling to an occupied slot', function () {
    $this->appointmentService->book(
        $this->user->id,
        $this->service->id,
        Carbon::tomorrow()->setTime(10, 0),
    );

    $second = $this->appointmentService->book(
        $this->user->id,
        $this->service->id,
        Carbon::tomorrow()->setTime(14, 0),
    );

    // Try to move the second appointment to 10:00 (already taken)
    $this->appointmentService->reschedule($second, Carbon::tomorrow()->setTime(10, 0));
})->throws(SlotUnavailableException::class);

it('allows rescheduling to the same time (no self-conflict)', function () {
    $scheduledAt = Carbon::tomorrow()->setTime(10, 0);

    $appointment = $this->appointmentService->book(
        $this->user->id,
        $this->service->id,
        $scheduledAt,
    );

    $result = $this->appointmentService->reschedule($appointment, $scheduledAt->copy());

    expect($result)->toBeTrue();
});

// ─────────────────────────────────────────────────────
//  Exception rendering
// ─────────────────────────────────────────────────────

it('returns 409 JSON response for API conflict', function () {
    // Use next Monday to avoid the "no weekends" validation rule.
    $scheduledAt = Carbon::now()->next(Carbon::MONDAY)->setTime(10, 0);

    $this->appointmentService->book($this->user->id, $this->service->id, $scheduledAt);

    $response = $this->actingAs($this->user)
        ->postJson('/api/appointments', [
            'service_id' => $this->service->id,
            'scheduled_at' => $scheduledAt->toDateTimeString(),
        ]);

    $response->assertStatus(409)
        ->assertJsonPath('error', 'slot_unavailable');
});
