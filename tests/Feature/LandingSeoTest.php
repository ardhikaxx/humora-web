<?php

use App\Models\Series;

test('landing page contains comprehensive seo meta tags and structured data', function () {
    $series = Series::create([
        'title' => 'Serial Komedi Uji Coba SEO',
        'slug' => 'serial-komedi-uji-coba-seo',
        'synopsis' => 'Sinopsis lucu serial komedi untuk pengujian ranking SEO Google.',
        'status' => 'published',
        'catalog_order' => 1,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);

    // 1. Meta Tags
    $response->assertSee('<link rel="canonical"', false);
    $response->assertSee('meta name="description"', false);
    $response->assertSee('meta name="keywords"', false);
    $response->assertSee('serial komedi indonesia');
    $response->assertSee('sketsa komedi pendek');
    $response->assertSee('og:site_name', false);
    $response->assertSee('twitter:card', false);

    // 2. Schema.org JSON-LD
    $response->assertSee('application/ld+json', false);
    $response->assertSee('MobileApplication');
    $response->assertSee('FAQPage');
    $response->assertSee('HowTo');

    // 3. High-Converting Content Sections
    $response->assertSee('id="hero"', false);
    $response->assertSee('id="about"', false);
    $response->assertSee('id="katalog"', false);
    $response->assertSee('id="langkah"', false);
    $response->assertSee('id="paket"', false);
    $response->assertSee('id="faq"', false);

    // 4. Mobile Sticky Download Action
    $response->assertSee('mobile-sticky-bar');
    $response->assertSee('Unduh APK');
});

test('sitemap xml endpoint returns valid xml document with public routes', function () {
    $series = Series::create([
        'title' => 'Serial Sitemap Test',
        'slug' => 'serial-sitemap-test',
        'synopsis' => 'Sinopsis sitemap test.',
        'status' => 'published',
        'catalog_order' => 2,
    ]);

    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
    $response->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);
    $response->assertSee('<urlset', false);
    $response->assertSee(route('landing'));
    $response->assertSee(route('app.download'));
    $response->assertSee('serial-'.$series->slug);
});
