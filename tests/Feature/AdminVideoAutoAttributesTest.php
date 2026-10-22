<?php

use App\Models\Admin;
use App\Models\Category;
use App\Models\Episode;
use App\Models\Series;
use App\Services\FileStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = Admin::updateOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'Admin Humora',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]
    );

    $this->category = Category::firstOrCreate(
        ['slug' => 'komedi-sketsa'],
        ['name' => 'Komedi Sketsa', 'is_active' => true]
    );

    $this->series = Series::firstOrCreate(
        ['slug' => 'test-series-auto-ep'],
        [
            'title' => 'Test Series Auto Ep',
            'synopsis' => 'Sinopsis test series',
            'release_year' => 2026,
            'catalog_order' => 99,
            'status' => 'published',
            'thumbnail' => 'test-thumb.webp',
        ]
    );
});

test('videos create page renders auto episode number and dynamic dataset attributes', function () {
    $response = $this->actingAs($this->admin, 'admin')->get('/admin/videos/create');

    $response->assertStatus(200);
    $response->assertSee('Nomor Episode');
    $response->assertSee('Otomatis Urutan ke-');
    $response->assertSee('Durasi Episode Otomatis');
    $response->assertSee('data-next-ep=', false);
});

test('storing video without manual episode number automatically assigns next sequential number', function () {
    // Delete existing episodes for this test series
    Episode::where('series_id', $this->series->id)->delete();

    // Create first episode without passing episode_number
    $res1 = $this->actingAs($this->admin, 'admin')->post('/admin/videos', [
        'series_id' => $this->series->id,
        'title' => 'Episode Pertama Auto',
        'synopsis' => 'Deskripsi ep 1',
        'status' => 'published',
    ]);

    $res1->assertRedirect('/admin/videos');

    $ep1 = Episode::where('series_id', $this->series->id)->where('title', 'Episode Pertama Auto')->first();
    expect($ep1)->not->toBeNull();
    expect($ep1->episode_number)->toBe(1);
    expect($ep1->access_type)->toBe('free'); // Policy: Ep 1 is free

    // Create second episode without passing episode_number
    $res2 = $this->actingAs($this->admin, 'admin')->post('/admin/videos', [
        'series_id' => $this->series->id,
        'title' => 'Episode Kedua Auto',
        'synopsis' => 'Deskripsi ep 2',
        'status' => 'published',
    ]);

    $res2->assertRedirect('/admin/videos');

    $ep2 = Episode::where('series_id', $this->series->id)->where('title', 'Episode Kedua Auto')->first();
    expect($ep2)->not->toBeNull();
    expect($ep2->episode_number)->toBe(2);
    expect($ep2->access_type)->toBe('free'); // Policy: Ep 2 is free

    // Create third episode without passing episode_number -> should be 3 and premium
    $res3 = $this->actingAs($this->admin, 'admin')->post('/admin/videos', [
        'series_id' => $this->series->id,
        'title' => 'Episode Ketiga Auto',
        'synopsis' => 'Deskripsi ep 3',
        'status' => 'published',
    ]);

    $res3->assertRedirect('/admin/videos');

    $ep3 = Episode::where('series_id', $this->series->id)->where('title', 'Episode Ketiga Auto')->first();
    expect($ep3)->not->toBeNull();
    expect($ep3->episode_number)->toBe(3);
    expect($ep3->access_type)->toBe('premium'); // Policy: Ep 3+ is premium
});

test('storing video with uploaded file automatically extracts video duration via ffprobe', function () {
    $ffmpeg = FileStorageService::getFfmpegPath();
    expect($ffmpeg)->not->toBeEmpty();

    // Generate a 4-second video
    $tempVideo = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test_upload_dur_' . uniqid() . '.mp4';
    $cmd = sprintf(
        '"%s" -y -f lavfi -i testsrc=duration=4:size=320x180:rate=15 -f lavfi -i sine=frequency=440:duration=4 -c:v libx264 -c:a aac %s 2>&1',
        $ffmpeg,
        escapeshellarg($tempVideo)
    );
    $out = [];
    $ret = 0;
    @exec($cmd, $out, $ret);

    expect($ret)->toBe(0);
    expect(file_exists($tempVideo))->toBeTrue();

    $uploadedFile = new UploadedFile($tempVideo, 'video_skit.mp4', 'video/mp4', null, true);

    $response = $this->actingAs($this->admin, 'admin')->post('/admin/videos', [
        'series_id' => $this->series->id,
        'title' => 'Episode Video Upload Duration Test',
        'status' => 'published',
        'video_file' => $uploadedFile,
    ]);

    $response->assertRedirect('/admin/videos');

    $episode = Episode::where('series_id', $this->series->id)->where('title', 'Episode Video Upload Duration Test')->first();
    expect($episode)->not->toBeNull();
    expect($episode->duration_seconds)->toBe(4);
    expect($episode->duration)->toBe('0m 04s');

    // Clean up created files
    if ($episode->video_file && $episode->video_file !== 'sample-skit.mp4') {
        app(FileStorageService::class)->deleteFile('videos', $episode->video_file);
    }
    if (file_exists($tempVideo)) {
        @unlink($tempVideo);
    }
});

test('admin can explicitly set and update episode access_type to free or premium', function () {
    // Seed 2 initial episodes so series has > 2 published episodes
    Episode::create([
        'series_id' => $this->series->id,
        'episode_number' => 1,
        'title' => 'Ep 1',
        'status' => 'published',
    ]);
    Episode::create([
        'series_id' => $this->series->id,
        'episode_number' => 2,
        'title' => 'Ep 2',
        'status' => 'published',
    ]);

    // 1. Create episode with explicit premium access
    $resCreatePrem = $this->actingAs($this->admin, 'admin')->post('/admin/videos', [
        'series_id' => $this->series->id,
        'episode_number' => 3,
        'title' => 'Explicit Premium Ep',
        'access_type' => 'premium',
        'status' => 'published',
    ]);
    $resCreatePrem->assertRedirect('/admin/videos');

    $epPrem = Episode::where('series_id', $this->series->id)->where('episode_number', 3)->first();
    expect($epPrem)->not->toBeNull();
    expect($epPrem->access_type)->toBe('premium');
    expect($epPrem->isFreePreview())->toBeFalse();

    // 2. Create episode with explicit free access
    $resCreateFree = $this->actingAs($this->admin, 'admin')->post('/admin/videos', [
        'series_id' => $this->series->id,
        'episode_number' => 4,
        'title' => 'Explicit Free Ep',
        'access_type' => 'free',
        'status' => 'published',
    ]);
    $resCreateFree->assertRedirect('/admin/videos');

    $epFree = Episode::where('series_id', $this->series->id)->where('episode_number', 4)->first();
    expect($epFree)->not->toBeNull();
    expect($epFree->access_type)->toBe('free');
    expect($epFree->isFreePreview())->toBeTrue();

    // 3. Edit epFree and change access_type to premium
    $resUpdate = $this->actingAs($this->admin, 'admin')->put('/admin/videos/' . $epFree->id, [
        'series_id' => $this->series->id,
        'episode_number' => 4,
        'title' => 'Explicit Free Ep (Now Premium)',
        'duration_minutes' => 2,
        'duration_seconds' => 0,
        'access_type' => 'premium',
        'status' => 'published',
    ]);
    $resUpdate->assertRedirect('/admin/videos');

    $epFree->refresh();
    expect($epFree->access_type)->toBe('premium');
    expect($epFree->isFreePreview())->toBeFalse();
});
