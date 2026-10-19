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
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial setup data.
     */
    public function run(): void
    {
        // 1. Single Administrator
        Admin::where('email', '!=', 'admin@humora.id')->delete();

        Admin::updateOrCreate(
            ['email' => 'admin@humora.id'],
            [
                'name' => 'Admin Humora',
                'phone' => '081234567890',
                'password' => Hash::make('admin123'),
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
        $planMonthly = SubscriptionPlan::firstOrCreate(
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

        $planQuarterly = SubscriptionPlan::firstOrCreate(
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

        $planYearly = SubscriptionPlan::firstOrCreate(
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

        // 5. Series & Episodes
        $seriesData = [
            [
                'title' => 'Cara Deketin Cewek',
                'slug' => 'cara-deketin-cewek',
                'synopsis' => 'Panduan sketsa komedi kocak berbagai taktik jitu (tapi sering berujung apes) anak muda saat memberanikan diri mendekati gebetan impian.',
                'release_year' => '2026',
                'thumbnail' => 'cara-deketin-cewek.jpg',
                'is_featured' => true,
                'catalog_order' => 1,
                'status' => 'published',
                'categories' => [$catRomantis->id, $catKomedi->id, $catSketsa->id],
                'episodes' => [
                    ['episode_number' => 1, 'title' => 'Pake Hadiah', 'synopsis' => 'Beli buket snack raksasa dan boneka segede motor matic, tapi paket kejutan malah salah kirim ke pos satpam kompleks.', 'duration' => '2m 10s', 'duration_seconds' => 130, 'access_type' => 'free'],
                    ['episode_number' => 2, 'title' => 'Pake Rayuan Maut', 'synopsis' => 'Niat gombal manis pake pantun romantis hasil riset semalaman, eh pas di depan gebetan lidah malah keseleo salah sebut nama.', 'duration' => '1m 55s', 'duration_seconds' => 115, 'access_type' => 'free'],
                    ['episode_number' => 3, 'title' => 'Sok Asik di Cafe', 'synopsis' => 'Mencoba sok ngerti pesanan kopi artisanal biar keliatan keren, berujung kepahitan kopi hitam pekat dan tersedak gula aren.', 'duration' => '2m 05s', 'duration_seconds' => 125, 'access_type' => 'premium'],
                    ['episode_number' => 4, 'title' => 'Minta Tolong Teman', 'synopsis' => 'Minta bestie jadi sayap pendukung, tapi temennya malah keasikan flirting dan nikung tanpa ampun.', 'duration' => '2m 14s', 'duration_seconds' => 134, 'access_type' => 'premium'],
                ],
            ],
            [
                'title' => 'Drama Warga Grup RT',
                'slug' => 'drama-warga-grup-rt',
                'synopsis' => 'Keriuhan tanpa henti di grup WhatsApp RT 05. Dari urusan iuran sampah, kucing buang hajat di teras orang, hingga broadcast hoax kesehatan.',
                'release_year' => '2026',
                'thumbnail' => 'drama-warga-grup-rt.jpg',
                'is_featured' => true,
                'catalog_order' => 2,
                'status' => 'published',
                'categories' => [$catKeluarga->id, $catKomedi->id, $catSosial->id],
                'episodes' => [
                    ['episode_number' => 1, 'title' => 'Iuran Sampah Naik', 'synopsis' => 'Pengumuman iuran sampah naik 5 ribu rupiah memicu debat kusir sepanjang 200 chat hingga pak RT mengunci grup.', 'duration' => '2m 00s', 'duration_seconds' => 120, 'access_type' => 'free'],
                    ['episode_number' => 2, 'title' => 'Parkir Sembarangan', 'synopsis' => 'Mobil tetangga nutupin jalan gang sempit, foto diunggah ke grup RT dengan caption pasif-agresif tingkat dewa.', 'duration' => '1m 58s', 'duration_seconds' => 118, 'access_type' => 'free'],
                    ['episode_number' => 3, 'title' => 'Broadcast Hoax Subuh', 'synopsis' => 'Ibu-ibu share pesan berantai bahaya makan nanas dicampur boba pada jam 4 subuh yang memicu kepanikan warga.', 'duration' => '2m 05s', 'duration_seconds' => 125, 'access_type' => 'premium'],
                    ['episode_number' => 4, 'title' => 'Kerja Bakti Minggu', 'synopsis' => 'Drama warga yang pura-pura sakit pinggang mendadak pas jadwal kerja bakti membersihkan selokan kompleks.', 'duration' => '2m 20s', 'duration_seconds' => 140, 'access_type' => 'premium'],
                ],
            ],
            [
                'title' => 'Momen Canggung Kencan Pertama',
                'slug' => 'momen-canggung-kencan-pertama',
                'synopsis' => 'Kumpulan detik-detik paling bikin salah tingkah saat dua insan kencan pertama kali. Kehabisan topik, salah bill, sampai tersedak kuah sup.',
                'release_year' => '2026',
                'thumbnail' => 'momen-canggung-kencan-pertama.jpg',
                'is_featured' => false,
                'catalog_order' => 3,
                'status' => 'published',
                'categories' => [$catRomantis->id, $catKomedi->id],
                'episodes' => [
                    ['episode_number' => 1, 'title' => 'Siapa yang Bayar?', 'synopsis' => 'Momen tarik-menarik bon tagihan resto yang berakhir dengan pura-pura ke toilet untuk kabur sebentar.', 'duration' => '2m 00s', 'duration_seconds' => 120, 'access_type' => 'free'],
                    ['episode_number' => 2, 'title' => 'Hening 10 Menit', 'synopsis' => 'Topik obrolan cuaca sudah habis, kini keduanya sibuk mengamati pola keramik lantai restoran.', 'duration' => '1m 55s', 'duration_seconds' => 115, 'access_type' => 'free'],
                    ['episode_number' => 3, 'title' => 'Ketemu Mantan', 'synopsis' => 'Baru mau suap-suapan, eh mantan pacar masuk resto sambil bawa rombongan keluarga besar.', 'duration' => '2m 14s', 'duration_seconds' => 134, 'access_type' => 'premium'],
                ],
            ],
            [
                'title' => 'Tipe Pembeli di Toko Kelontong',
                'slug' => 'tipe-pembeli-di-toko-kelontong',
                'synopsis' => 'Observasi komedi para pelanggan warung kelontong Madura. Dari yang ngutang bawa kalender, uang kembalian permen, sampai nawar harga mie instan.',
                'release_year' => '2026',
                'thumbnail' => 'tipe-pembeli-di-toko-kelontong.jpg',
                'is_featured' => false,
                'catalog_order' => 4,
                'status' => 'published',
                'categories' => [$catKomedi->id, $catSosial->id],
                'episodes' => [
                    ['episode_number' => 1, 'title' => 'Kembalian Permen', 'synopsis' => 'Uang kurang seratus perak diganti permen jahe, pembeli berniat belanja balik pake permen itu esok harinya.', 'duration' => '2m 00s', 'duration_seconds' => 120, 'access_type' => 'free'],
                    ['episode_number' => 2, 'title' => 'Ngutang Buku Hitam', 'synopsis' => 'Buku catatan legendaris toko kelontong dibuka, tebalnya ngalahin kamus bahasa asing.', 'duration' => '1m 58s', 'duration_seconds' => 118, 'access_type' => 'free'],
                    ['episode_number' => 3, 'title' => 'Beli Telur Satu Butir', 'synopsis' => 'Minta telur dicek pake lampu senter satu per satu cuma buat beli sebiji gorengan mie.', 'duration' => '2m 05s', 'duration_seconds' => 125, 'access_type' => 'premium'],
                ],
            ],
            [
                'title' => 'Tipe Penumpang Taxi Online',
                'slug' => 'tipe-penumpang-taxi-online',
                'synopsis' => 'Kisah dari balik kemudi driver taxi online menghadapi aneka watak penumpang yang unik, cerewet, hobi karaoke di mobil, hingga curhat asmara.',
                'release_year' => '2026',
                'thumbnail' => 'tipe-penumpang-taxi-online.jpg',
                'is_featured' => false,
                'catalog_order' => 5,
                'status' => 'published',
                'categories' => [$catKomedi->id, $catSosial->id],
                'episodes' => [
                    ['episode_number' => 1, 'title' => 'DJ Mobil Dadakan', 'synopsis' => 'Penumpang minta sambungin Bluetooth lalu nyetel musik disko jedag-jedug sampai speaker mobil getar.', 'duration' => '2m 00s', 'duration_seconds' => 120, 'access_type' => 'free'],
                    ['episode_number' => 2, 'title' => 'Sesi Curhat 45 Menit', 'synopsis' => 'Sepanjang perjalanan macet, driver terpaksa berubah wujud jadi konsultan psikolog asmara.', 'duration' => '2m 10s', 'duration_seconds' => 130, 'access_type' => 'free'],
                    ['episode_number' => 3, 'title' => 'Rating Bintang Satu', 'synopsis' => 'Semua pelayanan sempurna, AC dingin dan permen tersedia, tapi dikasih bintang satu karena warna mobil bukan kesukaannya.', 'duration' => '2m 05s', 'duration_seconds' => 125, 'access_type' => 'premium'],
                ],
            ],
            [
                'title' => 'Tipe-Tipe Teman Sekantor',
                'slug' => 'tipe-tipe-teman-sekantor',
                'synopsis' => 'Parodi kehidupan kantor: Si Tukang Titip Makan Siang, Ratu Gosip Pantry, Ahli Menghilang saat Jam Meeting, dan Si Lembur Paling Berisik.',
                'release_year' => '2026',
                'thumbnail' => 'tipe-tipe-teman-sekantor.jpg',
                'is_featured' => false,
                'catalog_order' => 6,
                'status' => 'published',
                'categories' => [$catKantor->id, $catKomedi->id],
                'episodes' => [
                    ['episode_number' => 1, 'title' => 'Titip Makan Siang', 'synopsis' => 'Titip beli makan siang tapi uangnya pas-pasan, lalu lupa transfer balik sampai berganti tahun.', 'duration' => '2m 00s', 'duration_seconds' => 120, 'access_type' => 'free'],
                    ['episode_number' => 2, 'title' => 'Gosip di Pantry', 'synopsis' => 'Niat cuma seduh kopi instan, malah dapet bocoran rahasia merger korporat dari obrolan pantry.', 'duration' => '1m 55s', 'duration_seconds' => 115, 'access_type' => 'free'],
                    ['episode_number' => 3, 'title' => 'Paling Rajin Pas Bos Datang', 'synopsis' => 'Jari mengetik keyboard secepat kilat membuka spreadsheet random tepat saat atasan melintas di cubicle.', 'duration' => '2m 05s', 'duration_seconds' => 125, 'access_type' => 'premium'],
                ],
            ],
        ];

        foreach ($seriesData as $sData) {
            $categories = $sData['categories'];
            $episodes = $sData['episodes'];
            unset($sData['categories'], $sData['episodes']);

            $series = Series::updateOrCreate(['slug' => $sData['slug']], $sData);
            $series->categories()->sync($categories);

            foreach ($episodes as $epData) {
                $epData['series_id'] = $series->id;
                $epData['video_file'] = 'sample-skit.mp4';
                $epData['status'] = 'published';
                Episode::updateOrCreate(
                    [
                        'series_id' => $series->id,
                        'episode_number' => $epData['episode_number'],
                    ],
                    $epData
                );
            }
        }

        // 6. Test Mobile Users
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
