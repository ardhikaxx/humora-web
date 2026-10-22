<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SubscriptionPlan;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial setup data.
     */
    public function run(): void
    {
        // 1. Single Administrator
        Admin::where('email', '!=', 'admin@gmail.com')->delete();

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

        // 2. System Settings
        SystemSetting::set('free_preview_episodes', '2', 'integer');
        SystemSetting::set('bca_account_number', '8830-192-888', 'text');
        SystemSetting::set('bca_account_name', 'PT HUMORA MEDIA KREATIF', 'text');
        SystemSetting::set('mandiri_account_number', '137-00-998822-1', 'text');
        SystemSetting::set('mandiri_account_name', 'HUMORA', 'text');
        SystemSetting::set('official_bank_accounts', [
            [
                'bank_code' => 'bca',
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '8830-192-888',
                'account_name' => 'PT HUMORA MEDIA KREATIF',
            ],
            [
                'bank_code' => 'mandiri',
                'bank_name' => 'Bank Mandiri',
                'account_number' => '137-00-998822-1',
                'account_name' => 'HUMORA',
            ],
        ], 'json');
        SystemSetting::set('qris_official_image', '', 'text');

        // 3. Subscription Plans
        SubscriptionPlan::firstOrCreate(
            ['code' => 'monthly'],
            [
                'name' => 'Paket Bulanan',
                'duration_days' => 30,
                'original_price' => 29000,
                'price' => 19000,
                'badge_text' => null,
                'benefits' => [
                    'Akses seluruh episode VIP',
                    'Streaming kualitas HD tanpa buffer',
                    'Nonton tanpa jeda iklan',
                ],
                'is_active' => true,
            ]
        );

        SubscriptionPlan::firstOrCreate(
            ['code' => 'quarterly'],
            [
                'name' => 'Paket 3 Bulan',
                'duration_days' => 90,
                'original_price' => 87000,
                'price' => 49000,
                'badge_text' => 'Favorit Pengguna',
                'benefits' => [
                    'Akses seluruh episode VIP',
                    'Streaming kualitas HD tanpa buffer',
                    'Nonton tanpa jeda iklan',
                    'Akses rilis episode lebih awal',
                ],
                'is_active' => true,
            ]
        );

        SubscriptionPlan::firstOrCreate(
            ['code' => 'yearly'],
            [
                'name' => 'Paket Tahunan',
                'duration_days' => 365,
                'original_price' => 228000,
                'price' => 179000,
                'badge_text' => 'VIP Emas',
                'benefits' => [
                    'Akses seluruh episode VIP 1 Tahun Penuh',
                    'Streaming kualitas HD tanpa buffer',
                    'Nonton tanpa jeda iklan',
                    'Akses rilis episode lebih awal',
                    'Hemat hingga 40% dari paket bulanan',
                ],
                'is_active' => true,
            ]
        );

        // 4. Video content (20 series, 71 episodes) and their categories
        $this->call(VideoContentSeeder::class);

        // 5. Test Mobile Users (Default 3 accounts: pengguna1@gmail.com, pengguna2@gmail.com, pengguna3@gmail.com)
        $this->call(UserSeeder::class);
    }
}
