@extends('layouts.admin')

@section('title', 'Tambah Episode Video Baru - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ route('admin.videos.index') }}" class="text-decoration-none text-muted">Kelola Video</a> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Tambah Episode</span>
    </small>
</div>

<!-- Header & Action Buttons -->
<form id="formCreateVideo" action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title mb-1">Tambah Episode Video Baru</h1>
            <p class="page-subtitle mb-0">Tambahkan episode sketsa pendek (~2 menit) ke dalam series pilihan. Episode 1 & 2 otomatis Gratis, Episode 3+ otomatis VIP.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.videos.index') }}" class="btn btn-humora-secondary">
                <i data-lucide="arrow-left"></i>
                <span>Batal</span>
            </a>
            <button type="submit" name="status" value="draft" class="btn btn-outline-secondary">
                <span>Simpan Draf</span>
            </button>
            <button type="submit" name="status" value="published" class="btn btn-humora-primary">
                <i data-lucide="save"></i>
                <span>Simpan & Terbitkan</span>
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-lg-8">
            <!-- 1. PILIH JUDUL KONTEN (SERIES) -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">1. PILIH SERIES INDUK</h5>
                        <a href="{{ route('admin.series.create') }}" class="text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.78rem;">+ Buat Series Baru</a>
                    </div>

                    <div class="mb-3">
                        <label for="seriesSelect" class="form-label-custom">Series Induk <span class="text-danger">*</span></label>
                        <select id="seriesSelect" name="series_id" class="form-select-custom @error('series_id') is-invalid @enderror" required>
                            @foreach ($seriesList as $s)
                                @php
                                    $nextEp = $nextEpisodeNumbers[$s->id] ?? 1;
                                    $count = $seriesEpisodeCounts[$s->id] ?? 0;
                                @endphp
                                <option value="{{ $s->id }}" 
                                        data-next-ep="{{ $nextEp }}"
                                        data-ep-count="{{ $count }}"
                                        data-title="{{ $s->title }}"
                                        {{ old('series_id', $selectedSeriesId) == $s->id ? 'selected' : '' }}>
                                    {{ $s->title }} ({{ $s->categories->pluck('name')->implode(', ') ?: 'Umum' }}) — {{ $count }} Episode tersimpan
                                </option>
                            @endforeach
                        </select>
                        @error('series_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. INFORMASI & DETAIL EPISODE -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 0.95rem;">2. DETAIL EPISODE</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <div class="px-3 rounded-3 border bg-light d-flex align-items-center justify-content-between" style="min-height: 52px; height: 52px; border-color: #E2E8F0 !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0" style="width: 32px; height: 32px; background: #FAF5FF; color: #43135C;">
                                        <i data-lucide="hash" style="width: 16px; height: 16px;"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark" style="font-size: 0.82rem; line-height: 1.2; white-space: nowrap;">Nomor Episode</span>
                                        <small class="text-muted d-block" id="epNumberNotice" style="font-size: 0.72rem; white-space: nowrap;">Otomatis Urutan ke-{{ $suggestedNumber }}</small>
                                    </div>
                                </div>
                                <span class="badge py-1 px-2.5 rounded-pill fw-bold flex-shrink-0" id="epNumberBadge" style="background: #FAF5FF; color: #43135C; border: 1px solid #E9D5FF; font-size: 0.78rem;">
                                    Auto Ep #{{ $suggestedNumber }}
                                </span>
                            </div>
                            <input type="hidden" id="episodeNumberInput" name="episode_number" value="{{ old('episode_number', $suggestedNumber) }}">
                            @error('episode_number')
                                <div class="text-danger mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="humora-field-group mb-0">
                                <div class="humora-field @error('title') is-error @enderror">
                                    <input type="text" id="videoTitleInput" name="title" class="form-control-custom @error('title') is-invalid @enderror" placeholder=" " value="{{ old('title') }}" required>
                                    <label for="videoTitleInput" class="humora-floating-label">Judul Episode <span class="text-danger">*</span></label>
                                </div>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="humora-field-group mb-4">
                        <div class="humora-field is-textarea @error('synopsis') is-error @enderror">
                            <textarea id="synopsisInput" rows="3" name="synopsis" class="form-control-custom @error('synopsis') is-invalid @enderror" placeholder=" ">{{ old('synopsis') }}</textarea>
                            <label for="synopsisInput" class="humora-floating-label">Sinopsis Cerita / Sketsa</label>
                        </div>
                        @error('synopsis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Durasi Episode Otomatis -->
                    <div class="mb-3">
                        <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between mb-2" id="durationCard" style="border-color: #E2E8F0 !important;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded-2" id="durationIconBox" style="width: 36px; height: 36px; background: #FAF5FF; color: #43135C;">
                                    <i data-lucide="timer" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1.2;">Durasi Episode Otomatis</span>
                                        <span class="badge py-0.5 px-2 rounded-pill fw-bold" id="durationBadge" style="background: #FAF5FF; color: #43135C; border: 1px solid #E9D5FF; font-size: 0.72rem;">
                                            Standar ~2m (Auto)
                                        </span>
                                    </div>
                                    <small class="text-muted d-block mt-0.5" id="durationStatusText" style="font-size: 0.73rem;">
                                        Dihitung otomatis saat berkas video dipilih pada bagian 3 di bawah.
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm text-muted p-0 d-flex align-items-center gap-1" id="toggleManualDurationBtn" style="font-size: 0.72rem; text-decoration: underline;" title="Klik jika ingin melakukan penyesuaian manual">
                                    <i data-lucide="sliders-horizontal" style="width: 12px; height: 12px;"></i>
                                    <span id="toggleManualDurationText">Atur Manual</span>
                                </button>
                            </div>
                        </div>

                        <!-- Hidden Form Inputs for automatic submission -->
                        <input type="hidden" name="duration_minutes" id="minuteInput" value="{{ old('duration_minutes', 2) }}">
                        <input type="hidden" name="duration_seconds" id="secondInput" value="{{ old('duration_seconds', 0) }}">

                        <!-- Optional Manual Duration Panel (Collapsed by default) -->
                        <div id="manualDurationPanel" style="display: none;" class="p-3 bg-white rounded-3 border mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-dark" style="font-size: 0.78rem;">Penyesuaian Manual Durasi:</span>
                                <small class="text-muted" style="font-size: 0.72rem;">Format: Menit &amp; Detik</small>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" id="manualMinuteInput" class="form-control-custom" value="{{ old('duration_minutes', 2) }}" min="0" max="60">
                                        <span class="input-group-text bg-light text-muted">Menit</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" id="manualSecondInput" class="form-control-custom" value="{{ old('duration_seconds', 0) }}" min="0" max="59" placeholder="0">
                                        <span class="input-group-text bg-light text-muted">Detik</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-2 align-items-center">
                                <small class="text-muted me-1" style="font-size: 0.72rem;">Preset Cepat:</small>
                                @foreach ($durationPresets as $preset)
                                    <button type="button" class="btn btn-sm btn-light border py-0 px-2 rounded-pill preset-btn" data-min="{{ $preset['minutes'] }}" data-sec="{{ $preset['seconds'] }}" style="font-size: 0.72rem;">
                                        {{ $preset['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. UNGGAH BERKAS VIDEO MASTER -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">3. BERKAS VIDEO MASTER</h5>
                        <span class="badge py-1 px-2" style="background: rgba(67, 19, 92, 0.1); color: #43135C; font-size: 0.75rem;">Auto-Kompres & WebM</span>
                    </div>
                    <p class="text-muted mb-2" style="font-size: 0.76rem;">Format yang didukung: MP4, WEBM, MOV (Maksimal 100MB). Video disimpan di direktori fisik <code>storage/uploads/videos/</code> tanpa symlink.</p>
                    <div class="p-2 px-3 rounded-2 mb-3" style="background: #F8FAFC; border: 1px dashed #CBD5E1; font-size: 0.75rem; color: #475569;">
                        <i data-lucide="sparkles" class="text-warning me-1" style="width: 13px; height: 13px;"></i>
                        <strong>Optimalisasi Mobile:</strong> Berkas video otomatis dikompresi dan dikonversi ke format <strong>WebM (.webm)</strong> dengan <u>tetap mempertahankan resolusi aslinya 100% tanpa penurunan resolusi</u>, agar pemutaran video di aplikasi mobile menjadi sangat ringan, hemat kuota, dan bebas *buffering*.
                    </div>

                    <label class="form-label-custom">Pilih Berkas Video (Opsional, fallback ke sample video jika kosong)</label>
                    <input type="file" name="video_file" id="videoUpload" class="form-control-custom @error('video_file') is-invalid @enderror" accept="video/mp4,video/webm,video/quicktime">
                    @error('video_file')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <!-- Pratinjau Pemutar Video HTML5 -->
                    <div class="mt-3 p-3 bg-light rounded-3 border" id="videoPreviewContainer" style="display: none;">
                        <div class="fw-semibold text-dark mb-2" style="font-size: 0.8rem;">Pratinjau Video Lokal:</div>
                        <video id="videoPreviewPlayer" controls class="w-100 rounded" style="max-height: 240px; background-color: #000;"></video>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Options Column -->
        <div class="col-12 col-lg-4">
            <!-- Aturan Akses Otomatis -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem; text-transform: uppercase;">Kebijakan Akses Episode</h6>
                    <div id="accessBadgeContainer" class="p-3 rounded-3 border bg-light">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i data-lucide="info" class="text-purple" style="width: 16px; height: 16px;"></i>
                            <span class="fw-bold" id="accessTitle" style="font-size: 0.84rem;">Otomatis Ditentukan</span>
                        </div>
                        <p class="text-muted mb-0" id="accessDesc" style="font-size: 0.74rem; line-height: 1.4;">
                            Berdasarkan kebijakan HUMORA, Episode 1 & 2 adalah preview gratis untuk semua pengguna. Episode 3 ke atas secara otomatis dikunci untuk pelanggan Premium VIP.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status Publikasi -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.85rem; text-transform: uppercase;">Status Publikasi</h6>
                    <select name="status" class="form-select-custom">
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Terpublikasi (Siap Ditonton)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draf (Disembunyikan)</option>
                    </select>
                    <small class="text-muted d-block mt-2" style="font-size: 0.74rem;">Episode draf tidak akan tampil pada katalog pengguna mobile.</small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Dynamic Episode Number & Series Selection Sync
    const seriesSelect = document.getElementById('seriesSelect');
    const epNumInput = document.getElementById('episodeNumberInput');
    const epNumberBadge = document.getElementById('epNumberBadge');
    const epNumberNotice = document.getElementById('epNumberNotice');
    const accessTitle = document.getElementById('accessTitle');
    const accessDesc = document.getElementById('accessDesc');

    function updateAccessNotice() {
        const num = parseInt(epNumInput.value, 10) || 1;
        if (num <= 2) {
            accessTitle.innerHTML = '<span class="text-success d-flex align-items-center gap-1"><i data-lucide="unlock" style="width: 14px; height: 14px;"></i>Gratis (Free Preview)</span>';
            accessDesc.innerText = 'Episode ini dapat ditonton secara gratis oleh seluruh pengguna (Guest, Free, & VIP).';
        } else {
            accessTitle.innerHTML = '<span class="text-warning-emphasis d-flex align-items-center gap-1"><i data-lucide="lock" style="width: 14px; height: 14px;"></i>Premium VIP</span>';
            accessDesc.innerText = 'Episode ini terkunci dan memerlukan paket langganan Premium VIP aktif untuk dapat diputar.';
        }
        if (window.lucide) lucide.createIcons();
    }

    function syncSeriesSelection() {
        if (!seriesSelect) return;
        const opt = seriesSelect.options[seriesSelect.selectedIndex];
        if (!opt) return;

        const nextEp = parseInt(opt.dataset.nextEp || 1, 10);
        const count = parseInt(opt.dataset.epCount || 0, 10);
        const title = opt.dataset.title || 'judul ini';

        if (epNumInput) epNumInput.value = nextEp;
        if (epNumberBadge) epNumberBadge.textContent = 'Auto Ep #' + nextEp;
        if (epNumberNotice) {
            epNumberNotice.textContent = 'Otomatis Urutan ke-' + nextEp;
        }

        updateAccessNotice();
    }

    if (seriesSelect) {
        seriesSelect.addEventListener('change', syncSeriesSelection);
        // Initial sync on load
        syncSeriesSelection();
    }

    // 2. Automatic Video Duration Calculation & Handlers
    const minuteInput = document.getElementById('minuteInput');
    const secondInput = document.getElementById('secondInput');
    const manualMinuteInput = document.getElementById('manualMinuteInput');
    const manualSecondInput = document.getElementById('manualSecondInput');
    const durationIconBox = document.getElementById('durationIconBox');
    const durationBadge = document.getElementById('durationBadge');
    const durationStatusText = document.getElementById('durationStatusText');
    const toggleManualBtn = document.getElementById('toggleManualDurationBtn');
    const manualPanel = document.getElementById('manualDurationPanel');
    const toggleManualText = document.getElementById('toggleManualDurationText');

    function setDurationValues(mins, secs, fromFile = false) {
        if (minuteInput) minuteInput.value = mins;
        if (secondInput) secondInput.value = secs;
        if (manualMinuteInput) manualMinuteInput.value = mins;
        if (manualSecondInput) manualSecondInput.value = secs;

        const formatted = mins + 'm ' + (secs < 10 ? '0' : '') + secs + 's';
        const totalSecs = (mins * 60) + secs;

        if (durationBadge) {
            if (fromFile) {
                durationBadge.innerHTML = '<i data-lucide="check" style="width: 12px; height: 12px; vertical-align: -1px;"></i> Auto: ' + formatted;
                durationBadge.style.background = '#ECFDF5';
                durationBadge.style.color = '#065F46';
                durationBadge.style.borderColor = '#A7F3D0';
                if (durationIconBox) {
                    durationIconBox.style.background = '#ECFDF5';
                    durationIconBox.style.color = '#059669';
                }
                if (durationStatusText) {
                    durationStatusText.innerHTML = '<span class="text-success fw-medium"><i data-lucide="sparkles" style="width: 12px; height: 12px; vertical-align: -2px;"></i> Dihitung otomatis dari berkas video: <strong>' + mins + ' Menit ' + secs + ' Detik</strong> (' + totalSecs + ' detik)</span>';
                }
            } else {
                durationBadge.textContent = 'Auto: ' + formatted;
            }
        }
        if (window.lucide) lucide.createIcons();
    }

    // Toggle optional manual duration controls
    if (toggleManualBtn && manualPanel) {
        toggleManualBtn.addEventListener('click', function () {
            const isHidden = manualPanel.style.display === 'none';
            manualPanel.style.display = isHidden ? 'block' : 'none';
            toggleManualText.textContent = isHidden ? 'Sembunyikan' : 'Atur Manual';
        });
    }

    // Sync from manual inputs if admin adjusts them
    if (manualMinuteInput && manualSecondInput) {
        function syncFromManual() {
            const mins = parseInt(manualMinuteInput.value, 10) || 0;
            const secs = parseInt(manualSecondInput.value, 10) || 0;
            setDurationValues(mins, secs, false);
        }
        manualMinuteInput.addEventListener('input', syncFromManual);
        manualSecondInput.addEventListener('input', syncFromManual);
    }

    // Preset buttons
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const mins = parseInt(this.dataset.min, 10) || 0;
            const secs = parseInt(this.dataset.sec, 10) || 0;
            setDurationValues(mins, secs, false);
        });
    });

    // 3. Video Upload Change & In-Browser Duration Extraction
    const videoUpload = document.getElementById('videoUpload');
    const previewContainer = document.getElementById('videoPreviewContainer');
    const previewPlayer = document.getElementById('videoPreviewPlayer');

    if (videoUpload && previewPlayer) {
        videoUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                previewPlayer.src = url;
                previewContainer.style.display = 'block';

                // Automatically calculate duration from video metadata
                previewPlayer.onloadedmetadata = function () {
                    const totalSecs = Math.round(previewPlayer.duration);
                    if (totalSecs > 0) {
                        const mins = Math.floor(totalSecs / 60);
                        const secs = totalSecs % 60;
                        setDurationValues(mins, secs, true);

                        if (window.showHumoraToast) {
                            window.showHumoraToast(`Durasi video otomatis dihitung: ${mins}m ${secs}s (${totalSecs} detik).`, 'success');
                        }
                    }
                };

                // Notification toast
                if (window.showHumoraToast) {
                    const sizeStr = file.size > 1048576 
                        ? (file.size / 1048576).toFixed(1) + ' MB' 
                        : Math.round(file.size / 1024) + ' KB';
                    window.showHumoraToast(`Video dipilih: ${file.name} (${sizeStr}). Format WebM otomatis saat disimpan.`, 'info');
                }
            }
        });
    }

    // 4. Show processing modal during video compression & upload
    const formCreateVideo = document.getElementById('formCreateVideo');
    if (formCreateVideo) {
        formCreateVideo.addEventListener('submit', function (e) {
            if (!this.checkValidity()) return;

            const videoFile = videoUpload && videoUpload.files && videoUpload.files.length > 0;
            if (videoFile && typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Mengompresi & Memproses Video...',
                    html: `
                        <div class="text-center py-2">
                            <div class="spinner-border text-purple mb-3" style="width: 3rem; height: 3rem; color: #43135C;" role="status"></div>
                            <p class="mb-1 fw-bold text-dark" style="font-size: 0.95rem;">Sedang mengompresi ukuran dan mengonversi video ke WebM</p>
                            <p class="mb-2 text-muted" style="font-size: 0.82rem;">Video disimpan ke <code>storage/uploads/videos/</code> dengan codec VP9/Opus agar sangat ringan dan hemat kuota pengguna mobile.</p>
                            <small class="text-warning-emphasis bg-warning-subtle py-1 px-2.5 rounded-pill" style="font-size: 0.75rem;">
                                &bull; Mohon jangan menutup atau me-refresh browser &bull;
                            </small>
                        </div>
                    `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                });
            }
        });
    }
});
</script>
@endsection
