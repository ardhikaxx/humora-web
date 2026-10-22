<?php

use App\Models\Admin;
use App\Models\Category;
use App\Models\Episode;
use App\Models\Series;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserVideoAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = Admin::firstOrCreate(
        ['email' => 'admin_test@humora.id'],
        [
            'name' => 'Admin Tester',
            'phone' => '081299990000',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]
    );

    $this->planWeekly = SubscriptionPlan::create([
        'code' => 'weekly_15',
        'plan_type' => 'weekly',
        'name' => 'Paket Akses Mingguan (15 Video)',
        'duration_days' => 7,
        'video_quota' => 15,
        'price' => 15000,
        'is_active' => true,
    ]);

    $this->planAddon = SubscriptionPlan::create([
        'code' => 'addon_5',
        'plan_type' => 'addon',
        'name' => 'Add-on 5 Video',
        'duration_days' => 0,
        'video_quota' => 5,
        'price' => 7500,
        'is_active' => true,
    ]);

    $this->planFull = SubscriptionPlan::create([
        'code' => 'full_30',
        'plan_type' => 'full',
        'name' => 'Full Access Seluruh Katalog',
        'duration_days' => 30,
        'video_quota' => null,
        'price' => 49000,
        'is_active' => true,
    ]);

    // Create 40 locked test episodes across 10 series for allocation testing
    for ($s = 1; $s <= 10; $s++) {
        $series = Series::create([
            'title' => "Series Test $s",
            'slug' => "series-test-$s-" . uniqid(),
            'synopsis' => 'Sinopsis test',
            'release_year' => 2026,
            'catalog_order' => $s,
            'status' => 'published',
        ]);

        for ($e = 1; $e <= 6; $e++) {
            Episode::create([
                'series_id' => $series->id,
                'episode_number' => $e,
                'title' => "Series $s Ep $e",
                'duration' => '02:00',
                'status' => 'published',
            ]);
        }
    }
});

test('trial rule: series with 2 episodes has 1 free video, series with >2 episodes has 2 free videos', function () {
    // 1. Series with 2 episodes
    $series2 = Series::create([
        'title' => 'Serial 2 Episode',
        'slug' => 'serial-2-episode-' . uniqid(),
        'synopsis' => 'Test sinopsis',
        'release_year' => 2026,
        'catalog_order' => 100,
        'status' => 'published',
    ]);
    $ep1_s2 = Episode::create([
        'series_id' => $series2->id,
        'episode_number' => 1,
        'title' => 'Ep 1',
        'duration' => '02:00',
        'status' => 'published',
    ]);
    $ep2_s2 = Episode::create([
        'series_id' => $series2->id,
        'episode_number' => 2,
        'title' => 'Ep 2',
        'duration' => '02:00',
        'status' => 'published',
    ]);

    expect($ep1_s2->isFreePreview())->toBeTrue();
    expect($ep2_s2->isFreePreview())->toBeFalse(); // 2nd episode is locked because total is 2!

    // 2. Series with 4 episodes
    $series4 = Series::create([
        'title' => 'Serial 4 Episode',
        'slug' => 'serial-4-episode-' . uniqid(),
        'synopsis' => 'Test sinopsis',
        'release_year' => 2026,
        'catalog_order' => 101,
        'status' => 'published',
    ]);
    $ep1_s4 = Episode::create([
        'series_id' => $series4->id,
        'episode_number' => 1,
        'title' => 'Ep 1',
        'duration' => '02:00',
        'status' => 'published',
    ]);
    $ep2_s4 = Episode::create([
        'series_id' => $series4->id,
        'episode_number' => 2,
        'title' => 'Ep 2',
        'duration' => '02:00',
        'status' => 'published',
    ]);
    $ep3_s4 = Episode::create([
        'series_id' => $series4->id,
        'episode_number' => 3,
        'title' => 'Ep 3',
        'duration' => '02:00',
        'status' => 'published',
    ]);

    expect($ep1_s4->isFreePreview())->toBeTrue();
    expect($ep2_s4->isFreePreview())->toBeTrue(); // 2nd episode is free trial because total > 2!
    expect($ep3_s4->isFreePreview())->toBeFalse(); // 3rd episode is locked!
});

test('weekly 15-video plan allocates 15 non-free episodes for 7 days', function () {
    $user = User::factory()->create(['tier' => 'free', 'status' => 'active']);

    $trx = Transaction::create([
        'trx_code' => 'TRX-TEST-W1',
        'user_id' => $user->id,
        'plan_id' => $this->planWeekly->id,
        'payment_method' => 'bca',
        'amount' => 15000,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.transactions.approve', $trx->id));

    $response->assertRedirect();
    $trx->refresh();
    $user->refresh();

    expect($trx->status)->toBe('success');
    expect($user->hasActiveVip())->toBeTrue();
    expect($user->valid_until->isFuture())->toBeTrue();

    // Check allocated episodes count
    $accesses = UserVideoAccess::where('user_id', $user->id)->get();
    expect($accesses->count())->toBe(15);

    // Each allocated episode should be accessible
    foreach ($accesses as $access) {
        $ep = Episode::find($access->episode_id);
        expect($ep->isAccessibleBy($user))->toBeTrue();
    }
});

test('subsequent weekly purchase does not repeat previously granted videos', function () {
    $user = User::factory()->create(['tier' => 'free', 'status' => 'active']);

    // 1st weekly purchase
    $trx1 = Transaction::create([
        'trx_code' => 'TRX-TEST-W2-A',
        'user_id' => $user->id,
        'plan_id' => $this->planWeekly->id,
        'payment_method' => 'bca',
        'amount' => 15000,
        'status' => 'pending',
    ]);
    $this->actingAs($this->admin, 'admin')->post(route('admin.transactions.approve', $trx1->id));

    $firstBatchIds = UserVideoAccess::where('user_id', $user->id)->pluck('episode_id')->all();
    expect(count($firstBatchIds))->toBe(15);

    // Simulate expiration of 1st package
    UserVideoAccess::where('user_id', $user->id)->update([
        'expires_at' => Carbon::now()->subDay(),
    ]);
    $user->update(['valid_until' => Carbon::now()->subDay()]);
    $user->refresh();
    expect($user->hasActiveVip())->toBeFalse();

    // 2nd weekly purchase
    $trx2 = Transaction::create([
        'trx_code' => 'TRX-TEST-W2-B',
        'user_id' => $user->id,
        'plan_id' => $this->planWeekly->id,
        'payment_method' => 'mandiri',
        'amount' => 15000,
        'status' => 'pending',
    ]);
    $this->actingAs($this->admin, 'admin')->post(route('admin.transactions.approve', $trx2->id));

    $secondBatchIds = UserVideoAccess::where('user_id', $user->id)
        ->where('expires_at', '>', Carbon::now())
        ->pluck('episode_id')
        ->all();

    expect(count($secondBatchIds))->toBe(15);

    // Ensure completely disjoint sets (NO DUPLICATES!)
    $intersection = array_intersect($firstBatchIds, $secondBatchIds);
    expect($intersection)->toBeEmpty();
});

test('addon plan requires active weekly package at checkout', function () {
    $user = User::factory()->create(['tier' => 'free', 'status' => 'active']);
    $token = $user->createToken('test')->plainTextToken;

    // Attempting checkout without active weekly package -> 422
    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/v1/subscription/checkout', [
            'plan_id' => $this->planAddon->id,
            'payment_method' => 'qris',
        ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'error_code' => 'WEEKLY_PACKAGE_REQUIRED',
    ]);
});

test('full access plan unlocks entire catalog for 30 days without quota', function () {
    $user = User::factory()->create(['tier' => 'free', 'status' => 'active']);

    $trx = Transaction::create([
        'trx_code' => 'TRX-TEST-FULL-1',
        'user_id' => $user->id,
        'plan_id' => $this->planFull->id,
        'payment_method' => 'qris',
        'amount' => 49000,
        'status' => 'pending',
    ]);

    $this->actingAs($this->admin, 'admin')->post(route('admin.transactions.approve', $trx->id));
    $user->refresh();

    expect($user->hasActiveVip())->toBeTrue();
    expect($user->hasActiveFullAccess())->toBeTrue();

    // Check random locked episode
    $randomLockedEp = Episode::where('status', 'published')
        ->where('episode_number', '>', 2)
        ->first();

    if ($randomLockedEp) {
        expect($randomLockedEp->isAccessibleBy($user))->toBeTrue();
    }
});
