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
                                <option value="{{ $s->id }}" {{ old('series_id', $selectedSeriesId) == $s->id ? 'selected' : '' }}>
                                    {{ $s->title }} ({{ $s->categories->pluck('name')->implode(', ') ?: 'Umum' }})
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
                        <div class="col-12 col-md-4">
                            <label for="episodeNumberInput" class="form-label-custom">Nomor Episode <span class="text-danger">*</span></label>
                            <input type="number" id="episodeNumberInput" name="episode_number" class="form-control-custom @error('episode_number') is-invalid @enderror" value="{{ old('episode_number', $suggestedNumber) }}" min="1" required>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Episode 1 & 2 gratis; 3 ke atas premium.</small>
                            @error('episode_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="videoTitleInput" class="form-label-custom">Judul Episode <span class="text-danger">*</span></label>
                            <input type="text" id="videoTitleInput" name="title" class="form-control-custom @error('title') is-invalid @enderror" placeholder="Misal: Tipe Penumpang Bawel" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="synopsisInput" class="form-label-custom">Sinopsis Cerita / Sketsa</label>
                        <textarea id="synopsisInput" rows="3" name="synopsis" class="form-control-custom @error('synopsis') is-invalid @enderror" placeholder="Ceritakan adegan lucu atau momen unik yang terjadi di episode sketsa ini...">{{ old('synopsis') }}</textarea>
                        @error('synopsis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Durasi Video (~2 Menit Standar) -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label-custom mb-0">Durasi Episode (~2 Menit) <span class="text-danger">*</span></label>
                            <small class="text-purple fw-semibold d-flex align-items-center gap-1" style="color: #7C3AED; font-size: 0.72rem;">
                                <i data-lucide="clock"></i> Standar HUMORA ~2m
                            </small>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="number" name="duration_minutes" class="form-control-custom" value="{{ old('duration_minutes', 2) }}" id="minuteInput" min="0" max="60" required>
                                    <span class="input-group-text bg-light text-muted">Menit</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="number" name="duration_seconds" class="form-control-custom" value="{{ old('duration_seconds', 0) }}" id="secondInput" min="0" max="59" required>
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
    // Preset buttons
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('minuteInput').value = this.dataset.min;
            document.getElementById('secondInput').value = this.dataset.sec;
        });
    });

    // Update access indicator when episode number changes
    const epNumInput = document.getElementById('episodeNumberInput');
    const accessTitle = document.getElementById('accessTitle');
    const accessDesc = document.getElementById('accessDesc');

    function updateAccessNotice() {
        const num = parseInt(epNumInput.value, 10);
        if (num <= 2) {
            accessTitle.innerHTML = '<span class="text-success"><i data-lucide="unlock" class="me-1"></i>Gratis (Free Preview)</span>';
            accessDesc.innerText = 'Episode ini dapat ditonton secara gratis oleh seluruh pengguna (Guest, Free, & VIP).';
        } else {
            accessTitle.innerHTML = '<span class="text-warning-emphasis"><i data-lucide="lock" class="me-1"></i>Premium VIP</span>';
            accessDesc.innerText = 'Episode ini terkunci dan memerlukan paket langganan Premium VIP aktif untuk dapat diputar.';
        }
        if (window.lucide) lucide.createIcons();
    }

    if (epNumInput) {
        epNumInput.addEventListener('input', updateAccessNotice);
        updateAccessNotice();
    }

    // Video preview handler
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

                // Try to auto-populate duration from video metadata
                previewPlayer.onloadedmetadata = function () {
                    const totalSecs = Math.round(previewPlayer.duration);
                    if (totalSecs > 0) {
                        const mins = Math.floor(totalSecs / 60);
                        const secs = totalSecs % 60;
                        document.getElementById('minuteInput').value = mins;
                        document.getElementById('secondInput').value = secs;
                    }
                };
            }
        });
    }
});
</script>
@endsection
