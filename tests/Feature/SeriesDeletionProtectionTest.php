<?php

use App\Models\Admin;
use App\Models\Category;
use App\Models\Episode;
use App\Models\Series;
use App\Services\FileStorageService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = Admin::firstOrCreate(
        ['email' => 'admin_protect_test@example.com'],
        [
            'name' => 'Protect Admin',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]
    );

    $this->category = Category::firstOrCreate(
        ['slug' => 'test-protect-category'],
        ['name' => 'Category Protect', 'is_active' => true]
    );
});

test('admin cannot delete a series that still has episode video data', function () {
    $series = Series::create([
        'title' => 'Protected Series With Episodes',
        'slug' => 'protected-series-'.uniqid(),
        'synopsis' => 'Sinopsis protected series',
        'release_year' => '2026',
        'status' => 'published',
    ]);
    $series->categories()->attach($this->category->id);

    $episode = Episode::create([
        'series_id' => $series->id,
        'episode_number' => 1,
        'title' => 'Episode 1 of Protected Series',
        'video_file' => 'vid_test_protected.webm',
        'duration' => '02:00',
        'duration_seconds' => 120,
        'access_type' => 'free',
        'status' => 'published',
    ]);

    // Admin attempts to delete the series via HTTP
    $response = $this->actingAs($this->admin, 'admin')
        ->delete(route('admin.series.destroy', $series->id));

    // Must be redirected with error flash message
    $response->assertRedirect(route('admin.series.index'));
    $response->assertSessionHas('error');

    // Series and episode must still exist in the database
    expect(Series::find($series->id))->not->toBeNull();
    expect(Episode::find($episode->id))->not->toBeNull();

    // Clean up
    $episode->delete();
    $series->delete();
});

test('model event cancels series deletion directly if episodes exist', function () {
    $series = Series::create([
        'title' => 'Model Event Protected Series',
        'slug' => 'model-event-protected-'.uniqid(),
        'synopsis' => 'Sinopsis model event',
        'release_year' => '2026',
        'status' => 'draft',
    ]);

    $episode = Episode::create([
        'series_id' => $series->id,
        'episode_number' => 1,
        'title' => 'Blocking Episode',
        'duration' => '01:00',
        'duration_seconds' => 60,
        'access_type' => 'free',
        'status' => 'draft',
    ]);

    // Attempting direct Eloquent deletion
    $result = $series->delete();

    // Eloquent delete() returns false when event listener returns false
    expect($result)->toBeFalse();
    expect(Series::find($series->id))->not->toBeNull();

    // Clean up
    $episode->delete();
    $series->delete();
});

test('admin can delete a series if it has no episode videos', function () {
    $testThumb = 'thumb_empty_series_'.uniqid().'.webp';
    $thumbPath = FileStorageService::getUploadPath('thumbnails').DIRECTORY_SEPARATOR.$testThumb;
    File::put($thumbPath, 'dummy thumb for empty series');

    $series = Series::create([
        'title' => 'Empty Series Ready To Delete',
        'slug' => 'empty-series-'.uniqid(),
        'synopsis' => 'Sinopsis empty series',
        'release_year' => '2026',
        'thumbnail' => $testThumb,
        'status' => 'draft',
    ]);
    $series->categories()->attach($this->category->id);

    expect($series->episodes()->count())->toBe(0);
    expect(File::exists($thumbPath))->toBeTrue();

    // Admin attempts to delete the empty series
    $response = $this->actingAs($this->admin, 'admin')
        ->delete(route('admin.series.destroy', $series->id));

    $response->assertRedirect(route('admin.series.index'));
    $response->assertSessionHas('success');

    // Series must be deleted from database and thumbnail removed from disk
    expect(Series::find($series->id))->toBeNull();
    expect(File::exists($thumbPath))->toBeFalse();
});
