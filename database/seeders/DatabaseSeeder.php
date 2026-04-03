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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        Service::create([
            'name' => 'Basic Haircut',
            'description' => 'A standard haircut session.',
            'duration_minutes' => 30,
            'price' => 20.00,
        ]);
        
        Service::create([
            'name' => 'Haircut & Beard Trim',
            'description' => 'Haircut and beard trim combo.',
            'duration_minutes' => 45,
            'price' => 35.00,
        ]);
        
        Service::create([
            'name' => 'Premium Styling',
            'description' => 'Full styling including washing.',
            'duration_minutes' => 60,
            'price' => 50.00,
        ]);
    }
}
