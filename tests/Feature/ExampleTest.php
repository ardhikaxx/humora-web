<?php

test('root redirects to admin dashboard', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
    $response->assertRedirect(route('admin.dashboard'));
});
