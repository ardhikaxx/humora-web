<?php

test('root displays mobile app landing page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('HUMORA');
    $response->assertSee('Download');
});

test('download app route redirects gracefully if no apk in storage uploads mobile', function () {
    $response = $this->get('/download/app');

    // If no APK is in storage/uploads/mobile, redirects back to landing with friendly flash notification
    $response->assertRedirect(route('landing'));
    $response->assertSessionHas('apk_not_found');
});

test('download app route returns file when apk exists in storage uploads mobile', function () {
    $mobileDir = storage_path('uploads/mobile');
    if (! is_dir($mobileDir)) {
        mkdir($mobileDir, 0755, true);
    }

    $dummyApk = $mobileDir.DIRECTORY_SEPARATOR.'humora-test.apk';
    file_put_contents($dummyApk, 'dummy-apk-binary-content');

    try {
        $response = $this->get('/download/app');
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    } finally {
        if (file_exists($dummyApk)) {
            unlink($dummyApk);
        }
    }
});
