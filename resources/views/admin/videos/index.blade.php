@extends('layouts.admin')

@section('title', 'Kelola Video Konten - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs & Header -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i> <span class="text-dark fw-semibold">Kelola Video</span>
    </small>
</div>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Kelola Video Konten</h1>
        <p class="page-subtitle mb-0">Kelola seluruh video sketsa (~+2 menit) berdasarkan judul besar, aturan akses Free Preview vs Premium, serta status publikasi.</p>
    </div>
    <a href="{{ url('/admin/videos/create') }}" class="btn btn-humora-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Video Baru</span>
    </a>
</div>

<!-- 4 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardTotalVideos"
            label="Total Video" 
            value="24" 
            icon="bi bi-camera-video" 
            iconBg="purple" 
            subtext="Seluruh video terdaftar" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardFreeVideos"
            label="Free Preview" 
            value="12 Video" 
            icon="bi bi-stars" 
            iconBg="green" 
            subtext="Gratis tanpa langganan" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardPremiumVideos"
            label="Episode Premium" 
            value="12 Ep" 
            icon="bi bi-gem" 
            iconBg="purple" 
            subtext="Terkunci untuk subscriber" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardPublishedVideos"
            label="Status Terbit" 
            value="24 Terbit" 
            icon="bi bi-check-circle" 
            iconBg="blue" 
            subtext="0 masih draf" 
        />
    </div>
</div>

<!-- Search & Filters -->
<div class="humora-card mb-4 p-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-4 col-lg-5">
            <div class="search-input-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="videoSearchInput" class="form-control-custom bg-light" placeholder="Cari judul episode atau nama series...">
            </div>
        </div>
        <div class="col-6 col-md-2 col-lg-2">
            <select class="form-select-custom" id="videoSeriesFilter">
                <option selected>Semua Series</option>
                <option>Tipe Penumpang Taxi Online</option>
                <option>Cara Deketin Cewek</option>
                <option>Tipe-Tipe Teman Sekantor</option>
                <option>Drama Warga Grup RT</option>
                <option>Momen Canggung Kencan Pertama</option>
                <option>Tipe Pembeli di Toko Kelontong</option>
            </select>
        </div>
        <div class="col-6 col-md-2 col-lg-2">
            <select class="form-select-custom" id="videoAccessFilter">
                <option selected>Semua Akses</option>
                <option>Free Preview</option>
                <option>Premium</option>
            </select>
        </div>
        <div class="col-6 col-md-2 col-lg-1">
            <select class="form-select-custom" id="videoPubFilter">
                <option selected>Semua Publikasi</option>
                <option>Terpublikasi</option>
                <option>Draft</option>
            </select>
        </div>
        <div class="col-6 col-md-2 col-lg-2">
            <select class="form-select-custom" id="videoSortFilter">
                <option value="views" selected>Views Terbanyak</option>
                <option value="newest">Terbaru</option>
                <option value="likes">Paling Disukai</option>
            </select>
        </div>
    </div>
</div>

<!-- Videos Table Card -->
<div class="humora-card mb-0">
    <div class="table-responsive">
        <table class="table-humora" id="videosTable">
            <thead>
                <tr>
                    <th>Episode & Series</th>
                    <th>No.</th>
                    <th>Durasi</th>
                    <th>Tipe Akses (Klik Toggle)</th>
                    <th>Status Publikasi</th>
                    <th>Penayangan</th>
                    <th>Jumlah Suka</th>
                    <th>Completion</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1 -->
                <tr class="video-row"
                    data-title="Tipe Bawel"
                    data-series="Tipe Penumpang Taxi Online"
                    data-access="free"
                    data-pub="Terpublikasi"
                    data-views="24000"
                    data-likes="4250"
                    data-date="2026-02-10">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-penumpang-taxi-online.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Tipe Bawel</div>
                                <small class="text-muted video-series">Tipe Penumpang Taxi Online</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#1</td>
                    <td class="fw-medium text-dark">2m 14s</td>
                    <td class="access-cell">
                        <span class="badge-pill-free badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>4.250</span></td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Tipe Bawel" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 2 -->
                <tr class="video-row"
                    data-title="Pake Hadiah"
                    data-series="Cara Deketin Cewek"
                    data-access="free"
                    data-pub="Terpublikasi"
                    data-views="24000"
                    data-likes="5600"
                    data-date="2026-02-12">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/cara-deketin-cewek.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Pake Hadiah</div>
                                <small class="text-muted video-series">Cara Deketin Cewek</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#1</td>
                    <td class="fw-medium text-dark">2m 10s</td>
                    <td class="access-cell">
                        <span class="badge-pill-free badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>5.600</span></td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Pake Hadiah" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 3 -->
                <tr class="video-row"
                    data-title="Tipe Cari Muka"
                    data-series="Tipe-Tipe Teman Sekantor"
                    data-access="free"
                    data-pub="Terpublikasi"
                    data-views="24000"
                    data-likes="3800"
                    data-date="2026-02-14">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-tipe-teman-sekantor.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Tipe Cari Muka</div>
                                <small class="text-muted video-series">Tipe-Tipe Teman Sekantor</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#1</td>
                    <td class="fw-medium text-dark">2m 12s</td>
                    <td class="access-cell">
                        <span class="badge-pill-free badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>3.800</span></td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Tipe Cari Muka" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 4 -->
                <tr class="video-row"
                    data-title="Iuran Sampah Naik 5 Ribu"
                    data-series="Drama Warga Grup RT"
                    data-access="free"
                    data-pub="Terpublikasi"
                    data-views="24000"
                    data-likes="6100"
                    data-date="2026-02-15">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/drama-warga-grup-rt.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Iuran Sampah Naik 5 Ribu</div>
                                <small class="text-muted video-series">Drama Warga Grup RT</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#1</td>
                    <td class="fw-medium text-dark">2m 08s</td>
                    <td class="access-cell">
                        <span class="badge-pill-free badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>6.100</span></td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Iuran Sampah Naik 5 Ribu" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 5 -->
                <tr class="video-row"
                    data-title="Salah Sebut Nama Teman Kencan"
                    data-series="Momen Canggung Kencan Pertama"
                    data-access="free"
                    data-pub="Terpublikasi"
                    data-views="24000"
                    data-likes="4900"
                    data-date="2026-02-16">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/momen-canggung-kencan-pertama.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Salah Sebut Nama Teman Kencan</div>
                                <small class="text-muted video-series">Momen Canggung Kencan Pertama</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#1</td>
                    <td class="fw-medium text-dark">2m 11s</td>
                    <td class="access-cell">
                        <span class="badge-pill-free badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>4.900</span></td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Salah Sebut Nama Teman Kencan" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 6 -->
                <tr class="video-row"
                    data-title="Tipe Nawar Nggak Masuk Akal"
                    data-series="Tipe Pembeli di Toko Kelontong"
                    data-access="free"
                    data-pub="Terpublikasi"
                    data-views="24000"
                    data-likes="2900"
                    data-date="2026-02-18">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Ep 1" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Tipe Nawar Nggak Masuk Akal</div>
                                <small class="text-muted video-series">Tipe Pembeli di Toko Kelontong</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#1</td>
                    <td class="fw-medium text-dark">2m 04s</td>
                    <td class="access-cell">
                        <span class="badge-pill-free badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">24.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>2.900</span></td>
                    <td><span class="fw-bold text-success">92%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Tipe Nawar Nggak Masuk Akal" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 7 -->
                <tr class="video-row"
                    data-title="Tipe Navigator Handal"
                    data-series="Tipe Penumpang Taxi Online"
                    data-access="premium"
                    data-pub="Terpublikasi"
                    data-views="22500"
                    data-likes="3900"
                    data-date="2026-02-20">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-penumpang-taxi-online.jpg') }}" alt="Ep 2" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Tipe Navigator Handal</div>
                                <small class="text-muted video-series">Tipe Penumpang Taxi Online</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#2</td>
                    <td class="fw-medium text-dark">2m 18s</td>
                    <td class="access-cell">
                        <span class="badge-pill-premium badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-gem"></i> Premium</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">22.500</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>3.900</span></td>
                    <td><span class="fw-bold text-success">90%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Tipe Navigator Handal" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 8 -->
                <tr class="video-row"
                    data-title="Curhat ke Driver"
                    data-series="Tipe Penumpang Taxi Online"
                    data-access="premium"
                    data-pub="Terpublikasi"
                    data-views="19800"
                    data-likes="3100"
                    data-date="2026-02-22">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/tipe-penumpang-taxi-online.jpg') }}" alt="Ep 3" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Curhat ke Driver</div>
                                <small class="text-muted video-series">Tipe Penumpang Taxi Online</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#3</td>
                    <td class="fw-medium text-dark">2m 25s</td>
                    <td class="access-cell">
                        <span class="badge-pill-premium badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-gem"></i> Premium</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">19.800</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>3.100</span></td>
                    <td><span class="fw-bold text-success">86%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Curhat ke Driver" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 9 -->
                <tr class="video-row"
                    data-title="Gombalan Klasik"
                    data-series="Cara Deketin Cewek"
                    data-access="premium"
                    data-pub="Terpublikasi"
                    data-views="23100"
                    data-likes="5200"
                    data-date="2026-02-24">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/cara-deketin-cewek.jpg') }}" alt="Ep 2" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Gombalan Klasik</div>
                                <small class="text-muted video-series">Cara Deketin Cewek</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#2</td>
                    <td class="fw-medium text-dark">2m 05s</td>
                    <td class="access-cell">
                        <span class="badge-pill-premium badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-gem"></i> Premium</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">23.100</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>5.200</span></td>
                    <td><span class="fw-bold text-success">91%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Gombalan Klasik" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 10 -->
                <tr class="video-row"
                    data-title="Parkir Depan Pagar Tetangga"
                    data-series="Drama Warga Grup RT"
                    data-access="free"
                    data-pub="Terpublikasi"
                    data-views="21000"
                    data-likes="5800"
                    data-date="2026-02-26">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/thumbnails/drama-warga-grup-rt.jpg') }}" alt="Ep 2" class="thumbnail-table-thumb">
                            <div>
                                <div class="fw-bold text-dark video-title">Parkir Depan Pagar Tetangga</div>
                                <small class="text-muted video-series">Drama Warga Grup RT</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted fw-bold">#2</td>
                    <td class="fw-medium text-dark">2m 15s</td>
                    <td class="access-cell">
                        <span class="badge-pill-free badge-interactive btn-toggle-video-access" title="Klik untuk ubah akses"><i class="bi bi-play-circle"></i> Free Preview</span>
                    </td>
                    <td><span class="badge-pill-success"><i class="bi bi-check-circle"></i> Terpublikasi</span></td>
                    <td class="fw-bold text-dark">21.000</td>
                    <td><span class="text-danger fw-semibold"><i class="bi bi-heart-fill me-1"></i>5.800</span></td>
                    <td><span class="fw-bold text-success">88%</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ url('/admin/videos/1/edit') }}" class="btn-humora-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-humora-icon btn-duplicate-video" title="Duplikasi"><i class="bi bi-copy"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Video Parkir Depan Pagar Tetangga" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top gap-2">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted" id="videoPaginationCounter" style="font-size: 0.8rem;">Menampilkan <strong>1 - 10</strong> dari <strong>24</strong> data</small>
            <div class="d-flex align-items-center gap-1 ms-3">
                <small class="text-muted" style="font-size: 0.8rem;">Baris:</small>
                <select class="form-select-custom py-1 px-2" style="width: 70px; font-size: 0.78rem;">
                    <option selected>10</option>
                    <option>20</option>
                    <option>50</option>
                </select>
            </div>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                <li class="page-item active"><a class="page-link" href="#" style="background-color: #43135C; border-color: #43135C;">1</a></li>
                <li class="page-item"><a class="page-link text-dark" href="#">2</a></li>
                <li class="page-item"><a class="page-link text-dark" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let totalVideos = 24;
        let freeVideos = 12;
        let premiumVideos = 12;

        const cardTotal = document.querySelector('#cardTotalVideos .stat-value');
        const cardFree = document.querySelector('#cardFreeVideos .stat-value');
        const cardPremium = document.querySelector('#cardPremiumVideos .stat-value');

        const sortFilter = document.getElementById('videoSortFilter');
        const tableBody = document.querySelector('#videosTable tbody');

        // Toggle Video Access (Free <-> Premium)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-toggle-video-access');
            if (btn) {
                e.preventDefault();
                const row = btn.closest('tr');
                if (!row) return;

                const currentAccess = row.dataset.access;
                const title = row.dataset.title;

                if (currentAccess === 'free') {
                    row.dataset.access = 'premium';
                    btn.className = 'badge-pill-premium badge-interactive btn-toggle-video-access';
                    btn.innerHTML = '<i class="bi bi-gem"></i> Premium';
                    freeVideos = Math.max(0, freeVideos - 1);
                    premiumVideos += 1;
                    window.showHumoraToast(`Akses video "${title}" diubah: Premium (Terkunci)`);
                } else {
                    row.dataset.access = 'free';
                    btn.className = 'badge-pill-free badge-interactive btn-toggle-video-access';
                    btn.innerHTML = '<i class="bi bi-play-circle"></i> Free Preview';
                    freeVideos += 1;
                    premiumVideos = Math.max(0, premiumVideos - 1);
                    window.showHumoraToast(`Akses video "${title}" diubah: Free Preview (Gratis)`);
                }

                if (cardFree) cardFree.innerText = `${freeVideos} Video`;
                if (cardPremium) cardPremium.innerText = `${premiumVideos} Ep`;
            }
        });

        // Duplicate Video row
        document.addEventListener('click', function (e) {
            const dupBtn = e.target.closest('.btn-duplicate-video');
            if (dupBtn) {
                e.preventDefault();
                const row = dupBtn.closest('tr');
                if (!row) return;

                const clone = row.cloneNode(true);
                const titleEl = clone.querySelector('.video-title');
                const originalTitle = row.dataset.title;
                const newTitle = `${originalTitle} (Salinan)`;

                clone.dataset.title = newTitle;
                if (titleEl) titleEl.innerText = newTitle;

                const delBtn = clone.querySelector('[data-confirm-delete]');
                if (delBtn) delBtn.setAttribute('data-item-name', `Video ${newTitle}`);

                tableBody.insertBefore(clone, row.nextSibling);

                totalVideos += 1;
                if (cardTotal) cardTotal.innerText = totalVideos;

                window.showHumoraToast(`Video "${originalTitle}" berhasil diduplikasi!`);
            }
        });

        // Sorting handler
        if (sortFilter && tableBody) {
            sortFilter.addEventListener('change', function () {
                const sortType = this.value;
                const rows = Array.from(tableBody.querySelectorAll('tr.video-row'));

                rows.sort((a, b) => {
                    if (sortType === 'views') {
                        return parseInt(b.dataset.views || 0) - parseInt(a.dataset.views || 0);
                    } else if (sortType === 'likes') {
                        return parseInt(b.dataset.likes || 0) - parseInt(a.dataset.likes || 0);
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
