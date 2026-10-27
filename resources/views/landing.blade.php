<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUMORA — Platform Serial Komedi | Aplikasi Mobile</title>

    <!-- Meta SEO -->
    <meta name="description" content="Nonton puluhan serial sketsa komedi khas Indonesia di aplikasi HUMORA. Tontonan lancar bebas putus-putus. Episode 1 &amp; 2 GRATIS! Unduh sekarang.">
    <meta name="keywords" content="humora, serial komedi, serial komedi indonesia, video pendek, sketsa komedi, sketsa komedi pendek, streaming indonesia, download aplikasi humora, video lucu, hiburan singkat">
    <meta name="author" content="HUMORA">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Social -->
    <meta property="og:site_name" content="HUMORA">
    <meta property="og:title" content="HUMORA — Platform Serial Komedi">
    <meta property="og:description" content="Nonton serial sketsa komedi khas Indonesia di HUMORA. Episode 1 &amp; 2 GRATIS!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('landing') }}">
    <meta property="og:image" content="{{ asset('images/logo-humora-rounded.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="HUMORA — Platform Serial Komedi">
    <meta name="twitter:description" content="Nonton serial sketsa komedi khas Indonesia di HUMORA. Episode 1 &amp; 2 GRATIS!">
    <meta name="twitter:image" content="{{ asset('images/logo-humora-rounded.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-humora-rounded.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-humora-rounded.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Landing Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ file_exists(public_path('css/landing.css')) ? filemtime(public_path('css/landing.css')) : time() }}">
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
                <div class="nav-left-group">
                    <a href="{{ route('landing') }}" class="brand-capsule">
                        <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="HUMORA Logo">
                        <span>HUMORA</span>
                    </a>
                    <a href="#about" class="nav-pill-link d-none d-sm-inline-flex">Fitur Unggulan</a>
                    <a href="#katalog" class="nav-pill-link d-none d-md-inline-flex">Serial Populer</a>
                </div>

                <div class="nav-right-group">
                    <a href="#about" class="nav-text-link d-none d-lg-inline-block">Tentang</a>
                    <a href="#katalog" class="nav-text-link d-none d-sm-inline-block">Katalog</a>
                    <a href="{{ route('app.download') }}" class="btn-pill-yellow">
                        <i class="bi bi-download"></i>
                        <span class="d-none d-sm-inline">Download Aplikasi</span>
                        <span class="d-inline d-sm-none">Download</span>
                    </a>
                </div>
            </header>

            <!-- Hero Main Content Grid (Ref: image.png Asymmetrical Editorial Layout) -->
            <div class="hero-content-grid">
                
                <!-- Left Column: Pure Editorial Typography & Action CTAs -->
                <div class="hero-text-col">
                    <div class="hero-kicker-box">
                        <p class="hero-kicker-lead">Scroll, laugh, repeat.</p>
                        <p class="hero-kicker-title">HUMORA — Platform Serial Komedi</p>
                    </div>

                    <h1 class="hero-headline">
                        Tertawa Lepas,<br>
                        Tanpa Batas
                    </h1>

                    <p class="hero-description">
                        Serial sketsa komedi pendek dengan cerita lucu langsung ke inti lelucon. Nonton santai bebas putus-putus, dan <strong>Episode 1 &amp; 2 GRATIS</strong> untuk semua serial!
                    </p>

                    <div class="hero-btn-group">
                        <a href="{{ route('app.download') }}" class="btn-hero-download">
                            <i class="bi bi-download"></i>
                            <span>Download Aplikasi</span>
                            @if(!empty($apkSize))
                            <span class="btn-size-pill">{{ $apkSize }}</span>
                            @endif
                        </a>
                        <a href="#katalog" class="btn-hero-browse">
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
                            <i class="bi bi-phone-fill text-muted"></i>
                            <span>Android 5.0+</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Dual 3D Smartphone Mockup (Clean) -->
                <div class="hero-visual-wrapper">
                    <!-- Dual 3D Smartphone Image -->
                    <img src="{{ asset('images/mockup-device.webp') }}" 
                         alt="HUMORA Mobile App — Serial Komedi Singkat" 
                         class="hero-mockup-img" 
                         width="1100" 
                         height="1250" 
                         loading="eager" 
                         fetchpriority="high">
                </div>

            </div>
        </section>


        <!-- ==================================================================
             SECTION 2: ABOUT SECTION + DOWNLOAD (Ref: image.png Middle Section)
             ================================================================== -->
        <section class="about-section" id="about">

            <!-- Big Centered Headline with Inline Avatars (Direct match of image.png) -->
            <div class="about-headline-container">
                <h2 class="about-headline">
                    Nikmati hiburan serial komedi
                    <span class="inline-avatar-pill">
                        <!-- Avatar 1 -->
                        <svg class="avatar-img" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" fill="#E9D5FF"/>
                            <circle cx="20" cy="16" r="8" fill="#7E22CE"/>
                            <path d="M8 36 C8 28, 14 26, 20 26 C26 26, 32 28, 32 36 Z" fill="#7E22CE"/>
                        </svg>
                        <!-- Avatar 2 -->
                        <svg class="avatar-img" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" fill="#FEF08A"/>
                            <circle cx="20" cy="16" r="8" fill="#CA8A04"/>
                            <path d="M8 36 C8 28, 14 26, 20 26 C26 26, 32 28, 32 36 Z" fill="#CA8A04"/>
                        </svg>
                        <!-- Plus Badge -->
                        <span class="avatar-badge">
                            <i class="bi bi-plus-lg"></i>
                        </span>
                    </span>
                    langsung di genggaman Anda
                </h2>
            </div>

            <!-- Two-Column Grid: Left Device Mockup Card vs Right Numbered Features -->
            <div class="about-grid">

                <!-- Left Column Card: App Showcase with Direct Download Action (Ref: image.png Left Laptop Card) -->
                <div class="about-left-card">
                    <div>
                        <div class="about-device-mockup">
                            <!-- Window Top Bar -->
                            <div class="device-top-bar">
                                <span class="device-dot dot-red"></span>
                                <span class="device-dot dot-yellow"></span>
                                <span class="device-dot dot-green"></span>
                                <span class="ms-2 text-muted" style="font-size: 0.75rem; font-weight: 600;">Aplikasi HUMORA Mobile</span>
                            </div>

                            <!-- Screen Inner Preview -->
                            <div class="device-screen-inner text-center">
                                <div class="row align-items-center g-3">
                                    <div class="col-sm-5 text-center">
                                        <img src="{{ asset('images/logo-humora.png') }}" alt="HUMORA" class="img-fluid rounded-3 mb-2" style="max-height: 120px;">
                                        <div class="badge rounded-pill" style="background: var(--humora-primary-light); color: var(--humora-primary); font-size: 0.75rem;">
                                            Aplikasi Ringan &amp; Nyaman
                                        </div>
                                    </div>
                                    <div class="col-sm-7 text-start">
                                        <h5 class="fw-bold mb-1" style="color: var(--humora-text-main); font-size: 1.05rem;">
                                            Pengalaman Nonton Paling Asyik
                                        </h5>
                                        <p class="text-muted small mb-3">
                                            Dibuat khusus agar pas di genggaman tangan: layar tidak redup saat asyik tertawa, getaran tombol halus yang nyaman, dan video yang pernah ditonton bisa diputar ulang dengan mudah.
                                        </p>
                                        <div class="d-flex align-items-center gap-2 small text-muted mb-1.5">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            <span>Video Cepat Terbuka &amp; Bebas Putus-Putus</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 small text-muted mb-1.5">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            <span>Performa Ringan &amp; Kualitas Jernih</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            <span>Layar Ponsel Tetap Terang Selama Menonton</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Laptop Keyboard Silhouette (image.png style) -->
                        <div class="laptop-keyboard-decor"></div>

                        <!-- App Perks Grid (Fills the lower left area with valuable benefits) -->
                        <div class="about-card-perks-grid mt-3 pt-1">
                            <div class="perk-mini-card">
                                <div class="perk-mini-icon icon-yellow">
                                    <i class="bi bi-play-btn-fill"></i>
                                </div>
                                <div class="perk-mini-body">
                                    <h6 class="perk-mini-title">Pemutar Video 16:9 Jernih</h6>
                                    <p class="perk-mini-desc">Kualitas HD layar lebar dengan kontrol putar yang nyaman.</p>
                                </div>
                            </div>

                            <div class="perk-mini-card">
                                <div class="perk-mini-icon icon-purple">
                                    <i class="bi bi-gift-fill"></i>
                                </div>
                                <div class="perk-mini-body">
                                    <h6 class="perk-mini-title">Episode 1 &amp; 2 Gratis</h6>
                                    <p class="perk-mini-desc">Bebas tonton awal episode di semua serial komedi.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Trust & Specs Strip -->
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

                    <!-- Bottom Action Bar (Ref: image.png "Demo our dashboard" + Arrow Button) -->
                    <div class="about-card-bottom-actions mt-4">
                        <a href="{{ route('app.download') }}" class="btn-card-pill-cta">
                            <i class="bi bi-download"></i>
                            <span class="d-none d-sm-inline">Unduh Aplikasi HUMORA (Android)</span>
                            <span class="d-inline d-sm-none">Unduh Aplikasi</span>
                        </a>

                        <a href="{{ route('app.download') }}" class="btn-circle-arrow" title="Unduh Sekarang">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Numbered 01, 02, 03 List + Green Highlight Stat Card (Ref: image.png Right Column) -->
                <div class="about-right-col">

                    <!-- Numbered Features List -->
                    <div class="numbered-feature-list">

                        <!-- Item 01 -->
                        <div class="feature-item-row">
                            <span class="feature-number">01</span>
                            <h3 class="feature-title">Sketsa Singkat ~2 Menit</h3>
                            <p class="feature-desc">
                                Cerita komedi padat langsung ke inti lelucon tanpa bertele-tele. Pas banget dinikmati saat istirahat kerja, menunggu antrean, atau bersantai di rumah.
                            </p>
                        </div>

                        <!-- Item 02 -->
                        <div class="feature-item-row">
                            <span class="feature-number">02</span>
                            <h3 class="feature-title">Gratis Nonton &amp; Langganan Murah</h3>
                            <p class="feature-desc">
                                Episode 1 &amp; 2 GRATIS untuk semua judul serial. Mau lanjut nonton? Tersedia pilihan paket mingguan hemat mulai Rp15.000 atau tambah video mulai Rp7.500 saja.
                            </p>
                        </div>

                        <!-- Item 03 -->
                        <div class="feature-item-row">
                            <span class="feature-number">03</span>
                            <h3 class="feature-title">Nonton Mulus Bebas Buffering</h3>
                            <p class="feature-desc">
                                Video langsung terputar tanpa jeda berputar (*buffering*), bisa dimajukan atau dimundurkan dengan mulus dan responsif di setiap perangkat.
                            </p>
                        </div>

                    </div>

                    <!-- Bottom Split: Explanatory Text + Green Highlight Card (Direct match of image.png) -->
                    <div class="about-bottom-split">
                        <p class="about-bottom-lead mb-0">
                            Setiap episode dibuat khusus untuk bikin Anda tersenyum dan tertawa lepas kapan pun butuh hiburan di sela rutinitas harian.
                        </p>

                        <!-- Vibrant Yellow Highlight Card (Ref: Website Theme Accent Yellow) -->
                        <div class="yellow-highlight-card">
                            <div class="stat-big">~2m</div>
                            <div class="stat-label">
                                Durasi ideal sketsa komedi, punchline instan bikin mood ceria seketika tanpa bertele-tele.
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>


        <!-- ==================================================================
             SECTION 3: KATALOG SERIAL KOMEDI (Simple Showcase without Card, Full Edge-to-Edge)
             ================================================================== -->
        <section class="thumbnails-section" id="katalog">
            @php
                $availableThumbs = [
                    asset('images/thumbnails/1.webp'),
                    asset('images/thumbnails/2.webp'),
                    asset('images/thumbnails/3.webp'),
                    asset('images/thumbnails/4.webp'),
                    asset('images/thumbnails/5.webp'),
                    asset('images/thumbnails/6.webp'),
                    asset('images/thumbnails/7.webp'),
                    asset('images/thumbnails/8.webp'),
                    asset('images/thumbnails/9.webp'),
                    asset('images/thumbnails/10.webp'),
                ];
                // Subset of 5 selected thumbnails per row
                $row1 = array_slice($availableThumbs, 0, 5);
                $row2 = array_slice($availableThumbs, 5, 5);
                $row3 = [$availableThumbs[1], $availableThumbs[3], $availableThumbs[5], $availableThumbs[7], $availableThumbs[9]];

                // 15 tiles per set (2,790px width) guarantees seamless infinite loop without gaps on large 1440p/4K monitors
                $row1Set = array_merge($row1, $row1, $row1);
                $row2Set = array_merge($row2, $row2, $row2);
                $row3Set = array_merge($row3, $row3, $row3);
            @endphp

            <!-- Big Centered Headline with Inline Avatars (Direct match of Section 2 & image.png) -->
            <div class="catalog-headline-container">
                <h2 class="catalog-headline">
                    Katalog serial komedi
                    <span class="inline-avatar-pill">
                        @if(!empty($availableThumbs[0]))
                            <img src="{{ $availableThumbs[0] }}" class="avatar-img" alt="Serial Komedi" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
                        @else
                            <svg class="avatar-img" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="40" height="40" fill="#E9D5FF"/>
                                <circle cx="20" cy="16" r="8" fill="#7E22CE"/>
                                <path d="M8 36 C8 28, 14 26, 20 26 C26 26, 32 28, 32 36 Z" fill="#7E22CE"/>
                            </svg>
                        @endif
                        @if(!empty($availableThumbs[1]))
                            <img src="{{ $availableThumbs[1] }}" class="avatar-img" alt="Serial Komedi" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
                        @else
                            <svg class="avatar-img" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
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

            <!-- 3-Row Thumbnail Mosaic Layout (Full Width Dari Samping ke Samping) -->
            <div class="catalog-mosaic-wrapper">
                <!-- Edge Fades (Smooth blending into page background #F8F9FB) -->
                <div class="mosaic-fade-edge-left"></div>
                <div class="mosaic-fade-edge-right"></div>

                <!-- Row 1 (Scrolling Left) -->
                <div class="mosaic-row row-stagger-1">
                    <div class="mosaic-track track-left">
                        <div class="mosaic-set">
                            @foreach($row1Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
                            </div>
                            @endforeach
                        </div>
                        <div class="mosaic-set">
                            @foreach($row1Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Row 2 (Scrolling Right) -->
                <div class="mosaic-row row-stagger-2">
                    <div class="mosaic-track track-right">
                        <div class="mosaic-set">
                            @foreach($row2Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
                            </div>
                            @endforeach
                        </div>
                        <div class="mosaic-set">
                            @foreach($row2Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Row 3 (Scrolling Left) -->
                <div class="mosaic-row row-stagger-3">
                    <div class="mosaic-track track-left">
                        <div class="mosaic-set">
                            @foreach($row3Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
                            </div>
                            @endforeach
                        </div>
                        <div class="mosaic-set">
                            @foreach($row3Set as $thumb)
                            <div class="mosaic-tile">
                                <img src="{{ $thumb }}" alt="Serial Komedi HUMORA" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ==================================================================
             MINIMALIST FOOTER
             ================================================================== -->
        <footer class="humora-footer">
            <div class="footer-brand">
                <img src="{{ asset('images/logo-humora-rounded.png') }}" alt="HUMORA Logo">
                <span>HUMORA — Platform Serial Komedi oleh PT Global Mobile Content</span>
            </div>

            <div class="footer-links">
                <a href="#about" class="footer-link">Fitur</a>
                <a href="#katalog" class="footer-link">Katalog</a>
                <a href="{{ route('app.download') }}" class="footer-link">Download Aplikasi</a>
                <a href="#hero" class="footer-link">Kembali ke Atas</a>
            </div>

            <div class="w-100 text-center text-md-start small text-muted pt-2 border-top border-light-subtle">
                &copy; {{ date('Y') }} <strong>PT Global Mobile Content</strong>. Seluruh Hak Cipta Dilindungi.
            </div>
        </footer>

    </div>


    <!-- Bootstrap 5.3.3 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        window.addEventListener('error', function(event) {
            const target = event.target;
            if (target && target.tagName === 'IMG') {
                const defaultImg = "{{ asset('images/default-img.png') }}";
                if (target.src !== defaultImg && !target.dataset.fallbackApplied) {
                    target.dataset.fallbackApplied = 'true';
                    target.src = defaultImg;
                }
            }
        }, true);
    </script>
</body>
</html>
