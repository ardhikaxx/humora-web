@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Rekening - HUMORA Portal Admin')

@section('styles')
<!-- Select2 CSS CDN & Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
/* Select2 customization for Humora Admin */
.select2-container--bootstrap-5 .select2-selection {
    border-color: #E2E8F0 !important;
    border-radius: 10px !important;
    min-height: 44px !important;
    padding: 0.35rem 0.65rem !important;
    font-size: 0.88rem !important;
    background-color: #F9FAFB !important;
    display: flex !important;
    align-items: center !important;
    transition: all 0.2s ease !important;
}
.select2-container--bootstrap-5.select2-container--focus .select2-selection,
.select2-container--bootstrap-5.select2-container--open .select2-selection {
    border-color: #A855F7 !important;
    background-color: #FFFFFF !important;
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.15) !important;
}
.select2-container--bootstrap-5 .select2-dropdown {
    border-color: #CBD5E1 !important;
    border-radius: 12px !important;
    box-shadow: 0 12px 28px rgba(67, 19, 92, 0.12) !important;
    overflow: hidden !important;
    z-index: 1055 !important;
}
.select2-search--dropdown {
    padding: 8px 10px !important;
    background: #FAF8FD !important;
    border-bottom: 1px solid #F1F5F9 !important;
}
.select2-search--dropdown .select2-search__field {
    border-radius: 8px !important;
    border: 1px solid #DDD6FE !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.84rem !important;
    outline: none !important;
}
.select2-search--dropdown .select2-search__field:focus {
    border-color: #A855F7 !important;
    box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.2) !important;
}
.select2-container--bootstrap-5 .select2-results__optgroup {
    font-size: 0.72rem !important;
    font-weight: 800 !important;
    color: #6D28D9 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    padding: 0.55rem 0.85rem 0.3rem !important;
    background: #FAF5FF !important;
    border-top: 1px solid #F3E8FF !important;
}
.select2-container--bootstrap-5 .select2-results__option {
    padding: 0.45rem 0.85rem !important;
}
.select2-container--bootstrap-5 .select2-results__option--highlighted {
    background-color: #F5F3FF !important;
    color: #4C1D95 !important;
}
.select2-container--bootstrap-5 .select2-results__option--selected {
    background-color: #EDE9FE !important;
    color: #6D28D9 !important;
    font-weight: 700 !important;
}

/* Custom Bank Option styling inside Select2 dropdown */
.bank-select-option-row {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}
.bank-option-logo-wrapper {
    width: 36px;
    height: 24px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2px 4px;
    flex-shrink: 0;
}
.bank-option-logo-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.bank-option-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #1E293B;
    flex: 1;
}
.bank-option-badge {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.18rem 0.55rem;
    border-radius: 12px;
    margin-left: auto;
}
.badge-cat-konvensional {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}
.badge-cat-digital {
    background: #FAF5FF;
    color: #7E22CE;
    border: 1px solid #E9D5FF;
}
.badge-cat-syariah {
    background: #ECFDF5;
    color: #047857;
    border: 1px solid #A7F3D0;
}

.bank-card-logo-preview {
    height: 24px;
    max-width: 90px;
    object-fit: contain;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    padding: 2px 6px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}
</style>
@endsection

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
                        <div class="humora-card-subtitle">Daftar bank konvensional & digital resmi yang tampil dinamis dengan logo di aplikasi mobile.</div>
                    </div>
                    <div class="text-muted"><i data-lucide="credit-card"></i></div>
                </div>
                <div class="humora-card-body p-4">
                    <!-- Dynamic Bank Accounts Container -->
                    <div id="bankAccountsContainer" class="d-flex flex-column gap-3 mb-3">
                        @foreach ($bankAccounts as $index => $bank)
                        @php
                            $currentCode = $bank['bank_code'] ?? 'bca';
                            $currentLogo = !empty($bank['bank_logo']) ? asset('images/banks/' . $bank['bank_logo']) : null;
                        @endphp
                        <div class="bank-account-card" data-index="{{ $index }}" data-pattern="{{ $bank['pattern'] ?? '4,3,3' }}" data-placeholder="{{ $bank['placeholder'] ?? '8830-192-888' }}">
                            <div class="bank-card-header">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bank-order-badge">
                                        <i data-lucide="landmark"></i>
                                        <span>Rekening #<span class="bank-item-number">{{ $index + 1 }}</span></span>
                                    </div>
                                    <img class="bank-card-logo-preview" src="{{ $currentLogo ?? '' }}" style="{{ empty($currentLogo) ? 'display: none;' : '' }}" alt="Logo Bank" />
                                </div>
                                <button type="button" class="btn-remove-bank-custom btn-remove-bank" title="Hapus Rekening" style="{{ count($bankAccounts) <= 1 ? 'display: none;' : '' }}">
                                    <i data-lucide="trash-2"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-custom">Pilih Nama Bank Resmi <span class="text-danger">*</span></label>
                                    <div class="bank-select-wrapper">
                                        <select name="bank_accounts[{{ $index }}][bank_name]" class="form-select select2-bank input-bank-name" data-index="{{ $index }}" required>
                                            <option value="">-- Ketik untuk Cari atau Pilih Bank --</option>
                                            @foreach ($groupedBanks as $category => $banksInCategory)
                                            <optgroup label="{{ $category }}">
                                                @foreach ($banksInCategory as $b)
                                                @php
                                                    $isMatched = (strtolower($bank['bank_name'] ?? '') === strtolower($b['name']))
                                                        || (strtolower($bank['bank_code'] ?? '') === strtolower($b['code']));
                                                @endphp
                                                <option value="{{ $b['name'] }}"
                                                    data-code="{{ $b['code'] }}"
                                                    data-logo="{{ asset('images/banks/' . $b['logo']) }}"
                                                    data-pattern="{{ implode(',', $b['pattern']) }}"
                                                    data-placeholder="{{ $b['placeholder'] }}"
                                                    data-category="{{ $b['category'] }}"
                                                    {{ $isMatched ? 'selected' : '' }}>
                                                    {{ $b['name'] }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="bank_accounts[{{ $index }}][bank_code]" class="input-bank-code" value="{{ $currentCode }}">

                                    <div class="d-flex flex-wrap gap-1 mt-2 align-items-center">
                                        <span class="text-muted small me-1" style="font-size: 0.7rem; font-weight: 600;">Pintasan Bank Populer:</span>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'bca')">BCA</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'mandiri')">Mandiri</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'bri')">BRI</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'bni')">BNI</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'bsi')">BSI</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'jago')">Bank Jago</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'seabank')">SeaBank</button>
                                        <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'blu')">Blu BCA</button>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label-custom">Nomor Rekening <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #E2E8F0;">
                                            <i data-lucide="hash" style="width: 15px; height: 15px;"></i>
                                        </span>
                                        <input type="text" name="bank_accounts[{{ $index }}][account_number]" class="form-control-custom input-bank-number border-start-0" placeholder="Contoh: {{ $bank['placeholder'] ?? '8830-192-888' }}" value="{{ $bank['account_number'] ?? '' }}" required style="border-radius: 0 10px 10px 0; font-family: 'JetBrains Mono', 'Courier New', monospace; font-weight: 600; letter-spacing: 0.04em;">
                                    </div>
                                    <small class="text-muted d-block mt-1 bank-mask-hint" style="font-size: 0.7rem;">
                                        Ketik angka saja, format tanda hubung otomatis terbentuk.
                                    </small>
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
<!-- jQuery CDN (Required for Select2) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<!-- Select2 JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
window.bankDirectoryData = {!! json_encode($groupedBanks) !!};
window.bankLogoBaseUrl = "{{ asset('images/banks') }}";

// Template for rendering option in Select2 dropdown
function formatBankOption(state) {
    if (!state.id) return state.text;
    const element = state.element;
    if (!element) return state.text;

    const logo = $(element).data('logo');
    const category = $(element).data('category') || '';
    const name = state.text;

    let badgeClass = 'badge-cat-konvensional';
    if (category.toLowerCase().includes('digital')) badgeClass = 'badge-cat-digital';
    else if (category.toLowerCase().includes('syariah')) badgeClass = 'badge-cat-syariah';

    return $(`
        <div class="bank-select-option-row">
            <div class="bank-option-logo-wrapper">
                ${logo ? `<img src="${logo}" class="bank-option-logo-img" alt="${name}" onerror="this.parentElement.innerHTML='🏦'" />` : '🏦'}
            </div>
            <span class="bank-option-name">${name}</span>
            ${category ? `<span class="bank-option-badge ${badgeClass}">${category}</span>` : ''}
        </div>
    `);
}

// Template for rendering selected item in Select2 box
function formatBankSelection(state) {
    if (!state.id) return state.text;
    const element = state.element;
    if (!element) return state.text;
    const logo = $(element).data('logo');
    const name = state.text;

    if (logo) {
        return $(`
            <span class="d-inline-flex align-items-center gap-2">
                <span class="bank-option-logo-wrapper" style="width:26px; height:18px; padding:1px 2px; border-radius:4px;">
                    <img src="${logo}" class="bank-option-logo-img" alt="${name}" onerror="this.style.display='none'" />
                </span>
                <span class="fw-semibold text-dark">${name}</span>
            </span>
        `);
    }
    return name;
}

// Initialize Select2 on target element
function initBankSelect2($select) {
    $select.select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Ketik untuk Cari atau Pilih Bank --',
        allowClear: false,
        templateResult: formatBankOption,
        templateSelection: formatBankSelection,
        escapeMarkup: function(m) { return m; }
    });

    // Handle change event
    $select.on('change', function () {
        const card = $(this).closest('.bank-account-card');
        const selected = $(this).find(':selected');
        const pattern = selected.data('pattern') || '4,3,3';
        const placeholder = selected.data('placeholder') || '8830-192-888';
        const code = selected.data('code') || '';
        const logo = selected.data('logo') || '';

        card.attr('data-pattern', pattern);
        card.attr('data-placeholder', placeholder);

        const codeInput = card.find('.input-bank-code');
        if (codeInput.length) codeInput.val(code);

        const logoPreview = card.find('.bank-card-logo-preview');
        if (logo) {
            logoPreview.attr('src', logo).show();
        } else {
            logoPreview.hide();
        }

        const numInput = card.find('.input-bank-number');
        if (numInput.length) {
            numInput.attr('placeholder', 'Contoh: ' + placeholder);
            applyAccountMask(numInput[0]);
        }
    });
}

// Formatting bank account numbers automatically
function applyAccountMask(input) {
    if (!input) return;
    const card = input.closest('.bank-account-card');
    const patternStr = (card ? card.getAttribute('data-pattern') : null) || '4,3,3';
    const pattern = patternStr.split(',').map(n => parseInt(n.trim(), 10)).filter(n => !isNaN(n));
    const maxDigits = pattern.reduce((a, b) => a + b, 0);

    // Keep only numbers
    let digits = input.value.replace(/\D/g, '');
    if (digits.length > maxDigits) {
        digits = digits.substring(0, maxDigits);
    }

    // Format with dashes
    let formatted = '';
    let curr = 0;
    for (let i = 0; i < pattern.length; i++) {
        const chunkSize = pattern[i];
        if (curr >= digits.length) break;
        if (i > 0) formatted += '-';
        formatted += digits.substring(curr, curr + chunkSize);
        curr += chunkSize;
    }

    input.value = formatted;
}

// Preset Quick Chips handler
window.applyBankPreset = function(chip, bankCode) {
    const card = $(chip).closest('.bank-account-card');
    if (!card.length) return;
    const select = card.find('.select2-bank');
    if (!select.length) return;

    // Find option with matching data-code
    const matchingOption = select.find(`option[data-code="${bankCode}"]`);
    if (matchingOption.length) {
        select.val(matchingOption.val()).trigger('change');
    }
};

$(document).ready(function () {
    const container = document.getElementById('bankAccountsContainer');
    const addBtn = document.getElementById('btnAddBankAccount');

    // Initialize Select2 on existing cards
    $('.select2-bank').each(function () {
        initBankSelect2($(this));
    });

    // Attach mask event listeners to existing number inputs
    $(document).on('input', '.input-bank-number', function () {
        applyAccountMask(this);
    });
    $(document).on('paste', '.input-bank-number', function () {
        const self = this;
        setTimeout(function() { applyAccountMask(self); }, 15);
    });

    function updateDeleteButtons() {
        if (!container) return;
        const items = container.querySelectorAll('.bank-account-card');
        const showDelete = items.length > 1;

        items.forEach((item, index) => {
            const numSpan = item.querySelector('.bank-item-number');
            if (numSpan) numSpan.textContent = index + 1;

            const nameSelect = item.querySelector('.input-bank-name');
            const codeInput = item.querySelector('.input-bank-code');
            const numberInput = item.querySelector('.input-bank-number');
            const holderInput = item.querySelector('.input-bank-holder');

            if (nameSelect) nameSelect.name = `bank_accounts[${index}][bank_name]`;
            if (codeInput) codeInput.name = `bank_accounts[${index}][bank_code]`;
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

    // Dynamic bank addition
    if (addBtn && container) {
        addBtn.addEventListener('click', function () {
            const currentItems = container.querySelectorAll('.bank-account-card');
            const newIndex = currentItems.length;

            // Generate options HTML from window.bankDirectoryData
            let optionsHtml = '<option value="">-- Ketik untuk Cari atau Pilih Bank --</option>';
            for (const category in window.bankDirectoryData) {
                optionsHtml += `<optgroup label="${category}">`;
                const banks = window.bankDirectoryData[category];
                banks.forEach(b => {
                    const logoUrl = `${window.bankLogoBaseUrl}/${b.logo}`;
                    optionsHtml += `<option value="${b.name}" data-code="${b.code}" data-logo="${logoUrl}" data-pattern="${b.pattern.join(',')}" data-placeholder="${b.placeholder}" data-category="${b.category}">${b.name}</option>`;
                });
                optionsHtml += `</optgroup>`;
            }

            const newCard = document.createElement('div');
            newCard.className = 'bank-account-card';
            newCard.setAttribute('data-index', newIndex);
            newCard.setAttribute('data-pattern', '4,3,3');
            newCard.setAttribute('data-placeholder', '8830-192-888');
            newCard.innerHTML = `
                <div class="bank-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bank-order-badge">
                            <i data-lucide="landmark"></i>
                            <span>Rekening #<span class="bank-item-number">${newIndex + 1}</span></span>
                        </div>
                        <img class="bank-card-logo-preview" src="" style="display: none;" alt="Logo Bank" />
                    </div>
                    <button type="button" class="btn-remove-bank-custom btn-remove-bank" title="Hapus Rekening">
                        <i data-lucide="trash-2"></i>
                        <span>Hapus</span>
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom">Pilih Nama Bank Resmi <span class="text-danger">*</span></label>
                        <div class="bank-select-wrapper">
                            <select name="bank_accounts[${newIndex}][bank_name]" class="form-select select2-bank input-bank-name" data-index="${newIndex}" required>
                                ${optionsHtml}
                            </select>
                        </div>
                        <input type="hidden" name="bank_accounts[${newIndex}][bank_code]" class="input-bank-code" value="">

                        <div class="d-flex flex-wrap gap-1 mt-2 align-items-center">
                            <span class="text-muted small me-1" style="font-size: 0.7rem; font-weight: 600;">Pintasan Bank Populer:</span>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'bca')">BCA</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'mandiri')">Mandiri</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'bri')">BRI</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'bni')">BNI</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'bsi')">BSI</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'jago')">Bank Jago</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'seabank')">SeaBank</button>
                            <button type="button" class="bank-preset-chip" onclick="applyBankPreset(this, 'blu')">Blu BCA</button>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label-custom">Nomor Rekening <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 10px 0 0 10px; border-color: #E2E8F0;">
                                <i data-lucide="hash" style="width: 15px; height: 15px;"></i>
                            </span>
                            <input type="text" name="bank_accounts[${newIndex}][account_number]" class="form-control-custom input-bank-number border-start-0" placeholder="Nomor rekening" required style="border-radius: 0 10px 10px 0; font-family: 'JetBrains Mono', 'Courier New', monospace; font-weight: 600; letter-spacing: 0.04em;">
                        </div>
                        <small class="text-muted d-block mt-1 bank-mask-hint" style="font-size: 0.7rem;">
                            Ketik angka saja, format tanda hubung otomatis terbentuk.
                        </small>
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
            initBankSelect2($(newCard).find('.select2-bank'));
            updateDeleteButtons();
        });

        // Delete card event delegation
        container.addEventListener('click', function (e) {
            const delBtn = e.target.closest('.btn-remove-bank');
            if (!delBtn) return;

            const item = delBtn.closest('.bank-account-card');
            if (item && container.querySelectorAll('.bank-account-card').length > 1) {
                // Destroy select2 instance before removal
                $(item).find('.select2-bank').select2('destroy');
                item.remove();
                updateDeleteButtons();
            }
        });
    }

    updateDeleteButtons();
});
</script>
@endsection
