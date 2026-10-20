@extends('layouts.admin')

@section('title', 'Edit Episode #' . $episode->episode_number . ': ' . $episode->title . ' - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ route('admin.videos.index') }}" class="text-decoration-none text-muted">Kelola Video</a> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-muted">Ep #{{ $episode->episode_number }}</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Edit</span>
    </small>
</div>

<!-- Header & Actions -->
<form id="formEditVideo" action="{{ route('admin.videos.update', $episode->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title mb-1">Edit Episode #{{ $episode->episode_number }}: {{ $episode->title }}</h1>
            <p class="page-subtitle mb-0">Series: <strong>{{ $episode->series?->title ?? '-' }}</strong>. Episode 1 & 2 Gratis, Episode 3+ Premium VIP.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.videos.index') }}" class="btn btn-humora-secondary">
                <i data-lucide="arrow-left"></i>
                <span>Batal</span>
            </a>
            <button type="submit" class="btn btn-humora-primary">
                <i data-lucide="save"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-lg-8">
            <!-- 1. PILIH SERIES INDUK -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">1. SERIES INDUK</h5>

                    <div class="mb-3">
                        <label for="seriesSelect" class="form-label-custom">Series Induk <span class="text-danger">*</span></label>
                        <select id="seriesSelect" name="series_id" class="form-select-custom @error('series_id') is-invalid @enderror" required>
                            @foreach ($seriesList as $s)
                                <option value="{{ $s->id }}" {{ old('series_id', $episode->series_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('series_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. DETAIL EPISODE -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 0.95rem;">2. DETAIL EPISODE</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label for="episodeNumberInput" class="form-label-custom">Nomor Episode <span class="text-danger">*</span></label>
                            <input type="number" id="episodeNumberInput" name="episode_number" class="form-control-custom @error('episode_number') is-invalid @enderror" value="{{ old('episode_number', $episode->episode_number) }}" min="1" required>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Ep 1 & 2 Gratis, Ep 3+ Premium</small>
                            @error('episode_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="title" class="form-label-custom">Judul Episode <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control-custom @error('title') is-invalid @enderror" value="{{ old('title', $episode->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="synopsis" class="form-label-custom">Sinopsis Cerita / Sketsa</label>
                        <textarea id="synopsis" rows="3" name="synopsis" class="form-control-custom @error('synopsis') is-invalid @enderror">{{ old('synopsis', $episode->synopsis) }}</textarea>
                        @error('synopsis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Durasi -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label-custom mb-0">Durasi Video <span class="text-danger">*</span></label>
                            <small class="text-purple fw-semibold d-flex align-items-center gap-1" style="color: #7C3AED; font-size: 0.72rem;">
                                <i data-lucide="clock"></i> Standar HUMORA ~2m
                            </small>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="number" name="duration_minutes" class="form-control-custom" value="{{ old('duration_minutes', $minutes) }}" id="minuteInput" min="0" max="60" required>
                                    <span class="input-group-text bg-light text-muted">Menit</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="number" name="duration_seconds" class="form-control-custom" value="{{ old('duration_seconds', $seconds) }}" id="secondInput" min="0" max="59" required>
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

            <!-- 3. BERKAS VIDEO MASTER -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">3. BERKAS VIDEO MASTER</h5>
                        <span class="badge py-1 px-2" style="background: rgba(67, 19, 92, 0.1); color: #43135C; font-size: 0.75rem;">Auto-Kompres & WebM</span>
                    </div>
                    <p class="text-muted mb-2" style="font-size: 0.76rem;">File saat ini: <code>{{ $episode->video_file ?? 'sample-skit.mp4' }}</code></p>
                    <div class="p-2 px-3 rounded-2 mb-3" style="background: #F8FAFC; border: 1px dashed #CBD5E1; font-size: 0.75rem; color: #475569;">
                        <i data-lucide="sparkles" class="text-warning me-1" style="width: 13px; height: 13px;"></i>
                        <strong>Optimalisasi Mobile:</strong> Berkas baru otomatis dikompresi & dikonversi ke format <strong>WebM (.webm)</strong> dengan <u>tetap mempertahankan resolusi aslinya 100% tanpa penurunan resolusi</u>.
                    </div>

                    <label class="form-label-custom">Ganti File Video (Opsional, MP4/WEBM/MOV, Maks 100MB)</label>
                    <input type="file" name="video_file" id="videoUpload" class="form-control-custom @error('video_file') is-invalid @enderror" accept="video/mp4,video/webm,video/quicktime">
                    @error('video_file')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <!-- Pratinjau Video HTML5 -->
                    <div class="mt-3 p-3 bg-light rounded-3 border">
                        <div class="fw-semibold text-dark mb-2" style="font-size: 0.8rem;">Pratinjau Video:</div>
                        <video id="videoPreviewPlayer" controls class="w-100 rounded" style="max-height: 240px; background-color: #000;" src="{{ route('file.stream', $episode->id) }}"></video>
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
                            Episode 1 & 2 adalah preview gratis. Episode 3 ke atas memerlukan paket langganan VIP aktif.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status Publikasi -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.85rem; text-transform: uppercase;">Status Publikasi</h6>
                    <select name="status" class="form-select-custom">
                        <option value="published" {{ old('status', $episode->status) === 'published' ? 'selected' : '' }}>Terpublikasi (Tampil di App)</option>
                        <option value="draft" {{ old('status', $episode->status) === 'draft' ? 'selected' : '' }}>Draf (Disembunyikan)</option>
                    </select>
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

    // Video preview upload handler & notification
    const videoUpload = document.getElementById('videoUpload');
    const previewPlayer = document.getElementById('videoPreviewPlayer');

    if (videoUpload && previewPlayer) {
        videoUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                previewPlayer.src = URL.createObjectURL(file);
                previewPlayer.onloadedmetadata = function () {
                    const totalSecs = Math.round(previewPlayer.duration);
                    if (totalSecs > 0) {
                        document.getElementById('minuteInput').value = Math.floor(totalSecs / 60);
                        document.getElementById('secondInput').value = totalSecs % 60;
                    }
                };

                if (window.showHumoraToast) {
                    const sizeStr = file.size > 1048576 
                        ? (file.size / 1048576).toFixed(1) + ' MB' 
                        : Math.round(file.size / 1024) + ' KB';
                    window.showHumoraToast(`Video baru dipilih: ${file.name} (${sizeStr}). Akan dikompresi ke WebM saat disimpan.`, 'info');
                }
            }
        });
    }

    // Show processing modal during video compression & upload
    const formEditVideo = document.getElementById('formEditVideo');
    if (formEditVideo) {
        formEditVideo.addEventListener('submit', function (e) {
            if (!this.checkValidity()) return;

            const videoFile = videoUpload && videoUpload.files && videoUpload.files.length > 0;
            if (videoFile && typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Mengompresi & Memproses Video...',
                    html: `
                        <div class="text-center py-2">
                            <div class="spinner-border text-purple mb-3" style="width: 3rem; height: 3rem; color: #43135C;" role="status"></div>
                            <p class="mb-1 fw-bold text-dark" style="font-size: 0.95rem;">Sedang mengompresi dan mengonversi video ke WebM</p>
                            <p class="mb-2 text-muted" style="font-size: 0.82rem;">File video baru akan dikompresi ke format <strong>WebM</strong> dan disimpan ke server lokal.</p>
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
