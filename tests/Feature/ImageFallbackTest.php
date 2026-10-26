<?php

use App\Models\Series;
use App\Models\Transaction;

test('series thumbnail falls back to default-img.png when empty', function () {
    $series = new Series([
        'title' => 'Test Fallback Series',
        'thumbnail' => null,
    ]);

    expect($series->thumbnail_url)->toBe(asset('images/default-img.png'));

    $series->thumbnail = '';
    expect($series->thumbnail_url)->toBe(asset('images/default-img.png'));
});

test('transaction proof falls back to default-img.png when empty', function () {
    $transaction = new Transaction([
        'trx_code' => 'TRX-TEST-001',
        'proof_file' => null,
    ]);

    expect($transaction->proof_url)->toBe(asset('images/default-img.png'));

    $transaction->proof_file = '';
    expect($transaction->proof_url)->toBe(asset('images/default-img.png'));
});

test('file storage endpoint falls back to default-img.png for missing thumbnail', function () {
    $response = $this->get('/api/v1/files/thumbnail/non-existent-image-12345.jpg');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'image/png');
});
