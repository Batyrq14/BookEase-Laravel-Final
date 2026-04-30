<?php

declare(strict_types=1);

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Carbon::setTestNow('2026-05-01 09:00:00');
});

afterEach(function () {
    Carbon::setTestNow();
});

it('allows an admin to create a provider account with staff profile data', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('providers.store'), [
        'mode' => 'create',
        'name' => 'Taylor Provider',
        'email' => 'taylor.provider@example.com',
        'phone' => '+1 555 111 2222',
        'bio' => 'Experienced stylist with a focus on recurring clients.',
        'profile_photo' => UploadedFile::fake()->image('provider.jpg'),
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('providers.index'));

    $provider = User::query()->where('email', 'taylor.provider@example.com')->first();

    expect($provider)->not->toBeNull()
        ->and($provider->role)->toBe(UserRole::Provider)
        ->and($provider->bio)->toContain('Experienced stylist')
        ->and($provider->profile_photo_path)->not->toBeNull();

    Storage::disk('public')->assertExists($provider->profile_photo_path);
});

it('allows an admin to invite a provider and returns a reset link', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('providers.store'), [
        'mode' => 'invite',
        'name' => 'Invited Provider',
        'email' => 'invite.provider@example.com',
        'phone' => '+1 555 111 3333',
        'bio' => 'Will join next week.',
    ]);

    $response->assertRedirect(route('providers.index'))
        ->assertSessionHas('invitation_link')
        ->assertSessionHas('invited_provider_email', 'invite.provider@example.com');

    $provider = User::query()->where('email', 'invite.provider@example.com')->first();

    expect($provider)->not->toBeNull()
        ->and($provider->role)->toBe(UserRole::Provider);
});

it('shows providers only their own upcoming appointments', function () {
    $provider = User::factory()->provider()->create(['name' => 'Primary Provider']);
    $otherProvider = User::factory()->provider()->create(['name' => 'Other Provider']);
    $client = User::factory()->client()->create(['name' => 'Visible Client']);

    $providerService = Service::factory()->create([
        'name' => 'Visible Service',
        'provider_id' => $provider->id,
        'creator_user_id' => User::factory()->admin()->create()->id,
    ]);
    $otherService = Service::factory()->create([
        'name' => 'Hidden Service',
        'provider_id' => $otherProvider->id,
        'creator_user_id' => User::factory()->admin()->create()->id,
    ]);

    Appointment::factory()->create([
        'user_id' => $client->id,
        'service_id' => $providerService->id,
        'scheduled_at' => now()->addDay(),
        'ends_at' => now()->addDay()->addHour(),
        'status' => AppointmentStatus::Booked->value,
        'notes' => 'Visible appointment',
    ]);
    Appointment::factory()->create([
        'user_id' => $client->id,
        'service_id' => $providerService->id,
        'scheduled_at' => now()->subDay(),
        'ends_at' => now()->subDay()->addHour(),
        'status' => AppointmentStatus::Booked->value,
        'notes' => 'Past appointment',
    ]);
    Appointment::factory()->create([
        'user_id' => $client->id,
        'service_id' => $otherService->id,
        'scheduled_at' => now()->addDay(),
        'ends_at' => now()->addDay()->addHour(),
        'status' => AppointmentStatus::Booked->value,
        'notes' => 'Hidden appointment',
    ]);

    $response = $this->actingAs($provider)->get(route('provider.appointments.index'));

    $response->assertOk()
        ->assertSee('Visible Service')
        ->assertSee('Visible Client')
        ->assertDontSee('Hidden Service')
        ->assertDontSee('Past appointment')
        ->assertDontSee('Hidden appointment');
});

it('allows providers to complete only their own appointments', function () {
    $provider = User::factory()->provider()->create();
    $otherProvider = User::factory()->provider()->create();
    $client = User::factory()->client()->create();
    $admin = User::factory()->admin()->create();

    $ownService = Service::factory()->create([
        'provider_id' => $provider->id,
        'creator_user_id' => $admin->id,
    ]);
    $otherService = Service::factory()->create([
        'provider_id' => $otherProvider->id,
        'creator_user_id' => $admin->id,
    ]);

    $ownAppointment = Appointment::factory()->create([
        'user_id' => $client->id,
        'service_id' => $ownService->id,
        'scheduled_at' => now()->addDay(),
        'ends_at' => now()->addDay()->addHour(),
        'status' => AppointmentStatus::Booked->value,
    ]);
    $otherAppointment = Appointment::factory()->create([
        'user_id' => $client->id,
        'service_id' => $otherService->id,
        'scheduled_at' => now()->addDay(),
        'ends_at' => now()->addDay()->addHour(),
        'status' => AppointmentStatus::Booked->value,
    ]);

    $this->actingAs($provider)
        ->patch(route('appointments.complete', $ownAppointment))
        ->assertRedirect();

    $this->assertDatabaseHas('appointments', [
        'id' => $ownAppointment->id,
        'status' => AppointmentStatus::Completed->value,
    ]);

    $this->actingAs($provider)
        ->patch(route('appointments.complete', $otherAppointment))
        ->assertForbidden();

    $this->assertDatabaseHas('appointments', [
        'id' => $otherAppointment->id,
        'status' => AppointmentStatus::Booked->value,
    ]);
});

it('prevents providers from deleting services created by an admin', function () {
    $admin = User::factory()->admin()->create();
    $provider = User::factory()->provider()->create();

    $service = Service::factory()->create([
        'provider_id' => $provider->id,
        'creator_user_id' => $admin->id,
    ]);

    $this->actingAs($provider)
        ->delete(route('services.destroy', $service))
        ->assertForbidden();

    $this->assertDatabaseHas('services', ['id' => $service->id]);
});
