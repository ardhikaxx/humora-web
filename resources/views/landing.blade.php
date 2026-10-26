<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>HUMORA — Aplikasi Nonton Serial Komedi Indonesia | Streaming Video Sketsa Lucu Singkat APK Gratis</title>

    <!-- Meta SEO Utilitas & Pengindeksan Google (Peringkat #1 Search Engine) -->
    <meta name="description" content="Nonton puluhan serial sketsa komedi Indonesia lucu berdurasi ~2 menit di aplikasi HUMORA. Cerita lucu to the point, hemat kuota internet hingga 70%, bebas buffering. Episode 1 &amp; 2 GRATIS! Unduh APK Android resmi sekarang!">
    <meta name="keywords" content="humora, aplikasi humora, download apk humora, humora apk download, aplikasi nonton serial komedi, serial komedi indonesia, sketsa komedi pendek, nonton serial komedi lucu, streaming komedi indonesia, video lucu singkat, serial komedi gratis, nonton komedi hemat kuota, aplikasi streaming komedi indonesia, aplikasi nonton video lucu indonesia, aplikasi komedi android gratis, web series komedi indonesia, film pendek komedi lucu, sitkom indonesia terbaru 2026, sketsa lucu 2 menit, komedi pengusir stres, download video komedi offline, streaming komedi qris murah, rekomendasi tontonan lucu indonesia, drama komedi pendek indonesia, tontonan pengisi waktu luang lucu, komedi situasi indonesia, komedi keluarga indonesia, stand up comedy sketsa">
    <meta name="author" content="HUMORA Indonesia">
    <meta name="news_keywords" content="serial komedi indonesia, sketsa komedi, humora, streaming komedi, video lucu singkat">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="id" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    <!-- Target Wilayah & Bahasa (Lokal Indonesia) -->
    <meta name="geo.region" content="ID">
    <meta name="geo.country" content="ID">
    <meta name="geo.placename" content="Indonesia">
    <meta name="language" content="Indonesian">
    <meta name="rating" content="general">

    <!-- Open Graph / Facebook / WhatsApp Meta Tags -->
    <meta property="og:site_name" content="HUMORA">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('landing') }}">
    <meta property="og:title" content="HUMORA — Aplikasi Nonton Serial Komedi Indonesia | Streaming Video Sketsa Lucu APK">
    <meta property="og:description" content="Nonton puluhan serial sketsa komedi lucu khas Indonesia di aplikasi HUMORA. Cerita lucu ~2 menit langsung ke inti lelucon. Episode 1 &amp; 2 GRATIS untuk semua judul serial!">
    <meta property="og:image" content="{{ asset('images/logo-humora.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('images/logo-humora.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="HUMORA — Platform Serial Komedi Indonesia">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@humora_id">
    <meta name="twitter:creator" content="@humora_id">
    <meta name="twitter:title" content="HUMORA — Aplikasi Serial Komedi Indonesia | Streaming Sketsa Lucu APK">
    <meta name="twitter:description" content="Tonton serial sketsa komedi lucu khas Indonesia berdurasi ~2 menit. Episode 1 &amp; 2 GRATIS! Hemat kuota data, unduh aplikasi sekarang.">
    <meta name="twitter:image" content="{{ asset('images/logo-humora.png') }}">
    <meta name="twitter:image:alt" content="HUMORA — Platform Serial Komedi Indonesia">
    <meta name="twitter:app:name:googleplay" content="HUMORA">

    <!-- Browser & PWA Theme -->
    <meta name="theme-color" content="#43135C">
    <meta name="msapplication-TileColor" content="#43135C">
    <meta name="application-name" content="HUMORA">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-humora-rounded.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-humora-rounded.png') }}">

    <!-- Resource Preconnect & Preloads untuk Core Web Vitals (FCP, LCP) -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="image" href="{{ asset('images/mockup-device.webp') }}" fetchpriority="high">

    <!-- Google Fonts: Plus Jakarta Sans (Non-blocking display swap) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Landing Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ file_exists(public_path('css/landing.css')) ? filemtime(public_path('css/landing.css')) : time() }}">

    <!-- Schema.org JSON-LD Structured Data (Rich Snippets, Breadcrumb & Google SERP Features) -->
    @php
    $seriesItemsSchema = [];
    if (!empty($seriesList) && $seriesList->count() > 0) {
        foreach ($seriesList->take(12)->values() as $idx => $s) {
            $seriesItemsSchema[] = [
                '@type' => 'ListItem',
                'position' => $idx + 1,
                'item' => [
                    '@type' => 'CreativeWorkSeries',
                    'name' => $s->title,
                    'headline' => $s->title . ' — Serial Sketsa Komedi HUMORA',
                    'description' => $s->synopsis,
                    'image' => $s->thumbnail_url,
                    'genre' => $s->categories->pluck('name')->implode(', ') ?: 'Sketsa Komedi',
                    'inLanguage' => 'id-ID',
                    'countryOfOrigin' => 'ID',
                    'numberOfEpisodes' => $s->published_episodes_count ?? $s->episodes_count ?? 5,
                    'url' => route('landing') . '#serial-' . $s->slug,
                ],
            ];
        }
    }

    $structuredData = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebSite',
                '@id' => route('landing') . '#website',
                'url' => route('landing'),
                'name' => 'HUMORA',
                'alternateName' => 'Aplikasi Serial Komedi Indonesia',
                'description' => 'Aplikasi streaming serial sketsa komedi khas Indonesia berdurasi ~2 menit. Nonton Episode 1 & 2 GRATIS!',
                'inLanguage' => 'id-ID',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => route('landing') . '#katalog',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => route('landing') . '#breadcrumb',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Beranda HUMORA',
                        'item' => route('landing'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Katalog Serial Komedi',
                        'item' => route('landing') . '#katalog',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => 'Kategori & Tema Komedi',
                        'item' => route('landing') . '#kategori-topik',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 4,
                        'name' => 'Paket Langganan Hemat',
                        'item' => route('landing') . '#paket',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 5,
                        'name' => 'Download APK Android',
                        'item' => route('app.download'),
                    ],
                ],
            ],
            [
                '@type' => 'Organization',
                '@id' => route('landing') . '#organization',
                'name' => 'HUMORA Indonesia',
                'alternateName' => 'PT Humora Digital Kreatif Indonesia',
                'url' => route('landing'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo-humora-rounded.png'),
                    'caption' => 'Logo HUMORA Indonesia',
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer support',
                    'email' => 'marketing@globalmc.co.id',
                    'availableLanguage' => 'Indonesian',
                ],
                'sameAs' => [
                    'https://instagram.com/humora_id',
                    'https://tiktok.com/@humora_id',
                    'https://youtube.com/@humora_id',
                ],
            ],
            [
                '@type' => 'MobileApplication',
                '@id' => route('landing') . '#application',
                'name' => 'HUMORA — Serial Komedi Indonesia',
                'operatingSystem' => 'Android 5.0 (Lollipop) atau lebih baru',
                'applicationCategory' => 'EntertainmentApplication',
                'applicationSubCategory' => 'ComedyStreamingApplication',
                'downloadUrl' => route('app.download'),
                'fileFormat' => 'application/vnd.android.package-archive',
                'fileSize' => $apkSize ?? '15 MB',
                'softwareVersion' => '1.0.0',
                'contentRating' => 'Everyone',
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => '4.9',
                    'bestRating' => '5',
                    'worstRating' => '1',
                    'ratingCount' => '1420',
                    'reviewCount' => '890',
                ],
                'offers' => [
                    '@type' => 'AggregateOffer',
                    'lowPrice' => '0',
                    'highPrice' => '49000',
                    'priceCurrency' => 'IDR',
                    'offerCount' => '4',
                    'priceValidUntil' => '2027-12-31',
                ],
                'featureList' => [
                    'Streaming Video Sketsa Komedi HD Bebas Buffering',
                    'Hemat Kuota Internet hingga 70% dengan Kompresi WebM VP9',
                    'Episode 1 & 2 Gratis untuk Seluruh Judul Serial Komedi',
                    'Durasi Singkat ~2 Menit Langsung ke Inti Lelucon (Punchline)',
                    'Navigasi Intuitif dengan Tampilan Layar Tetap Aktif saat Menonton',
                    'Paket Langganan Terjangkau Didukung QRIS Instan Semua E-Wallet & Bank',
                ],
            ],
            [
                '@type' => 'ItemList',
                '@id' => route('landing') . '#series-list',
                'name' => 'Daftar Serial Sketsa Komedi Indonesia di HUMORA',
                'description' => 'Koleksi serial komedi situasi dan video sketsa lucu khas Indonesia. Episode 1 & 2 GRATIS!',
                'numberOfItems' => count($seriesItemsSchema),
                'itemListElement' => $seriesItemsSchema,
            ],
            [
                '@type' => 'HowTo',
                'name' => 'Cara Menonton Serial Komedi Gratis di Aplikasi HUMORA',
                'description' => 'Panduan 3 langkah mudah untuk mulai menonton serial sketsa komedi di ponsel Android.',
                'totalTime' => 'PT2M',
                'step' => [
                    [
                        '@type' => 'HowToStep',
                        'position' => 1,
                        'name' => 'Unduh Aplikasi HUMORA',
                        'text' => 'Klik tombol Download Aplikasi pada halaman ini untuk mengunduh berkas APK HUMORA Android (~15 MB).',
                        'url' => route('app.download'),
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 2,
                        'name' => 'Pilih Serial Komedi Favorit',
                        'text' => 'Buka aplikasi HUMORA di HP Anda dan jelajahi puluhan judul serial komedi khas Indonesia.',
                        'url' => route('landing') . '#katalog',
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 3,
                        'name' => 'Tonton Episode 1 & 2 Gratis',
                        'text' => 'Episode 1 dan 2 pada semua serial dapat langsung ditonton gratis tanpa batas dan hemat kuota data.',
                        'url' => route('landing') . '#about',
                    ],
                ],
            ],
            [
                '@type' => 'FAQPage',
                '@id' => route('landing') . '#faq',
                'mainEntity' => [
                    [
                        '@type' => 'Question',
                        'name' => 'Apa itu aplikasi HUMORA dan bagaimana konsep tontonannya?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'HUMORA adalah platform serial komedi pendek pertama di Indonesia yang menyajikan cerita sketsa komedi berdurasi ringkas sekitar 2 menit. Konsepnya to the point langsung ke inti lelucon tanpa basa-basi, sangat pas dinikmati di sela-sela kesibukan dan ramah kuota ponsel.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Apakah bisa menonton serial komedi di HUMORA secara gratis?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Ya! Episode 1 dan Episode 2 pada SEMUA judul serial komedi di HUMORA bisa ditonton 100% GRATIS selamanya tanpa memerlukan kartu kredit atau komitmen biaya awal.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Berapa biaya paket langganan video di HUMORA dan apa metode pembayarannya?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Paket langganan HUMORA sangat fleksibel dan terjangkau: Paket Mingguan 15 Video mulai Rp15.000 / 7 hari, Add-on 5 Video hanya Rp7.500, dan Full Access 30 hari Rp49.000. Seluruh transaksi didukung pembayaran instan QRIS (GoPay, OVO, Dana, ShopeePay, BCA, Mandiri, BRI, BNI).',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Mengapa video di HUMORA tidak lemot dan sangat hemat kuota internet?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'HUMORA menggunakan teknologi kompresi video WebM (VP9 + Opus audio) modern yang mampu mereduksi penggunaan kuota internet hingga 70% dibanding platform video konvensional, menghasilkan tayangan kualitas jernih HD bebas buffering.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Bagaimana cara download dan install aplikasi HUMORA di HP Android?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Cukup klik tombol Download Aplikasi pada halaman ini. Setelah berkas APK selesai diunduh (~15 MB), buka file tersebut di HP Anda, pilih Izinkan Instalasi dari Sumber Ini, dan aplikasi HUMORA siap digunakan langsung tanpa ribet.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Apakah aplikasi HUMORA aman untuk diunduh langsung?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Sangat aman. Berkas APK resmi HUMORA dibangun tanpa iklan berbahaya, bebas virus, tidak meminta izin data sensitif ponsel, dan dioptimalkan secara ketat untuk Android 5.0 ke atas.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Apakah serial komedi di HUMORA cocok ditonton bersama keluarga?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Sangat cocok! Cerita sketsa komedi di HUMORA mengangkat dinamika kehidupan sehari-hari masyarakat Indonesia, humor keluarga, dan situasi kerja yang lucu serta menghibur tanpa materi vulgar.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Apakah ada fitur unduh untuk menonton video secara offline?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Ya, aplikasi HUMORA dilengkapi fitur unduh offline di latar belakang sehingga Anda dapat menyimpan episode serial favorit saat terhubung Wi-Fi dan menontonnya di perjalanan tanpa menggunakan kuota internet.',
                        ],
                    ],
                ],
            ],
        ],
    ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>
<body>

    <div class="page-canvas">

        <!-- Flash Alert for APK not found (if redirected) -->
        @if(session('apk_not_found'))
        <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert" style="background: #FEF3C7; color: #92400E;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill fs-5 text-warning"></i>
                <div class="small">
                    <strong>Pemberitahuan Unduhan:</strong> {{ session('apk_not_found') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- ==================================================================
             SECTION 1: HERO SECTION (Ref: image.png Top Rounded Card)
             ================================================================== -->
        <section class="hero-card-container" id="hero">

            <!-- Embedded Navbar inside Hero Card -->
            <header class="hero-navbar">
                <nav class="nav-left-group" aria-label="Navigasi Utama HUMORA">
                    <a href="{{ route('landing') }}" class="brand-capsule" title="Beranda HUMORA">
                        <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="HUMORA Logo" width="28" height="28">
                        <span>HUMORA</span>
                    </a>
                    <a href="#about" class="nav-pill-link d-none d-sm-inline-flex">Fitur Unggulan</a>
                    <a href="#katalog" class="nav-pill-link d-none d-md-inline-flex">Serial Populer</a>
                    <a href="#kategori-topik" class="nav-pill-link d-none d-lg-inline-flex">Genre Komedi</a>
                    <a href="#langkah" class="nav-pill-link d-none d-lg-inline-flex">Cara Nonton</a>
                    <a href="#paket" class="nav-pill-link d-none d-xl-inline-flex">Paket Langganan</a>
                    <a href="#faq" class="nav-pill-link d-none d-xl-inline-flex">FAQ</a>
                </nav>

                <div class="nav-right-group">
                    <a href="#about" class="nav-text-link d-none d-lg-inline-block">Tentang</a>
                    <a href="#katalog" class="nav-text-link d-none d-sm-inline-block">Katalog</a>
                    <a href="{{ route('app.download') }}" class="btn-pill-yellow" title="Download APK HUMORA Android">
                        <i class="bi bi-download"></i>
                        <span class="d-none d-sm-inline">Download Aplikasi</span>
                        <span class="d-inline d-sm-none">Download</span>
                    </a>
                </div>
            </header>

            <!-- Hero Main Content Grid -->
            <div class="hero-content-grid">
                
                <!-- Left Column: Editorial Typography & Action CTAs -->
                <div class="hero-text-col">
                    <div class="hero-kicker-box">
                        <p class="hero-kicker-lead">Scroll, laugh, repeat. • Hiburan Komedi Indonesia</p>
                        <p class="hero-kicker-title">HUMORA — Platform Serial Komedi</p>
                    </div>

                    <h1 class="hero-headline">
                        Serial Komedi Indonesia,<br>
                        Tertawa Lepas Tanpa Batas
                    </h1>

                    <p class="hero-description">
                        Serial sketsa komedi pendek pertama di Indonesia dengan cerita lucu langsung ke inti lelucon. Nonton santai berdurasi <strong>~2 menit</strong>, hemat kuota internet hingga 70%, bebas putus-putus, dan <strong>Episode 1 &amp; 2 GRATIS</strong> untuk semua judul serial!
                    </p>

                    <div class="hero-btn-group">
                        <a href="{{ route('app.download') }}" class="btn-hero-download" title="Unduh Aplikasi HUMORA Android APK">
                            <i class="bi bi-download"></i>
                            <span>Download Aplikasi</span>
                            @if(!empty($apkSize))
                            <span class="btn-size-pill">{{ $apkSize }}</span>
                            @else
                            <span class="btn-size-pill">~15 MB</span>
                            @endif
                        </a>
                        <a href="#katalog" class="btn-hero-browse" title="Jelajahi Katalog Serial Komedi">
                            <span>Jelajahi Serial</span>
                            <i class="bi bi-arrow-down-short fs-5"></i>
                        </a>
                    </div>

                    <div class="hero-trust-badges">
                        <div class="trust-item">
                            <i class="bi bi-gift-fill text-warning"></i>
                            <span>Episode 1 &amp; 2 Gratis</span>
                        </div>
                        <div class="trust-divider"></div>
                        <div class="trust-item">
                            <i class="bi bi-lightning-charge-fill" style="color: var(--humora-primary);"></i>
                            <span>Bebas Buffering</span>
                        </div>
                        <div class="trust-divider"></div>
                        <div class="trust-item">
                            <i class="bi bi-star-fill text-warning"></i>
                            <span>Rating 4.9/5.0</span>
                        </div>
                        <div class="trust-divider"></div>
                        <div class="trust-item">
                            <i class="bi bi-phone-fill text-muted"></i>
                            <span>Android 5.0+</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Dual 3D Smartphone Mockup -->
                <div class="hero-visual-wrapper">
                    <img src="{{ asset('images/mockup-device.webp') }}" 
                         alt="Aplikasi Nonton Serial Komedi Indonesia HUMORA — Streaming Video Sketsa Lucu di HP Android" 
                         class="hero-mockup-img" 
                         width="1100" 
                         height="1250" 
                         loading="eager" 
                         fetchpriority="high">
                </div>

            </div>
        </section>


        <!-- ==================================================================
             SECTION 2: ABOUT SECTION + NUMBERED FEATURES
             ================================================================== -->
        <section class="about-section" id="about">

            <div class="about-headline-container">
                <h2 class="about-headline">
                    Nikmati hiburan serial komedi
                    <span class="inline-avatar-pill">
                        <svg class="avatar-img" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="40" height="40" fill="#E9D5FF"/>
                            <circle cx="20" cy="16" r="8" fill="#7E22CE"/>
                            <path d="M8 36 C8 28, 14 26, 20 26 C26 26, 32 28, 32 36 Z" fill="#7E22CE"/>
                        </svg>
                        <svg class="avatar-img" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="40" height="40" fill="#FEF08A"/>
                            <circle cx="20" cy="16" r="8" fill="#CA8A04"/>
                            <path d="M8 36 C8 28, 14 26, 20 26 C26 26, 32 28, 32 36 Z" fill="#CA8A04"/>
                        </svg>
                        <span class="avatar-badge">
                            <i class="bi bi-plus-lg"></i>
                        </span>
                    </span>
                    langsung di genggaman Anda
                </h2>
            </div>

            <div class="about-grid">

                <!-- Left Column Card: App Showcase with Direct Download Action -->
                <div class="about-left-card">
                    <div>
                        <div class="about-device-mockup">
                            <div class="device-top-bar">
                                <span class="device-dot dot-red"></span>
                                <span class="device-dot dot-yellow"></span>
                                <span class="device-dot dot-green"></span>
                                <span class="ms-2 text-muted" style="font-size: 0.75rem; font-weight: 600;">Aplikasi HUMORA Mobile</span>
                            </div>

                            <div class="device-screen-inner text-center">
                                <div class="row align-items-center g-3">
                                    <div class="col-sm-5 text-center">
                                        <img src="{{ asset('images/logo-humora.png') }}" alt="Aplikasi HUMORA" class="img-fluid rounded-3 mb-2" style="max-height: 120px;" width="120" height="120" loading="lazy">
                                        <div class="badge rounded-pill" style="background: var(--humora-primary-light); color: var(--humora-primary); font-size: 0.75rem;">
                                            Aplikasi Ringan &amp; Nyaman
                                        </div>
                                    </div>
                                    <div class="col-sm-7 text-start">
                                        <h3 class="fw-bold mb-1" style="color: var(--humora-text-main); font-size: 1.05rem;">
                                            Pengalaman Nonton Paling Asyik
                                        </h3>
                                        <p class="text-muted small mb-3">
                                            Didesain khusus untuk ponsel cerdas: layar tidak redup saat asyik tertawa, navigasi intuitif, dan video hemat kuota data berkat kompresi WebM VP9.
                                        </p>
                                        <div class="d-flex align-items-center gap-2 small text-muted mb-1.5">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            <span>Video Cepat Terbuka &amp; Bebas Putus-Putus</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 small text-muted mb-1.5">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            <span>Sangat Hemat Kuota Data Internet (Hingga 70%)</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            <span>Layar Ponsel Tetap Terang Selama Menonton</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="laptop-keyboard-decor"></div>

                        <div class="about-card-perks-grid mt-3 pt-1">
                            <div class="perk-mini-card">
                                <div class="perk-mini-icon icon-yellow">
                                    <i class="bi bi-play-btn-fill"></i>
                                </div>
                                <div class="perk-mini-body">
                                    <h4 class="perk-mini-title">Pemutar Video 16:9 Jernih</h4>
                                    <p class="perk-mini-desc">Format sinematik layar lebar dengan visual HD dan audio jernih.</p>
                                </div>
                            </div>

                            <div class="perk-mini-card">
                                <div class="perk-mini-icon icon-purple">
                                    <i class="bi bi-gift-fill"></i>
                                </div>
                                <div class="perk-mini-body">
                                    <h4 class="perk-mini-title">Episode 1 &amp; 2 Gratis</h4>
                                    <p class="perk-mini-desc">Bebas tonton awal episode di semua serial komedi tanpa bayar.</p>
                                </div>
                            </div>
                        </div>

                        <div class="about-card-specs-strip mt-3">
                            <div class="spec-pill">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span>Rating 4.9 / 5.0</span>
                            </div>
                            <div class="spec-dot">•</div>
                            <div class="spec-pill">
                                <i class="bi bi-hdd-fill text-muted"></i>
                                <span>Ringan ~{{ $apkSize ?? '15 MB' }}</span>
                            </div>
                            <div class="spec-dot">•</div>
                            <div class="spec-pill">
                                <i class="bi bi-shield-check text-success"></i>
                                <span>Android 5.0+ Bebas Lemot</span>
                            </div>
                        </div>
                    </div>

                    <div class="about-card-bottom-actions mt-4">
                        <a href="{{ route('app.download') }}" class="btn-card-pill-cta" title="Unduh Aplikasi HUMORA Android">
                            <i class="bi bi-download"></i>
                            <span class="d-none d-sm-inline">Unduh Aplikasi HUMORA (Android)</span>
                            <span class="d-inline d-sm-none">Unduh Aplikasi</span>
                        </a>

                        <a href="{{ route('app.download') }}" class="btn-circle-arrow" title="Unduh Sekarang">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Numbered 01, 02, 03 List + Yellow Stat Card -->
                <div class="about-right-col">

                    <div class="numbered-feature-list">

                        <div class="feature-item-row">
                            <span class="feature-number">01</span>
                            <h3 class="feature-title">Sketsa Singkat ~2 Menit</h3>
                            <p class="feature-desc">
                                Cerita komedi padat langsung ke inti lelucon tanpa bertele-tele. Pas banget dinikmati saat istirahat kerja, menunggu antrean, atau bersantai di rumah.
                            </p>
                        </div>

                        <div class="feature-item-row">
                            <span class="feature-number">02</span>
                            <h3 class="feature-title">Gratis Nonton &amp; Langganan Murah</h3>
                            <p class="feature-desc">
                                Episode 1 &amp; 2 GRATIS untuk semua judul serial. Mau lanjut nonton? Tersedia pilihan paket mingguan hemat mulai Rp15.000 atau tambah video mulai Rp7.500 saja via QRIS.
                            </p>
                        </div>

                        <div class="feature-item-row">
                            <span class="feature-number">03</span>
                            <h3 class="feature-title">Nonton Mulus &amp; Hemat Kuota</h3>
                            <p class="feature-desc">
                                Video langsung terputar tanpa jeda berputar (*buffering*), bisa dimajukan atau dimundurkan dengan mulus, dan sangat ramah kuota internet ponsel Anda.
                            </p>
                        </div>

                    </div>

                    <div class="about-bottom-split">
                        <p class="about-bottom-lead mb-0">
                            Setiap episode dibuat khusus untuk bikin Anda tersenyum dan tertawa lepas kapan pun butuh hiburan di sela rutinitas harian.
                        </p>

                        <div class="yellow-highlight-card">
                            <div class="stat-big">~2m</div>
                            <div class="stat-label">
                                Durasi ideal sketsa komedi, punchline instan bikin mood ceria seketika tanpa buang kuota.
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>


        <!-- ==================================================================
             SECTION 3: KATALOG SERIAL KOMEDI (Cards Showcase & Mosaic Loop)
             ================================================================== -->
        <section class="thumbnails-section" id="katalog">
            @php
                $availableThumbs = !empty($heroThumbnails) ? $heroThumbnails : [];
                $row1 = array_slice($availableThumbs, 0, 5);
                $row2 = array_slice($availableThumbs, 5, 5);
                $row3 = array_slice($availableThumbs, 10, 5);
                $row1Set = !empty($row1) ? array_merge($row1, $row1, $row1) : [];
                $row2Set = !empty($row2) ? array_merge($row2, $row2, $row2) : [];
                $row3Set = !empty($row3) ? array_merge($row3, $row3, $row3) : [];
            @endphp

            <div class="catalog-headline-container">
                <h2 class="catalog-headline">
                    Katalog serial komedi
                    <span class="inline-avatar-pill">
                        @if(!empty($availableThumbs[0]))
                            <img src="{{ $availableThumbs[0] }}" class="avatar-img" alt="Serial Komedi HUMORA" width="32" height="32" loading="lazy">
                        @else
                            <svg class="avatar-img" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect width="40" height="40" fill="#E9D5FF"/>
                                <circle cx="20" cy="16" r="8" fill="#7E22CE"/>
                                <path d="M8 36 C8 28, 14 26, 20 26 C26 26, 32 28, 32 36 Z" fill="#7E22CE"/>
                            </svg>
                        @endif
                        @if(!empty($availableThumbs[1]))
                            <img src="{{ $availableThumbs[1] }}" class="avatar-img" alt="Serial Komedi HUMORA" width="32" height="32" loading="lazy">
                        @else
                            <svg class="avatar-img" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect width="40" height="40" fill="#FEF08A"/>
                                <circle cx="20" cy="16" r="8" fill="#CA8A04"/>
                                <path d="M8 36 C8 28, 14 26, 20 26 C26 26, 32 28, 32 36 Z" fill="#CA8A04"/>
                            </svg>
                        @endif
                        <span class="avatar-badge">
                            <i class="bi bi-collection-play-fill text-warning"></i>
                        </span>
                    </span>
                    pilihan terpopuler
                </h2>
            </div>

            <!-- Real Serial Showcase Cards Grid (Rich Content & SEO Crawlability) -->
            @if(!empty($seriesList) && $seriesList->count() > 0)
            <div class="series-showcase-container">
                <div class="series-showcase-grid">
                    @foreach($seriesList->take(6) as $series)
                    <article class="series-preview-card" id="serial-{{ $series->slug }}">
                        <div class="series-thumb-frame">
                            <img src="{{ $series->thumbnail_url }}" 
                                 alt="{{ $series->title }} — Serial Sketsa Komedi HUMORA" 
                                 class="series-thumb-img" 
                                 width="480" 
                                 height="270" 
                                 loading="lazy" 
                                 decoding="async"
                                 onerror="this.src='{{ asset('images/logo-humora.png') }}'">
                            <span class="badge-free-trial">
                                <i class="bi bi-gift-fill"></i> Ep 1 &amp; 2 Gratis
                            </span>
                            <span class="badge-ep-count">
                                <i class="bi bi-play-circle"></i> {{ $series->published_episodes_count ?? $series->episodes_count ?? 5 }} Episode
                            </span>
                        </div>

                        <div class="series-card-body">
                            <div class="series-card-categories">
                                @forelse($series->categories as $cat)
                                <span class="category-tag-pill">{{ $cat->name }}</span>
                                @empty
                                <span class="category-tag-pill">Sketsa Komedi</span>
                                @endforelse
                                <span class="badge bg-light text-muted border" style="font-size: 0.72rem;">{{ $series->release_year }}</span>
                            </div>

                            <h3 class="series-card-title">{{ $series->title }}</h3>
                            <p class="series-card-synopsis">{{ $series->synopsis }}</p>

                            <div class="series-card-footer">
                                <span class="small text-muted">
                                    <i class="bi bi-clock me-1"></i>~2 Menit/Ep
                                </span>
                                <a href="{{ route('app.download') }}" class="series-play-cta" title="Nonton {{ $series->title }} di Aplikasi">
                                    <span>Tonton di Aplikasi</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- 3-Row Thumbnail Mosaic Layout (Edge-to-Edge Visual Banner) -->
            <div class="catalog-mosaic-wrapper">
                <div class="mosaic-fade-edge-left"></div>
                <div class="mosaic-fade-edge-right"></div>

                <div class="mosaic-row row-stagger-1">
                    <div class="mosaic-track track-left">
                        <div class="mosaic-set">
                            @foreach($row1Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" width="186" height="105" loading="lazy" decoding="async">
                            </div>
                            @endforeach
                        </div>
                        <div class="mosaic-set">
                            @foreach($row1Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" width="186" height="105" loading="lazy" decoding="async">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mosaic-row row-stagger-2">
                    <div class="mosaic-track track-right">
                        <div class="mosaic-set">
                            @foreach($row2Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" width="186" height="105" loading="lazy" decoding="async">
                            </div>
                            @endforeach
                        </div>
                        <div class="mosaic-set">
                            @foreach($row2Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" width="186" height="105" loading="lazy" decoding="async">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mosaic-row row-stagger-3">
                    <div class="mosaic-track track-left">
                        <div class="mosaic-set">
                            @foreach($row3Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" width="186" height="105" loading="lazy" decoding="async">
                            </div>
                            @endforeach
                        </div>
                        <div class="mosaic-set">
                            @foreach($row3Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" width="186" height="105" loading="lazy" decoding="async">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ==================================================================
             SECTION: KATEGORI & TEMA SERIAL KOMEDI INDONESIA (SEO Topical Authority)
             ================================================================== -->
        <section class="seo-topics-section py-4 my-2" id="kategori-topik">
            <div class="section-headline-block text-center mb-4">
                <span class="section-eyebrow">
                    <i class="bi bi-compass-fill"></i> Eksplorasi Genre &amp; Tema
                </span>
                <h2 class="section-main-title">Pilihan Genre Serial Komedi Indonesia Terpopuler</h2>
                <p class="section-subtext">Dari sketsa komedi keluarga yang hangat, candaan absurd anak kos, hingga parodi dunia kerja yang bikin angguk-angguk sambil tertawa lepas.</p>
            </div>

            <div class="row g-3 g-md-4 justify-content-center">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border-0 rounded-4 shadow-sm" style="background: var(--humora-card-bg); border: 1px solid var(--humora-card-border) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2.5 d-inline-flex" style="background: var(--humora-primary-light); color: var(--humora-primary); font-size: 1.3rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0" style="color: var(--humora-text-main);">Komedi Situasi Keluarga (Sitkom)</h3>
                        </div>
                        <p class="small text-muted mb-0">
                            Cerita sketsa kocak seputar dinamika rumah tangga Indonesia, lelucon antar tetangga, perdebatan menantu dan mertua, hingga drama kehidupan sehari-hari yang sangat dekat dengan realitas kita.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border-0 rounded-4 shadow-sm" style="background: var(--humora-card-bg); border: 1px solid var(--humora-card-border) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2.5 d-inline-flex" style="background: var(--humora-accent-yellow-bg); color: #B45309; font-size: 1.3rem;">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0" style="color: var(--humora-text-main);">Humor Kantor &amp; Dunia Kerja</h3>
                        </div>
                        <p class="small text-muted mb-0">
                            Obat mujarab pengusir stres setelah seharian bekerja! Sketsa lucu seputar drama deadline, tingkah rekan kerja unik, rapat kantor yang tak kunjung usai, hingga sindiran halus kehidupan pekerja kantoran.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border-0 rounded-4 shadow-sm" style="background: var(--humora-card-bg); border: 1px solid var(--humora-card-border) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2.5 d-inline-flex" style="background: var(--humora-success-bg); color: var(--humora-success); font-size: 1.3rem;">
                                <i class="bi bi-cup-hot-fill"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0" style="color: var(--humora-text-main);">Candaan Tongkrongan &amp; Kostan</h3>
                        </div>
                        <p class="small text-muted mb-0">
                            Humor santai tanpa filter khas obrolan warung kopi, tips bertahan hidup anak kos akhir bulan, candaan persahabatan masa muda, dan obrolan random yang selalu berhasil memicu tawa renyah.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border-0 rounded-4 shadow-sm" style="background: var(--humora-card-bg); border: 1px solid var(--humora-card-border) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2.5 d-inline-flex" style="background: #FEE2E2; color: #DC2626; font-size: 1.3rem;">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0" style="color: var(--humora-text-main);">Romansa Komedi &amp; Percintaan Lucu</h3>
                        </div>
                        <p class="small text-muted mb-0">
                            Lika-liku cinta masa kini yang dikemas jenaka: canggungnya kencan pertama, salah paham gombalan cinta, hingga tips pdkt kocak yang dijamin mengundang senyum sekaligus tawa geli.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border-0 rounded-4 shadow-sm" style="background: var(--humora-card-bg); border: 1px solid var(--humora-card-border) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2.5 d-inline-flex" style="background: #E0E7FF; color: #4338CA; font-size: 1.3rem;">
                                <i class="bi bi-fire"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0" style="color: var(--humora-text-main);">Parodi Tren Viral &amp; Pop Culture</h3>
                        </div>
                        <p class="small text-muted mb-0">
                            Sketsa komedi satire segar yang memparodikan fenomena media sosial, tren konten viral media sosial, hingga kebiasaan unik netizen Indonesia dalam menyikapi berita hangat sehari-hari.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 p-4 border-0 rounded-4 shadow-sm" style="background: var(--humora-card-bg); border: 1px solid var(--humora-card-border) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2.5 d-inline-flex" style="background: #FEF9C3; color: #A16207; font-size: 1.3rem;">
                                <i class="bi bi-moon-stars-fill"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-0" style="color: var(--humora-text-main);">Komedi Misteri &amp; Ronda Malam</h3>
                        </div>
                        <p class="small text-muted mb-0">
                            Bukan horor bikin takut, tapi cerita seram berujung konyol! Kisah ronda malam jaga poskamling, mitos hantu lokal yang salah sasaran, dan kepanikan warga yang berujung tawa terbahak-bahak.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Keyword Tags Cloud for Topical Authority -->
            <div class="mt-4 p-4 rounded-4" style="background: var(--humora-primary-subtle); border: 1px dashed var(--humora-primary-light);">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="small fw-bold me-1" style="color: var(--humora-primary);">
                        <i class="bi bi-search me-1"></i> Topik Pencarian Populer:
                    </span>
                    <a href="#katalog" class="badge text-decoration-none rounded-pill px-3 py-1.5" style="background: #FFFFFF; color: var(--humora-text-main); border: 1px solid #E5E7EB;">Aplikasi Nonton Serial Komedi Indonesia</a>
                    <a href="#katalog" class="badge text-decoration-none rounded-pill px-3 py-1.5" style="background: #FFFFFF; color: var(--humora-text-main); border: 1px solid #E5E7EB;">Sketsa Komedi Lucu Pendek</a>
                    <a href="{{ route('app.download') }}" class="badge text-decoration-none rounded-pill px-3 py-1.5" style="background: #FFFFFF; color: var(--humora-text-main); border: 1px solid #E5E7EB;">Download APK HUMORA Android</a>
                    <a href="#about" class="badge text-decoration-none rounded-pill px-3 py-1.5" style="background: #FFFFFF; color: var(--humora-text-main); border: 1px solid #E5E7EB;">Video Komedi Hemat Kuota WebM</a>
                    <a href="#paket" class="badge text-decoration-none rounded-pill px-3 py-1.5" style="background: #FFFFFF; color: var(--humora-text-main); border: 1px solid #E5E7EB;">Langganan Video QRIS Murah</a>
                    <a href="#katalog" class="badge text-decoration-none rounded-pill px-3 py-1.5" style="background: #FFFFFF; color: var(--humora-text-main); border: 1px solid #E5E7EB;">Sitkom Indonesia Lucu 2026</a>
                    <a href="#about" class="badge text-decoration-none rounded-pill px-3 py-1.5" style="background: #FFFFFF; color: var(--humora-text-main); border: 1px solid #E5E7EB;">Episode 1 &amp; 2 Gratis Selamanya</a>
                    <a href="{{ route('app.download') }}" class="badge text-decoration-none rounded-pill px-3 py-1.5" style="background: #FFFFFF; color: var(--humora-text-main); border: 1px solid #E5E7EB;">Unduh Offline Nonton Bebas Kuota</a>
                </div>
            </div>
        </section>
        <section class="howto-section" id="langkah">
            <div class="section-headline-block">
                <span class="section-eyebrow">
                    <i class="bi bi-play-circle-fill"></i> Langkah Praktis
                </span>
                <h2 class="section-main-title">Cara Mudah Menikmati Serial Komedi HUMORA</h2>
                <p class="section-subtext">Hanya butuh 3 langkah singkat tanpa registrasi rumit untuk mulai tertawa lepas kapan pun dan di mana pun.</p>
            </div>

            <div class="howto-grid">
                <div class="step-card">
                    <div class="step-badge">1</div>
                    <h3 class="step-title">Unduh APK HUMORA</h3>
                    <p class="step-desc">
                        Klik tombol download untuk mendapatkan berkas aplikasi resmi (~15 MB). Aplikasi ini sangat ringan, aman, bebas virus, dan langsung kompatibel dengan ponsel Android 5.0+.
                    </p>
                </div>

                <div class="step-card">
                    <div class="step-badge">2</div>
                    <h3 class="step-title">Pilih Serial Favorit</h3>
                    <p class="step-desc">
                        Buka aplikasi dan telusuri puluhan judul sketsa komedi. Anda bisa langsung memutar <strong>Episode 1 &amp; 2 secara GRATIS</strong> untuk semua serial tanpa perlu bayar apa pun.
                    </p>
                </div>

                <div class="step-card">
                    <div class="step-badge">3</div>
                    <h3 class="step-title">Tertawa &amp; Langganan Hemat</h3>
                    <p class="step-desc">
                        Suka ceritanya dan ingin maraton episode selanjutnya? Buka akses episode premium mulai Rp15.000 dengan pembayaran instan QRIS semua bank dan e-wallet.
                    </p>
                </div>
            </div>
        </section>


        <!-- ==================================================================
             SECTION 5: PAKET LANGGANAN SUPER HEMAT (Pricing Section)
             ================================================================== -->
        <section class="pricing-section" id="paket">
            <div class="section-headline-block">
                <span class="section-eyebrow">
                    <i class="bi bi-tags-fill"></i> Paket &amp; Harga Transparan
                </span>
                <h2 class="section-main-title">Pilihan Paket Langganan Hemat &amp; Fleksibel</h2>
                <p class="section-subtext">Mulai dari nonton gratis tanpa biaya, hingga paket mingguan hemat untuk maraton serial komedi sepuasnya.</p>
            </div>

            <div class="pricing-grid">

                <!-- Plan 1: Akses Gratis -->
                <div class="pricing-card">
                    <div class="plan-header">
                        <h3 class="plan-name">Akses Gratis (Trial)</h3>
                        <div class="plan-subtitle">Untuk Pengguna Baru</div>
                    </div>
                    <div class="plan-price-box">
                        <div class="plan-price">Rp 0</div>
                        <div class="plan-period">Selamanya / Tanpa Batas</div>
                    </div>
                    <ul class="plan-features-list">
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Episode 1 &amp; 2 Gratis di semua judul serial</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Kualitas streaming video jernih HD</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Bebas putus-putus &amp; hemat kuota internet</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Tanpa perlu kartu kredit</span>
                        </li>
                    </ul>
                    <a href="{{ route('app.download') }}" class="btn-plan-cta btn-plan-outline" title="Mulai Nonton Gratis">
                        <span>Mulai Nonton Gratis</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Plan 2: Paket Mingguan 15 Video (Featured) -->
                <div class="pricing-card featured-plan">
                    <span class="pricing-badge-popular">Paling Diminati</span>
                    <div class="plan-header">
                        <h3 class="plan-name">Paket Mingguan (15 Video)</h3>
                        <div class="plan-subtitle">Akses Maraton Hemat 7 Hari</div>
                    </div>
                    <div class="plan-price-box">
                        <div class="plan-price">Rp 15.000</div>
                        <div class="plan-period">per minggu (Rp 1.000 / video)</div>
                    </div>
                    <ul class="plan-features-list">
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Akses 15 video episode premium pilihan Anda</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Masa aktif fleksibel 7 hari penuh</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Bisa putar ulang video sepuasnya</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Aktivasi otomatis &amp; instan via QRIS</span>
                        </li>
                    </ul>
                    <a href="{{ route('app.download') }}" class="btn-plan-cta btn-plan-primary" title="Pilih Paket Mingguan">
                        <span>Pilih Paket di Aplikasi</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Plan 3: Add-on 5 Video -->
                <div class="pricing-card">
                    <div class="plan-header">
                        <h3 class="plan-name">Add-on Ekstra 5 Video</h3>
                        <div class="plan-subtitle">Tambah Kuota Video Tambahan</div>
                    </div>
                    <div class="plan-price-box">
                        <div class="plan-price">Rp 7.500</div>
                        <div class="plan-period">kuota ekstra instan</div>
                    </div>
                    <ul class="plan-features-list">
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Tambahan kuota 5 video episode bebas pilih</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Bisa dibeli kapan pun saat kuota habis</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Cocok untuk lanjut nonton cliffhanger lelucon</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Pembayaran cepat via QRIS e-wallet</span>
                        </li>
                    </ul>
                    <a href="{{ route('app.download') }}" class="btn-plan-cta btn-plan-outline" title="Beli Tambahan Video">
                        <span>Beli di Aplikasi</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Plan 4: Full Access 30 Hari -->
                <div class="pricing-card">
                    <div class="plan-header">
                        <h3 class="plan-name">Full Access Seluruh Katalog</h3>
                        <div class="plan-subtitle">Bebas Nonton Tanpa Batas</div>
                    </div>
                    <div class="plan-price-box">
                        <div class="plan-price">Rp 49.000</div>
                        <div class="plan-period">30 hari bebas kuota video</div>
                    </div>
                    <ul class="plan-features-list">
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Akses 100% seluruh episode dan serial tanpa batas</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Masa aktif panjang 30 hari penuh</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Akses prioritas untuk serial komedi rilis terbaru</span>
                        </li>
                        <li class="plan-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Pengalaman komedi VIP paling puas</span>
                        </li>
                    </ul>
                    <a href="{{ route('app.download') }}" class="btn-plan-cta btn-plan-outline" title="Langganan Full Access">
                        <span>Langganan di Aplikasi</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            </div>

            <!-- QRIS Payment Strip -->
            <div class="qris-payment-strip">
                <div class="qris-strip-left">
                    <i class="bi bi-qr-code-scan fs-4 text-warning"></i>
                    <span class="qris-strip-text">Didukung Pembayaran Digital QRIS Otomatis:</span>
                </div>
                <div class="qris-strip-logos">
                    <span>GoPay</span>
                    <span>&bull;</span>
                    <span>OVO</span>
                    <span>&bull;</span>
                    <span>DANA</span>
                    <span>&bull;</span>
                    <span>ShopeePay</span>
                    <span>&bull;</span>
                    <span>BCA</span>
                    <span>&bull;</span>
                    <span>Mandiri</span>
                    <span>&bull;</span>
                    <span>BRI</span>
                    <span>&bull;</span>
                    <span>Semua Bank</span>
                </div>
            </div>
        </section>


        <!-- ==================================================================
             SECTION 6: PERTANYAAN UMUM / FAQ (Rich Snippets Google)
             ================================================================== -->
        <section class="faq-section" id="faq">
            <div class="section-headline-block">
                <span class="section-eyebrow">
                    <i class="bi bi-question-circle-fill"></i> Tanya Jawab
                </span>
                <h2 class="section-main-title">Pertanyaan yang Sering Diajukan (FAQ)</h2>
                <p class="section-subtext">Segala hal yang perlu Anda ketahui mengenai aplikasi HUMORA, sistem tontonan, dan paket langganan.</p>
            </div>

            <div class="faq-container">
                <div class="faq-card-item">
                    <button class="faq-trigger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                        <span>Apa itu aplikasi HUMORA dan bagaimana konsep tontonannya?</span>
                        <div class="faq-icon-toggle">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                    <div id="faq1" class="collapse show">
                        <div class="faq-content-body">
                            HUMORA adalah platform serial sketsa komedi pendek pertama di Indonesia yang menyajikan cerita komedi berdurasi ringkas sekitar 2 menit. Konsepnya to the point langsung ke inti lelucon tanpa bertele-tele, sangat pas dinikmati di sela-sela kesibukan harian dan ramah kuota ponsel Anda.
                        </div>
                    </div>
                </div>

                <div class="faq-card-item">
                    <button class="faq-trigger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                        <span>Apakah bisa menonton serial komedi di HUMORA secara gratis?</span>
                        <div class="faq-icon-toggle">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                    <div id="faq2" class="collapse">
                        <div class="faq-content-body">
                            Tentu saja! Episode 1 dan Episode 2 pada <strong>semua judul serial komedi</strong> di katalog HUMORA dapat Anda tonton secara 100% GRATIS selamanya tanpa memerlukan kartu kredit atau komitmen biaya awal apa pun.
                        </div>
                    </div>
                </div>

                <div class="faq-card-item">
                    <button class="faq-trigger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                        <span>Berapa biaya paket langganan video di HUMORA dan apa metode pembayarannya?</span>
                        <div class="faq-icon-toggle">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                    <div id="faq3" class="collapse">
                        <div class="faq-content-body">
                            Paket langganan HUMORA sangat bersahabat di kantong: Paket Mingguan 15 Video mulai Rp15.000 / 7 hari, Add-on 5 Video hanya Rp7.500, dan Full Access 30 hari Rp49.000. Seluruh transaksi didukung pembayaran instan QRIS (GoPay, OVO, Dana, ShopeePay, BCA, Mandiri, BRI, BNI).
                        </div>
                    </div>
                </div>

                <div class="faq-card-item">
                    <button class="faq-trigger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                        <span>Mengapa video di HUMORA tidak lemot dan sangat hemat kuota internet?</span>
                        <div class="faq-icon-toggle">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                    <div id="faq4" class="collapse">
                        <div class="faq-content-body">
                            HUMORA menggunakan teknologi kompresi video WebM generasi baru (VP9 codec + Opus audio) yang mampu menghemat kuota internet hingga 70% dibanding format video biasa, menghasilkan tayangan kualitas jernih HD yang langsung berputar seketika tanpa jeda buffering.
                        </div>
                    </div>
                </div>

                <div class="faq-card-item">
                    <button class="faq-trigger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                        <span>Bagaimana cara download dan install aplikasi HUMORA di HP Android?</span>
                        <div class="faq-icon-toggle">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                    <div id="faq5" class="collapse">
                        <div class="faq-content-body">
                            Cukup klik tombol 'Download Aplikasi' pada halaman web ini. Setelah berkas APK selesai diunduh (~15 MB), ketuk file tersebut di HP Anda, pilih Izinkan Instalasi dari Sumber Ini jika diminta, dan aplikasi HUMORA siap dipakai untuk menonton langsung.
                        </div>
                    </div>
                </div>

                <div class="faq-card-item">
                    <button class="faq-trigger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                        <span>Apakah aplikasi HUMORA aman untuk diunduh langsung?</span>
                        <div class="faq-icon-toggle">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                    <div id="faq6" class="collapse">
                        <div class="faq-content-body">
                            Sangat aman. Berkas APK resmi HUMORA dibangun tanpa iklan mengganggu, bebas malware/virus, tidak meminta izin data sensitif ponsel, dan telah diuji secara ketat agar bekerja mulus pada sistem operasi Android 5.0 hingga Android versi terbaru.
                        </div>
                    </div>
                </div>

                <div class="faq-card-item">
                    <button class="faq-trigger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
                        <span>Apakah serial komedi di HUMORA cocok ditonton bersama keluarga?</span>
                        <div class="faq-icon-toggle">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                    <div id="faq7" class="collapse">
                        <div class="faq-content-body">
                            Sangat cocok! Cerita sketsa komedi di HUMORA mengangkat dinamika kehidupan sehari-hari masyarakat Indonesia, humor keluarga, dan situasi kerja yang lucu serta menghibur tanpa materi vulgar. Tontonan sehat dan menyenangkan untuk segala usia.
                        </div>
                    </div>
                </div>

                <div class="faq-card-item">
                    <button class="faq-trigger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                        <span>Apakah ada fitur unduh untuk menonton video secara offline?</span>
                        <div class="faq-icon-toggle">
                            <i class="bi bi-plus"></i>
                        </div>
                    </button>
                    <div id="faq8" class="collapse">
                        <div class="faq-content-body">
                            Ya, aplikasi HUMORA dilengkapi fitur unduh offline di latar belakang (*background downloader*) sehingga Anda dapat menyimpan episode serial favorit saat terhubung Wi-Fi dan menontonnya di perjalanan tanpa menggunakan kuota internet.
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ==================================================================
             SECTION 7: FINAL CALL TO ACTION (CTA BANNER)
             ================================================================== -->
        <section class="final-cta-section">
            <div class="final-cta-card">
                <h2 class="final-cta-title">Siap Tertawa Lepas Hari Ini?</h2>
                <p class="final-cta-subtitle">
                    Unduh aplikasi HUMORA sekarang dan nikmati puluhan serial sketsa komedi pendek Indonesia. Episode 1 &amp; 2 GRATIS untuk semua judul!
                </p>
                <div class="final-cta-buttons">
                    <a href="{{ route('app.download') }}" class="btn-final-download" title="Unduh APK HUMORA Sekarang">
                        <i class="bi bi-download"></i>
                        <span>Unduh Aplikasi HUMORA ({{ $apkSize ?? '~15 MB' }})</span>
                    </a>
                    <a href="#katalog" class="btn-final-browse" title="Jelajahi Serial Komedi">
                        <span>Lihat Semua Serial</span>
                    </a>
                </div>
            </div>
        </section>


        <!-- ==================================================================
             MINIMALIST EDITORIAL FOOTER
             ================================================================== -->
        <footer class="humora-footer">
            <div class="footer-brand">
                <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="HUMORA Logo" width="26" height="26" loading="lazy">
                <span>HUMORA — Platform Serial Komedi Indonesia</span>
            </div>

            <div class="footer-links">
                <a href="#about" class="footer-link">Fitur Unggulan</a>
                <a href="#katalog" class="footer-link">Katalog Serial</a>
                <a href="#kategori-topik" class="footer-link">Genre Komedi</a>
                <a href="#langkah" class="footer-link">Cara Nonton</a>
                <a href="#paket" class="footer-link">Paket Langganan</a>
                <a href="#faq" class="footer-link">FAQ</a>
                <a href="{{ route('app.download') }}" class="footer-link">Download APK</a>
                <a href="{{ route('admin.login') }}" class="footer-link text-muted" style="font-size: 0.8rem;">Portal Admin</a>
                <a href="#hero" class="footer-link">Kembali ke Atas</a>
            </div>

            <!-- SEO Keywords Footer Strip -->
            <div class="w-100 text-center text-md-start small text-muted pt-3 pb-2 border-top border-light-subtle">
                <div class="mb-1.5">
                    <strong style="color: var(--humora-text-main); font-size: 0.8rem;">Pencarian Terpopuler di HUMORA:</strong>
                </div>
                <div class="d-flex flex-wrap gap-2 text-muted" style="font-size: 0.76rem; line-height: 1.8;">
                    <a href="#katalog" class="text-secondary text-decoration-none">Aplikasi Nonton Serial Komedi Indonesia</a> &bull;
                    <a href="#katalog" class="text-secondary text-decoration-none">Streaming Sketsa Komedi Pendek Lucu</a> &bull;
                    <a href="{{ route('app.download') }}" class="text-secondary text-decoration-none">Download APK HUMORA Android Resmi</a> &bull;
                    <a href="#about" class="text-secondary text-decoration-none">Video Komedi Bebas Buffering WebM</a> &bull;
                    <a href="#about" class="text-secondary text-decoration-none">Episode 1 &amp; 2 Gratis Semua Serial</a> &bull;
                    <a href="#kategori-topik" class="text-secondary text-decoration-none">Sitkom Keluarga Indonesia 2026</a> &bull;
                    <a href="#kategori-topik" class="text-secondary text-decoration-none">Sketsa Lucu Anak Kos &amp; Kantor</a> &bull;
                    <a href="#paket" class="text-secondary text-decoration-none">Paket Langganan Video Komedi QRIS</a> &bull;
                    <a href="{{ route('app.download') }}" class="text-secondary text-decoration-none">Nonton Komedi Hemat Kuota Hingga 70%</a>
                </div>
            </div>

            <div class="w-100 text-center text-md-start small text-muted pt-2 border-top border-light-subtle">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <div>
                        &copy; {{ date('Y') }} <strong>HUMORA</strong>. Seluruh Hak Cipta Dilindungi. Platform Serial Sketsa Komedi Pendek Indonesia.
                    </div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                        Hemat Kuota Data &bull; Bebas Buffering &bull; Tayang Eksklusif
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- Sticky Mobile Download Floating Bar (Maximizes Mobile Install Conversions) -->
    <aside class="mobile-sticky-bar" aria-label="Aksi Cepat Unduh Aplikasi HUMORA">
        <div class="sticky-bar-info">
            <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="HUMORA" class="sticky-bar-icon" width="36" height="36" loading="lazy">
            <div>
                <div class="sticky-bar-title">HUMORA: Serial Komedi</div>
                <div class="sticky-bar-sub">Ep 1 &amp; 2 Gratis &bull; {{ $apkSize ?? '~15 MB' }}</div>
            </div>
        </div>
        <a href="{{ route('app.download') }}" class="btn-sticky-download" title="Unduh APK HUMORA">
            <i class="bi bi-download"></i>
            <span>Unduh APK</span>
        </a>
    </aside>

    <!-- Bootstrap 5.3.3 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
