<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Beneficiary;
use App\Models\Campaign;
use App\Models\Inventory;
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
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@leofoundation.org'],
            [
                'name' => 'Admin User',
                'email' => 'admin@leofoundation.org',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create Staff User
        User::updateOrCreate(
            ['email' => 'staff@leofoundation.org'],
            [
                'name' => 'Staff Member',
                'email' => 'staff@leofoundation.org',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // Create Sample Beneficiaries
        $beneficiaries = [
            [
                'first_name' => 'Ravi',
                'last_name' => 'Kumar',
                'type' => 'orphan',
                'status' => 'active',
                'admission_date' => now()->subMonths(6),
                'dob' => now()->subYears(10),
                'gender' => 'male',
            ],
            [
                'first_name' => 'Priya',
                'last_name' => 'Sharma',
                'type' => 'orphan',
                'status' => 'active',
                'admission_date' => now()->subMonths(3),
                'dob' => now()->subYears(8),
                'gender' => 'female',
            ],
            [
                'first_name' => 'Anand',
                'last_name' => 'Verma',
                'type' => 'patient',
                'status' => 'active',
                'admission_date' => now()->subMonths(2),
                'dob' => now()->subYears(35),
                'gender' => 'male',
            ],
            [
                'first_name' => 'Lakshmi',
                'last_name' => 'Devi',
                'type' => 'welfare_recipient',
                'status' => 'active',
                'admission_date' => now()->subMonths(1),
                'dob' => now()->subYears(65),
                'gender' => 'female',
            ],
        ];

        foreach ($beneficiaries as $data) {
            Beneficiary::updateOrCreate(
                ['first_name' => $data['first_name'], 'last_name' => $data['last_name']],
                $data
            );
        }

        // Create Sample Campaigns
        Campaign::updateOrCreate(
            ['slug' => 'education-for-all'],
            [
                'title' => 'Education for All',
                'slug' => 'education-for-all',
                'description' => 'Help us provide quality education to underprivileged children. Every donation helps purchase books, uniforms, and school supplies.',
                'short_description' => 'Support education for underprivileged children',
                'target_amount' => 500000,
                'current_amount' => 125000,
                'currency' => 'INR',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(4),
                'status' => 'active',
                'is_featured' => true,
                'created_by' => 1,
            ]
        );

        Campaign::updateOrCreate(
            ['slug' => 'feed-the-hungry'],
            [
                'title' => 'Feed the Hungry',
                'slug' => 'feed-the-hungry',
                'description' => 'Your donation helps us provide nutritious meals to those who need it most. We serve over 500 meals daily to the homeless and elderly.',
                'short_description' => 'Help us serve nutritious meals to the needy',
                'target_amount' => 300000,
                'current_amount' => 180000,
                'currency' => 'INR',
                'start_date' => now()->subMonths(1),
                'status' => 'active',
                'is_featured' => true,
                'created_by' => 1,
            ]
        );

        // Create Sample Inventory Items
        $inventoryItems = [
            ['name' => 'Rice', 'category' => 'food', 'quantity' => 150, 'unit' => 'kg', 'minimum_threshold' => 50],
            ['name' => 'Dal (Lentils)', 'category' => 'food', 'quantity' => 30, 'unit' => 'kg', 'minimum_threshold' => 20],
            ['name' => 'Cooking Oil', 'category' => 'food', 'quantity' => 25, 'unit' => 'liters', 'minimum_threshold' => 10],
            ['name' => 'Paracetamol', 'category' => 'medicine', 'quantity' => 200, 'unit' => 'pieces', 'minimum_threshold' => 50],
            ['name' => 'Bandages', 'category' => 'medicine', 'quantity' => 15, 'unit' => 'boxes', 'minimum_threshold' => 10],
            ['name' => 'Blankets', 'category' => 'household', 'quantity' => 45, 'unit' => 'pieces', 'minimum_threshold' => 20],
            ['name' => 'Notebooks', 'category' => 'education', 'quantity' => 100, 'unit' => 'pieces', 'minimum_threshold' => 30],
            ['name' => 'Pencils', 'category' => 'education', 'quantity' => 5, 'unit' => 'boxes', 'minimum_threshold' => 10],
        ];

        foreach ($inventoryItems as $item) {
            Inventory::updateOrCreate(
                ['name' => $item['name']],
                array_merge($item, ['last_updated_by' => 1])
            );
        }

        $this->command->info('Database seeded successfully!');
        $this->command->line('');
        $this->command->info('Admin Login:');
        $this->command->line('Email: admin@leofoundation.org');
        $this->command->line('Password: password');
    }
}

