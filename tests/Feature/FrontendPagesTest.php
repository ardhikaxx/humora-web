<?php

use App\Models\Admin;

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
