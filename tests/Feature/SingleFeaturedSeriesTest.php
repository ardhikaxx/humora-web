<?php

use App\Models\Admin;
use App\Models\Category;
use App\Models\Series;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = Admin::firstOrCreate(
        ['email' => 'admin_featured_test@example.com'],
        [
            'name' => 'Featured Test Admin',
            'phone' => '081233344455',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]
    );

    $this->category = Category::firstOrCreate(
        ['slug' => 'cat-featured-test'],
        ['name' => 'Category Featured Test', 'is_active' => true]
    );
});

test('admin cannot create a new featured series if an active featured series already exists', function () {
    // Reset existing featured series for this test
    Series::query()->update(['is_featured' => false]);

    $featuredSeries = Series::create([
        'title' => 'Series Juara Unggulan',
        'slug' => 'series-juara-unggulan-'.uniqid(),
        'synopsis' => 'Sinopsis unggulan aktif',
        'release_year' => '2026',
        'is_featured' => true,
        'status' => 'published',
    ]);

    $fakeImage = UploadedFile::fake()->image('thumb_new.jpg', 640, 360);

    $response = $this->actingAs($this->admin, 'admin')->post(route('admin.series.store'), [
        'title' => 'Series Baru Mau Jadi Unggulan',
        'synopsis' => 'Sinopsis baru',
        'release_year' => '2026',
        'categories' => [$this->category->id],
        'thumbnail' => $fakeImage,
        'status' => 'published',
        'is_featured' => '1',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $errorMessage = session('error');
    expect($errorMessage)->toContain('Gagal mengaktifkan unggulan');
    expect($errorMessage)->toContain($featuredSeries->title);

    // Make sure no new featured series was created
    $newSeries = Series::where('title', 'Series Baru Mau Jadi Unggulan')->first();
    expect($newSeries)->toBeNull();
});

test('admin cannot update another series to featured if an active featured series already exists', function () {
    Series::query()->update(['is_featured' => false]);

    $currentFeatured = Series::create([
        'title' => 'Series Unggulan Utama',
        'slug' => 'series-unggulan-utama-'.uniqid(),
        'synopsis' => 'Sinopsis unggulan aktif',
        'release_year' => '2026',
        'is_featured' => true,
        'status' => 'published',
    ]);

    $otherSeries = Series::create([
        'title' => 'Series Lain Non Unggulan',
        'slug' => 'series-lain-non-unggulan-'.uniqid(),
        'synopsis' => 'Sinopsis series lain',
        'release_year' => '2026',
        'is_featured' => false,
        'status' => 'published',
    ]);
    $otherSeries->categories()->attach($this->category->id);

    $response = $this->actingAs($this->admin, 'admin')->put(route('admin.series.update', $otherSeries->id), [
        'title' => $otherSeries->title,
        'synopsis' => $otherSeries->synopsis,
        'release_year' => '2026',
        'categories' => [$this->category->id],
        'status' => 'published',
        'is_featured' => '1',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $errorMessage = session('error');
    expect($errorMessage)->toContain('Gagal mengaktifkan unggulan');
    expect($errorMessage)->toContain($currentFeatured->title);

    $otherSeries->refresh();
    expect($otherSeries->is_featured)->toBeFalse();
});

test('current featured series can update itself while keeping is_featured active', function () {
    Series::query()->update(['is_featured' => false]);

    $currentFeatured = Series::create([
        'title' => 'Series Unggulan Sah',
        'slug' => 'series-unggulan-sah-'.uniqid(),
        'synopsis' => 'Sinopsis awal',
        'release_year' => '2026',
        'is_featured' => true,
        'status' => 'published',
    ]);
    $currentFeatured->categories()->attach($this->category->id);

    $response = $this->actingAs($this->admin, 'admin')->put(route('admin.series.update', $currentFeatured->id), [
        'title' => 'Series Unggulan Sah (Updated)',
        'synopsis' => 'Sinopsis diperbarui',
        'release_year' => '2026',
        'categories' => [$this->category->id],
        'status' => 'published',
        'is_featured' => '1',
    ]);

    $response->assertRedirect(route('admin.series.index'));
    $response->assertSessionHas('success');

    $currentFeatured->refresh();
    expect($currentFeatured->is_featured)->toBeTrue();
    expect($currentFeatured->title)->toBe('Series Unggulan Sah (Updated)');
});

test('series create and edit pages render active featured restriction data attributes', function () {
    Series::query()->update(['is_featured' => false]);

    $activeFeatured = Series::create([
        'title' => 'Series Bintang Spotlight',
        'slug' => 'series-bintang-spotlight-'.uniqid(),
        'synopsis' => 'Sinopsis spotlight',
        'release_year' => '2026',
        'is_featured' => true,
        'status' => 'published',
    ]);
    $activeFeatured->categories()->attach($this->category->id);

    $plainSeries = Series::create([
        'title' => 'Series Biasa',
        'slug' => 'series-biasa-'.uniqid(),
        'synopsis' => 'Sinopsis biasa',
        'release_year' => '2026',
        'is_featured' => false,
        'status' => 'published',
    ]);
    $plainSeries->categories()->attach($this->category->id);

    // 1. Create page should pass active featured series title
    $resCreate = $this->actingAs($this->admin, 'admin')->get(route('admin.series.create'));
    $resCreate->assertStatus(200);
    $resCreate->assertSee('data-active-featured="Series Bintang Spotlight"', false);
    $resCreate->assertSee('Unggulan aktif saat ini:');

    // 2. Edit page for non-featured series should also show restriction
    $resEditPlain = $this->actingAs($this->admin, 'admin')->get(route('admin.series.edit', $plainSeries->id));
    $resEditPlain->assertStatus(200);
    $resEditPlain->assertSee('data-active-featured="Series Bintang Spotlight"', false);
    $resEditPlain->assertSee('Unggulan aktif saat ini:');

    // 3. Edit page for the active featured series itself should allow editing without restriction
    $resEditSelf = $this->actingAs($this->admin, 'admin')->get(route('admin.series.edit', $activeFeatured->id));
    $resEditSelf->assertStatus(200);
    $resEditSelf->assertSee('data-active-featured=""', false);
    $resEditSelf->assertSee('Judul ini sedang aktif sebagai Series Unggulan utama');
});
