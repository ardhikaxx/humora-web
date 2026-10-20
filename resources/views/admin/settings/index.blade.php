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

        <!-- Right Column: Informasi Pembayaran Resmi (Rekening Bank Dinamis & QRIS) -->
        <div class="col-12 col-lg-6">
            <div class="humora-card mb-4">
                <div class="humora-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="humora-card-title">REKENING TRANSFER BANK RESMI</div>
                        <div class="humora-card-subtitle">Daftar rekening bank manual yang tampil dinamis pada aplikasi mobile.</div>
                    </div>
                    <div class="text-muted"><i data-lucide="credit-card"></i></div>
                </div>
                <div class="humora-card-body p-4">
                    <!-- Dynamic Bank Accounts Container -->
                    <div id="bankAccountsContainer" class="d-flex flex-column gap-3 mb-3">
                        @foreach ($bankAccounts as $index => $bank)
                        <div class="bank-account-card" data-index="{{ $index }}">
                            <div class="bank-card-header">
                                <div class="bank-order-badge">
                                    <i data-lucide="landmark"></i>
                                    <span>Rekening #<span class="bank-item-number">{{ $index + 1 }}</span></span>
                                </div>
                                <button type="button" class="btn-remove-bank-custom btn-remove-bank" title="Hapus Rekening" style="{{ count($bankAccounts) <= 1 ? 'display: none;' : '' }}">
                                    <i data-lucide="trash-2"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-custom">Nama Bank <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #E2E8F0;">
                                            <i data-lucide="building-2" style="width: 16px; height: 16px;"></i>
                                        </span>
                                        <input type="text" name="bank_accounts[{{ $index }}][bank_name]" class="form-control-custom input-bank-name border-start-0" placeholder="Misal: Bank Central Asia (BCA)" value="{{ $bank['bank_name'] ?? '' }}" required style="border-radius: 0 10px 10px 0;">
                                    </div>
                                    <div class="d-flex flex-wrap gap-1 mt-2 align-items-center">
                                        <span class="text-muted small me-1" style="font-size: 0.7rem;">Pilih Cepat:</span>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Central Asia (BCA)')">BCA</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Mandiri')">Mandiri</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Rakyat Indonesia (BRI)')">BRI</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Negara Indonesia (BNI)')">BNI</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Syariah Indonesia (BSI)')">BSI</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Jago')">Jago</button>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label-custom">Nomor Rekening <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #E2E8F0;">
                                            <i data-lucide="hash" style="width: 15px; height: 15px;"></i>
                                        </span>
                                        <input type="text" name="bank_accounts[{{ $index }}][account_number]" class="form-control-custom input-bank-number border-start-0" placeholder="Misal: 8830-192-888" value="{{ $bank['account_number'] ?? '' }}" required style="border-radius: 0 10px 10px 0; font-family: 'JetBrains Mono', 'Courier New', monospace; font-weight: 600; letter-spacing: 0.04em;">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label-custom">Atas Nama Penerima <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #E2E8F0;">
                                            <i data-lucide="user-check" style="width: 15px; height: 15px;"></i>
                                        </span>
                                        <input type="text" name="bank_accounts[{{ $index }}][account_name]" class="form-control-custom input-bank-holder border-start-0" placeholder="Misal: PT HUMORA MEDIA KREATIF" value="{{ $bank['account_name'] ?? '' }}" required style="border-radius: 0 10px 10px 0;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Button Tambah Rekening (Di bawah inputan) -->
                    <button type="button" class="btn-add-bank-custom" id="btnAddBankAccount">
                        <i data-lucide="plus-circle"></i>
                        <span>Tambah Rekening Bank Baru</span>
                    </button>
                    <small class="text-muted mt-2 d-block text-center" style="font-size: 0.72rem;">
                        Aplikasi mobile akan menampilkan tombol metode transfer otomatis sesuai jumlah bank di atas.
                    </small>
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
                                Aplikasi Flutter akan menampilkan bahwa metode QRIS belum tersedia sampai file gambar QRIS resmi diunggah oleh Admin.
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

@section('scripts')
<script>
window.applyBankPreset = function(btn, bankName) {
    const card = btn.closest('.bank-account-card');
    if (!card) return;
    const input = card.querySelector('.input-bank-name');
    if (input) {
        input.value = bankName;
        input.focus();
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('bankAccountsContainer');
    const addBtn = document.getElementById('btnAddBankAccount');

    function updateDeleteButtons() {
        if (!container) return;
        const items = container.querySelectorAll('.bank-account-card');
        const showDelete = items.length > 1;

        items.forEach((item, index) => {
            const numSpan = item.querySelector('.bank-item-number');
            if (numSpan) numSpan.textContent = index + 1;

            const nameInput = item.querySelector('.input-bank-name');
            const numberInput = item.querySelector('.input-bank-number');
            const holderInput = item.querySelector('.input-bank-holder');

            if (nameInput) nameInput.name = `bank_accounts[${index}][bank_name]`;
            if (numberInput) numberInput.name = `bank_accounts[${index}][account_number]`;
            if (holderInput) holderInput.name = `bank_accounts[${index}][account_name]`;

            const delBtn = item.querySelector('.btn-remove-bank');
            if (delBtn) {
                delBtn.style.display = showDelete ? 'inline-flex' : 'none';
            }
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    if (addBtn && container) {
        addBtn.addEventListener('click', function () {
            const currentItems = container.querySelectorAll('.bank-account-card');
            const newIndex = currentItems.length;

            const newCard = document.createElement('div');
            newCard.className = 'bank-account-card';
            newCard.setAttribute('data-index', newIndex);
            newCard.innerHTML = `
                <div class="bank-card-header">
                    <div class="bank-order-badge">
                        <i data-lucide="landmark"></i>
                        <span>Rekening #<span class="bank-item-number">${newIndex + 1}</span></span>
                    </div>
                    <button type="button" class="btn-remove-bank-custom btn-remove-bank" title="Hapus Rekening">
                        <i data-lucide="trash-2"></i>
                        <span>Hapus</span>
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom">Nama Bank <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #E2E8F0;">
                                <i data-lucide="building-2" style="width: 16px; height: 16px;"></i>
                            </span>
                            <input type="text" name="bank_accounts[${newIndex}][bank_name]" class="form-control-custom input-bank-name border-start-0" placeholder="Misal: Bank Central Asia (BCA)" required style="border-radius: 0 10px 10px 0;">
                        </div>
                        <div class="d-flex flex-wrap gap-1 mt-2 align-items-center">
                            <span class="text-muted small me-1" style="font-size: 0.7rem;">Pilih Cepat:</span>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Central Asia (BCA)')">BCA</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Mandiri')">Mandiri</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Rakyat Indonesia (BRI)')">BRI</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Negara Indonesia (BNI)')">BNI</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Syariah Indonesia (BSI)')">BSI</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'Bank Jago')">Jago</button>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label-custom">Nomor Rekening <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #E2E8F0;">
                                <i data-lucide="hash" style="width: 15px; height: 15px;"></i>
                            </span>
                            <input type="text" name="bank_accounts[${newIndex}][account_number]" class="form-control-custom input-bank-number border-start-0" placeholder="Misal: 8830-192-888" required style="border-radius: 0 10px 10px 0; font-family: 'JetBrains Mono', 'Courier New', monospace; font-weight: 600; letter-spacing: 0.04em;">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label-custom">Atas Nama Penerima <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #E2E8F0;">
                                <i data-lucide="user-check" style="width: 15px; height: 15px;"></i>
                            </span>
                            <input type="text" name="bank_accounts[${newIndex}][account_name]" class="form-control-custom input-bank-holder border-start-0" placeholder="Misal: PT HUMORA MEDIA KREATIF" required style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(newCard);
            updateDeleteButtons();
            const inputToFocus = newCard.querySelector('.input-bank-name');
            if (inputToFocus) inputToFocus.focus();
        });

        container.addEventListener('click', function (e) {
            const delBtn = e.target.closest('.btn-remove-bank');
            if (!delBtn) return;

            const item = delBtn.closest('.bank-account-card');
            if (item && container.querySelectorAll('.bank-account-card').length > 1) {
                item.remove();
                updateDeleteButtons();
            }
        });
    }

    updateDeleteButtons();
});
</script>
@endsection
