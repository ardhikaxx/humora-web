<?php

use App\Models\Category;
use App\Models\Episode;
use App\Models\Series;
use App\Models\User;
use App\Services\FileStorageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->streamFile = 'test-stream.webm';
    $this->streamPath = FileStorageService::getUploadPath('videos').DIRECTORY_SEPARATOR.$this->streamFile;
    copy(base_path('tests/fixtures/test-stream.webm'), $this->streamPath);

    $this->category = Category::create([
        'name' => 'Sketsa Kantor',
        'slug' => 'sketsa-kantor',
        'description' => 'Komedi seputar dunia kerja',
    ]);

    $this->series = Series::create([
        'title' => 'Lembur Berujung Makmur',
        'slug' => 'lembur-berujung-makmur',
        'synopsis' => 'Kisah komedi perjuangan karyawan lembur',
        'creator' => 'Humora Studio',
        'cast' => 'Budi, Siti, Joko',
        'rating' => '13+',
        'status' => 'published',
        'is_featured' => true,
    ]);

    $this->series->categories()->attach($this->category->id);

    // Episode 1 (Free Preview)
    $this->episode1 = Episode::create([
        'series_id' => $this->series->id,
        'episode_number' => 1,
        'title' => 'Hari Pertama Bikin Pusing',
        'synopsis' => 'Budi pertama kali masuk kerja',
        'duration' => '02:54',
        'duration_seconds' => 174,
        'video_file' => $this->streamFile,
        'access_type' => 'free',
        'status' => 'published',
    ]);

    // Episode 2 (Free Preview)
    $this->episode2 = Episode::create([
        'series_id' => $this->series->id,
        'episode_number' => 2,
        'title' => 'Kopi Tumpah di Rapat Direksi',
        'synopsis' => 'Insiden kopi panas berujung promosi jabatan',
        'duration' => '02:53',
        'duration_seconds' => 173,
        'video_file' => $this->streamFile,
        'access_type' => 'free',
        'status' => 'published',
    ]);

    // Episode 3 (Premium VIP Only)
    $this->episode3 = Episode::create([
        'series_id' => $this->series->id,
        'episode_number' => 3,
        'title' => 'Drama Naik Gaji',
        'synopsis' => 'Negosiasi gaji yang berubah jadi kompetisi stand-up',
        'duration' => '02:43',
        'duration_seconds' => 163,
        'video_file' => $this->streamFile,
        'access_type' => 'premium',
        'status' => 'published',
    ]);
});

afterEach(function () {
    if (! empty($this->streamPath) && is_file($this->streamPath)) {
        @unlink($this->streamPath);
    }
});

test('guest can list series catalog and see published series', function () {
    $response = $this->getJson('/api/v1/series');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'title', 'slug', 'total_episodes', 'categories'],
            ],
        ]);
});

test('guest can view series details with all episodes metadata', function () {
    $response = $this->getJson('/api/v1/series/'.$this->series->slug);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'title' => 'Lembur Berujung Makmur',
            ],
        ]);

    $episodes = $response->json('data.episodes');
    expect(count($episodes))->toBe(3);
    expect($episodes[0]['is_free'])->toBeTrue();
    expect($episodes[0]['is_locked'])->toBeFalse();
    expect($episodes[1]['is_free'])->toBeTrue();
    expect($episodes[1]['is_locked'])->toBeFalse();
    expect($episodes[2]['is_free'])->toBeFalse();
    expect($episodes[2]['is_locked'])->toBeTrue();
});

test('guest and free user can stream episode 1 and 2 preview', function () {
    // Guest streaming episode 1
    $guestResponse = $this->get('/api/v1/files/stream/'.$this->episode1->id);
    $guestResponse->assertStatus(200);

    // Free registered user streaming episode 2
    $freeUser = User::create([
        'name' => 'Free Watcher',
        'email' => 'free@watcher.com',
        'password' => Hash::make('Secret123!'),
        'tier' => 'free',
        'status' => 'active',
    ]);
    $token = $freeUser->createToken('test-token')->plainTextToken;

    $userResponse = $this->withHeader('Authorization', 'Bearer '.$token)
        ->get('/api/v1/files/stream/'.$this->episode2->id);
    $userResponse->assertStatus(200);
});

test('stream endpoint supports HTTP 206 Partial Content for video range requests', function () {
    $response = $this->withHeader('Range', 'bytes=0-100')
        ->get('/api/v1/files/stream/'.$this->episode1->id);

    $response->assertStatus(206);
    $response->assertHeader('Accept-Ranges', 'bytes');
    $response->assertHeader('Content-Range');
});

test('guest and free user cannot stream episode 3 (returns 403 PREMIUM_REQUIRED)', function () {
    // Guest attempt
    $guestResponse = $this->getJson('/api/v1/files/stream/'.$this->episode3->id);
    $guestResponse->assertStatus(403)
        ->assertJson([
            'success' => false,
            'error_code' => 'PREMIUM_REQUIRED',
        ]);

    // Free user attempt
    $freeUser = User::create([
        'name' => 'Free User 2',
        'email' => 'free2@example.com',
        'password' => Hash::make('Secret123!'),
        'tier' => 'free',
        'status' => 'active',
    ]);
    $token = $freeUser->createToken('test-token')->plainTextToken;

    $userResponse = $this->withHeader('Authorization', 'Bearer '.$token)
        ->getJson('/api/v1/files/stream/'.$this->episode3->id);
    $userResponse->assertStatus(403)
        ->assertJson([
            'success' => false,
            'error_code' => 'PREMIUM_REQUIRED',
        ]);
});

test('active VIP user can stream episode 3 without restriction', function () {
    $vipUser = User::create([
        'name' => 'VIP Boss',
        'email' => 'vip@boss.com',
        'password' => Hash::make('Secret123!'),
        'tier' => 'premium',
        'valid_until' => Carbon::now()->addDays(30),
        'status' => 'active',
    ]);
    $token = $vipUser->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->get('/api/v1/files/stream/'.$this->episode3->id);

    $response->assertStatus(200);
});
