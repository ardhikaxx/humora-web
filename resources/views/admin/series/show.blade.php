@extends('layouts.admin')

@section('title', 'Detail Series - Tipe Pembeli di Toko Kelontong - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ url('/admin/series') }}" class="text-decoration-none text-muted">Series</a> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Tipe Pembeli di Toko Kelontong</span>
    </small>
</div>

<!-- Header & Action Buttons -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Tipe Pembeli di Toko Kelontong</h1>
        <span class="badge-pill-success" id="showStatusBadge"><i class="bi bi-check-circle"></i> Terpublikasi</span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/admin/series') }}" class="btn btn-humora-icon" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <button class="btn btn-humora-secondary" id="btnToggleShowStatus">
            <i class="bi bi-check2-circle text-success" id="btnToggleStatusIcon"></i>
            <span id="btnToggleStatusText">Ubah ke Draft</span>
        </button>
        <a href="{{ url('/admin/series/1/edit') }}" class="btn btn-humora-secondary">
            <i class="bi bi-pencil"></i>
            <span>Edit Series</span>
        </a>
        <button class="btn-humora-icon danger" id="btnDeleteSeries" data-confirm-delete data-item-name="Series Tipe Pembeli di Toko Kelontong" title="Hapus Series">
            <i class="bi bi-trash"></i>
        </button>
        <a href="{{ url('/admin/videos/create') }}" class="btn btn-humora-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Video</span>
        </a>
    </div>
</div>

<!-- Hero Banner Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-4">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-md-5 col-lg-4">
                <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Tipe Pembeli" class="img-fluid rounded-4 shadow-sm w-100" style="object-fit: cover; max-height: 220px;">
            </div>
            <div class="col-12 col-md-7 col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge-pill-tag">Komedi</span>
                    <span class="badge-pill-tag">Slice of Life</span>
                    <span class="badge-pill-tag">Sketsa</span>
                    <span class="text-muted" style="font-size: 0.8rem;">&bull; 2026</span>
                    <span class="text-danger fw-semibold" style="font-size: 0.8rem;"><i class="bi bi-heart-fill me-1"></i>9.6rb suka (9.600)</span>
                </div>
                <h2 class="fw-bold text-dark mb-1" style="font-size: 1.45rem;">Tipe Pembeli di Toko Kelontong</h2>
                <div class="text-muted mb-3" style="font-size: 0.82rem;">Slug: <code>/tipe-pembeli-di-toko-kelontong</code></div>
                <p class="text-muted mb-4" style="font-size: 0.88rem; line-height: 1.6;">
                    Toko kelontong Madura buka 24 jam jadi saksi bisu berbagai keajaiban dan tingkah nyeleneh para pelanggan setia di tengah malam.
                </p>
                <div class="d-flex align-items-center gap-3 text-muted" style="font-size: 0.78rem;">
                    <span><i class="bi bi-calendar-event me-1"></i> Dibuat: 18 Feb 2026</span>
                    <span>&bull;</span>
                    <span>Diperbarui: 1 Mar 2026</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardShowTotalEp"
            label="Total Episode" 
            value="4" 
            icon="bi bi-play-circle" 
            iconBg="purple" 
            subtext="Tercatat dalam series" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardShowFreeEp"
            label="Episode Gratis" 
            value="2 Episode" 
            icon="bi bi-stars" 
            iconBg="green" 
            subtext="Free Preview untuk umum" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardShowPremiumEp"
            label="Episode Premium" 
            value="2 Episode" 
            icon="bi bi-gem" 
            iconBg="purple" 
            subtext="Khusus langganan aktif" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Total Views Series" 
            value="69.750" 
            icon="bi bi-eye" 
            iconBg="orange" 
            subtext="2.415 jam ditonton" 
        />
    </div>
</div>

<!-- Episode Table Card -->
<div class="humora-card mb-0">
    <div class="humora-card-header">
        <div>
            <div class="humora-card-title" id="showEpisodeTitle">DAFTAR EPISODE (4)</div>
            <div class="humora-card-subtitle">Atur status akses Free Preview dan publikasi untuk setiap episode serial ini.</div>
        </div>
        <a href="{{ url('/admin/videos/create') }}" class="btn btn-sm btn-humora-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Episode</span>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table-humora" id="showEpisodesTable">
            <thead>
                <tr>
                    <th style="width: 40px;">EP #</th>
                    <th>Judul Episode</th>
                    <th>Durasi</th>
                    <th>Tipe Akses (Klik Toggle)</th>
                    <th>Publikasi</th>
                    <th>Views</th>
                    <th>Jumlah Suka</th>
                    <th>Completion</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <!-- Ep 1 -->
                <tr class="episode-row" data-title="Tipe Nawar Nggak Masuk Akal" data-access="free">
                    <td class="text-muted fw-bold">1</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb" style="width: 52px; height: 52px;">
                            <div>
                                <div class="fw-bold text-dark">Tipe Nawar Nggak Masuk Akal</div>
                                <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">Beli mie instan sebungkus minta diskon grosir, rokok eceran minta korek gratisan</small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-medium text-dark">2m 04s</td>
                    <td>
                        <span class="badge-pill-free badge-interactive" title="Klik untuk ubah tipe akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>2.900</span></td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Episode 1: Tipe Nawar Nggak Masuk Akal" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Ep 2 -->
                <tr class="episode-row" data-title="Tipe Salah Masuk Toko" data-access="free">
                    <td class="text-muted fw-bold">2</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Ep 2" class="thumbnail-table-thumb" style="width: 52px; height: 52px;">
                            <div>
                                <div class="fw-bold text-dark">Tipe Salah Masuk Toko</div>
                                <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">Niatnya nyari minimarket ber-AC buat ngadem, pas sadar malah nongkrong di warung Madura</small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-medium text-dark">2m 10s</td>
                    <td>
                        <span class="badge-pill-free badge-interactive" title="Klik untuk ubah tipe akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">21.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>2.500</span></td>
                    <td><span class="fw-bold text-success">89%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Episode 2: Tipe Salah Masuk Toko" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Ep 3 -->
                <tr class="episode-row" data-title="Tipe Curhat Panjang Lebar" data-access="premium">
                    <td class="text-muted fw-bold">3</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Ep 3" class="thumbnail-table-thumb" style="width: 52px; height: 52px;">
                            <div>
                                <div class="fw-bold text-dark">Tipe Curhat Panjang Lebar</div>
                                <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">Tujuan awal cuma beli obat nyamuk bakar, tapi malah curhat masalah rumah tangga dan tetangga ke penjaga warung</small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-medium text-dark">2m 22s</td>
                    <td>
                        <span class="badge-pill-premium badge-interactive" title="Klik untuk ubah tipe akses"><i class="bi bi-gem"></i> Premium</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">13.500</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>2.200</span></td>
                    <td><span class="fw-bold text-success">84%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Episode 3: Tipe Curhat Panjang Lebar" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Ep 4 -->
                <tr class="episode-row" data-title="Tipe Ngutang Dulu" data-access="premium">
                    <td class="text-muted fw-bold">4</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Ep 4" class="thumbnail-table-thumb" style="width: 52px; height: 52px;">
                            <div>
                                <div class="fw-bold text-dark">Tipe Ngutang Dulu</div>
                                <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">Sudah selesai ditotal di kalkulator, tiba-tiba pasang tampang memelas sambil bilang "Catet di buku dulu ya bang, besok"</small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-medium text-dark">2m 16s</td>
                    <td>
                        <span class="badge-pill-premium badge-interactive" title="Klik untuk ubah tipe akses"><i class="bi bi-gem"></i> Premium</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">11.250</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>2.000</span></td>
                    <td><span class="fw-bold text-success">80%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Episode 4: Tipe Ngutang Dulu" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let isPublished = true;
        const statusBadge = document.getElementById('showStatusBadge');
        const btnToggleStatus = document.getElementById('btnToggleShowStatus');
        const btnText = document.getElementById('btnToggleStatusText');
        const btnIcon = document.getElementById('btnToggleStatusIcon');

        btnToggleStatus.addEventListener('click', function () {
            if (isPublished) {
                isPublished = false;
                statusBadge.className = 'badge-pill-warning';
                statusBadge.innerHTML = '<i class="bi bi-clock"></i> Draft';
                btnText.innerText = 'Terbitkan Series';
                btnIcon.className = 'bi bi-check-circle text-success';
                window.showHumoraToast('Series diubah menjadi Draft.', 'warning');
            } else {
                isPublished = true;
                statusBadge.className = 'badge-pill-success';
                statusBadge.innerHTML = '<i class="bi bi-check-circle"></i> Terpublikasi';
                btnText.innerText = 'Ubah ke Draft';
                btnIcon.className = 'bi bi-clock text-warning';
                window.showHumoraToast('Series berhasil dipublikasikan!', 'success');
            }
        });
    });
</script>
@endsection
