@extends('layouts.admin')

@section('title', 'Analytics & Performa - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs & Header -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i> <span class="text-dark fw-semibold">Analytics</span>
    </small>
</div>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Analytics & Performa</h1>
        <p class="page-subtitle mb-0">Analisis metrik penayangan aktual, penonton unik, rata-rata durasi, rasio konversi, dan konten terpopuler.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2 align-self-start align-self-md-auto">
        <div class="badge bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-1.5 px-3 py-2 rounded-pill shadow-2xs" style="font-size: 0.76rem;">
            <span class="spinner-grow spinner-grow-sm text-success" style="width: 7px; height: 7px;" role="status"></span>
            <span class="fw-bold">Sinkron Realtime</span>
            <span class="text-muted ms-1" style="font-size: 0.7rem;" id="liveTimeBadge">({{ now()->format('H:i:s') }})</span>
        </div>
        <button type="button" id="btnRefreshAnalytics" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-pill px-3 py-1.5 shadow-2xs" style="font-size: 0.76rem;" title="Perbarui Data Sekarang">
            <i data-lucide="refresh-cw" style="width: 13px; height: 13px;" id="iconRefresh"></i>
            <span>Refresh</span>
        </button>
        <div class="btn-filter-group ms-md-2">
            <a href="{{ route('admin.analytics', ['range' => 7]) }}" class="btn-filter-pill {{ $days === 7 ? 'active' : '' }}">
                7 Hari
            </a>
            <a href="{{ route('admin.analytics', ['range' => 30]) }}" class="btn-filter-pill {{ $days === 30 ? 'active' : '' }}">
                30 Hari
            </a>
            <a href="{{ route('admin.analytics', ['range' => 90]) }}" class="btn-filter-pill {{ $days === 90 ? 'active' : '' }}">
                90 Hari
            </a>
        </div>
    </div>
</div>

<!-- 5 Real Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            id="cardTotalViews"
            label="Total Views" 
            :value="number_format($totalViews, 0, ',', '.')" 
            icon="eye" 
            iconBg="purple" 
            :subtext="'Rentang ' . $days . ' hari'" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            id="cardUniqueViewers"
            label="Penonton Unik" 
            :value="number_format($uniqueViewers, 0, ',', '.')" 
            icon="users" 
            iconBg="blue" 
            subtext="User ID / IP unik" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <x-stat-card 
            id="cardWatchTime"
            label="Total Watch Time" 
            :value="$formattedWatchTime" 
            icon="clock" 
            iconBg="orange" 
            subtext="Durasi tonton tercatat" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <x-stat-card 
            id="cardAvgDuration"
            label="Rata-rata Durasi" 
            :value="$formattedAvgDuration" 
            icon="trending-up" 
            iconBg="green" 
            subtext="Per pemutaran video" 
        />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-stat-card 
            id="cardConversionRate"
            label="Rasio Konversi" 
            :value="$conversionRate . '%'" 
            badge="VIP"
            icon="gem" 
            iconBg="yellow" 
            subtext="Trx sukses / user baru" 
        />
    </div>
</div>

<!-- Viewing Events Chart -->
<div class="humora-card mb-4">
    <div class="humora-card-header d-flex justify-content-between align-items-center">
        <div>
            <div class="humora-card-title">GRAFIK TREN PENAYANGAN KONTEN</div>
            <div class="humora-card-subtitle">Frekuensi penayangan video sketsa komedi selama {{ $days }} hari terakhir (sumber: ViewingEvent)</div>
        </div>
        <span id="badgeChartTotalViews" class="badge bg-purple-subtle text-purple px-3 py-1 rounded-pill" style="font-size: 0.75rem;">
            Total: {{ number_format($totalViews, 0, ',', '.') }} views
        </span>
    </div>
    <div class="humora-card-body p-4">
        <div style="height: 300px; width: 100%;">
            <canvas id="analyticsChart"></canvas>
        </div>
    </div>
</div>

<!-- 2 Tables: Top Series & Top Episodes by Likes -->
<div class="row g-4">
    <!-- Left Column: Series Penayangan Tertinggi -->
    <div class="col-12 col-lg-6">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">SERIES DENGAN PENAYANGAN TERTINGGI</div>
                    <div class="humora-card-subtitle">Berdasarkan total event penayangan dalam {{ $days }} hari</div>
                </div>
            </div>
            <div class="humora-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Series</th>
                                <th>Genre</th>
                                <th class="text-end">Views</th>
                            </tr>
                        </thead>
                        <tbody id="tableTopSeriesBody">
                            @forelse ($topSeries as $index => $item)
                            <tr>
                                <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="rounded" style="width: 44px; height: 32px; object-fit: cover;" onerror="this.src='{{ asset('images/logo-humora.png') }}'">
                                        <div class="text-truncate" style="max-width: 200px;">
                                            <a href="{{ route('admin.series.show', $item->id) }}" class="fw-bold text-dark text-decoration-none text-truncate d-block" style="font-size: 0.82rem;">
                                                {{ $item->title }}
                                            </a>
                                            <small class="text-muted" style="font-size: 0.72rem;">{{ $item->release_year }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">
                                        {{ $item->categories->first()?->name ?? 'Komedi' }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-purple" style="font-size: 0.85rem;">
                                    {{ number_format($item->viewing_events_count ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted" style="font-size: 0.85rem;">
                                    Belum ada data penayangan tercatat dalam periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Episode Paling Banyak Disukai -->
    <div class="col-12 col-lg-6">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">EPISODE PALING BANYAK DISUKAI</div>
                    <div class="humora-card-subtitle">Berdasarkan total suka (likes) pengguna terdaftar</div>
                </div>
            </div>
            <div class="humora-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Episode</th>
                                <th>Akses</th>
                                <th class="text-end">Jumlah Suka</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topLikedEpisodes as $idx => $ep)
                            <tr>
                                <td class="fw-bold text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="text-truncate" style="max-width: 220px;">
                                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.82rem;">
                                            Ep {{ $ep->episode_number }}: {{ $ep->title }}
                                        </div>
                                        <small class="text-muted text-truncate d-block" style="font-size: 0.72rem;">
                                            {{ $ep->series?->title ?? '-' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if ($ep->is_free)
                                        <span class="badge bg-success-subtle text-success py-1" style="font-size: 0.7rem;">Gratis</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis py-1" style="font-size: 0.7rem;">Premium</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-danger" style="font-size: 0.85rem;">
                                    <i data-lucide="heart" class="me-1 d-inline" style="width: 14px; height: 14px;"></i>
                                    {{ number_format($ep->likes_count, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted" style="font-size: 0.85rem;">
                                    Belum ada data likes tercatat pada episode.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
@keyframes spinClockwise {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.spin-animation {
    animation: spinClockwise 0.8s linear infinite;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('analyticsChart');
    if (ctx) {
        window.analyticsChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Penayangan',
                    data: {!! json_encode($chartViews) !!},
                    backgroundColor: '#43135C',
                    borderRadius: 4,
                    barPercentage: 0.65,
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
                        grid: { color: '#E2E8F0' },
                        ticks: { precision: 0, font: { size: 11 }, color: '#64748B' }
                    }
                }
            }
        });
    }

    let isFetching = false;
    function updateCardValue(cardSelector, newValue) {
        const el = document.querySelector(cardSelector + ' .stat-value');
        if (el && el.textContent.trim() !== newValue.toString().trim()) {
            el.textContent = newValue;
            el.style.transition = 'color 0.3s ease, transform 0.3s ease';
            el.style.color = '#7028B8';
            el.style.transform = 'scale(1.08)';
            setTimeout(() => {
                el.style.color = '';
                el.style.transform = 'scale(1)';
            }, 800);
        }
    }

    function pollRealtimeAnalytics() {
        if (isFetching) return;
        isFetching = true;
        const icon = document.getElementById('iconRefresh');
        if (icon) icon.classList.add('spin-animation');

        fetch('{{ route('admin.analytics.realtime') }}?range={{ $days }}')
            .then(res => res.json())
            .then(json => {
                if (json.success && json.data) {
                    const d = json.data;
                    updateCardValue('#cardTotalViews', d.totalViewsFormatted);
                    updateCardValue('#cardUniqueViewers', d.uniqueViewersFormatted);
                    updateCardValue('#cardWatchTime', d.formattedWatchTime);
                    updateCardValue('#cardAvgDuration', d.formattedAvgDuration);
                    updateCardValue('#cardConversionRate', d.conversionRate + '%');

                    const badgeChart = document.getElementById('badgeChartTotalViews');
                    if (badgeChart) badgeChart.textContent = 'Total: ' + d.totalViewsFormatted + ' views';

                    const liveTime = document.getElementById('liveTimeBadge');
                    if (liveTime) liveTime.textContent = '(' + d.lastUpdated + ')';

                    // Update Chart smoothly
                    if (window.analyticsChartInstance && d.chartLabels && d.chartViews) {
                        window.analyticsChartInstance.data.labels = d.chartLabels;
                        window.analyticsChartInstance.data.datasets[0].data = d.chartViews;
                        window.analyticsChartInstance.update();
                    }

                    // Update Top Series Table
                    const tbody = document.getElementById('tableTopSeriesBody');
                    if (tbody && d.topSeries) {
                        if (d.topSeries.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted" style="font-size: 0.85rem;">Belum ada data penayangan tercatat dalam periode ini.</td></tr>';
                        } else {
                            tbody.innerHTML = d.topSeries.map(item => `
                                <tr>
                                    <td class="fw-bold text-muted">${item.rank}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="${item.thumbnail_url}" alt="${item.title}" class="rounded" style="width: 44px; height: 32px; object-fit: cover;" onerror="this.src='/images/logo-humora.png'">
                                            <div class="text-truncate" style="max-width: 200px;">
                                                <a href="${item.url}" class="fw-bold text-dark text-decoration-none text-truncate d-block" style="font-size: 0.82rem;">
                                                    ${item.title}
                                                </a>
                                                <small class="text-muted" style="font-size: 0.72rem;">${item.release_year}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">
                                            ${item.genre}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-purple" style="font-size: 0.85rem;">
                                        ${item.views_formatted}
                                    </td>
                                </tr>
                            `).join('');
                        }
                    }
                }
            })
            .catch(err => console.error('Realtime analytics sync error:', err))
            .finally(() => {
                isFetching = false;
                if (icon) icon.classList.remove('spin-animation');
            });
    }

    // Auto-poll every 4 seconds for live sync with mobile viewing activity
    const pollInterval = setInterval(pollRealtimeAnalytics, 4000);
    window.addEventListener('beforeunload', () => clearInterval(pollInterval));

    const btnRefresh = document.getElementById('btnRefreshAnalytics');
    if (btnRefresh) {
        btnRefresh.addEventListener('click', pollRealtimeAnalytics);
    }
});
</script>
@endsection
