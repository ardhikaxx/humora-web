<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUMORA — Nonton Serial Komedi Vertikal Pendek (~2 Menit) | Aplikasi Mobile</title>

    <!-- Meta SEO -->
    <meta name="description" content="Nonton puluhan serial sketsa komedi vertikal pendek (~2 menit) khas Indonesia di aplikasi HUMORA. Streaming cepat, hemat kuota, bebas buffering. Episode 1 &amp; 2 GRATIS! Unduh APK sekarang.">
    <meta name="keywords" content="humora, serial komedi, video pendek, sketsa komedi, streaming indonesia, download apk humora, aplikasi komedi">
    <meta name="author" content="HUMORA">

    <!-- Open Graph / Social -->
    <meta property="og:title" content="HUMORA — Serial Komedi Vertikal Pendek (~2 Menit)">
    <meta property="og:description" content="Nonton serial sketsa komedi vertikal pendek khas Indonesia. Episode 1 &amp; 2 GRATIS!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('landing') }}">
    <meta property="og:image" content="{{ asset('images/logo-humora-rounded.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="HUMORA — Serial Komedi Vertikal Pendek (~2 Menit)">
    <meta name="twitter:description" content="Nonton serial sketsa komedi vertikal pendek khas Indonesia. Episode 1 &amp; 2 GRATIS!">
    <meta name="twitter:image" content="{{ asset('images/logo-humora-rounded.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-humora-rounded.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-humora-rounded.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Anton&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Landing Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ file_exists(public_path('css/landing.css')) ? filemtime(public_path('css/landing.css')) : '3.0' }}">
</head>
<body>

    <!-- ========================================
         NAVIGATION
         ======================================== -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top px-4 px-lg-0" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('landing') }}">
                <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="HUMORA Logo" class="navbar-brand-img">
                <span class="fs-5 fw-black text-white font-anton tracking-wide">HUMORA</span>
            </a>
            <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHumora" aria-controls="navbarHumora" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-3 text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarHumora">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2 align-items-center">
                    <li class="nav-item"><a class="nav-link nav-link-modern px-2 px-lg-3" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern px-2 px-lg-3" href="#serial">Serial Populer</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-modern px-2 px-lg-3" href="#harga">Paket VIP</a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-modern px-2 px-lg-3" href="#download">Unduh APK</a></li>
                    <li class="nav-item d-lg-none"><a class="nav-link nav-link-modern px-2 px-lg-3 text-decoration-none" href="{{ route('app.download') }}"><span class="d-flex align-items-center gap-1"><i class="bi bi-download fs-5"></i><span>Unduh</span></span></a></li>
                    <li class="nav-item d-none d-lg-block ms-3"><a href="{{ route('app.download') }}" class="btn btn-outline-light btn-sm px-3 py-1.5 rounded-pill border-white text-white fw-semibold transition-all duration-300">Unduh Aplikasi</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- ========================================
         HERO SECTION
         ======================================== -->
    <section class="hero-section position-relative overflow-hidden min-vh-100 d-flex align-items-center" id="home">
        <div class="hero-bg position-absolute top-0 start-0 w-100 h-100 z-nth-1"></div>
        <div class="hero-glow position-absolute top-50 start-50 translate-middle z-nth-2"></div>
        <div class="hero-glow-sm position-absolute bottom-0 end-0 z-nth-2" style="width: 400px; height: 400px; background: radial-gradient(circle, rgba(163, 230, 53, 0.08) 0%, transparent 70%); border-radius: 50%; filter: blur(60px);"></div>

        <div class="container position-relative z-3">
            <div class="row align-items-center g-4 g-lg-5">
                <!-- Left: Content -->
                <div class="col-lg-6 order-lg-1 order-2 text-start text-lg-start">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="hero-badge rounded-pill px-3 py-1 text-xs fw-bold">📱 Aplikasi Mobile</span>
                        <span class="text-xs text-secondary">Baru | Tersedia untuk Android</span>
                    </div>
                    <h1 class="hero-title mb-4">
                        Komedi <br>
                        <span class="text-gradient">Tanpa Batas</span>
                    </h1>
                    <p class="hero-subtitle mb-5">
                        Serial sketsa komedi vertikal pendek (~2 menit) pertama di Indonesia. Tonton punchline kocak tanpa bertele-tele, hemat kuota data, bebas buffering, dan <strong>Episode 1 &amp; 2 GRATIS</strong> untuk semua judul!
                    </p>
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-5">
                        <a href="{{ route('app.download') }}" class="btn-hero-lime px-4 py-3 rounded-pill fw-bold text-decoration-none d-flex align-items-center gap-2">
                            <span>Unduh Aplikasi</span>
                            <span>&rarr;</span>
                        </a>
                        <a href="#serial" class="btn-hero-outline px-4 py-3 rounded-pill fw-bold text-decoration-none d-flex align-items-center gap-2">
                            <span>Lihat Katalog</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3 text-secondary small">
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill text-white me-1"></i> 100% Bebas Iklan</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-phone-fill text-white me-1"></i> Android 5.0+</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-gift-fill text-white me-1"></i> Tanpa Registrasi Awal</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-download text-white me-1"></i> Ukuran Ringan</span>
                    </div>
                </div>
                <!-- Right: Phone Mockup -->
                <div class="col-lg-6 order-lg-2 order-1 d-flex justify-content-center justify-content-lg-end position-relative">
                    <div class="phone-mockup-wrapper position-relative">
                        <div class="phone-shadow position-absolute bottom-0 start-50 translate-middle-x w-75 h-25 bg-black/40 rounded-full filter-blur-lg"></div>
                        <div class="phone-device position-relative z-2">
                            <div class="phone-screen overflow-hidden rounded-4 border border-white/10 bg-black">
                                <div class="app-preview p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3 text-white/70 small">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="w-1 h-1 bg-white/60 rounded-circle"></span>
                                            <span class="w-1 h-1 bg-white/60 rounded-circle"></span>
                                            <span class="w-1 h-1 bg-white/60 rounded-circle"></span>
                                        </div>
                                        <div class="text-right">
                                            <div class="w-4 h-1 bg-white/60 rounded"></div>
                                            <div class="w-2 h-1 bg-white/60 rounded mt-1"></div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="Logo" class="w-6 h-6 rounded">
                                            <span class="fw-bold text-white small">HUMORA</span>
                                        </div>
                                        <i class="bi bi-search text-white/50"></i>
                                    </div>
                                    <div class="overflow-x-auto mb-3 pb-2 no-scrollbar">
                                        <div class="d-flex gap-2">
                                            <span class="px-3 py-1 bg-white/5 rounded-pill text-white/70 small fw-semibold whitespace-nowrap">Populer</span>
                                            <span class="px-3 py-1 bg-white/5 rounded-pill text-white/70 small fw-semibold whitespace-nowrap">Romcom</span>
                                            <span class="px-3 py-1 bg-white/5 rounded-pill text-white/70 small fw-semibold whitespace-nowrap">Kantor</span>
                                            <span class="px-3 py-1 bg-white/5 rounded-pill text-white/70 small fw-semibold whitespace-nowrap">Sketsa</span>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="video-card bg-gradient-to-br from-purple-900 to-black rounded-3 overflow-hidden position-relative aspect-video">
                                                <img src="{{ $heroThumbnails[0] ?? asset('images/logo-humora-rounded.png') }}" alt="Video 1" class="w-100 h-100 object-fit-cover opacity-80">
                                                <div class="absolute inset-0 bg-black/20"></div>
                                                <div class="absolute bottom-2 start-2 bg-black/60 backdrop-blur px-2 py-1 rounded text-white xs"><strong>Episode 1</strong><br>GRATIS</div>
                                                <div class="absolute top-2 end-2 bg-black/60 backdrop-blur px-2 py-1 rounded text-white xs">~2 min</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="video-card bg-gradient-to-br from-pink-900 to-black rounded-3 overflow-hidden position-relative aspect-video">
                                                <img src="{{ $heroThumbnails[1] ?? asset('images/logo-humora-rounded.png') }}" alt="Video 2" class="w-100 h-100 object-fit-cover opacity-80">
                                                <div class="absolute inset-0 bg-black/20"></div>
                                                <div class="absolute bottom-2 start-2 bg-black/60 backdrop-blur px-2 py-1 rounded text-white xs"><strong>Episode 2</strong><br>GRATIS</div>
                                                <div class="absolute top-2 end-2 bg-black/60 backdrop-blur px-2 py-1 rounded text-white xs">~2 min</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="video-card bg-gradient-to-br from-indigo-900 to-black rounded-3 overflow-hidden position-relative aspect-video">
                                                <img src="{{ $heroThumbnails[2] ?? asset('images/logo-humora-rounded.png') }}" alt="Video 3" class="w-100 h-100 object-fit-cover opacity-80">
                                                <div class="absolute inset-0 bg-black/20"></div>
                                                <div class="absolute bottom-2 start-2 bg-black/60 backdrop-blur px-2 py-1 rounded text-white xs"><strong>Episode 3</strong><br>VIP</div>
                                                <div class="absolute top-2 end-2 bg-black/60 backdrop-blur px-2 py-1 rounded text-white xs">~2 min</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="video-card bg-gradient-to-br from-emerald-900 to-black rounded-3 overflow-hidden position-relative aspect-video">
                                                <img src="{{ $heroThumbnails[3] ?? asset('images/logo-humora-rounded.png') }}" alt="Video 4" class="w-100 h-100 object-fit-cover opacity-80">
                                                <div class="absolute inset-0 bg-black/20"></div>
                                                <div class="absolute bottom-2 start-2 bg-black/60 backdrop-blur px-2 py-1 rounded text-white xs"><strong>Episode 4</strong><br>VIP</div>
                                                <div class="absolute top-2 end-2 bg-black/60 backdrop-blur px-2 py-1 rounded text-white xs">~2 min</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-top border-white/10 d-flex justify-around text-white/50 small">
                                        <div class="text-white"><i class="bi bi-house-fill d-block text-center"></i><span class="d-block mt-0_5">Beranda</span></div>
                                        <div><i class="bi bi-play-circle d-block text-center"></i><span class="d-block mt-0_5">Tonton</span></div>
                                        <div><i class="bi bi-heart d-block text-center"></i><span class="d-block mt-0_5">Simpan</span></div>
                                        <div><i class="bi bi-person d-block text-center"></i><span class="d-block mt-0_5">Profil</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="phone-controls mt-3 d-flex justify-content-around">
                                <div class="phone-btn d-flex flex-column align-items-center gap-1"><div class="w-4 h-4 bg-white/10 rounded-full"></div><span class="text-white/30 xs">Side</span></div>
                                <div class="phone-btn d-flex flex-column align-items-center gap-1"><div class="w-1 h-6 bg-white/10 rounded"></div><span class="text-white/30 xs">Vol</span></div>
                                <div class="phone-btn d-flex flex-column align-items-center gap-1"><div class="w-4 h-4 bg-white/10 rounded-full"></div><span class="text-white/30 xs">Power</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator position-absolute bottom-10 start-50 translate-middle-x z-3 animate-bounce"><i class="bi bi-chevron-down text-white/50 fs-4"></i></div>
    </section>
    <!-- ========================================
         STATS BAR
         ======================================== -->
    <section class="stats-section py-4 position-relative z-3">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card text-center">
                        <span class="stat-number d-block fs-4 fw-black text-white font-anton">{{ $stats['total_series'] }}</span>
                        <span class="stat-label text-secondary small">Serial Tersedia</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card text-center">
                        <span class="stat-number d-block fs-4 fw-black text-white font-anton">{{ $stats['total_episodes'] }}</span>
                        <span class="stat-label text-secondary small">Episode Komedi</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card text-center">
                        <span class="stat-number d-block fs-4 fw-black text-white font-anton">2</span>
                        <span class="stat-label text-secondary small">Episode Gratis/Serial</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card text-center">
                        <span class="stat-number d-block fs-4 fw-black text-white font-anton">~2m</span>
                        <span class="stat-label text-secondary small">Durasi per Episode</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
         FEATURES SECTION
         ======================================== -->
    <section id="fitur" class="features-section py-5 py-lg-6 position-relative z-3">
        <div class="container">
            <div class="text-center mb-6">
                <span class="section-kicker text-accent-lime fw-bold text-uppercase small letter-spacing-wide">Fitur Unggulan</span>
                <h2 class="section-title mb-3">Dirancang untuk<br><span class="text-gradient">Pengalaman Maksimal</span></h2>
                <p class="text-secondary">Aplikasi streaming komedi yang dirancang khusus untuk pengguna mobile Indonesia.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-lime rounded-3 p-3 mb-3"><i class="bi bi-wifi-off fs-4 text-white"></i></div>
                        <h5 class="fw-bold text-white mb-2">Hemat Kuota & Auto-Cache</h5>
                        <p class="text-secondary small">Transcoding WebM VP9 canggih membuat video sangat padat tanpa pecah. Hemat kuota data hingga 85%.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-purple rounded-3 p-3 mb-3"><i class="bi bi-brightness-high fs-4 text-white"></i></div>
                        <h5 class="fw-bold text-white mb-2">Smart Wakelock Layar</h5>
                        <p class="text-secondary small">Layar HP tidak akan meredup atau mengunci otomatis saat video berputar. Tangan bebas santai atau ngemil!</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-indigo rounded-3 p-3 mb-3"><i class="bi bi-phone-vibrate fs-4 text-white"></i></div>
                        <h5 class="fw-bold text-white mb-2">Haptic Touch Feedback</h5>
                        <p class="text-secondary small">Rasakan getaran fisik taktil yang memuaskan saat memberi Like, memilih episode, atau menyimpan serial.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-emerald rounded-3 p-3 mb-3"><i class="bi bi-play-circle fs-4 text-white"></i></div>
                        <h5 class="fw-bold text-white mb-2">Lanjutkan Tontonan</h5>
                        <p class="text-secondary small">Aplikasi secara otomatis mencatat progres detik terakhir. Kamu bisa melanjutkan menonton kapan saja.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-rose rounded-3 p-3 mb-3"><i class="bi bi-shield-check fs-4 text-white"></i></div>
                        <h5 class="fw-bold text-white mb-2">Bebas Iklan Pop-up</h5>
                        <p class="text-secondary small">Pengalaman menonton yang bersih dan privat tanpa gangguan iklan pop-up banner di tengah-tengah cerita.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon bg-gradient-amber rounded-3 p-3 mb-3"><i class="bi bi-qr-code-scan fs-4 text-white"></i></div>
                        <h5 class="fw-bold text-white mb-2">Bayar VIP Instan via QRIS</h5>
                        <p class="text-secondary small">Buka seluruh episode serial favorit dengan pembayaran instan via QRIS (GoPay, OVO, Dana, BCA Mobile, dll).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================================
         HOW IT WORKS
         ======================================== -->
    <section class="how-it-works-section py-5 py-lg-6 position-relative z-3" style="background: var(--bg-black);">
        <div class="container">
            <div class="text-center mb-6">
                <span class="section-kicker text-accent-lime fw-bold text-uppercase small letter-spacing-wide">Cara Mudah</span>
                <h2 class="section-title mb-3">Mulai Nonton dalam<br><span class="text-gradient">3 Langkah Sederhana</span></h2>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="steps-container position-relative">
                        <div class="step-item d-flex align-items-start gap-3 mb-5 position-relative">
                            <div class="step-number bg-gradient-lime rounded-circle d-flex align-items-center justify-center text-white fw-black fs-4" style="width: 50px; height: 50px; flex-shrink: 0;">1</div>
                            <div>
                                <h5 class="fw-bold text-white mb-1">Unduh Aplikasi</h5>
                                <p class="text-secondary small mb-0">Kundurkan APK HUMORA dari situs resmi dan instal di ponsel Android Anda.</p>
                            </div>
                        </div>
                        <div class="step-item d-flex align-items-start gap-3 mb-5 position-relative">
                            <div class="step-number bg-gradient-purple rounded-circle d-flex align-items-center justify-center text-white fw-black fs-4" style="width: 50px; height: 50px; flex-shrink: 0;">2</div>
                            <div>
                                <h5 class="fw-bold text-white mb-1">Pilih Serial Favorit</h5>
                                <p class="text-secondary small mb-0">Jelajahi katalog serial sketsa komedi vertikal dan pilih judul yang ingin ditonton.</p>
                            </div>
                        </div>
                        <div class="step-item d-flex align-items-start gap-3 mb-0 position-relative">
                            <div class="step-number bg-gradient-indigo rounded-circle d-flex align-items-center justify-center text-white fw-black fs-4" style="width: 50px; height: 50px; flex-shrink: 0;">3</div>
                            <div>
                                <h5 class="fw-bold text-white mb-1">Nonton Gratis & VIP</h5>
                                <p class="text-secondary small mb-0">Episode 1 &amp; 2 gratis untuk semua serial. Upgrade ke VIP untuk akses tanpa batas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
         CATALOG SECTION
         ======================================== -->
    <section id="serial" class="catalog-section py-5 py-lg-6 position-relative z-3">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">
                <div>
                    <span class="section-kicker text-accent-lime fw-bold text-uppercase small letter-spacing-wide">Katalog Serial</span>
                    <h2 class="section-title mb-2">Serial Pilihan<br><span class="text-gradient">Terpopuler</span></h2>
                </div>
                <p class="text-secondary small mb-0" style="max-width: 380px;">Mulai dari sketsa romcom absurd, parodi kantor, hingga humor tongkrongan yang sangat relate dengan kehidupan kita!</p>
            </div>
            <div class="row g-3 g-md-4">
                @forelse ($seriesList as $series)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="catalog-card">
                            <div class="catalog-poster-box position-relative overflow-hidden rounded-3">
                                <img src="{{ $series->thumbnail_url }}" alt="{{ $series->title }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                                <div class="catalog-overlay position-absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300 d-flex align-items-center justify-content-center">
                                    <a href="#" class="btn-hero-lime px-3 py-2 rounded-pill fw-bold text-decoration-none small">Tonton Sekarang &rarr;</a>
                                </div>
                                <div class="catalog-badges position-absolute top-2 start-2 z-2 d-flex flex-column gap-1">
                                    <span class="badge-tag-free rounded-pill px-2 py-1 text-xs fw-bold">EPS 1-2 GRATIS</span>
                                    @if($series->is_featured)
                                        <span class="badge-tag-featured rounded-pill px-2 py-1 text-xs fw-bold">POPULER</span>
                                    @endif
                                </div>
                            </div>
                            <div class="catalog-body mt-3">
                                <div class="catalog-genre text-accent-lime fw-semibold text-xs mb-1">{{ $series->genre ?: 'Komedi' }}</div>
                                <h5 class="catalog-title fw-bold text-white mb-1" title="{{ $series->title }}">{{ $series->title }}</h5>
                                <p class="catalog-synopsis text-secondary text-xs mb-2">{{ $series->synopsis }}</p>
                                <div class="catalog-footer d-flex justify-content-between align-items-center">
                                    <span class="text-white/70 small"><i class="bi bi-film me-1 text-accent-lime"></i>{{ $series->total_episodes }} Episode</span>
                                    <span class="text-white/70 small"><i class="bi bi-clock me-1 text-accent-lime"></i>~2m</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-secondary">
                        Belum ada serial yang dipublikasikan.
                    </div>
                @endforelse
            </div>
            <div class="editorial-card p-4 text-center mt-5 rounded-3">
                <p class="text-white mb-0 fs-6">
                    Mau maraton nonton semua episode serial di atas sekarang juga? 
                    <a href="#download" class="fw-bold text-decoration-underline ms-1" style="color: var(--accent-lime);">Unduh aplikasi HUMORA Mobile &rarr;</a>
                </p>
            </div>
        </div>
    </section>
    <!-- ========================================
         PRICING SECTION
         ======================================== -->
    <section id="harga" class="pricing-section py-5 py-lg-6 position-relative z-3">
        <div class="container">
            <div class="text-center mb-6">
                <span class="section-kicker text-accent-lime fw-bold text-uppercase small letter-spacing-wide">Paket Langganan</span>
                <h2 class="section-title mb-3">Pilih Paket<br><span class="text-gradient">VIP Terbaik untukmu</span></h2>
                <p class="text-secondary">Buka seluruh episode serial favorit dengan paket yang terjangkau.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <!-- Weekly -->
                <div class="col-md-6 col-lg-3">
                    <div class="pricing-card border border-white/10 rounded-3 p-4">
                        <div class="text-start mb-3">
                            <span class="badge bg-white/10 text-light fw-bold px-3 py-1 rounded-pill small">MINGGUAN</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mb-3">
                            <span class="display-5 fw-black text-white">Rp 15.000</span>
                            <small class="text-secondary">/minggu</small>
                        </div>
                        <p class="text-secondary small mb-4">Akses 7 hari tanpa batas episode.</p>
                        <ul class="pricing-list list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Semua katalog terbuka</span></li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Bebas putar ulang 7 hari</span></li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Bayar via QRIS & Transfer</span></li>
                        </ul>
                        <a href="#download" class="btn-hero-outline w-100 py-2 rounded-pill fw-bold text-decoration-none text-center d-block">Pilih Mingguan</a>
                    </div>
                </div>
                <!-- Monthly (Featured) -->
                <div class="col-md-6 col-lg-3">
                    <div class="pricing-card border border-accent-lime/30 rounded-3 p-4 position-relative bg-gradient-card">
                        <div class="position-absolute top-0 start-50 translate-middle-x bg-accent-lime text-black fw-bold px-3 py-1 rounded-pill small">POPULER</div>
                        <div class="text-start mb-3">
                            <span class="badge bg-white/10 text-light fw-bold px-3 py-1 rounded-pill small">BULANAN</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mb-3">
                            <span class="display-5 fw-black text-white">Rp 49.000</span>
                            <small class="text-secondary text-decoration-line-through">Rp 99.000</small>
                        </div>
                        <p class="text-secondary small mb-4">Akses 30 hari tanpa batas episode dan prioritas update.</p>
                        <ul class="pricing-list list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Semua katalog terbuka (~70+ video)</span></li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Akses 30 hari tanpa batas</span></li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Prioritas update episode baru</span></li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Bayar instan via QRIS</span></li>
                        </ul>
                        <a href="#download" class="btn-hero-lime w-100 py-2.5 rounded-pill fw-bold text-decoration-none text-center d-block">Pilih Bulanan</a>
                    </div>
                </div>
                <!-- 30 Days Full -->
                <div class="col-md-6 col-lg-3">
                    <div class="pricing-card border border-white/10 rounded-3 p-4">
                        <div class="text-start mb-3">
                            <span class="badge bg-white/10 text-light fw-bold px-3 py-1 rounded-pill small">30 HARI PENUH</span>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mb-3">
                            <span class="display-5 fw-black text-white">Rp 89.000</span>
                            <small class="text-secondary">/30 hari</small>
                        </div>
                        <p class="text-secondary small mb-4">Akses penuh semua fitur tanpa batas selama 30 hari.</p>
                        <ul class="pricing-list list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Semua fitur premium dibuka</span></li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Prioritas support pelanggan</span></li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-accent-lime me-2"></i><span class="text-white/80 small">Download untuk offline tontonan</span></li>
                        </ul>
                        <a href="#download" class="btn-hero-outline w-100 py-2 rounded-pill fw-bold text-decoration-none text-center d-block">Pilih 30 Hari</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
         TESTIMONIALS
         ======================================== -->
    <section class="testimonials-section py-5 py-lg-6 position-relative z-3" style="background: var(--bg-black);">
        <div class="container">
            <div class="text-center mb-6">
                <span class="section-kicker text-accent-lime fw-bold text-uppercase small letter-spacing-wide">Apa yang Mereka Katakan</span>
                <h2 class="section-title mb-3">Kecandaian<br><span class="text-gradient">Nyata dari Pengguna</span></h2>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="testimonial-card rounded-3 p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="Avatar" class="w-8 h-8 rounded-circle">
                                    <div>
                                        <div class="fw-bold text-white small">Andi Pratama</div>
                                        <div class="text-accent-lime text-xs"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                                    </div>
                                </div>
                                <p class="text-secondary small mb-0">"Aplikasi yang keren! Sketsa-nyas lucuk banget dan episode cuma 2 menit, cocok buat di sela-sela kerja."</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="testimonial-card rounded-3 p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="Avatar" class="w-8 h-8 rounded-circle">
                                    <div>
                                        <div class="fw-bold text-white small">Siti Rahayu</div>
                                        <div class="text-accent-lime text-xs"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                                    </div>
                                </div>
                                <p class="text-secondary small mb-0">"Hemat kuota banget, ga ada iklan yang ganggu. Cocok buat nonton sambil ngopi di pagi hari."</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="testimonial-card rounded-3 p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="Avatar" class="w-8 h-8 rounded-circle">
                                    <div>
                                        <div class="fw-bold text-white small">Budi Santoso</div>
                                        <div class="text-accent-lime text-xs"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
                                    </div>
                                </div>
                                <p class="text-secondary small mb-0">"Paket VIP-nya murah, bisa nonton semua episode tanpa batas. Recommended!"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================================
         FAQ SECTION
         ======================================== -->
    <section class="faq-section py-5 py-lg-6 position-relative z-3">
        <div class="container">
            <div class="text-center mb-6">
                <span class="section-kicker text-accent-lime fw-bold text-uppercase small letter-spacing-wide">Pertanyaan Umum</span>
                <h2 class="section-title mb-3">Pertanyaan<br><span class="text-gradient">Sering Diajukan</span></h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 shadow-none mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false">
                                    Apa itu HUMORA?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-secondary">HUMORA adalah aplikasi streaming serial sketsa komedi vertikal pendek (~2 menit) khas Indonesia yang bisa ditonton kapan saja melalui ponsel Android.</div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 shadow-none mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false">
                                    Apakah Episode 1 &amp; 2 benar-benar gratis?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-secondary">Ya! Setiap pengguna dapat menonton Episode 1 dan 2 secara 100% GRATIS di seluruh judul serial komedi tanpa perlu login atau membayar sepeser pun.</div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 shadow-none mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false">
                                    Kenapa durasi videonya hanya ~2 menit?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-secondary">HUMORA mengusung format serial sketsa modern (micro-series). Durasi 2 menit adalah waktu optimal untuk menghibur Anda secara instan di sela kesibukan tanpa membuang waktu dan tanpa rasa bosan.</div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 shadow-none mb-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false">
                                    Bagaimana cara berlangganan VIP?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-secondary">Di dalam aplikasi HUMORA Mobile, Anda cukup memilih paket langganan yang diinginkan, lalu membayar melalui QRIS (mendukung GoPay, OVO, Dana, ShopeePay, BCA Mobile, dll.) atau melalui Transfer Bank Manual.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
         DOWNLOAD SECTION
         ======================================== -->
    <section id="download" class="download-section py-5 py-lg-6 position-relative z-3">
        <div class="container">
            <div class="download-card rounded-4 overflow-hidden position-relative">
                <!-- Background glow -->
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-radial from-accent-lime/10 to-transparent"></div>
                
                <div class="row align-items-center g-4 g-lg-5 position-relative z-2">
                    <div class="col-lg-6">
                        <span class="section-kicker text-accent-lime fw-bold text-uppercase small letter-spacing-wide d-block mb-2">Siap menonton?</span>
                        <h2 class="text-white fw-black mb-4">Unduh Aplikasi<br><span class="text-gradient">HUMORA Sekarang</span></h2>
                        <p class="text-secondary mb-4">Nikmati serial komedi vertikal pendek dengan kualitas HD, hemat kuota, dan bebas buffering. Episode 1 &amp; 2 gratis untuk semua judul!</p>
                        
                        @if($apkAvailable)
                            <a href="{{ route('app.download') }}" class="btn-hero-lime px-5 py-3 rounded-pill fw-bold text-decoration-none d-flex align-items-center gap-3 w-auto">
                                <i class="bi bi-download fs-4"></i>
                                <span>Unduh APK ({{ $apkSize }})</span>
                            </a>
                        @else
                            <a href="#" class="btn-hero-lime px-5 py-3 rounded-pill fw-bold text-decoration-none d-flex align-items-center gap-3 w-auto disabled">
                                <i class="bi bi-download fs-4"></i>
                                <span>APK Segera Tersedia</span>
                            </a>
                        @endif
                        
                        <div class="mt-3 d-flex flex-wrap align-items-center gap-3 text-secondary small">
                            <span><i class="bi bi-android text-white me-1"></i> Android 5.0+</span>
                            <span><i class="bi bi-file-earmark-zip text-white me-1"></i> APK Ringan</span>
                            <span><i class="bi bi-shield-check text-white me-1"></i> Terverifikasi Aman</span>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex justify-content-center">
                        <div class="qr-white-card rounded-4 p-4 shadow-2xl">
                            <div class="text-center mb-3">
                                <span class="text-accent-lime fw-bold text-xs text-uppercase">Scan untuk Unduh</span>
                            </div>
                            <div class="qr-box-inner bg-white/10 rounded-3 p-3 mb-3 inline-block">
                                <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl={{ urlencode(route('app.download')) }}&choe=UTF-8" alt="QR Code Download" class="w-64 h-64 rounded-2">
                            </div>
                            <p class="text-secondary small mb-3">Scan kode QR dengan kamera ponsel Anda untuk langsung mengakses halaman unduhan APK HUMORA.</p>
                            <a href="{{ route('app.download') }}" class="btn-hero-outline w-100 py-2 rounded-pill fw-bold text-decoration-none text-center d-block">Unduh Langsung APK</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
         FOOTER
         ======================================== -->
    <footer class="footer-editorial position-relative z-3">
        <div class="container">
            <div class="row align-items-center g-4 justify-content-between py-4">
                <div class="col-md-4 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                        <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="HUMORA" class="navbar-brand-img">
                        <span class="fs-5 fw-black text-white font-anton">HUMORA</span>
                    </div>
                    <small class="text-secondary d-block">Platform Streaming Serial Sketsa Komedi Vertikal Pendek (~2 Menit)</small>
                </div>
                <div class="col-md-4 text-center">
                    <div class="d-flex flex-wrap justify-content-center gap-3 small fw-bold mb-2">
                        <a href="#serial" class="text-decoration-none text-secondary hover-accent-lime">Serial</a>
                        <a href="#fitur" class="text-decoration-none text-secondary hover-accent-lime">Fitur</a>
                        <a href="#harga" class="text-decoration-none text-secondary hover-accent-lime">Paket VIP</a>
                        <a href="#download" class="text-decoration-none text-secondary hover-accent-lime">Unduh APK</a>
                        <a href="{{ route('admin.login') }}" class="text-decoration-none" style="color: var(--accent-lime);">Portal Admin</a>
                    </div>
                </div>
                <div class="col-md-4 text-center text-md-end small">
                    <div>&copy; {{ date('Y') }} HUMORA. All rights reserved.</div>
                    <small class="fst-italic" style="color: var(--accent-lime);">"scroll, laugh, repeat"</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom Landing Script -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
