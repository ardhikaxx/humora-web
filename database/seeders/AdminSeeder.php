<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Super Administrator account
        Admin::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Humora',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'ardhikayanuar58@gmail.com'],
            [
                'name' => 'Ardhika Admin',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // If a platform administrator email is configured in mail settings, ensure an active admin entry exists
        $configuredEmail = config('mail.mailers.smtp.username');
        if (! empty($configuredEmail) && filter_var($configuredEmail, FILTER_VALIDATE_EMAIL) && $configuredEmail !== 'admin@gmail.com') {
            Admin::updateOrCreate(
                ['email' => $configuredEmail],
                [
                    'name' => 'Administrator HUMORA',
                    'phone' => '081234567890',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'is_active' => true,
                ]
            );
        }
    }
}
