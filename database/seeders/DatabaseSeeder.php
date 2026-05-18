<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'email' => 'admin@bookease.kz',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin->value,
        ]);

        $categories = collect([
            'Hair & Beauty',
            'Wellness',
            'Home Services',
            'Automotive',
            'Education',
            'Healthcare',
        ])->map(fn (string $name): Category => Category::firstOrCreate(['name' => $name]));

        $providers = collect([
            ['name' => 'Aruzhan Stylist', 'email' => 'aruzhan.provider@example.com'],
            ['name' => 'Daniyar Therapist', 'email' => 'daniyar.provider@example.com'],
            ['name' => 'Mira Tutor', 'email' => 'mira.provider@example.com'],
            ['name' => 'Timur Mechanic', 'email' => 'timur.provider@example.com'],
            ['name' => 'Aigerim Cleaner', 'email' => 'aigerim.provider@example.com'],
            ['name' => 'Nurlan Doctor', 'email' => 'nurlan.provider@example.com'],
        ])->map(fn (array $provider): User => User::updateOrCreate([
            'email' => $provider['email'],
        ], [
            'name' => $provider['name'],
            'password' => Hash::make('password'),
            'role' => UserRole::Provider->value,
            'bio' => fake()->paragraph(),
        ]));

        $clients = User::factory()->count(10)->client()->create();

        $services = collect([
            ['Haircut & Styling', 'Hair & Beauty', 60, 12000],
            ['Deep Tissue Massage', 'Wellness', 90, 22000],
            ['Math Tutoring Session', 'Education', 60, 10000],
            ['Oil Change Service', 'Automotive', 45, 15000],
            ['Apartment Cleaning', 'Home Services', 120, 30000],
            ['General Consultation', 'Healthcare', 30, 18000],
            ['Manicure Appointment', 'Hair & Beauty', 75, 14000],
            ['Yoga Coaching', 'Wellness', 60, 9000],
        ])->map(function (array $serviceData, int $index) use ($admin, $categories, $providers): Service {
            [$name, $categoryName, $duration, $price] = $serviceData;
            $primaryProvider = $providers[$index % $providers->count()];

            $service = Service::updateOrCreate([
                'name' => $name,
            ], [
                'description' => fake()->sentence(12),
                'duration_minutes' => $duration,
                'price' => $price,
                'provider_id' => $primaryProvider->id,
                'creator_user_id' => $admin->id,
                'category_id' => $categories->firstWhere('name', $categoryName)?->id,
                'address' => fake()->streetAddress(),
                'latitude' => fake()->randomFloat(7, 43.18, 43.30),
                'longitude' => fake()->randomFloat(7, 76.82, 76.98),
            ]);

            $service->providers()->sync(
                $providers->shuffle()->take(fake()->numberBetween(1, 3))->pluck('id')->all(),
            );

            return $service;
        });

        $services->each(function (Service $service) use ($clients): void {
            collect(range(1, fake()->numberBetween(2, 4)))
                ->each(function () use ($service, $clients): void {
                    $scheduledAt = fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d H:00:00');

                    Appointment::factory()->create([
                        'user_id' => $clients->random()->id,
                        'service_id' => $service->id,
                        'scheduled_at' => $scheduledAt,
                        'ends_at' => Carbon::parse($scheduledAt)->addMinutes($service->duration_minutes),
                        'status' => fake()->randomElement([
                            AppointmentStatus::Booked->value,
                            AppointmentStatus::Booked->value,
                            AppointmentStatus::Completed->value,
                        ]),
                    ]);
                });
        });

        echo "BookEase seeded successfully with admin account.\n";
    }
}
