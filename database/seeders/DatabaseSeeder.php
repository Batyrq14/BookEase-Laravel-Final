<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin & demo client ──────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@bookease.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::Admin->value,
            'bio'      => 'Manages providers, services and appointments.',
        ]);

        User::create([
            'name'     => 'Demo Client',
            'email'    => 'client@bookease.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::Client->value,
        ]);

        // ── Categories ───────────────────────────────────────────────────────
        $hair     = Category::create(['name' => 'Hair & Styling']);
        $wellness = Category::create(['name' => 'Wellness & Spa']);
        $fitness  = Category::create(['name' => 'Fitness & Training']);
        $beauty   = Category::create(['name' => 'Beauty & Cosmetics']);
        $massage  = Category::create(['name' => 'Massage Therapy']);
        $dental   = Category::create(['name' => 'Dental Care']);

        // ── Providers (email slug matches category) ──────────────────────────
        $providerHair = User::create([
            'name'     => 'Sarah Mitchell',
            'email'    => 'hair@bookease.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::Provider->value,
            'phone'    => '+1 555 100 0001',
            'bio'      => 'Senior stylist with 10 years of experience in cuts, color and styling.',
        ]);

        $providerWellness = User::create([
            'name'     => 'Emma Clarke',
            'email'    => 'wellness@bookease.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::Provider->value,
            'phone'    => '+1 555 100 0002',
            'bio'      => 'Certified esthetician specializing in anti-aging and hydrating facials.',
        ]);

        $providerFitness = User::create([
            'name'     => 'Jake Torres',
            'email'    => 'fitness@bookease.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::Provider->value,
            'phone'    => '+1 555 100 0003',
            'bio'      => 'NASM-certified personal trainer focused on strength and functional movement.',
        ]);

        $providerBeauty = User::create([
            'name'     => 'Olivia Park',
            'email'    => 'beauty@bookease.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::Provider->value,
            'phone'    => '+1 555 100 0004',
            'bio'      => 'Nail artist and lash technician with a flair for creative designs.',
        ]);

        $providerMassage = User::create([
            'name'     => 'David Nguyen',
            'email'    => 'massage@bookease.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::Provider->value,
            'phone'    => '+1 555 100 0005',
            'bio'      => 'Licensed massage therapist skilled in Swedish, deep tissue and hot stone.',
        ]);

        $providerDental = User::create([
            'name'     => 'Dr. Lisa Chen',
            'email'    => 'dental@bookease.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::Provider->value,
            'phone'    => '+1 555 100 0006',
            'bio'      => 'General dentist offering preventive care and cosmetic whitening.',
        ]);

        // ── Services ─────────────────────────────────────────────────────────
        // Hair & Styling
        foreach ([
            ['Basic Haircut',       'Classic cut and finish.',                          30,  25.00],
            ['Haircut & Blow-Dry',  'Full cut with professional blow-dry styling.',      45,  50.00],
            ['Full Color & Style',  'Root-to-tip color treatment with styling finish.', 120, 120.00],
        ] as [$name, $desc, $mins, $price]) {
            Service::create(['name' => $name, 'description' => $desc, 'duration_minutes' => $mins, 'price' => $price, 'provider_id' => $providerHair->id, 'creator_user_id' => $admin->id, 'category_id' => $hair->id]);
        }

        // Wellness & Spa
        foreach ([
            ['Classic Facial',       'Deep cleanse and moisturising facial.',             60,  80.00],
            ['Anti-Aging Facial',    'Firming treatment with vitamin-C serum.',           75, 105.00],
            ['Aromatherapy Session', 'Relaxing aromatherapy and skin nourishment.',       50,  70.00],
        ] as [$name, $desc, $mins, $price]) {
            Service::create(['name' => $name, 'description' => $desc, 'duration_minutes' => $mins, 'price' => $price, 'provider_id' => $providerWellness->id, 'creator_user_id' => $admin->id, 'category_id' => $wellness->id]);
        }

        // Fitness & Training
        foreach ([
            ['Personal Training Session', '1-on-1 tailored workout session.',          60, 60.00],
            ['Group Fitness Class',       'High-energy group class (max 10 people).',  45, 25.00],
            ['Nutrition Consultation',    'Personalised meal plan and macro guidance.', 30, 50.00],
        ] as [$name, $desc, $mins, $price]) {
            Service::create(['name' => $name, 'description' => $desc, 'duration_minutes' => $mins, 'price' => $price, 'provider_id' => $providerFitness->id, 'creator_user_id' => $admin->id, 'category_id' => $fitness->id]);
        }

        // Beauty & Cosmetics
        foreach ([
            ['Classic Manicure', 'Shape, buff and polish for natural nails.',    45, 35.00],
            ['Gel Pedicure',     'Gel soak-off and full pedicure treatment.',     60, 55.00],
            ['Nail Art Design',  'Custom nail art on up to 10 nails.',            30, 25.00],
        ] as [$name, $desc, $mins, $price]) {
            Service::create(['name' => $name, 'description' => $desc, 'duration_minutes' => $mins, 'price' => $price, 'provider_id' => $providerBeauty->id, 'creator_user_id' => $admin->id, 'category_id' => $beauty->id]);
        }

        // Massage Therapy
        foreach ([
            ['Swedish Massage',    'Full-body relaxation massage.',                       60, 75.00],
            ['Deep Tissue Massage','Targeted pressure to release chronic muscle tension.', 60, 85.00],
            ['Hot Stone Massage',  'Heated basalt stones for deep warmth and relief.',    75, 95.00],
        ] as [$name, $desc, $mins, $price]) {
            Service::create(['name' => $name, 'description' => $desc, 'duration_minutes' => $mins, 'price' => $price, 'provider_id' => $providerMassage->id, 'creator_user_id' => $admin->id, 'category_id' => $massage->id]);
        }

        // Dental Care
        foreach ([
            ['Teeth Cleaning',      'Professional scaling and polish.',             60, 120.00],
            ['Teeth Whitening',     'In-chair LED whitening treatment.',            90, 200.00],
            ['Dental Consultation', 'Examination and personalised treatment plan.', 30,  60.00],
        ] as [$name, $desc, $mins, $price]) {
            Service::create(['name' => $name, 'description' => $desc, 'duration_minutes' => $mins, 'price' => $price, 'provider_id' => $providerDental->id, 'creator_user_id' => $admin->id, 'category_id' => $dental->id]);
        }
    }
}
