<?php

use App\Models\Episode;
use App\Models\Series;
use App\Models\User;
use App\Models\ViewingEvent;
use App\Models\WatchHistory;

beforeEach(function () {
    $this->series = Series::create([
        'title' => 'Serial Uji Coba Penayangan',
        'slug' => 'serial-uji-coba-penayangan-' . uniqid(),
        'synopsis' => 'Sinopsis serial uji coba',
        'status' => 'published',
    ]);

    $this->episode = Episode::create([
        'series_id' => $this->series->id,
        'episode_number' => 1,
        'title' => 'Episode Uji Coba 1',
        'duration' => '02:00',
        'duration_seconds' => 120,
        'status' => 'published',
    ]);
});

test('guest mobile user can record viewing event via track-view endpoint', function () {
    $sessionId = 'sess_guest_mobile_' . uniqid();

    $response = $this->postJson('/api/v1/videos/track-view', [
        'episode_id' => $this->episode->id,
        'session_id' => $sessionId,
        'watched_seconds' => 4,
        'last_position' => 4,
        'duration_seconds' => 120,
        'progress_percent' => 3,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    expect(ViewingEvent::where('session_id', $sessionId)->exists())->toBeTrue();
    $event = ViewingEvent::where('session_id', $sessionId)->first();
    expect($event->episode_id)->toBe($this->episode->id);
    expect($event->series_id)->toBe($this->series->id);
    expect($event->user_id)->toBeNull();
    expect($event->watched_seconds)->toBe(4);

    // Continuous playback update within the same session
    $updateRes = $this->postJson('/api/v1/videos/track-view', [
        'episode_id' => $this->episode->id,
        'session_id' => $sessionId,
        'watched_seconds' => 16,
        'last_position' => 16,
        'duration_seconds' => 120,
        'progress_percent' => 13,
    ]);
    $updateRes->assertStatus(200);

    $event->refresh();
    expect($event->watched_seconds)->toBe(16);
    expect(ViewingEvent::where('session_id', $sessionId)->count())->toBe(1);
});

test('authenticated mobile user records viewing event and syncs watch history', function () {
    $user = User::factory()->create(['status' => 'active']);
    $sessionId = 'sess_auth_user_' . uniqid();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/videos/track-view', [
        'episode_id' => $this->episode->id,
        'session_id' => $sessionId,
        'watched_seconds' => 10,
        'last_position' => 10,
        'duration_seconds' => 120,
        'progress_percent' => 8,
    ]);

    $response->assertStatus(200);

    $event = ViewingEvent::where('session_id', $sessionId)->first();
    expect($event)->not->toBeNull();
    expect($event->user_id)->toBe($user->id);

    $history = WatchHistory::where('user_id', $user->id)->where('episode_id', $this->episode->id)->first();
    expect($history)->not->toBeNull();
    expect($history->last_position)->toBe(10);
});
