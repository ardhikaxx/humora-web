@extends('layouts.admin')

@section('title', 'Judul Konten & Video - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs & Header -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i> <span class="text-dark fw-semibold">Judul Konten</span>
    </small>
</div>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Judul Konten & Video</h1>
        <p class="page-subtitle mb-0">Kelola seluruh judul konten besar dan video yang tampil dalam katalog mobile HUMORA.</p>
    </div>
    <a href="{{ url('/admin/series/create') }}" class="btn btn-humora-primary">
        <i data-lucide="plus"></i>
        <span>Tambah Judul Baru</span>
    </a>
</div>

<!-- 4 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardTotalSeries"
            label="Total Judul" 
            value="6" 
            icon="tv" 
            iconBg="purple" 
            subtext="Seluruh katalog lokal" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardPublishedSeries"
            label="Judul Terbit" 
            value="6" 
            icon="check-circle" 
            iconBg="green" 
            subtext="Tampil di mobile app" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardDraftSeries"
            label="Series Draf" 
            value="0" 
            icon="clock" 
            iconBg="orange" 
            subtext="Belum dipublikasikan" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Series Unggulan" 
            value="1" 
            icon="sparkles" 
            iconBg="yellow" 
            subtext="Spotlight hero banner" 
        />
    </div>
</div>

<!-- Filter & Search Bar -->
<div class="humora-card mb-4 p-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-6 col-lg-7">
            <div class="search-input-wrap">
                <i data-lucide="search"></i>
                <input type="text" id="seriesSearchInput" class="form-control-custom bg-light" placeholder="Cari judul series atau sinopsis...">
            </div>
        </div>
        <div class="col-6 col-md-2 col-lg-2">
            <select class="form-select-custom" id="seriesGenreFilter">
                <option selected>Semua Genre</option>
                <option>Komedi</option>
                <option>Romantis</option>
                <option>Drama</option>
                <option>Slice of Life</option>
                <option>Workplace</option>
            </select>
        </div>
        <div class="col-6 col-md-2 col-lg-1">
            <select class="form-select-custom" id="seriesStatusFilter">
                <option selected>Semua Status</option>
                <option>Terpublikasi</option>
                <option>Draft</option>
            </select>
        </div>
        <div class="col-12 col-md-2 col-lg-2">
            <select class="form-select-custom" id="seriesSortFilter">
                <option value="newest" selected>Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="likes">Suka Terbanyak</option>
            </select>
        </div>
    </div>
</div>

<!-- Series Table Card -->
<div class="humora-card mb-0">
    <div class="table-responsive">
        <table class="table-humora" id="seriesTable">
            <thead>
                <tr>
                    <th>Series</th>
                    <th>Genre / Kategori</th>
                    <th>Total Episode</th>
                    <th>Akses Episode</th>
                    <th>Status</th>
                    <th>Jumlah Suka</th>
                    <th>Diperbarui</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 1 -->
                <tr class="series-row"
                    data-title="Tipe Pembeli di Toko Kelontong"
                    data-genre="Komedi, Slice of Life, Sketsa"
                    data-status="Terpublikasi"
                    data-likes="9600"
                    data-date="2026-03-01">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Tipe Pembeli" class="thumbnail-table-thumb" style="width: 54px; height: 54px;">
                            <div>
                                <a href="{{ url('/admin/series/1') }}" class="text-decoration-none fw-bold text-dark d-block series-title-link">Tipe Pembeli di Toko Kelont...</a>
                                <small class="text-muted">/tipe-pembeli-di-toko-kelontong</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge-pill-tag">Komedi</span>
                            <span class="badge-pill-tag">Slice of Life</span>
                            <span class="badge-pill-tag">Sketsa</span>
                        </div>
                    </td>
                    <td><span class="fw-bold text-dark">4 Ep</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge-pill-free" style="font-size: 0.7rem;">2 Free</span>
                            <span class="badge-pill-premium" style="font-size: 0.7rem;">2 Premium</span>
                        </div>
                    </td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Terpublikasi</span></td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>9.600</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">1 Mar 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/series/1') }}" class="btn-humora-icon" title="Lihat Detail"><i data-lucide="eye"></i></a>
                            <a href="{{ url('/admin/series/1/edit') }}" class="btn-humora-icon" title="Edit"><i data-lucide="pencil"></i></a>
                            <button class="btn-humora-icon btn-toggle-series-status" title="Toggle Status (Terpublikasi/Draft)"><i data-lucide="check-circle"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Series Tipe Pembeli di Toko Kelontong" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="series-row"
                    data-title="Momen Canggung Kencan Pertama"
                    data-genre="Romantis, Komedi, Sketsa"
                    data-status="Terpublikasi"
                    data-likes="16700"
                    data-date="2026-03-04">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/momen-canggung-kencan-pertama.jpg') }}" alt="Momen Canggung" class="thumbnail-table-thumb" style="width: 54px; height: 54px;">
                            <div>
                                <a href="{{ url('/admin/series/1') }}" class="text-decoration-none fw-bold text-dark d-block series-title-link">Momen Canggung Kencan P...</a>
                                <small class="text-muted">/momen-canggung-kencan-pertama</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge-pill-tag">Romantis</span>
                            <span class="badge-pill-tag">Komedi</span>
                            <span class="badge-pill-tag">Sketsa</span>
                        </div>
                    </td>
                    <td><span class="fw-bold text-dark">4 Ep</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge-pill-free" style="font-size: 0.7rem;">2 Free</span>
                            <span class="badge-pill-premium" style="font-size: 0.7rem;">2 Premium</span>
                        </div>
                    </td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Terpublikasi</span></td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>16.700</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">4 Mar 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/series/1') }}" class="btn-humora-icon" title="Lihat Detail"><i data-lucide="eye"></i></a>
                            <a href="{{ url('/admin/series/1/edit') }}" class="btn-humora-icon" title="Edit"><i data-lucide="pencil"></i></a>
                            <button class="btn-humora-icon btn-toggle-series-status" title="Toggle Status (Terpublikasi/Draft)"><i data-lucide="check-circle"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Series Momen Canggung Kencan Pertama" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 3 -->
                <tr class="series-row"
                    data-title="Drama Warga Grup RT"
                    data-genre="Komedi, Slice of Life, Sketsa"
                    data-status="Terpublikasi"
                    data-likes="18900"
                    data-date="2026-03-03">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/drama-warga-grup-rt.jpg') }}" alt="Drama Warga" class="thumbnail-table-thumb" style="width: 54px; height: 54px;">
                            <div>
                                <a href="{{ url('/admin/series/1') }}" class="text-decoration-none fw-bold text-dark d-block series-title-link">Drama Warga Grup RT</a>
                                <small class="text-muted">/drama-warga-grup-rt</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge-pill-tag">Komedi</span>
                            <span class="badge-pill-tag">Slice of Life</span>
                            <span class="badge-pill-tag">Sketsa</span>
                        </div>
                    </td>
                    <td><span class="fw-bold text-dark">4 Ep</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge-pill-free" style="font-size: 0.7rem;">2 Free</span>
                            <span class="badge-pill-premium" style="font-size: 0.7rem;">2 Premium</span>
                        </div>
                    </td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Terpublikasi</span></td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>18.900</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">3 Mar 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/series/1') }}" class="btn-humora-icon" title="Lihat Detail"><i data-lucide="eye"></i></a>
                            <a href="{{ url('/admin/series/1/edit') }}" class="btn-humora-icon" title="Edit"><i data-lucide="pencil"></i></a>
                            <button class="btn-humora-icon btn-toggle-series-status" title="Toggle Status (Terpublikasi/Draft)"><i data-lucide="check-circle"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Series Drama Warga Grup RT" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 4 -->
                <tr class="series-row"
                    data-title="Tipe-Tipe Teman Sekantor"
                    data-genre="Komedi, Workplace, Sketsa"
                    data-status="Terpublikasi"
                    data-likes="12400"
                    data-date="2026-03-02">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/tipe-tipe-teman-sekantor.jpg') }}" alt="Teman Sekantor" class="thumbnail-table-thumb" style="width: 54px; height: 54px;">
                            <div>
                                <a href="{{ url('/admin/series/1') }}" class="text-decoration-none fw-bold text-dark d-block series-title-link">Tipe-Tipe Teman Sekantor</a>
                                <small class="text-muted">/tipe-tipe-teman-sekantor</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge-pill-tag">Komedi</span>
                            <span class="badge-pill-tag">Workplace</span>
                            <span class="badge-pill-tag">Sketsa</span>
                        </div>
                    </td>
                    <td><span class="fw-bold text-dark">4 Ep</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge-pill-free" style="font-size: 0.7rem;">2 Free</span>
                            <span class="badge-pill-premium" style="font-size: 0.7rem;">2 Premium</span>
                        </div>
                    </td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Terpublikasi</span></td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>12.400</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">2 Mar 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/series/1') }}" class="btn-humora-icon" title="Lihat Detail"><i data-lucide="eye"></i></a>
                            <a href="{{ url('/admin/series/1/edit') }}" class="btn-humora-icon" title="Edit"><i data-lucide="pencil"></i></a>
                            <button class="btn-humora-icon btn-toggle-series-status" title="Toggle Status (Terpublikasi/Draft)"><i data-lucide="check-circle"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Series Tipe-Tipe Teman Sekantor" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 5 -->
                <tr class="series-row"
                    data-title="Cara Deketin Cewek"
                    data-genre="Romantis, Komedi, Sketsa"
                    data-status="Terpublikasi"
                    data-likes="21500"
                    data-date="2026-03-05">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/cara-deketin-cewek.jpg') }}" alt="Cara Deketin" class="thumbnail-table-thumb" style="width: 54px; height: 54px;">
                            <div>
                                <a href="{{ url('/admin/series/1') }}" class="text-decoration-none fw-bold text-dark d-block series-title-link">Cara Deketin Cewek</a>
                                <small class="text-muted">/cara-deketin-cewek</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge-pill-tag">Romantis</span>
                            <span class="badge-pill-tag">Komedi</span>
                            <span class="badge-pill-tag">Sketsa</span>
                        </div>
                    </td>
                    <td><span class="fw-bold text-dark">4 Ep</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge-pill-free" style="font-size: 0.7rem;">2 Free</span>
                            <span class="badge-pill-premium" style="font-size: 0.7rem;">2 Premium</span>
                        </div>
                    </td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Terpublikasi</span></td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>21.500</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">5 Mar 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/series/1') }}" class="btn-humora-icon" title="Lihat Detail"><i data-lucide="eye"></i></a>
                            <a href="{{ url('/admin/series/1/edit') }}" class="btn-humora-icon" title="Edit"><i data-lucide="pencil"></i></a>
                            <button class="btn-humora-icon btn-toggle-series-status" title="Toggle Status (Terpublikasi/Draft)"><i data-lucide="check-circle"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Series Cara Deketin Cewek" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Row 6 (Unggulan) -->
                <tr class="series-row"
                    data-title="Tipe Penumpang Taxi Online"
                    data-genre="Komedi, Sketsa, Slice of Life"
                    data-status="Terpublikasi"
                    data-likes="14200"
                    data-date="2026-03-05">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/thumbnails/tipe-penumpang-taxi-online.jpg') }}" alt="Taxi Online" class="thumbnail-table-thumb" style="width: 54px; height: 54px;">
                            <div>
                                <a href="{{ url('/admin/series/1') }}" class="text-decoration-none fw-bold text-dark d-block series-title-link">Tipe Penumpang Taxi Online</a>
                                <small class="text-muted d-block">/tipe-penumpang-taxi-online</small>
                                <span class="badge mt-1" style="background-color: #FEF08A; color: #854D0E; font-size: 0.68rem; font-weight: 700;">Unggulan</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge-pill-tag">Komedi</span>
                            <span class="badge-pill-tag">Sketsa</span>
                            <span class="badge-pill-tag">Slice of Life</span>
                        </div>
                    </td>
                    <td><span class="fw-bold text-dark">4 Ep</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge-pill-free" style="font-size: 0.7rem;">2 Free</span>
                            <span class="badge-pill-premium" style="font-size: 0.7rem;">2 Premium</span>
                        </div>
                    </td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Terpublikasi</span></td>
                    <td><span class="text-danger fw-semibold table-stat-likes"><i data-lucide="heart"></i>14.200</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">5 Mar 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/series/1') }}" class="btn-humora-icon" title="Lihat Detail"><i data-lucide="eye"></i></a>
                            <a href="{{ url('/admin/series/1/edit') }}" class="btn-humora-icon" title="Edit"><i data-lucide="pencil"></i></a>
                            <button class="btn-humora-icon btn-toggle-series-status" title="Toggle Status (Terpublikasi/Draft)"><i data-lucide="check-circle"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Series Tipe Penumpang Taxi Online" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top gap-2">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted" id="seriesPaginationCounter" style="font-size: 0.8rem;">Menampilkan <strong>1 - 6</strong> dari <strong>6</strong> data</small>
            <div class="d-flex align-items-center gap-1 ms-3">
                <small class="text-muted" style="font-size: 0.8rem;">Baris:</small>
                <select class="form-select-custom py-1 px-2" style="width: 70px; font-size: 0.78rem;">
                    <option selected>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
            </div>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#"><i data-lucide="chevron-left"></i></a></li>
                <li class="page-item active"><a class="page-link" href="#" style="background-color: #43135C; border-color: #43135C;">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#"><i data-lucide="chevron-right"></i></a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let publishedCount = 6;
        let draftCount = 0;
        let totalCount = 6;

        const cardPublishedVal = document.querySelector('#cardPublishedSeries .stat-value');
        const cardDraftVal = document.querySelector('#cardDraftSeries .stat-value');
        const cardTotalVal = document.querySelector('#cardTotalSeries .stat-value');
        const sortFilter = document.getElementById('seriesSortFilter');
        const tableBody = document.querySelector('#seriesTable tbody');

        // Toggle Status handler
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-toggle-series-status');
            if (btn) {
                e.preventDefault();
                const row = btn.closest('tr');
                if (!row) return;

                const statusCell = row.querySelector('.status-cell');
                const title = row.dataset.title;
                const isPublished = row.dataset.status === 'Terpublikasi';

                if (isPublished) {
                    row.dataset.status = 'Draft';
                    if (statusCell) {
                        statusCell.innerHTML = '<span class="badge-pill-warning"><i data-lucide="clock"></i> Draft</span>';
                    }
                    publishedCount = Math.max(0, publishedCount - 1);
                    draftCount += 1;
                    window.showHumoraToast(`Series "${title}" diubah menjadi Draft.`, 'warning');
                } else {
                    row.dataset.status = 'Terpublikasi';
                    if (statusCell) {
                        statusCell.innerHTML = '<span class="badge-pill-success"><i data-lucide="check-circle"></i> Terpublikasi</span>';
                    }
                    publishedCount += 1;
                    draftCount = Math.max(0, draftCount - 1);
                    window.showHumoraToast(`Series "${title}" berhasil diterbitkan!`, 'success');
                }

                if (cardPublishedVal) cardPublishedVal.innerText = publishedCount;
                if (cardDraftVal) cardDraftVal.innerText = draftCount;
            }
        });

        // Sorting handler
        if (sortFilter && tableBody) {
            sortFilter.addEventListener('change', function () {
                const sortType = this.value;
                const rows = Array.from(tableBody.querySelectorAll('tr.series-row'));

                rows.sort((a, b) => {
                    if (sortType === 'likes') {
                        return parseInt(b.dataset.likes || 0) - parseInt(a.dataset.likes || 0);
                    } else if (sortType === 'oldest') {
                        return (a.dataset.date || '').localeCompare(b.dataset.date || '');
                    } else { // newest
                        return (b.dataset.date || '').localeCompare(a.dataset.date || '');
                    }
                });

                rows.forEach(r => tableBody.appendChild(r));
                window.showHumoraToast(`Urutan diubah: ${this.options[this.selectedIndex].text}`);
            });
        }
    });
</script>
@endsection
