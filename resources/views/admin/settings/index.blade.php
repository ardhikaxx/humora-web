@extends('layouts.admin')

@section('title', 'Pengaturan Sistem - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-muted">Sistem</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Pengaturan</span>
    </small>
</div>

<!-- Header -->
<div class="mb-4">
    <h1 class="page-title">Pengaturan Sistem & Portal</h1>
    <p class="page-subtitle mb-0">Konfigurasi parameter operasional platform HUMORA, preferensi konten, dan batas akses aplikasi mobile.</p>
</div>

<div class="row g-4">
    <!-- Left Column: Pengaturan Aplikasi & Bisnis -->
    <div class="col-12 col-lg-7">
        <div class="humora-card mb-4">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">KONFIGURASI AKSES & MONETISASI</div>
                    <div class="humora-card-subtitle">Aturan pembatasan tontonan gratis dan langganan subscriber.</div>
                </div>
                <div class="text-muted"><i data-lucide="sliders" class="fs-5"></i></div>
            </div>
            <div class="humora-card-body p-4">
                <form id="formSettings" action="{{ url('/admin/settings') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="freeEpisodesSelect" class="form-label-custom">Jumlah Episode Gratis Per Series (Free Preview)</label>
                        <select id="freeEpisodesSelect" name="free_episodes" class="form-select-custom">
                            <option value="1">1 Episode Gratis</option>
                            <option value="2" selected>2 Episode Gratis (Default Standar)</option>
                            <option value="3">3 Episode Gratis</option>
                        </select>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Episode berikutnya akan otomatis terkunci dan membutuhkan langganan Premium.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Harga Paket Langganan Standar</label>
                        <div class="row g-2">
                            <div class="col-12 col-sm-4">
                                <label for="priceMonthly" class="text-muted d-block mb-1" style="font-size: 0.72rem;">Bulanan</label>
                                <input type="text" id="priceMonthly" name="price_monthly" class="form-control-custom" value="Rp 19.000">
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="priceQuarterly" class="text-muted d-block mb-1" style="font-size: 0.72rem;">3 Bulan</label>
                                <input type="text" id="priceQuarterly" name="price_quarterly" class="form-control-custom" value="Rp 49.000">
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="priceYearly" class="text-muted d-block mb-1" style="font-size: 0.72rem;">Tahunan</label>
                                <input type="text" id="priceYearly" name="price_yearly" class="form-control-custom" value="Rp 143.200">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="auto_verify" value="1" id="autoVerifyQris" checked style="accent-color: #43135C; cursor: pointer;">
                            <label class="form-check-label fw-bold text-dark" for="autoVerifyQris" style="font-size: 0.84rem; cursor: pointer;">
                                Verifikasi Otomatis Gateway Pembayaran QRIS / VA
                            </label>
                        </div>
                        <small class="text-muted d-block ps-4" style="font-size: 0.74rem;">Aktifkan webhook instan tanpa verifikasi manual untuk pembayaran otomatis.</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-humora-primary">
                            <i data-lucide="save"></i>
                            <span>Simpan Preferensi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Informasi Platform & Pemeliharaan -->
    <div class="col-12 col-lg-5">
        <div class="humora-card mb-4">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">STATUS PLATFORM</div>
                    <div class="humora-card-subtitle">Informasi sistem dan pemeliharaan server.</div>
                </div>
                <div class="text-muted"><i data-lucide="server" class="fs-5"></i></div>
            </div>
            <div class="humora-card-body p-4">
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light mb-3">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Versi Aplikasi</div>
                        <small class="text-muted">HUMORA Web Portal v1.0.4 (Production)</small>
                    </div>
                    <span class="badge-pill-success"><i data-lucide="check-circle"></i> Stable</span>
                </div>

                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light mb-4">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Mode Pemeliharaan (Maintenance)</div>
                        <small class="text-muted">Kunci akses publik jika ada update server</small>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" id="maintMode" style="cursor: pointer;">
                    </div>
                </div>

                <div class="d-flex flex-column gap-2" style="font-size: 0.78rem;">
                    <div class="d-flex justify-content-between text-muted">
                        <span>Penyimpanan Media:</span>
                        <strong class="text-dark">42.8 GB / 100 GB</strong>
                    </div>
                    <div class="progress-humora">
                        <div class="progress-humora-bar" style="width: 42%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formSettings = document.getElementById('formSettings');
        const maintMode = document.getElementById('maintMode');
        const autoVerifyQris = document.getElementById('autoVerifyQris');

        formSettings.addEventListener('submit', function (e) {
            e.preventDefault();
            window.showHumoraToast('Preferensi pengaturan sistem berhasil disimpan!');
        });

        maintMode.addEventListener('change', function () {
            if (this.checked) {
                window.showHumoraToast('Mode pemeliharaan diaktifkan! Pengguna umum tidak dapat mengakses aplikasi.', 'warning');
            } else {
                window.showHumoraToast('Mode pemeliharaan dinonaktifkan. Akses publik berjalan normal.', 'success');
            }
        });

        autoVerifyQris.addEventListener('change', function () {
            if (this.checked) {
                window.showHumoraToast('Verifikasi otomatis gateway pembayaran diaktifkan.');
            } else {
                window.showHumoraToast('Verifikasi otomatis dinonaktifkan. Seluruh bukti transfer memerlukan persetujuan manual admin.', 'info');
            }
        });
    });
</script>
@endsection
