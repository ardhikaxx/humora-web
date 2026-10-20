<?php

use App\Models\Admin;
use App\Models\Notification;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->plan = SubscriptionPlan::create([
        'code' => 'monthly_vip',
        'name' => 'Bulanan Komedi VIP',
        'price' => 35000,
        'duration_days' => 30,
        'description' => 'Akses penuh seluruh episode VIP selama 30 hari.',
        'is_active' => true,
    ]);

    $this->user = User::create([
        'name' => 'Fajar Nugraha',
        'email' => 'fajar@example.com',
        'phone' => '081288889999',
        'password' => Hash::make('Secret123!'),
        'tier' => 'free',
        'status' => 'active',
    ]);

    $this->userToken = $this->user->createToken('test-token')->plainTextToken;

    $this->financeAdmin = Admin::create([
        'name' => 'Finance Officer',
        'email' => 'finance@humora.id',
        'password' => Hash::make('HumoraAdmin2026!'),
        'role' => 'admin',
        'is_active' => true,
    ]);
});

test('user can browse active subscription plans and system payment info', function () {
    $plansResponse = $this->getJson('/api/v1/subscription/plans');
    $plansResponse->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $infoResponse = $this->getJson('/api/v1/settings/payment-info');
    $infoResponse->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'bank_accounts' => [
                    '*' => ['code', 'bank_name', 'account_number', 'account_name'],
                ],
                'qris' => ['code', 'is_configured', 'account_name'],
            ],
        ]);
});

test('admin can save multiple dynamic bank accounts and API serves them', function () {
    $response = $this->actingAs($this->financeAdmin, 'admin')
        ->post('/admin/settings', [
            'free_preview_episodes' => 2,
            'price_monthly' => 19000,
            'price_quarterly' => 49000,
            'price_yearly' => 179000,
            'bank_accounts' => [
                [
                    'bank_name' => 'Bank BCA',
                    'account_number' => '111-222-333',
                    'account_name' => 'PT Humora Media',
                ],
                [
                    'bank_name' => 'Bank Mandiri',
                    'account_number' => '444-555-666',
                    'account_name' => 'PT Humora Media',
                ],
                [
                    'bank_name' => 'Bank BRI',
                    'account_number' => '777-888-999',
                    'account_name' => 'PT Humora Media',
                ],
            ],
        ]);

    $response->assertRedirect('/admin/settings');

    $infoResponse = $this->getJson('/api/v1/settings/payment-info');
    $infoResponse->assertStatus(200);
    $data = $infoResponse->json('data.bank_accounts');
    expect($data)->toHaveCount(3);
    expect($data[0]['bank_name'])->toBe('Bank BCA');
    expect($data[2]['bank_name'])->toBe('Bank BRI');
});

test('user can upload payment proof and create pending transaction', function () {
    $file = UploadedFile::fake()->image('bukti_transfer.jpg', 600, 800);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
        ->postJson('/api/v1/transactions/upload-proof', [
            'plan_id' => $this->plan->id,
            'payment_method' => 'bca',
            'proof_file' => $file,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'transaction' => [
                    'plan_id' => $this->plan->id,
                    'payment_method' => 'bca',
                    'status' => 'pending',
                    'proof_uploaded' => true,
                ],
            ],
        ]);

    $this->assertDatabaseHas('transactions', [
        'user_id' => $this->user->id,
        'plan_id' => $this->plan->id,
        'payment_method' => 'bca',
        'status' => 'pending',
    ]);
});

test('finance admin can approve pending transaction, activating VIP subscription', function () {
    $transaction = Transaction::create([
        'trx_code' => 'TRX-20261019-TEST01',
        'user_id' => $this->user->id,
        'plan_id' => $this->plan->id,
        'payment_method' => 'bca',
        'amount' => 35000,
        'status' => 'pending',
        'proof_file' => 'test-proof.jpg',
    ]);

    $response = $this->actingAs($this->financeAdmin, 'admin')
        ->post("/admin/transactions/{$transaction->id}/approve");

    $response->assertStatus(302);

    // Verify transaction status updated to success
    expect($transaction->fresh()->status)->toBe('success');

    // Verify user upgraded to premium
    $freshUser = $this->user->fresh();
    expect($freshUser->tier)->toBe('premium');
    expect($freshUser->hasActiveVip())->toBeTrue();
    expect($freshUser->valid_until)->not->toBeNull();

    // Verify subscription created
    $this->assertDatabaseHas('subscriptions', [
        'user_id' => $this->user->id,
        'plan_id' => $this->plan->id,
        'transaction_id' => $transaction->id,
        'status' => 'active',
    ]);

    // Verify notification sent to user
    $this->assertDatabaseHas('notifications', [
        'user_id' => $this->user->id,
        'type' => 'transaction',
    ]);
});

test('finance admin can reject pending transaction with reason', function () {
    $transaction = Transaction::create([
        'trx_code' => 'TRX-20261019-TEST02',
        'user_id' => $this->user->id,
        'plan_id' => $this->plan->id,
        'payment_method' => 'mandiri',
        'amount' => 35000,
        'status' => 'pending',
        'proof_file' => 'test-proof.jpg',
    ]);

    $response = $this->actingAs($this->financeAdmin, 'admin')
        ->post("/admin/transactions/{$transaction->id}/reject", [
            'reject_reason' => 'Nominal transfer tidak sesuai dengan harga paket.',
        ]);

    $response->assertStatus(302);

    $freshTrx = $transaction->fresh();
    expect($freshTrx->status)->toBe('rejected');
    expect($freshTrx->reject_reason)->toBe('Nominal transfer tidak sesuai dengan harga paket.');

    // User should remain free
    $freshUser = $this->user->fresh();
    expect($freshUser->tier)->toBe('free');
    expect($freshUser->hasActiveVip())->toBeFalse();
});
