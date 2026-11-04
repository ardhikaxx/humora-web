<?php

use App\Models\Admin;
use App\Models\Category;
use App\Models\Series;

test('unauthenticated visitors are redirected to login when accessing admin portal', function (string $url) {
    $response = $this->get($url);
    $response->assertStatus(302);
    $response->assertRedirect(route('admin.login'));
})->with([
    '/admin/dashboard',
    '/admin/analytics',
    '/admin/series',
    '/admin/series/create',
    '/admin/videos',
    '/admin/videos/create',
    '/admin/categories',
    '/admin/users',
    '/admin/subscribers',
    '/admin/transactions',
    '/admin/admins',
    '/admin/profile',
    '/admin/settings',
]);

test('public auth pages render successfully', function (string $url) {
    $response = $this->get($url);
    $response->assertStatus(200);
})->with([
    '/login',
    '/admin/login',
]);

test('landing page renders successfully with 3 distinct sections and mobile promotion', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('HUMORA');
    $response->assertSee('hero-card-container');
    $response->assertSee('about-section');
    $response->assertSee('thumbnails-section');
    $response->assertSee('Tertawa Lepas');
});

test('authenticated admin can access all admin portal pages', function (string $url) {
    $admin = Admin::where('role', 'admin')->first() ?? Admin::factory()->create(['role' => 'admin', 'is_active' => true]);

    $response = $this->actingAs($admin, 'admin')->get($url);
    $response->assertStatus(200);
})->with([
    '/admin/dashboard',
    '/admin/analytics',
    '/admin/series',
    '/admin/series/create',
    '/admin/videos',
    '/admin/videos/create',
    '/admin/categories',
    '/admin/users',
    '/admin/subscribers',
    '/admin/transactions',
    '/admin/admins',
    '/admin/profile',
    '/admin/settings',
]);

test('series create and edit pages render clean category pill badges without icon boxes', function () {
    $admin = Admin::where('role', 'admin')->first() ?? Admin::factory()->create(['role' => 'admin', 'is_active' => true]);

    $category = Category::firstOrCreate(
        ['slug' => 'uji-genre-komedi'],
        ['name' => 'Uji Genre Komedi', 'is_active' => true]
    );

    $series = Series::firstOrCreate(
        ['slug' => 'series-genre-test'],
        [
            'title' => 'Series Genre Test',
            'synopsis' => 'Sinopsis genre test',
            'release_year' => '2026',
            'status' => 'published',
        ]
    );

    $resCreate = $this->actingAs($admin, 'admin')->get('/admin/series/create');
    $resCreate->assertStatus(200);
    $resCreate->assertSee('genre-chips-grid');
    $resCreate->assertSee('genre-chip-title');
    $resCreate->assertSee($category->name);
    $resCreate->assertDontSee('genre-chip-icon-box');

    $resEdit = $this->actingAs($admin, 'admin')->get('/admin/series/'.$series->id.'/edit');
    $resEdit->assertStatus(200);
    $resEdit->assertSee('genre-chips-grid');
    $resEdit->assertSee('genre-chip-title');
    $resEdit->assertSee($category->name);
    $resEdit->assertDontSee('genre-chip-icon-box');
});
