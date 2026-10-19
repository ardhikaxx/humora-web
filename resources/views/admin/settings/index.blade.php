@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Rekening - HUMORA Portal Admin')

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
    <h1 class="page-title">Pengaturan Sistem & Konfigurasi Pembayaran</h1>
    <p class="page-subtitle mb-0">Konfigurasi parameter operasional platform HUMORA, harga paket VIP, rekening transfer manual resmi, dan file QRIS.</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <!-- Left Column: Aturan Preview & Harga Paket VIP -->
        <div class="col-12 col-lg-6">
            <!-- 1. Akses Konten -->
            <div class="humora-card mb-4">
                <div class="humora-card-header">
                    <div>
                        <div class="humora-card-title">ATURAN AKSES & FREE PREVIEW</div>
                        <div class="humora-card-subtitle">Jumlah episode preview gratis sebelum paywall VIP.</div>
                    </div>
                    <div class="text-muted"><i data-lucide="sliders"></i></div>
                </div>
                <div class="humora-card-body p-4">
                    <div class="mb-3">
                        <label for="freeEpisodesSelect" class="form-label-custom">Jumlah Episode Preview Gratis Per Series <span class="text-danger">*</span></label>
                        <select id="freeEpisodesSelect" name="free_preview_episodes" class="form-select-custom @error('free_preview_episodes') is-invalid @enderror">
                            <option value="1" {{ $freeEpisodes == 1 ? 'selected' : '' }}>1 Episode Gratis</option>
                            <option value="2" {{ $freeEpisodes == 2 ? 'selected' : '' }}>2 Episode Gratis (Default Standar HUMORA)</option>
                            <option value="3" {{ $freeEpisodes == 3 ? 'selected' : '' }}>3 Episode Gratis</option>
                        </select>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                            Episode berikutnya otomatis memerlukan langganan Premium VIP aktif.
                        </small>
                        @error('free_preview_episodes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Harga Paket VIP -->
            <div class="humora-card mb-0">
                <div class="humora-card-header">
                    <div>
                        <div class="humora-card-title">HARGA RESMI PAKET VIP</div>
                        <div class="humora-card-subtitle">Nominal tagihan yang ditarik oleh backend saat pengguna checkout.</div>
                    </div>
                    <div class="text-muted"><i data-lucide="gem"></i></div>
                </div>
                <div class="humora-card-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">Paket Bulanan (30 Hari) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="price_monthly" class="form-control-custom @error('price_monthly') is-invalid @enderror" value="{{ old('price_monthly', $plans['monthly']->price ?? 19000) }}" required min="0">
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Default: Rp19.000</small>
                        @error('price_monthly')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Paket 3 Bulan (90 Hari - Favorit Pengguna) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="price_quarterly" class="form-control-custom @error('price_quarterly') is-invalid @enderror" value="{{ old('price_quarterly', $plans['quarterly']->price ?? 49000) }}" required min="0">
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Default: Rp49.000</small>
                        @error('price_quarterly')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Paket Tahunan (365 Hari - VIP Emas / Paling Hemat) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="price_yearly" class="form-control-custom @error('price_yearly') is-invalid @enderror" value="{{ old('price_yearly', $plans['yearly']->price ?? 179000) }}" required min="0">
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Default: Rp179.000</small>
                        @error('price_yearly')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Informasi Pembayaran Resmi (Bank BCA, Mandiri, & QRIS) -->
        <div class="col-12 col-lg-6">
            <div class="humora-card mb-4">
                <div class="humora-card-header">
                    <div>
                        <div class="humora-card-title">REKENING TRANSFER BANK RESMI</div>
                        <div class="humora-card-subtitle">Informasi tujuan transfer manual yang tampil pada aplikasi Flutter.</div>
                    </div>
                    <div class="text-muted"><i data-lucide="credit-card"></i></div>
                </div>
                <div class="humora-card-body p-4">
                    <!-- BCA -->
                    <div class="p-3 rounded-3 border bg-light mb-3">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;">Bank Central Asia (BCA)</h6>
                        <div class="row g-2">
                            <div class="col-12 col-sm-6">
                                <label class="form-label-custom">Nomor Rekening BCA <span class="text-danger">*</span></label>
                                <input type="text" name="bca_account_number" class="form-control-custom @error('bca_account_number') is-invalid @enderror" value="{{ old('bca_account_number', $bcaNumber) }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label-custom">Atas Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" name="bca_account_name" class="form-control-custom @error('bca_account_name') is-invalid @enderror" value="{{ old('bca_account_name', $bcaName) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Mandiri -->
                    <div class="p-3 rounded-3 border bg-light mb-0">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;">Bank Mandiri</h6>
                        <div class="row g-2">
                            <div class="col-12 col-sm-6">
                                <label class="form-label-custom">Nomor Rekening Mandiri <span class="text-danger">*</span></label>
                                <input type="text" name="mandiri_account_number" class="form-control-custom @error('mandiri_account_number') is-invalid @enderror" value="{{ old('mandiri_account_number', $mandiriNumber) }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label-custom">Atas Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" name="mandiri_account_name" class="form-control-custom @error('mandiri_account_name') is-invalid @enderror" value="{{ old('mandiri_account_name', $mandiriName) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QRIS Statis Resmi -->
            <div class="humora-card mb-4">
                <div class="humora-card-header">
                    <div>
                        <div class="humora-card-title">QRIS STATIS RESMI</div>
                        <div class="humora-card-subtitle">Hanya sediakan QRIS bila file gambar barcode resmi tersedia.</div>
                    </div>
                    <div class="text-muted"><i data-lucide="qr-code"></i></div>
                </div>
                <div class="humora-card-body p-4">
                    @if (!empty($qrisImage))
                        <div class="text-center p-3 bg-light rounded-3 border mb-3">
                            <img src="{{ route('file.system', $qrisImage) }}" alt="QRIS Resmi" class="img-fluid rounded mb-2 shadow-sm" style="max-height: 180px;">
                            <div class="form-check text-center mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_qris" value="1" id="removeQris">
                                <label class="form-check-label text-danger fw-semibold" for="removeQris" style="font-size: 0.82rem; cursor: pointer;">
                                    Hapus QRIS Resmi saat ini (kembalikan ke status belum dikonfigurasi)
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary text-center py-3 mb-3" style="font-size: 0.82rem;">
                            <i data-lucide="alert-circle" class="mb-1 d-block mx-auto text-muted" style="width: 24px; height: 24px;"></i>
                            <strong>QRIS Belum Dikonfigurasi</strong>
                            <p class="mb-0 text-muted mt-1" style="font-size: 0.75rem;">
                                Aplikasi Flutter akan menampilkan bahwa metode QRIS belum tersedia sampai file gambar QRIS resmi diunggah oleh Super Admin.
                            </p>
                        </div>
                    @endif

                    <label class="form-label-custom">Unggah File Gambar QRIS Baru (JPG/PNG, Maks 2MB)</label>
                    <input type="file" name="qris_image" class="form-control-custom @error('qris_image') is-invalid @enderror" accept="image/jpeg,image/png">
                    @error('qris_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-humora-primary py-2 px-4">
                    <i data-lucide="save"></i>
                    <span>Simpan Seluruh Pengaturan</span>
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
