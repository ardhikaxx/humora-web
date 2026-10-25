<?php

use App\Jobs\ProcessVideoTranscodingJob;
use App\Models\Admin;
use App\Models\Episode;
use App\Models\Series;
use App\Services\FileStorageService;
use Illuminate\Support\Facades\File;

test('process video transcoding job handles transcode execution gracefully', function () {
    $series = Series::create([
        'title' => 'Job Transcode Test Series',
        'slug' => 'job-transcode-test-series',
        'synopsis' => 'Test series synopsis',
        'status' => 'published',
    ]);

    $episode = Episode::create([
        'series_id' => $series->id,
        'episode_number' => 1,
        'title' => 'Transcode Test Ep 1',
        'duration' => '1m 00s',
        'duration_seconds' => 60,
        'access_type' => 'free',
        'status' => 'published',
        'video_file' => 'nonexistent_test.mp4',
    ]);

    $mockStorage = Mockery::mock(FileStorageService::class);
    $mockStorage->shouldReceive('transcodeStoredVideoToWebm')
        ->once()
        ->with('nonexistent_test.mp4', 'videos', 'vid_ep1')
        ->andReturn('vid_ep1_transcoded.webm');

    $job = new ProcessVideoTranscodingJob($episode->id, 'nonexistent_test.mp4');
    $job->handle($mockStorage);

    $episode->refresh();
    expect($episode->video_file)->toBe('vid_ep1_transcoded.webm');
});

test('http caching headers are set correctly for public thumbnails and private proofs', function () {
    $thumbDir = FileStorageService::getUploadPath('thumbnails');
    $thumbFilename = 'test_cache_thumb.webp';
    File::put($thumbDir.DIRECTORY_SEPARATOR.$thumbFilename, 'dummy image content');

    $thumbResponse = $this->get('/api/v1/files/thumbnail/'.$thumbFilename);
    $thumbResponse->assertStatus(200);
    expect($thumbResponse->headers->get('Cache-Control'))
        ->toContain('public')
        ->toContain('max-age=604800');

    $proofDir = FileStorageService::getUploadPath('proofs');
    $proofFilename = 'test_cache_proof.jpg';
    File::put($proofDir.DIRECTORY_SEPARATOR.$proofFilename, 'dummy proof content');

    $admin = Admin::factory()->create([
        'role' => 'superadmin',
        'is_active' => true,
    ]);

    $proofResponse = $this->actingAs($admin, 'admin')->get('/admin/proofs/'.$proofFilename);
    $proofResponse->assertStatus(200);
    expect($proofResponse->headers->get('Cache-Control'))
        ->toContain('private')
        ->toContain('no-store');

    @unlink($thumbDir.DIRECTORY_SEPARATOR.$thumbFilename);
    @unlink($proofDir.DIRECTORY_SEPARATOR.$proofFilename);
});
