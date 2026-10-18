@extends('layouts.admin')

@section('title', 'Analytics & Performance - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs & Header -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i> <span class="text-dark fw-semibold">Analytics</span>
    </small>
</div>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Analytics & Performance</h1>
        <p class="page-subtitle mb-0">Analisis mendalam mengenai penayangan video, durasi tonton, retensi audiens, dan performa paket langganan HUMORA.</p>
    </div>
    <div class="btn-filter-group align-self-start align-self-md-auto" id="analyticsPeriodFilter">
        <button class="btn-filter-pill d-inline-flex align-items-center gap-1" data-period="7">
            <i data-lucide="calendar"></i> 7 Hari Terakhir
        </button>
        <button class="btn-filter-pill active" data-period="30">30 Hari Terakhir</button>
        <button class="btn-filter-pill" data-period="90">90 Hari Terakhir</button>
    </div>
</div>

<!-- 6 Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Views" 
            value="65.995" 
            icon="eye" 
            iconBg="green" 
            trend="14.2%" 
            subtext="vs periode lalu" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Penonton Unik" 
            value="44.877" 
            icon="users" 
            iconBg="purple" 
            subtext="68% dari total views" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Watch Time" 
            value="8.014 jam" 
            icon="clock" 
            iconBg="orange" 
            trend="16%" 
            subtext="vs periode lalu" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Rata-rata Tonton" 
            value="7 menit" 
            icon="trending-up" 
            iconBg="blue" 
            subtext="per penayangan episode" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Subscribers Baru" 
            value="1.282" 
            icon="gem" 
            iconBg="purple" 
            trend="26.3%" 
            subtext="vs periode lalu" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Pendapatan" 
            value="Rp 30.831.402" 
            icon="credit-card" 
            iconBg="green" 
            trend="26.4%" 
            subtext="vs periode lalu" 
        />
    </div>
</div>

<!-- Time Trend Exploration Chart -->
<div class="humora-card mb-4">
    <div class="humora-card-header flex-column flex-md-row align-items-start align-items-md-center gap-2">
        <div>
            <div class="humora-card-title">Eksplorasi Tren Metrik Waktu</div>
            <div class="humora-card-subtitle">Pilih metrik utama untuk melihat grafik visualisasi performa harian HUMORA.</div>
        </div>
        <div class="d-flex flex-wrap gap-2" id="metricFilterBtns">
            <button class="btn btn-sm btn-humora-primary rounded-pill px-3 metric-btn active" data-metric="views" style="font-size: 0.76rem;">Views (65.995)</button>
            <button class="btn btn-sm btn-light border rounded-pill px-3 text-muted metric-btn" data-metric="watchtime" style="font-size: 0.76rem;">Watch Time (8.014 jam)</button>
            <button class="btn btn-sm btn-light border rounded-pill px-3 text-muted metric-btn" data-metric="subscribers" style="font-size: 0.76rem;">Subscribers (+1.282)</button>
            <button class="btn btn-sm btn-light border rounded-pill px-3 text-muted metric-btn" data-metric="revenue" style="font-size: 0.76rem;">Revenue (Rp 30.831.402)</button>
        </div>
    </div>
    <div class="humora-card-body">
        <div style="height: 320px; width: 100%;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>

<!-- Audience Insights & Subscription Performance Row -->
<div class="row g-4 mb-4">
    <!-- Audience Insights -->
    <div class="col-12 col-lg-6">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="users" class="text-purple" style="color: #43135C;"></i>
                    <span class="humora-card-title">AUDIENCE INSIGHTS</span>
                </div>
                <span class="text-muted" style="font-size: 0.76rem;">Aktivitas Penonton</span>
            </div>
            <div class="humora-card-body">
                <p class="text-muted mb-4" style="font-size: 0.82rem;">Distribusi perilaku audiens penikmat serial pendek komedi dan romantis HUMORA.</p>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.82rem;">
                        <span class="fw-semibold text-dark">Penonton Aktif (Active Viewers)</span>
                        <span class="fw-bold" style="color: #43135C;">15.400 (62%)</span>
                    </div>
                    <div class="progress-humora" style="height: 8px;">
                        <div class="progress-humora-bar" style="width: 62%; background-color: #43135C;"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.82rem;">
                        <span class="fw-semibold text-dark">Penonton Baru (New Viewers)</span>
                        <span class="fw-bold text-success">5.960 (24%)</span>
                    </div>
                    <div class="progress-humora" style="height: 8px;">
                        <div class="progress-humora-bar" style="width: 24%; background-color: #10B981;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.82rem;">
                        <span class="fw-semibold text-dark">Penonton Kembali (Returning Viewers)</span>
                        <span class="fw-bold text-warning">3.490 (14%)</span>
                    </div>
                    <div class="progress-humora" style="height: 8px;">
                        <div class="progress-humora-bar" style="width: 14%; background-color: #F59E0B;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Performance -->
    <div class="col-12 col-lg-6">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="gem" class="text-warning"></i>
                    <span class="humora-card-title">SUBSCRIPTION PERFORMANCE</span>
                </div>
                <span class="badge rounded-pill" style="background-color: #ECFDF5; color: #059669; font-size: 0.74rem;">13.2% Conversion Rate</span>
            </div>
            <div class="humora-card-body">
                <p class="text-muted mb-4" style="font-size: 0.82rem;">Total 3.280 pelanggan premium aktif dengan churn rate hanya 4.8%.</p>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.82rem;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="fw-semibold text-dark">Paket 3 Bulan</span>
                        </div>
                        <div>
                            <span class="text-muted me-2">1.870 subs (57%)</span>
                            <span class="fw-bold text-success">Rp 91.630.000</span>
                        </div>
                    </div>
                    <div class="progress-humora" style="height: 8px;">
                        <div class="progress-humora-bar" style="width: 57%; background-color: #43135C;"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.82rem;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #FBBF24;"></span>
                            <span class="fw-semibold text-dark">Paket Bulanan</span>
                        </div>
                        <div>
                            <span class="text-muted me-2">880 subs (27%)</span>
                            <span class="fw-bold text-success">Rp 16.720.000</span>
                        </div>
                    </div>
                    <div class="progress-humora" style="height: 8px;">
                        <div class="progress-humora-bar" style="width: 27%; background-color: #FBBF24;"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.82rem;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #7C3AED;"></span>
                            <span class="fw-semibold text-dark">Paket Tahunan</span>
                        </div>
                        <div>
                            <span class="text-muted me-2">530 subs (16%)</span>
                            <span class="fw-bold text-success">Rp 94.870.000</span>
                        </div>
                    </div>
                    <div class="progress-humora" style="height: 8px;">
                        <div class="progress-humora-bar" style="width: 16%; background-color: #7C3AED;"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top" style="font-size: 0.78rem;">
                    <span class="text-muted">Model: 2 Ep Gratis &rarr; Unlock Premium</span>
                    <a href="{{ url('/admin/subscribers') }}" class="text-decoration-none fw-semibold" style="color: #43135C;">
                        Kelola Pelanggan &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Episode Performance Ranking Table -->
<div class="humora-card mb-0">
    <div class="humora-card-header flex-column flex-md-row align-items-start align-items-md-center gap-2">
        <div>
            <div class="humora-card-title">PERINGKAT PERFORMA EPISODE LENGKAP</div>
            <div class="humora-card-subtitle">Urutkan berdasarkan penayangan, retensi durasi, atau completion rate</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted" style="font-size: 0.78rem;">Urutkan:</span>
            <select class="form-select-custom py-1 px-3" id="analyticsSortSelect" style="width: 170px; font-size: 0.8rem;">
                <option value="views" selected>Views Terbanyak</option>
                <option value="completion">Completion Tertinggi</option>
                <option value="watchtime">Watch Time Tertinggi</option>
            </select>
        </div>
    </div>
    <div class="p-3 border-bottom bg-light">
        <div class="search-input-wrap">
            <i data-lucide="search"></i>
            <input type="text" id="analyticsSearchInput" class="form-control-custom bg-white" placeholder="Cari judul episode atau nama series...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table-humora" id="analyticsTable">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th>Episode & Series</th>
                    <th>Status Akses</th>
                    <th>Views</th>
                    <th>Jumlah Suka</th>
                    <th>Watch Time</th>
                    <th>Completion Rate</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <tr data-title="Ep 1: Tipe Bawel Tipe Penumpang Taxi Online" data-views="24000" data-watchtime="893" data-completion="92">
                    <td class="text-muted fw-bold row-index">1</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-penumpang-taxi-online.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 1: Tipe Bawel</div>
                                <small class="text-muted">Tipe Penumpang Taxi Online (2m 14s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>4.250</span></td>
                    <td class="text-muted">893 jam</td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 1: Pake Hadiah Cara Deketin Cewek" data-views="24000" data-watchtime="867" data-completion="92">
                    <td class="text-muted fw-bold row-index">2</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/cara-deketin-cewek.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 1: Pake Hadiah</div>
                                <small class="text-muted">Cara Deketin Cewek (2m 10s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>5.600</span></td>
                    <td class="text-muted">867 jam</td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 1: Tipe Cari Muka Tipe-Tipe Teman Sekantor" data-views="24000" data-watchtime="880" data-completion="92">
                    <td class="text-muted fw-bold row-index">3</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-tipe-teman-sekantor.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 1: Tipe Cari Muka</div>
                                <small class="text-muted">Tipe-Tipe Teman Sekantor (2m 12s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>3.800</span></td>
                    <td class="text-muted">880 jam</td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 1: Iuran Sampah Macet Drama Warga Grup RT" data-views="24000" data-watchtime="853" data-completion="92">
                    <td class="text-muted fw-bold row-index">4</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/drama-warga-grup-rt.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 1: Iuran Sampah Macet</div>
                                <small class="text-muted">Drama Warga Grup RT (2m 08s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>6.500</span></td>
                    <td class="text-muted">853 jam</td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 1: Dompet Ketinggalan Momen Canggung Kencan Pertama" data-views="24000" data-watchtime="867" data-completion="92">
                    <td class="text-muted fw-bold row-index">5</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/momen-canggung-kencan-pertama.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 1: Dompet Ketinggalan</div>
                                <small class="text-muted">Momen Canggung Kencan Pertama (2m 10s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>5.100</span></td>
                    <td class="text-muted">867 jam</td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 1: Nawar Sadis Tipe Pembeli di Toko Kelontong" data-views="24000" data-watchtime="827" data-completion="92">
                    <td class="text-muted fw-bold row-index">6</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 1: Nawar Sadis</div>
                                <small class="text-muted">Tipe Pembeli di Toko Kelontong (2m 04s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>2.900</span></td>
                    <td class="text-muted">827 jam</td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 2: Tipe Sensi Tipe Penumpang Taxi Online" data-views="21000" data-watchtime="688" data-completion="88">
                    <td class="text-muted fw-bold row-index">7</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-penumpang-taxi-online.jpg') }}" alt="Ep 2" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 2: Tipe Sensi</div>
                                <small class="text-muted">Tipe Penumpang Taxi Online (1m 58s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">21.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>3.820</span></td>
                    <td class="text-muted">688 jam</td>
                    <td><span class="fw-bold text-success">88%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 2: Pake Rayuan Maut Cara Deketin Cewek" data-views="21000" data-watchtime="671" data-completion="88">
                    <td class="text-muted fw-bold row-index">8</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/cara-deketin-cewek.jpg') }}" alt="Ep 2" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 2: Pake Rayuan Maut</div>
                                <small class="text-muted">Cara Deketin Cewek (1m 55s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">21.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>4.900</span></td>
                    <td class="text-muted">671 jam</td>
                    <td><span class="fw-bold text-success">88%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 2: Tipe Tukang Titip Jajan Tipe-Tipe Teman Sekantor" data-views="21000" data-watchtime="642" data-completion="88">
                    <td class="text-muted fw-bold row-index">9</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-tipe-teman-sekantor.jpg') }}" alt="Ep 2" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 2: Tipe Tukang Titip Jajan</div>
                                <small class="text-muted">Tipe-Tipe Teman Sekantor (1m 50s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">21.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>3.400</span></td>
                    <td class="text-muted">642 jam</td>
                    <td><span class="fw-bold text-success">88%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
                <tr data-title="Ep 2: Parkir Depan Pagar Tetangga Drama Warga Grup RT" data-views="21000" data-watchtime="788" data-completion="88">
                    <td class="text-muted fw-bold row-index">10</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/drama-warga-grup-rt.jpg') }}" alt="Ep 2" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark">Ep 2: Parkir Depan Pagar Tetangga</div>
                                <small class="text-muted">Drama Warga Grup RT (2m 15s)</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span></td>
                    <td class="fw-bold text-dark">21.000</td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>5.800</span></td>
                    <td class="text-muted">788 jam</td>
                    <td><span class="fw-bold text-success">88%</span></td>
                    <td class="text-end"><a href="{{ url('/admin/videos') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.8rem;">Kelola</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        const gradientTrend = ctxTrend.createLinearGradient(0, 0, 0, 300);
        gradientTrend.addColorStop(0, 'rgba(67, 19, 92, 0.22)');
        gradientTrend.addColorStop(1, 'rgba(67, 19, 92, 0.01)');

        const periodDatasets = {
            '7': {
                labels: ['10 Okt', '11 Okt', '12 Okt', '13 Okt', '14 Okt', '15 Okt', '16 Okt'],
                metrics: {
                    'views': { label: 'Views', data: [3100, 3450, 4200, 3900, 4400, 5200, 4800], unit: 'views', prefix: '' },
                    'watchtime': { label: 'Watch Time', data: [380, 410, 510, 470, 530, 620, 590], unit: 'jam', prefix: '' },
                    'subscribers': { label: 'Subscribers Baru', data: [58, 64, 82, 70, 85, 98, 91], unit: 'subscribers', prefix: '+' },
                    'revenue': { label: 'Pendapatan Harian', data: [1420000, 1680000, 2350000, 1920000, 2250000, 2890000, 2600000], unit: '', prefix: 'Rp ' }
                }
            },
            '30': {
                labels: ['16 Sep', '21 Sep', '26 Sep', '1 Okt', '6 Okt', '11 Okt', '15 Okt'],
                metrics: {
                    'views': { label: 'Views', data: [2300, 2400, 3000, 2450, 2600, 3200, 2900], unit: 'views', prefix: '' },
                    'watchtime': { label: 'Watch Time', data: [280, 310, 350, 290, 340, 420, 380], unit: 'jam', prefix: '' },
                    'subscribers': { label: 'Subscribers Baru', data: [45, 52, 68, 48, 59, 75, 62], unit: 'subscribers', prefix: '+' },
                    'revenue': { label: 'Pendapatan Harian', data: [1120000, 1340000, 1820000, 1420000, 1650000, 2190000, 1890000], unit: '', prefix: 'Rp ' }
                }
            },
            '90': {
                labels: ['Agt W1', 'Agt W3', 'Sep W1', 'Sep W3', 'Okt W1', 'Okt W3'],
                metrics: {
                    'views': { label: 'Views', data: [8400, 9600, 11200, 12800, 14500, 15900], unit: 'views', prefix: '' },
                    'watchtime': { label: 'Watch Time', data: [1100, 1240, 1480, 1650, 1820, 2010], unit: 'jam', prefix: '' },
                    'subscribers': { label: 'Subscribers Baru', data: [180, 215, 260, 290, 320, 355], unit: 'subscribers', prefix: '+' },
                    'revenue': { label: 'Pendapatan Harian', data: [4500000, 5200000, 6800000, 7400000, 8100000, 9200000], unit: '', prefix: 'Rp ' }
                }
            }
        };

        let currentPeriod = '30';
        let currentMetric = 'views';

        function getCurrentMetricConfig() {
            return periodDatasets[currentPeriod].metrics[currentMetric];
        }

        const trendChart = new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: periodDatasets[currentPeriod].labels,
                datasets: [{
                    label: getCurrentMetricConfig().label,
                    data: getCurrentMetricConfig().data,
                    borderColor: '#43135C',
                    borderWidth: 3,
                    backgroundColor: gradientTrend,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#43135C',
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#43135C'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const m = getCurrentMetricConfig();
                                return ' ' + m.label + ': ' + m.prefix + context.raw.toLocaleString('id-ID') + (m.unit ? ' ' + m.unit : '');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#F3F4F6' },
                        ticks: {
                            color: '#9CA3AF',
                            font: { size: 11 },
                            callback: function(val) {
                                if (currentMetric === 'revenue') {
                                    return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                                }
                                return val.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9CA3AF', font: { size: 11 } }
                    }
                }
            }
        });

        // Period filter pills
        const periodBtns = document.querySelectorAll('#analyticsPeriodFilter .btn-filter-pill');
        periodBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                periodBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                currentPeriod = this.dataset.period || '30';
                const pData = periodDatasets[currentPeriod];
                if (pData) {
                    trendChart.data.labels = pData.labels;
                    trendChart.data.datasets[0].label = pData.metrics[currentMetric].label;
                    trendChart.data.datasets[0].data = pData.metrics[currentMetric].data;
                    trendChart.update();
                    if (typeof window.showHumoraToast === 'function') {
                        window.showHumoraToast('Periode analytics diubah ke ' + currentPeriod + ' Hari Terakhir', 'info');
                    }
                }
            });
        });

        // Metric switch buttons
        const metricBtns = document.querySelectorAll('#metricFilterBtns .metric-btn');
        metricBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                metricBtns.forEach(b => {
                    b.classList.remove('btn-humora-primary', 'active');
                    b.classList.add('btn-light', 'border', 'text-muted');
                });
                this.classList.remove('btn-light', 'border', 'text-muted');
                this.classList.add('btn-humora-primary', 'active');

                const metric = this.dataset.metric;
                if (periodDatasets[currentPeriod].metrics[metric]) {
                    currentMetric = metric;
                    const m = getCurrentMetricConfig();
                    trendChart.data.datasets[0].label = m.label;
                    trendChart.data.datasets[0].data = m.data;
                    trendChart.update();
                }
            });
        });

        // Live Table Search & Sort
        const searchInput = document.getElementById('analyticsSearchInput');
        const sortSelect = document.getElementById('analyticsSortSelect');
        const table = document.getElementById('analyticsTable');
        const tbody = table ? table.querySelector('tbody') : null;

        function filterAndSortTable() {
            if (!tbody) return;
            const searchTerm = (searchInput ? searchInput.value : '').toLowerCase().trim();
            const sortMode = sortSelect ? sortSelect.value : 'views';

            const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-state-row)'));
            let visibleCount = 0;

            // Filter
            rows.forEach(row => {
                const title = (row.dataset.title || '').toLowerCase();
                const matches = !searchTerm || title.includes(searchTerm);
                row.style.display = matches ? '' : 'none';
                if (matches) visibleCount++;
            });

            // Sort visible rows
            const visibleRows = rows.filter(r => r.style.display !== 'none');
            visibleRows.sort((a, b) => {
                const valA = parseFloat(a.dataset[sortMode] || 0);
                const valB = parseFloat(b.dataset[sortMode] || 0);
                return valB - valA;
            });

            visibleRows.forEach((row, idx) => {
                tbody.appendChild(row);
                const idxCell = row.querySelector('.row-index');
                if (idxCell) idxCell.textContent = (idx + 1);
            });

            // Empty state row
            let emptyRow = tbody.querySelector('.empty-state-row');
            if (visibleCount === 0) {
                if (!emptyRow) {
                    emptyRow = document.createElement('tr');
                    emptyRow.className = 'empty-state-row';
                    emptyRow.innerHTML = `
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i data-lucide="search" class="fs-4 d-block mb-2 opacity-50"></i>
                            Tidak ada episode yang sesuai dengan pencarian "${searchTerm}".
                        </td>
                    `;
                    tbody.appendChild(emptyRow);
                }
            } else if (emptyRow) {
                emptyRow.remove();
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterAndSortTable);
        }
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                filterAndSortTable();
                if (typeof window.showHumoraToast === 'function') {
                    window.showHumoraToast('Daftar episode diurutkan berdasarkan ' + sortSelect.options[sortSelect.selectedIndex].text, 'info');
                }
            });
        }
    });
</script>
@endsection
