<?php

test('all frontend pages render successfully', function (string $url) {
    $response = $this->get($url);

    $response->assertStatus(200);
})->with([
    '/',
    '/login',
    '/forgot-password',
    '/reset-password',
    '/admin/dashboard',
    '/admin/analytics',
    '/admin/series',
    '/admin/series/create',
    '/admin/series/1',
    '/admin/series/1/edit',
    '/admin/videos',
    '/admin/videos/create',
    '/admin/videos/1/edit',
    '/admin/categories',
    '/admin/users',
    '/admin/subscribers',
    '/admin/transactions',
    '/admin/admins',
    '/admin/profile',
    '/admin/settings',
]);
