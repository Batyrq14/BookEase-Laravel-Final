<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@bookease.kz',
            'password' => Hash::make('password'),
            'role'     => UserRole::Admin->value,
        ]);

        echo "BookEase seeded successfully with admin account.\n";
    }
}
