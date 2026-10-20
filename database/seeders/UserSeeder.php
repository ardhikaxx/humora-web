<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds for mobile users with 3 distinct statuses:
     * 1. pengguna1@gmail.com - User Gratis (Free)
     * 2. pengguna2@gmail.com - User Premium (VIP Aktif)
     * 3. pengguna3@gmail.com - User Expired (Masa Langganan Habis)
     */
    public function run(): void
    {
        $planMonthly = SubscriptionPlan::where('code', 'monthly')->first() 
            ?? SubscriptionPlan::first();
        $admin = Admin::where('email', 'admin@gmail.com')->first();

        // 1. User Gratis (Free)
        $user1 = User::updateOrCreate(
            ['email' => 'pengguna1@gmail.com'],
            [
                'name' => 'Pengguna Gratis',
                'phone' => '081211112222',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'tier' => 'free',
                'valid_until' => null,
                'status' => 'active',
            ]
        );

        // 2. User Premium (VIP Aktif)
        $user2 = User::updateOrCreate(
            ['email' => 'pengguna2@gmail.com'],
            [
                'name' => 'Pengguna Premium',
                'phone' => '081233334444',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'tier' => 'premium',
                'valid_until' => Carbon::now()->addDays(30),
                'status' => 'active',
            ]
        );

        if ($planMonthly) {
            $trx2 = Transaction::updateOrCreate(
                ['trx_code' => 'TRX-PREMIUM-002'],
                [
                    'user_id' => $user2->id,
                    'plan_id' => $planMonthly->id,
                    'payment_method' => 'BCA Manual Transfer',
                    'amount' => $planMonthly->price,
                    'proof_file' => 'proof_demo_active.jpg',
                    'status' => 'success',
                    'verified_by' => $admin?->id,
                    'verified_at' => Carbon::now()->subDay(),
                ]
            );

            Subscription::updateOrCreate(
                ['transaction_id' => $trx2->id],
                [
                    'user_id' => $user2->id,
                    'plan_id' => $planMonthly->id,
                    'start_date' => Carbon::now()->subDay(),
                    'end_date' => Carbon::now()->addDays(29),
                    'status' => 'active',
                ]
            );
        }

        // 3. User Expired (Langganan Kedaluwarsa)
        $user3 = User::updateOrCreate(
            ['email' => 'pengguna3@gmail.com'],
            [
                'name' => 'Pengguna Expired',
                'phone' => '081255556666',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'tier' => 'premium',
                'valid_until' => Carbon::now()->subDays(5),
                'status' => 'active',
            ]
        );

        if ($planMonthly) {
            $trx3 = Transaction::updateOrCreate(
                ['trx_code' => 'TRX-EXPIRED-003'],
                [
                    'user_id' => $user3->id,
                    'plan_id' => $planMonthly->id,
                    'payment_method' => 'Mandiri Manual Transfer',
                    'amount' => $planMonthly->price,
                    'proof_file' => 'proof_demo_expired.jpg',
                    'status' => 'success',
                    'verified_by' => $admin?->id,
                    'verified_at' => Carbon::now()->subDays(35),
                ]
            );

            Subscription::updateOrCreate(
                ['transaction_id' => $trx3->id],
                [
                    'user_id' => $user3->id,
                    'plan_id' => $planMonthly->id,
                    'start_date' => Carbon::now()->subDays(35),
                    'end_date' => Carbon::now()->subDays(5),
                    'status' => 'expired',
                ]
            );
        }
    }
}
