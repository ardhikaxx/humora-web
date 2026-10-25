<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SubscriptionPlan;
use App\Models\SystemSetting;
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
        SystemSetting::set('bca_account_number', '2103039177', 'text');
        SystemSetting::set('bca_account_name', 'Global Mobile Content', 'text');
        SystemSetting::set('mandiri_account_number', '', 'text');
        SystemSetting::set('mandiri_account_name', '', 'text');
        SystemSetting::set('official_bank_accounts', [
            [
                'bank_code' => 'bca',
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '2103039177',
                'account_name' => 'Global Mobile Content',
            ],
        ], 'json');
        SystemSetting::set('qris_enabled', 'false', 'boolean');
        SystemSetting::set('qris_official_image', '', 'text');
        SystemSetting::set('qris_raw_payload', '', 'text');
        SystemSetting::set('qris_account_name', 'Global Mobile Content', 'text');

        // 3. Subscription Plans (New Client Business Model)
        // Deactivate or delete old legacy plans if they have no active foreign key constraints
        SubscriptionPlan::whereNotIn('code', ['weekly_15', 'addon_5', 'addon_10', 'full_30'])->update(['is_active' => false]);

        SubscriptionPlan::updateOrCreate(
            ['code' => 'weekly_15'],
            [
                'plan_type' => 'weekly',
                'name' => 'Paket Akses Mingguan (15 Video)',
                'duration_days' => 7,
                'video_quota' => 15,
                'original_price' => 25000,
                'price' => 15000,
                'badge_text' => 'Akses Mingguan',
                'benefits' => [
                    'Akses 15 video sketsa komedi selama 7 hari',
                    'Bebas tonton ulang video dalam paket sepuasnya',
                    'Streaming HD tanpa jeda buffering',
                    'Dukungan pembelian Add-on video tambahan',
                ],
                'is_active' => true,
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['code' => 'addon_5'],
            [
                'plan_type' => 'addon',
                'name' => 'Add-on 5 Video',
                'duration_days' => 0,
                'video_quota' => 5,
                'original_price' => 10000,
                'price' => 7500,
                'badge_text' => 'Ekstra 5 Video',
                'benefits' => [
                    'Tambahan 5 video katalog baru',
                    'Masa aktif mengikuti paket mingguan aktif',
                    'Bebas tonton ulang selama masa aktif',
                    'Video baru tidak pernah mengulang',
                ],
                'is_active' => true,
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['code' => 'addon_10'],
            [
                'plan_type' => 'addon',
                'name' => 'Add-on 10 Video',
                'duration_days' => 0,
                'video_quota' => 10,
                'original_price' => 15000,
                'price' => 12500,
                'badge_text' => 'Paling Populer',
                'benefits' => [
                    'Tambahan 10 video katalog baru',
                    'Masa aktif mengikuti paket mingguan aktif',
                    'Bebas tonton ulang selama masa aktif',
                    'Video baru tidak pernah mengulang',
                ],
                'is_active' => true,
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['code' => 'full_30'],
            [
                'plan_type' => 'full',
                'name' => 'Full Access Seluruh Katalog',
                'duration_days' => 30,
                'video_quota' => null,
                'original_price' => 99000,
                'price' => 49000,
                'badge_text' => 'Akses Penuh 30 Hari',
                'benefits' => [
                    'Akses seluruh video katalog (~68+ video)',
                    'Bebas nonton ulang sepuasnya selama 30 hari',
                    'Tidak perlu membeli paket mingguan atau add-on',
                    'Streaming HD tercepat tanpa hambatan',
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
