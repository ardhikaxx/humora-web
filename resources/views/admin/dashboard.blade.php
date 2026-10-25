@extends('layouts.admin')

@section('title', 'Dashboard - HUMORA Portal Admin')

@section('content')
<!-- Header & Quick Action Buttons -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Dashboard HUMORA</h1>
        <p class="page-subtitle mb-0">Pantau metrik penayangan, pertumbuhan pengguna, langganan VIP, dan verifikasi pembayaran.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('admin.series.create') }}" class="btn btn-sm btn-humora-secondary">
            <i data-lucide="tv"></i>
            <span>+ Series</span>
        </a>
        <a href="{{ route('admin.videos.create') }}" class="btn btn-sm btn-humora-secondary">
            <i data-lucide="video"></i>
            <span>+ Episode</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-humora-secondary">
            <i data-lucide="tag"></i>
            <span>Kategori</span>
        </a>
        @if ($pendingTransactionsCount > 0)
        <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning text-dark fw-bold d-flex align-items-center gap-1">
            <i data-lucide="alert-circle"></i>
            <span>{{ $pendingTransactionsCount }} Trx Pending</span>
        </a>
        @endif
    </div>
</div>

<!-- 6 Real Database KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Episode" 
            :value="number_format($totalEpisodes, 0, ',', '.')" 
            icon="video" 
            iconBg="purple" 
            subtext="Terdaftar di database" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Series" 
            :value="number_format($totalSeries, 0, ',', '.')" 
            icon="tv" 
            iconBg="yellow" 
            subtext="Katalog aktif" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Akun Pengguna" 
            :value="number_format($totalUsers, 0, ',', '.')" 
            icon="users" 
            iconBg="blue" 
            subtext="Mobile terdaftar" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Subscriber VIP Aktif" 
            :value="number_format($activeSubscribers, 0, ',', '.')" 
            badge="VIP"
            icon="gem" 
            iconBg="purple" 
            subtext="Masa aktif valid" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Transaksi Pending" 
            :value="number_format($pendingTransactionsCount, 0, ',', '.')" 
            icon="clock" 
            iconBg="orange" 
            subtext="Butuh verifikasi" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            label="Total Pendapatan" 
            :value="'Rp ' . number_format($totalRevenue, 0, ',', '.')" 
            icon="banknote" 
            iconBg="green" 
            subtext="Transaksi sukses" 
        />
    </div>
</div>

<!-- Charts Row: 2 Real Visualizations -->
<div class="row g-4 mb-4">
    <!-- Chart 1: Tren Penayangan Harian (7 Hari Terakhir) -->
    <div class="col-12 col-xl-7">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">TREN PENAYANGAN HARIAN</div>
                    <div class="humora-card-subtitle">Frekuensi penayangan video sketsa komedi selama 7 hari terakhir</div>
                </div>
                <span class="badge rounded-pill bg-light text-dark border" style="font-size: 0.74rem;">7 Hari Terakhir</span>
            </div>
            <div class="humora-card-body p-3">
                <div style="height: 260px; width: 100%; position: relative;">
                    <canvas id="dailyViewsChart"></canvas>
                    @if (array_sum($dailyViewCounts) === 0)
                        <div class="position-absolute top-50 start-50 translate-middle text-center p-3 rounded-3" style="background: rgba(255, 255, 255, 0.94); backdrop-filter: blur(4px); border: 1px dashed #CBD5E1; max-width: 350px; width: 90%; z-index: 5;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 38px; height: 38px; background: #FAF5FF; color: #43135C;">
                                <i data-lucide="play-circle" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Belum Ada Penayangan 7 Hari Ini</div>
                            <p class="text-muted mb-0" style="font-size: 0.74rem; line-height: 1.4;">
                                Data grafik murni dicatat saat pengguna memutar video di <strong>Aplikasi Mobile HUMORA</strong>.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 2: Konversi Subscriber Mingguan -->
    <div class="col-12 col-xl-5">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">KONVERSI LANGGANAN VIP MINGGUAN</div>
                    <div class="humora-card-subtitle">Transaksi berhasil diverifikasi per minggu</div>
                </div>
                <span class="badge rounded-pill bg-light text-dark border" style="font-size: 0.74rem;">4 Minggu Terakhir</span>
            </div>
            <div class="humora-card-body p-3">
                <div style="height: 260px; width: 100%;">
                    <canvas id="weeklyConversionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row g-4">
    <!-- Left Column: Transaksi Menunggu Verifikasi -->
    <div class="col-12 col-xl-7">
        <div class="humora-card h-100">
            <div class="humora-card-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="humora-card-title">TRANSAKSI MENUNGGU VERIFIKASI</div>
                    <div class="humora-card-subtitle">{{ $pendingTransactionsCount }} transaksi pending memerlukan pengecekan bukti pembayaran</div>
                </div>
                <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}" class="btn btn-sm btn-humora-secondary" style="font-size: 0.75rem;">
                    <span>Lihat Semua</span>
                    <i data-lucide="arrow-right"></i>
                </a>
            </div>
            <div class="humora-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Kode Trx</th>
                                <th>Pengguna</th>
                                <th>Paket</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th class="text-end">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingTransactions as $trx)
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark" style="font-size: 0.82rem;">{{ $trx->trx_code }}</span>
                                    <small class="d-block text-muted" style="font-size: 0.72rem;">{{ $trx->created_at?->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="sidebar-avatar-circle" style="width: 28px; height: 28px; font-size: 0.72rem;">
                                            {{ $trx->user?->initials ?? 'US' }}
                                        </div>
                                        <div class="text-truncate" style="max-width: 130px;">
                                            <div class="fw-semibold text-dark text-truncate" style="font-size: 0.8rem;">{{ $trx->user?->name ?? 'User' }}</div>
                                            <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">{{ $trx->user?->email ?? $trx->user?->phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">{{ $trx->plan?->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark" style="font-size: 0.82rem;">{{ $trx->formatted_amount }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info text-uppercase" style="font-size: 0.7rem;">{{ $trx->payment_method }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.transactions.index', ['search' => $trx->trx_code]) }}" class="btn btn-sm btn-humora-primary py-1 px-2" style="font-size: 0.75rem;">
                                        <span>Periksa</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <x-empty-state 
                                colspan="6"
                                icon="check-circle"
                                title="Semua Transaksi Selesai Diproses"
                                subtitle="Tidak ada pembayaran yang tertunda. Seluruh bukti transfer telah diverifikasi."
                                :compact="true"
                            />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Judul Konten Terbit Terbaru -->
    <div class="col-12 col-xl-5">
        <div class="humora-card h-100">
            <div class="humora-card-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="humora-card-title">KATALOG SERIES AKTIF</div>
                    <div class="humora-card-subtitle">Series yang sedang tayang di aplikasi mobile</div>
                </div>
                <a href="{{ route('admin.series.index') }}" class="btn btn-sm btn-humora-secondary" style="font-size: 0.75rem;">
                    <span>Semua Series</span>
                    <i data-lucide="arrow-right"></i>
                </a>
            </div>
            <div class="humora-card-body p-3">
                <div class="d-flex flex-column gap-3">
                    @forelse ($recentSeries as $series)
                    <div class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-white">
                        <img src="{{ $series->thumbnail_url }}" alt="{{ $series->title }}" loading="lazy" class="rounded object-fit-cover" style="width: 60px; height: 45px; aspect-ratio: 16/9;" onerror="this.src='{{ asset('images/logo-humora.png') }}'">
                        <div class="flex-grow-1 overflow-hidden">
                            <a href="{{ route('admin.series.show', $series->id) }}" class="fw-bold text-dark text-truncate d-block text-decoration-none" style="font-size: 0.85rem;">
                                {{ $series->title }}
                            </a>
                            <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.72rem;">
                                <span>{{ $series->published_episodes_count ?? $series->episodes->count() }} Episode</span>
                                <span>&bull;</span>
                                <span>Tahun {{ $series->release_year }}</span>
                                @if ($series->is_featured)
                                <span>&bull;</span>
                                <span class="badge bg-warning text-dark py-0" style="font-size: 0.65rem;">Unggulan</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.series.edit', $series->id) }}" class="btn btn-sm btn-outline-secondary p-1" title="Edit">
                            <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                        </a>
                    </div>
                    @empty
                    <x-empty-state 
                        icon="film"
                        title="Katalog Series Belum Ada"
                        subtitle="Belum ada series komedi yang dipublikasikan ke aplikasi mobile."
                        :actionUrl="route('admin.series.create')"
                        actionText="Tambah Series"
                        actionIcon="plus"
                        :compact="true"
                    />
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Daily Views Chart (Chart.js)
    const dailyCtx = document.getElementById('dailyViewsChart');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyViewLabels) !!},
                datasets: [{
                    label: 'Penayangan',
                    data: {!! json_encode($dailyViewCounts) !!},
                    borderColor: '#43135C',
                    backgroundColor: 'rgba(67, 19, 92, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#FFE600',
                    pointBorderColor: '#43135C',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleFont: { size: 12 },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMax: 5,
                        grid: { color: '#E2E8F0' },
                        ticks: { precision: 0, font: { size: 11 }, color: '#64748B' }
                    }
                }
            }
        });
    }

    // 2. Weekly Conversions Chart (Chart.js)
    const weeklyCtx = document.getElementById('weeklyConversionChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($weeklyLabels) !!},
                datasets: [{
                    label: 'Transaksi VIP Berhasil',
                    data: {!! json_encode($weeklyConversions) !!},
                    backgroundColor: '#16A34A',
                    borderRadius: 6,
                    barPercentage: 0.55,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleFont: { size: 12 },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMax: 5,
                        grid: { color: '#E2E8F0' },
                        ticks: { precision: 0, font: { size: 11 }, color: '#64748B' }
                    }
                }
            }
        });
    }
});
</script>
@endsection
