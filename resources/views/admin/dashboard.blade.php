@extends('layouts.admin')

@section('title', 'Dashboard - HUMORA Portal Admin')

@section('content')
<!-- Header & Action Buttons -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle mb-0">Pantau performa konten, pertumbuhan audiens, dan aktivitas HUMORA dalam satu tempat.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/admin/analytics') }}" class="btn btn-humora-secondary">
            <i data-lucide="trending-up"></i>
            <span>Lihat Analytics</span>
        </a>
        <a href="{{ url('/admin/videos/create') }}" class="btn btn-humora-primary">
            <i data-lucide="plus"></i>
            <span>Tambah Video</span>
        </a>
    </div>
</div>

<!-- 6 Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Users" 
            value="24.850" 
            icon="users" 
            iconBg="purple" 
            trend="12.4%" 
            subtext="vs bln lalu" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Views" 
            value="432.500" 
            icon="eye" 
            iconBg="green" 
            trend="18.2%" 
            subtext="vs bln lalu" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Watch Time" 
            value="15.960 jam" 
            icon="clock" 
            iconBg="orange" 
            trend="15.6%" 
            subtext="vs bln lalu" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Subscribers" 
            value="3.279" 
            badge="VIP"
            icon="gem" 
            iconBg="purple" 
            trend="8.5%" 
            subtext="vs bln lalu" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Revenue" 
            value="Rp 48.699.200" 
            icon="credit-card" 
            iconBg="green" 
            trend="14.2%" 
            subtext="vs bln lalu" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Published Ep" 
            value="24" 
            icon="video" 
            iconBg="blue" 
            trend="16.7%" 
            subtext="vs bln lalu" 
        />
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Area Chart: Views & Watch Time -->
    <div class="col-12 col-xl-8">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">Pertumbuhan Views & Watch Time</div>
                    <div class="humora-card-subtitle">Total 65.995 views (+14.2% vs periode lalu)</div>
                </div>
                <div class="btn-filter-group" id="dashboardFilterGroup">
                    <button class="btn-filter-pill" data-period="7">7 Hari</button>
                    <button class="btn-filter-pill active" data-period="30">30 Hari</button>
                    <button class="btn-filter-pill" data-period="90">90 Hari</button>
                </div>
            </div>
            <div class="humora-card-body">
                <div style="height: 280px; width: 100%;">
                    <canvas id="viewsChart"></canvas>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-1" style="font-size: 0.78rem; font-weight: 600;">
                            <span class="rounded-circle d-inline-block" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="text-muted">Penayangan</span>
                        </div>
                        <div class="d-flex align-items-center gap-1" style="font-size: 0.78rem; font-weight: 600;">
                            <span class="rounded-circle d-inline-block" style="width: 8px; height: 8px; background-color: #F59E0B;"></span>
                            <span class="text-muted">Durasi Tonton (Jam)</span>
                        </div>
                    </div>
                    <a href="{{ url('/admin/analytics') }}" class="text-decoration-none fw-semibold d-inline-flex align-items-center gap-1" style="color: #43135C; font-size: 0.82rem;">
                        <span>Laporan Mendalam</span>
                        <i data-lucide="arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Donut Chart: Genre Distribution -->
    <div class="col-12 col-xl-4">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">DISTRIBUSI GENRE TONTONAN</div>
                    <div class="humora-card-subtitle">Proporsi penayangan konten</div>
                </div>
                <span class="badge rounded-pill" style="background-color: #F3E8FF; color: #6B21A8; font-size: 0.74rem;">5 Genre</span>
            </div>
            <div class="humora-card-body">
                <div class="position-relative d-flex justify-content-center align-items-center mb-3" style="height: 180px;">
                    <canvas id="genreChart"></canvas>
                    <div class="position-absolute text-center" style="pointer-events: none;">
                        <div class="fw-bold fs-5 text-dark">186.4K</div>
                        <div class="text-muted text-uppercase" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.05em;">TOTAL VIEWS</div>
                    </div>
                </div>
                <div class="px-2">
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <div class="d-flex align-items-center gap-2" style="font-size: 0.82rem;">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #FBBF24;"></span>
                            <span class="fw-medium">Komedi</span>
                        </div>
                        <div style="font-size: 0.8rem;">
                            <span class="text-muted me-2">76.420 views</span>
                            <span class="fw-bold text-dark">41%</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <div class="d-flex align-items-center gap-2" style="font-size: 0.82rem;">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #F43F5E;"></span>
                            <span class="fw-medium">Romantis</span>
                        </div>
                        <div style="font-size: 0.8rem;">
                            <span class="text-muted me-2">48.900 views</span>
                            <span class="fw-bold text-dark">26%</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <div class="d-flex align-items-center gap-2" style="font-size: 0.82rem;">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="fw-medium">Drama</span>
                        </div>
                        <div style="font-size: 0.8rem;">
                            <span class="text-muted me-2">31.250 views</span>
                            <span class="fw-bold text-dark">17%</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <div class="d-flex align-items-center gap-2" style="font-size: 0.82rem;">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #F97316;"></span>
                            <span class="fw-medium">Slice of Life</span>
                        </div>
                        <div style="font-size: 0.8rem;">
                            <span class="text-muted me-2">18.400 views</span>
                            <span class="fw-bold text-dark">10%</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <div class="d-flex align-items-center gap-2" style="font-size: 0.82rem;">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #10B981;"></span>
                            <span class="fw-medium">Persahabatan</span>
                        </div>
                        <div style="font-size: 0.8rem;">
                            <span class="text-muted me-2">11.450 views</span>
                            <span class="fw-bold text-dark">6%</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top" style="font-size: 0.8rem;">
                    <span class="text-muted">Total 186.420 Views</span>
                    <a href="{{ url('/admin/categories') }}" class="text-decoration-none fw-semibold" style="color: #43135C;">
                        Kelola Kategori <i data-lucide="arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row: Top Episodes & Recent Activity -->
<div class="row g-4">
    <!-- Top Performing Episodes -->
    <div class="col-12 col-xl-8">
        <div class="humora-card mb-0">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">TOP PERFORMING EPISODES</div>
                    <div class="humora-card-subtitle">Paling banyak ditonton dengan completion rate tertinggi</div>
                </div>
                <a href="{{ url('/admin/videos') }}" class="text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.82rem;">
                    Lihat Semua Video <i data-lucide="arrow-up-right"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table-humora">
                    <thead>
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th>Episode</th>
                            <th>Series & Kategori</th>
                            <th>Views</th>
                            <th>Suka</th>
                            <th>Completion</th>
                            <th>Akses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-muted fw-bold">1</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/thumbnails/tipe-penumpang-taxi-online.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                                    <div>
                                        <div class="fw-bold text-dark">Ep 1: Tipe Bawel</div>
                                        <small class="text-muted">2m 14s</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">Tipe Penumpang Taxi On...</div>
                                <small class="text-muted">Serial Komedi</small>
                            </td>
                            <td class="fw-bold text-dark">24.000</td>
                            <td>
                                <span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>4.250</span>
                            </td>
                            <td style="width: 140px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-humora flex-grow-1">
                                        <div class="progress-humora-bar" style="width: 92%;"></div>
                                    </div>
                                    <span class="fw-bold text-success" style="font-size: 0.78rem;">92%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">2</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/thumbnails/cara-deketin-cewek.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                                    <div>
                                        <div class="fw-bold text-dark">Ep 1: Pake Hadiah</div>
                                        <small class="text-muted">2m 10s</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">Cara Deketin Cewek</div>
                                <small class="text-muted">Serial Komedi</small>
                            </td>
                            <td class="fw-bold text-dark">24.000</td>
                            <td>
                                <span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>5.600</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-humora flex-grow-1">
                                        <div class="progress-humora-bar" style="width: 92%;"></div>
                                    </div>
                                    <span class="fw-bold text-success" style="font-size: 0.78rem;">92%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">3</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/thumbnails/tipe-tipe-teman-sekantor.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                                    <div>
                                        <div class="fw-bold text-dark">Ep 1: Tipe Cari Muka</div>
                                        <small class="text-muted">2m 12s</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">Tipe-Tipe Teman Sekant...</div>
                                <small class="text-muted">Serial Komedi</small>
                            </td>
                            <td class="fw-bold text-dark">24.000</td>
                            <td>
                                <span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>3.800</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-humora flex-grow-1">
                                        <div class="progress-humora-bar" style="width: 92%;"></div>
                                    </div>
                                    <span class="fw-bold text-success" style="font-size: 0.78rem;">92%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">4</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/thumbnails/drama-warga-grup-rt.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                                    <div>
                                        <div class="fw-bold text-dark">Ep 1: Iuran Sampah Macet</div>
                                        <small class="text-muted">2m 08s</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">Drama Warga Grup RT</div>
                                <small class="text-muted">Serial Komedi</small>
                            </td>
                            <td class="fw-bold text-dark">24.000</td>
                            <td>
                                <span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>6.500</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-humora flex-grow-1">
                                        <div class="progress-humora-bar" style="width: 92%;"></div>
                                    </div>
                                    <span class="fw-bold text-success" style="font-size: 0.78rem;">92%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">5</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/thumbnails/momen-canggung-kencan-pertama.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                                    <div>
                                        <div class="fw-bold text-dark">Ep 1: Dompet Ketinggalan</div>
                                        <small class="text-muted">2m 10s</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">Momen Canggung Kenc...</div>
                                <small class="text-muted">Serial Komedi</small>
                            </td>
                            <td class="fw-bold text-dark">24.000</td>
                            <td>
                                <span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>5.100</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-humora flex-grow-1">
                                        <div class="progress-humora-bar" style="width: 92%;"></div>
                                    </div>
                                    <span class="fw-bold text-success" style="font-size: 0.78rem;">92%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">6</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                                    <div>
                                        <div class="fw-bold text-dark">Ep 1: Nawar Sadis</div>
                                        <small class="text-muted">2m 04s</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">Tipe Pembeli di Toko Kel...</div>
                                <small class="text-muted">Serial Komedi</small>
                            </td>
                            <td class="fw-bold text-dark">24.000</td>
                            <td>
                                <span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>2.900</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-humora flex-grow-1">
                                        <div class="progress-humora-bar" style="width: 92%;"></div>
                                    </div>
                                    <span class="fw-bold text-success" style="font-size: 0.78rem;">92%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill-free"><i data-lucide="play-circle"></i> Free Preview</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center py-3 border-top">
                <a href="{{ url('/admin/videos') }}" class="text-decoration-none fw-bold" style="color: #43135C; font-size: 0.84rem;">
                    Kelola Seluruh Episode (24 Total) &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="col-12 col-xl-4">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">AKTIVITAS TERBARU</div>
                </div>
                <span class="text-muted" style="font-size: 0.75rem;">Log Real-time</span>
            </div>
            <div class="humora-card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon-wrap stat-icon-blue flex-shrink-0 mt-1" style="width: 32px; height: 32px; font-size: 0.95rem;">
                            <i data-lucide="video"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.84rem;">Video Terbit</div>
                            <div class="text-muted" style="font-size: 0.78rem; line-height: 1.4;">Tipe Penumpang Taxi Online: Video 1 "Tipe Bawel" dipublikasikan.</div>
                            <small class="text-muted d-flex align-items-center gap-1 mt-1" style="font-size: 0.72rem;">
                                <i data-lucide="calendar"></i> 15 Okt 2026, 16:00 WIB
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon-wrap stat-icon-orange flex-shrink-0 mt-1" style="width: 32px; height: 32px; font-size: 0.95rem;">
                            <i data-lucide="badge-check"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.84rem;">Pembayaran Diverifikasi</div>
                            <div class="text-muted" style="font-size: 0.78rem; line-height: 1.4;">Pembayaran Paket Tahunan oleh Dimas Aditya berhasil diverifikasi.</div>
                            <small class="text-muted d-flex align-items-center gap-1 mt-1" style="font-size: 0.72rem;">
                                <i data-lucide="calendar"></i> 14 Okt 2026, 18:30 WIB
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon-wrap stat-icon-purple flex-shrink-0 mt-1" style="width: 32px; height: 32px; font-size: 0.95rem;">
                            <i data-lucide="tv"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.84rem;">Judul Konten Baru</div>
                            <div class="text-muted" style="font-size: 0.78rem; line-height: 1.4;">Judul "Cara Deketin Cewek" berhasil ditambahkan ke katalog.</div>
                            <small class="text-muted d-flex align-items-center gap-1 mt-1" style="font-size: 0.72rem;">
                                <i data-lucide="calendar"></i> 12 Okt 2026, 17:00 WIB
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon-wrap stat-icon-green flex-shrink-0 mt-1" style="width: 32px; height: 32px; font-size: 0.95rem;">
                            <i data-lucide="sliders"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.84rem;">Pembaruan Akses Video</div>
                            <div class="text-muted" style="font-size: 0.78rem; line-height: 1.4;">Video 2 Cara Deketin Cewek diubah menjadi Free Preview.</div>
                            <small class="text-muted d-flex align-items-center gap-1 mt-1" style="font-size: 0.72rem;">
                                <i data-lucide="calendar"></i> 10 Okt 2026, 21:15 WIB
                            </small>
                        </div>
                    </div>
                </div>
                <div class="text-center pt-3 mt-3 border-top">
                    <small class="text-muted" style="font-size: 0.75rem;">Semua aktivitas tersinkronisasi otomatis</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Line chart for views
        const ctxViews = document.getElementById('viewsChart').getContext('2d');
        const gradientViews = ctxViews.createLinearGradient(0, 0, 0, 260);
        gradientViews.addColorStop(0, 'rgba(67, 19, 92, 0.22)');
        gradientViews.addColorStop(1, 'rgba(67, 19, 92, 0.01)');

        const viewsDatasets = {
            '7': {
                labels: ['10 Okt', '11 Okt', '12 Okt', '13 Okt', '14 Okt', '15 Okt', '16 Okt'],
                data: [3200, 3650, 4100, 3850, 4300, 5100, 4950]
            },
            '30': {
                labels: ['16 Sep', '21 Sep', '26 Sep', '1 Okt', '6 Okt', '11 Okt', '15 Okt'],
                data: [2100, 2450, 2200, 2800, 2300, 2900, 2850]
            },
            '90': {
                labels: ['Agt W1', 'Agt W3', 'Sep W1', 'Sep W3', 'Okt W1', 'Okt W3'],
                data: [8400, 9600, 11200, 12800, 14500, 15900]
            }
        };

        const viewsChart = new Chart(ctxViews, {
            type: 'line',
            data: {
                labels: viewsDatasets['30'].labels,
                datasets: [{
                    label: 'Penayangan',
                    data: viewsDatasets['30'].data,
                    borderColor: '#43135C',
                    borderWidth: 3,
                    backgroundColor: gradientViews,
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
                                return ' Penayangan: ' + context.raw.toLocaleString('id-ID') + ' views';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#F3F4F6' },
                        ticks: { color: '#9CA3AF', font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9CA3AF', font: { size: 11 } }
                    }
                }
            }
        });

        // Filter button clicks
        const filterBtns = document.querySelectorAll('#dashboardFilterGroup .btn-filter-pill');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const period = this.dataset.period || '30';
                if (viewsDatasets[period]) {
                    viewsChart.data.labels = viewsDatasets[period].labels;
                    viewsChart.data.datasets[0].data = viewsDatasets[period].data;
                    viewsChart.update();
                    if (typeof window.showHumoraToast === 'function') {
                        window.showHumoraToast('Grafik penayangan diperbarui untuk ' + period + ' hari terakhir', 'info');
                    }
                }
            });
        });

        // Donut chart for genres
        const ctxGenre = document.getElementById('genreChart').getContext('2d');
        new Chart(ctxGenre, {
            type: 'doughnut',
            data: {
                labels: ['Komedi', 'Romantis', 'Drama', 'Slice of Life', 'Persahabatan'],
                datasets: [{
                    data: [41, 26, 17, 10, 6],
                    backgroundColor: ['#FBBF24', '#F43F5E', '#43135C', '#F97316', '#10B981'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
