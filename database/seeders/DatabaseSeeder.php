<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Episode;
use App\Models\Series;
use App\Models\SubscriptionPlan;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // 2. System Settings
        SystemSetting::set('free_preview_episodes', '2', 'integer');
        SystemSetting::set('bca_account_number', '8830-192-888', 'text');
        SystemSetting::set('bca_account_name', 'PT HUMORA MEDIA KREATIF', 'text');
        SystemSetting::set('mandiri_account_number', '137-00-998822-1', 'text');
        SystemSetting::set('mandiri_account_name', 'HUMORA', 'text');
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

        // 4. Categories
        $catRomantis = Category::firstOrCreate(['slug' => 'romantis'], ['name' => 'Romantis', 'description' => 'Sketsa komedi cinta, PDKT, dan kencan', 'is_active' => true]);
        $catKomedi = Category::firstOrCreate(['slug' => 'komedi'], ['name' => 'Komedi', 'description' => 'Komedi menggelitik tawa', 'is_active' => true]);
        $catSketsa = Category::firstOrCreate(['slug' => 'sketsa'], ['name' => 'Sketsa', 'description' => 'Cerita sketsa situasi pendek', 'is_active' => true]);
        $catKeluarga = Category::firstOrCreate(['slug' => 'keluarga'], ['name' => 'Keluarga', 'description' => 'Dinamika lucu keluarga dan tetangga', 'is_active' => true]);
        $catKantor = Category::firstOrCreate(['slug' => 'kantor'], ['name' => 'Kantor', 'description' => 'Komedi lingkungan kerja dan rekan kantor', 'is_active' => true]);
        $catSosial = Category::firstOrCreate(['slug' => 'sosial'], ['name' => 'Sosial', 'description' => 'Sindiran komedi interaksi sosial', 'is_active' => true]);

        // 5. Clean up old series and episodes to ensure ONLY ONE series exists
        Episode::query()->delete();
        DB::table('category_series')->delete();
        Series::query()->delete();

        // 6. Seed ONLY ONE Series: "Tipe Penumpang Taxi Online" with 4 Types (Episodes)
        $series = Series::create([
            'title' => 'Tipe Penumpang Taxi Online',
            'slug' => 'tipe-penumpang-taxi-online',
            'synopsis' => 'Kisah dari balik kemudi driver taxi online menghadapi aneka watak penumpang yang unik, super cerewet, serba kepo, mudah baper, hingga sesi curhat asmara tiada henti.',
            'release_year' => '2026',
            'thumbnail' => 'tipe-penumpang-taxi-online.webp',
            'is_featured' => true,
            'catalog_order' => 1,
            'status' => 'published',
        ]);

        $series->categories()->sync([$catKomedi->id, $catSosial->id, $catSketsa->id]);

        $episodes = [
            [
                'episode_number' => 1,
                'title' => 'Tipe Bawel',
                'synopsis' => 'Driver taxi online kewalahan meladeni celotehan penumpang yang super bawel dan ngomong nonstop dari titik awal penjemputan sampai ke tempat tujuan.',
                'duration' => '2m 54s',
                'duration_seconds' => 174,
                'video_file' => 'tipe-penumpang-taxi-online-ep1-bawel.webm',
                'access_type' => 'free',
                'status' => 'published',
            ],
            [
                'episode_number' => 2,
                'title' => 'Tipe Kepo',
                'synopsis' => 'Penumpang dengan rasa penasaran tingkat tinggi yang menginterogasi driver mulai dari tarif aplikasi, riwayat orderan, hingga urusan keluarga pribadi.',
                'duration' => '2m 53s',
                'duration_seconds' => 173,
                'video_file' => 'tipe-penumpang-taxi-online-ep2-kepo.webm',
                'access_type' => 'free',
                'status' => 'published',
            ],
            [
                'episode_number' => 3,
                'title' => 'Tipe Baper',
                'synopsis' => 'Driver cuma menanyakan rute belok kiri atau kanan, eh penumpangnya malah langsung tersinggung dan baper seolah dunianya mau runtuh.',
                'duration' => '2m 43s',
                'duration_seconds' => 163,
                'video_file' => 'tipe-penumpang-taxi-online-ep3-baper.webm',
                'access_type' => 'premium',
                'status' => 'published',
            ],
            [
                'episode_number' => 4,
                'title' => 'Tipe Curhat',
                'synopsis' => 'Mobil bertransformasi jadi ruang konsultasi psikolog dadakan saat penumpang mencurahkan seluruh beban asmara dan air matanya di tengah kemacetan.',
                'duration' => '2m 24s',
                'duration_seconds' => 144,
                'video_file' => 'tipe-penumpang-taxi-online-ep4-curhat.webm',
                'access_type' => 'premium',
                'status' => 'published',
            ],
        ];

        foreach ($episodes as $epData) {
            $epData['series_id'] = $series->id;
            Episode::create($epData);
        }

        // 7. Test Mobile Users
        // Free User
        User::firstOrCreate(
            ['email' => 'ardhika@humora.id'],
            [
                'name' => 'Ardhika Pradana',
                'phone' => '081288997766',
                'password' => Hash::make('UserHumora2026!'),
                'gender' => 'Laki-laki',
                'tier' => 'free',
                'status' => 'active',
            ]
        );

        // Premium User
        User::firstOrCreate(
            ['email' => 'budi@humora.id'],
            [
                'name' => 'Budi Pratama',
                'phone' => '081299887766',
                'password' => Hash::make('UserHumora2026!'),
                'gender' => 'Laki-laki',
                'tier' => 'premium',
                'valid_until' => Carbon::now()->addDays(90),
                'status' => 'active',
            ]
        );

        // Expired User
        User::firstOrCreate(
            ['email' => 'expired@humora.id'],
            [
                'name' => 'Siti Nurhaliza',
                'phone' => '081277665544',
                'password' => Hash::make('UserHumora2026!'),
                'gender' => 'Perempuan',
                'tier' => 'premium',
                'valid_until' => Carbon::now()->subDays(5),
                'status' => 'active',
            ]
        );
    }
}
