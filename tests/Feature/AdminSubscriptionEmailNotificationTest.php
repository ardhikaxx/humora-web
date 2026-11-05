<?php

use App\Mail\NewSubscriptionNotificationMail;
use App\Models\Admin;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AdminNotificationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    // Reset test admins
    Admin::whereIn('email', [
        'superadmin@example.com',
        'financeadmin@example.com',
        'inactiveadmin@example.com',
    ])->delete();

    // Create 2 active admins and 1 inactive admin
    Admin::create([
        'name' => 'Super Admin Humora',
        'email' => 'superadmin@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    Admin::create([
        'name' => 'Finance Admin Humora',
        'email' => 'financeadmin@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    Admin::create([
        'name' => 'Inactive Admin',
        'email' => 'inactiveadmin@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => false,
    ]);

    // Ensure standard weekly plan exists for in-memory SQLite tests
    SubscriptionPlan::firstOrCreate(
        ['code' => 'weekly_15'],
        [
            'name' => 'Paket Akses Mingguan (15 Video)',
            'price' => 15000,
            'duration_days' => 7,
            'video_quota' => 15,
            'plan_type' => 'weekly',
            'is_active' => true,
        ]
    );
});

test('mailable renders correct subject, content, and verification url', function () {
    $user = User::factory()->create([
        'name' => 'Siti Pelanggan',
        'email' => 'siti@example.com',
        'phone' => '081299998888',
    ]);

    $plan = SubscriptionPlan::where('code', 'weekly_15')->first();

    $transaction = Transaction::create([
        'trx_code' => 'TRX-TEST-RENDER-001',
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'payment_method' => 'bca',
        'amount' => 15000,
        'status' => 'pending',
    ]);

    $mailable = new NewSubscriptionNotificationMail($transaction, 'proof_uploaded');

    $mailable->assertHasSubject('[HUMORA] Bukti Pembayaran Masuk: TRX-TEST-RENDER-001 - Siti Pelanggan (Paket Akses Mingguan (15 Video))');
    $mailable->assertSeeInHtml('Pemberitahuan Langganan Baru');
    $mailable->assertSeeInHtml('TRX-TEST-RENDER-001');
    $mailable->assertSeeInHtml('Siti Pelanggan');
    $mailable->assertSeeInHtml('siti@example.com');
    $mailable->assertSeeInHtml('Rp 15.000');
    $mailable->assertSeeInHtml('Lihat & Verifikasi Transaksi');
    $mailable->assertSeeInHtml('admin/transactions?search=TRX-TEST-RENDER-001');
});

test('admin notification service sends email to all active admins and skips inactive admins', function () {
    Mail::fake();

    $user = User::factory()->create();
    $plan = SubscriptionPlan::where('code', 'weekly_15')->first();

    $transaction = Transaction::create([
        'trx_code' => 'TRX-TEST-SERVICE-002',
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'payment_method' => 'bca',
        'amount' => $plan->price,
        'status' => 'pending',
    ]);

    $service = app(AdminNotificationService::class);
    $recipients = $service->notifyAdminsNewSubscription($transaction, 'proof_uploaded');

    expect($recipients)->toContain('superadmin@example.com')
        ->and($recipients)->toContain('financeadmin@example.com')
        ->and($recipients)->not->toContain('inactiveadmin@example.com');

    Mail::assertSent(NewSubscriptionNotificationMail::class, function ($mail) {
        return $mail->hasTo('superadmin@example.com') && $mail->transaction->trx_code === 'TRX-TEST-SERVICE-002';
    });

    Mail::assertSent(NewSubscriptionNotificationMail::class, function ($mail) {
        return $mail->hasTo('financeadmin@example.com');
    });

    Mail::assertNotSent(NewSubscriptionNotificationMail::class, function ($mail) {
        return $mail->hasTo('inactiveadmin@example.com');
    });
});

test('checkout api triggers new subscription email notification to all active admins', function () {
    Mail::fake();

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $plan = SubscriptionPlan::where('code', 'weekly_15')->first();

    $response = $this->postJson('/api/v1/subscription/checkout', [
        'plan_id' => $plan->id,
        'payment_method' => 'bca',
    ]);

    $response->assertStatus(201);
    $trxCode = $response->json('data.transaction.trx_code');

    Mail::assertSent(NewSubscriptionNotificationMail::class, function ($mail) use ($trxCode) {
        return $mail->hasTo('superadmin@example.com')
            && $mail->transaction->trx_code === $trxCode
            && $mail->eventType === 'checkout';
    });

    Mail::assertSent(NewSubscriptionNotificationMail::class, function ($mail) {
        return $mail->hasTo('financeadmin@example.com');
    });
});

test('upload proof api triggers admin notification email with proof uploaded event', function () {
    Mail::fake();

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $plan = SubscriptionPlan::where('code', 'weekly_15')->first();

    $transaction = Transaction::create([
        'trx_code' => 'TRX-TEST-PROOF-003',
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'payment_method' => 'bca',
        'amount' => $plan->price,
        'status' => 'pending',
    ]);

    $file = UploadedFile::fake()->image('bukti_transfer.jpg', 600, 600);

    $response = $this->postJson('/api/v1/transactions/upload-proof', [
        'transaction_id' => $transaction->id,
        'proof_file' => $file,
    ]);

    $response->assertStatus(200);

    Mail::assertSent(NewSubscriptionNotificationMail::class, function ($mail) {
        return $mail->hasTo('superadmin@example.com')
            && $mail->transaction->trx_code === 'TRX-TEST-PROOF-003'
            && $mail->eventType === 'proof_uploaded';
    });

    Mail::assertSent(NewSubscriptionNotificationMail::class, function ($mail) {
        return $mail->hasTo('financeadmin@example.com');
    });
});
