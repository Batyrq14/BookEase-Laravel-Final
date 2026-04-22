<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
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
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password'), 'role' => 'admin'],
        );

        User::firstOrCreate(
            ['email' => 'client@example.com'],
            ['name' => 'Client User', 'password' => Hash::make('password'), 'role' => 'client'],
        );

        Service::firstOrCreate(
            ['name' => 'Basic Haircut'],
            ['description' => 'A standard haircut session.', 'duration_minutes' => 30, 'price' => 20.00],
        );

        Service::firstOrCreate(
            ['name' => 'Haircut & Beard Trim'],
            ['description' => 'Haircut and beard trim combo.', 'duration_minutes' => 45, 'price' => 35.00],
        );

        Service::firstOrCreate(
            ['name' => 'Premium Styling'],
            ['description' => 'Full styling including washing.', 'duration_minutes' => 60, 'price' => 50.00],
        );
    }
}
