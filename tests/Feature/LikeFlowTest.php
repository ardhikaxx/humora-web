<?php

use App\Models\Category;
use App\Models\Episode;
use App\Models\Like;
use App\Models\Series;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->category = Category::firstOrCreate(
        ['slug' => 'komedi-standup-test'],
        ['name' => 'Komedi Standup Test', 'is_active' => true]
    );

    $this->series = Series::create([
        'title' => 'Test Series Like Flow',
        'slug' => 'test-series-like-flow-'.uniqid(),
        'synopsis' => 'Test Series Synopsis',
        'status' => 'published',
        'release_year' => 2026,
    ]);

    $this->series->categories()->attach($this->category->id);

    $this->episode = Episode::create([
        'series_id' => $this->series->id,
        'episode_number' => 1,
        'title' => 'Episode 1 Pilot',
        'duration' => '02:00',
        'duration_seconds' => 120,
        'access_type' => 'free',
        'status' => 'published',
    ]);
});

test('guest can toggle like on episode and it increments episode likes count', function () {
    $epId = $this->episode->id;

    // Initial count is 0
    expect(Episode::withCount('likes')->find($epId)->likes_count)->toBe(0);

    // Guest likes episode
    $response = $this->withHeaders(['X-Session-ID' => 'guest-test-session-1'])
        ->postJson("/api/v1/episodes/{$epId}/like");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'is_liked' => true,
                'likes_count' => 1,
            ],
        ]);

    expect(Like::count())->toBe(1);
    expect(Episode::withCount('likes')->find($epId)->likes_count)->toBe(1);

    // Guest unlikes episode
    $unlikeResponse = $this->withHeaders(['X-Session-ID' => 'guest-test-session-1'])
        ->postJson("/api/v1/episodes/{$epId}/like");

    $unlikeResponse->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'is_liked' => false,
                'likes_count' => 0,
            ],
        ]);

    expect(Like::count())->toBe(0);
    expect(Episode::withCount('likes')->find($epId)->likes_count)->toBe(0);
});

test('authenticated user can toggle like on episode and series', function () {
    $user = User::create([
        'name' => 'Test Member',
        'email' => 'member-'.uniqid().'@example.com',
        'phone' => '0812'.rand(10000000, 99999999),
        'password' => Hash::make('password'),
        'status' => 'active',
        'tier' => 'free',
    ]);

    $epId = $this->episode->id;
    $seriesId = $this->series->id;

    // Member likes episode
    $response = $this->actingAs($user, 'sanctum')
        ->postJson("/api/v1/episodes/{$epId}/like");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'is_liked' => true,
                'likes_count' => 1,
            ],
        ]);

    $like = Like::first();
    expect($like->user_id)->toBe($user->id);
    expect($like->episode_id)->toBe($epId);

    // Series detail includes is_liked true for this episode
    $detailResponse = $this->actingAs($user, 'sanctum')
        ->getJson("/api/v1/series/{$seriesId}");

    $detailResponse->assertStatus(200);
    $epData = collect($detailResponse->json('data.episodes'))->firstWhere('id', $epId);
    expect($epData['is_liked'])->toBeTrue();
    expect($epData['likes_count'])->toBe(1);
});

test('guest likes are merged to user on login', function () {
    $userEmail = 'login-merge-'.uniqid().'@example.com';
    $user = User::create([
        'name' => 'Merge Member',
        'email' => $userEmail,
        'phone' => '0813'.rand(10000000, 99999999),
        'password' => Hash::make('secret123'),
        'status' => 'active',
        'tier' => 'free',
    ]);

    $epId = $this->episode->id;
    $guestSession = 'merge-session-'.uniqid();

    // Guest likes episode
    $this->withHeaders(['X-Session-ID' => $guestSession])
        ->postJson("/api/v1/episodes/{$epId}/like");

    expect(Like::first()->user_id)->toBeNull();

    // User logs in with same X-Session-ID
    $loginResponse = $this->withHeaders(['X-Session-ID' => $guestSession])
        ->postJson('/api/v1/auth/login', [
            'login' => $userEmail,
            'password' => 'secret123',
        ]);

    $loginResponse->assertStatus(200);

    // Guest like should now be associated with the user
    expect(Like::first()->user_id)->toBe($user->id);
});
