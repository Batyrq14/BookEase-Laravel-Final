<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin->value,
                'bio' => 'Manages provider staffing, service assignments, and appointment oversight.',
            ],
        );

        User::firstOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('password'),
                'role' => UserRole::Client->value,
            ],
        );

        $provider = User::firstOrCreate(
            ['email' => 'provider@example.com'],
            [
                'name' => 'Provider User',
                'password' => Hash::make('password'),
                'role' => UserRole::Provider->value,
                'phone' => '+1 555 100 2000',
                'bio' => 'Specializes in client-ready appointments and day-of service delivery.',
            ],
        );

        Service::updateOrCreate(
            ['name' => 'Basic Haircut'],
            [
                'description' => 'A standard haircut session.',
                'duration_minutes' => 30,
                'price' => 20.00,
                'provider_id' => $provider->id,
                'creator_user_id' => $admin->id,
            ],
        );

        Service::updateOrCreate(
            ['name' => 'Haircut & Beard Trim'],
            [
                'description' => 'Haircut and beard trim combo.',
                'duration_minutes' => 45,
                'price' => 35.00,
                'provider_id' => $provider->id,
                'creator_user_id' => $admin->id,
            ],
        );

        Service::updateOrCreate(
            ['name' => 'Premium Styling'],
            [
                'description' => 'Full styling including washing.',
                'duration_minutes' => 60,
                'price' => 50.00,
                'provider_id' => $provider->id,
                'creator_user_id' => $admin->id,
            ],
        );
    }
}
