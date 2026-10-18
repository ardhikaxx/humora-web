@extends('layouts.admin')

@section('title', 'Tambah Judul Konten Baru - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ url('/admin/series') }}" class="text-decoration-none text-muted">Judul Konten</a> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Buat Baru</span>
    </small>
</div>

<!-- Header & Top Action Buttons -->
<form id="formCreateSeries" action="{{ url('/admin/series') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title mb-1">Tambah Judul Konten Baru</h1>
            <p class="page-subtitle mb-0">Buat judul besar baru untuk menampung video sketsa pendek di aplikasi mobile HUMORA.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/admin/series') }}" class="btn btn-humora-secondary">
                <i class="bi bi-arrow-left"></i>
                <span>Batal</span>
            </a>
            <button type="submit" name="status" value="draft" class="btn btn-humora-secondary btn-submit-draft">
                <span>Simpan sebagai Draft</span>
            </button>
            <button type="submit" name="status" value="published" class="btn btn-humora-primary btn-submit-publish">
                <i class="bi bi-floppy"></i>
                <span>Simpan & Terbitkan</span>
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-lg-8">
            <!-- 1. INFORMASI JUDUL KONTEN -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 0.95rem;">1. INFORMASI JUDUL KONTEN</h5>

                    <div class="mb-3">
                        <label for="title" class="form-label-custom">Judul Besar <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control-custom" placeholder="Misal: Tipe Penumpang Taxi Online" required>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label-custom">Slug Identifikasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0" style="font-size: 0.85rem; border-radius: 10px 0 0 10px;">humora.id/watch/</span>
                            <input type="text" id="slug" name="slug" class="form-control-custom border-start-0" placeholder="tipe-penumpang-taxi-online" style="border-radius: 0 10px 10px 0;" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="synopsis" class="form-label-custom">Sinopsis & Ringkasan Konten <span class="text-danger">*</span></label>
                        <textarea id="synopsis" name="synopsis" rows="4" class="form-control-custom" placeholder="Ceritakan tema besar, latar belakang, dan daya tarik utama video sketsa ini..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Pilih Genre / Kategori <span class="text-danger">*</span></label>
                        <input type="hidden" name="genre" id="selectedGenreInput" value="Komedi">
                        <div class="d-flex flex-wrap gap-2" id="genreBtnContainer">
                            <button type="button" class="btn btn-sm btn-humora-primary rounded-pill px-3 genre-tag" data-genre="Komedi">Komedi</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-tag" data-genre="Romantis">Romantis</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-tag" data-genre="Drama">Drama</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-tag" data-genre="Slice of Life">Slice of Life</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-tag" data-genre="Workplace">Workplace</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-tag" data-genre="Sketsa">Sketsa</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-tag" data-genre="Keluarga">Keluarga</button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="release_year" class="form-label-custom">Tahun Rilis</label>
                            <input type="text" id="release_year" name="release_year" class="form-control-custom" value="2026">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="catalog_order" class="form-label-custom">Urutan Tampil di Katalog</label>
                            <input type="number" id="catalog_order" name="catalog_order" class="form-control-custom" value="7">
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Terisi otomatis berdasarkan jumlah judul konten.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. THUMBNAIL KONTEN (16:9) -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">2. THUMBNAIL KONTEN (16:9)</h5>
                        <small class="text-muted" style="font-size: 0.76rem;">Format rasio 16:9 terpadu</small>
                    </div>

                    <label class="form-label-custom">Thumbnail Utama (16:9)</label>
                    <div class="humora-dropzone">
                        <div class="humora-dropzone-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">
                            Tarik file ke sini, atau <span class="text-decoration-underline" style="color: #43135C;">pilih dari komputer</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.76rem;">Gambar sampul utama untuk beranda, daftar putar, dan katalog mobile</small>
                        <input type="file" name="thumbnail" class="d-none" id="thumbnailUpload" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options & Live Preview -->
        <div class="col-12 col-lg-4">
            <!-- Pengaturan Publikasi -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-3">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.04em;">Pengaturan Publikasi</h6>
                    <label class="form-label-custom">Status Publikasi</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="status_radio" id="status1" value="published" checked>
                        <label class="btn btn-outline-info text-dark fw-semibold py-2" for="status1" style="background-color: #E0F2FE; border-color: #BAE6FD; font-size: 0.82rem;">Terpublikasi</label>

                        <input type="radio" class="btn-check" name="status_radio" id="status2" value="draft">
                        <label class="btn btn-outline-secondary py-2" for="status2" style="font-size: 0.82rem;">Draft</label>
                    </div>
                </div>
            </div>

            <!-- Pratinjau Kartu 16:9 -->
            <div class="humora-card mb-0">
                <div class="humora-card-header">
                    <span class="humora-card-title" style="font-size: 0.82rem; text-transform: uppercase;">PRATINJAU KARTU 16:9</span>
                    <i class="bi bi-stars text-warning"></i>
                </div>
                <div class="humora-card-body p-3">
                    <div class="rounded-3 p-4 text-center mb-3 position-relative d-flex flex-column align-items-center justify-content-center overflow-hidden" id="previewImgWrap" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; aspect-ratio: 16/9;">
                        <span class="badge-pill-success position-absolute top-0 start-0 m-2" id="previewStatusBadge" style="font-size: 0.68rem; z-index: 2;">
                            <i class="bi bi-check-circle"></i> Terpublikasi
                        </span>
                        <div id="previewEmptyIcon">
                            <i class="bi bi-image text-muted fs-3 mb-1"></i>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Belum ada thumbnail</small>
                        </div>
                        <img id="previewImageTag" src="" alt="Thumbnail Preview" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0 d-none">
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1" id="previewTitle" style="font-size: 0.92rem;">Judul Konten Anda</h6>
                        <p class="text-muted mb-2" id="previewSynopsis" style="font-size: 0.78rem; line-height: 1.4;">Sinopsis judul konten akan tampil di sini saat penonton membuka katalog mobile.</p>
                        <span class="badge-pill-tag" id="previewBadge">Komedi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const synopsisInput = document.getElementById('synopsis');
        const previewTitle = document.getElementById('previewTitle');
        const previewSynopsis = document.getElementById('previewSynopsis');
        const previewBadge = document.getElementById('previewBadge');
        const previewStatusBadge = document.getElementById('previewStatusBadge');
        const genreInput = document.getElementById('selectedGenreInput');

        titleInput.addEventListener('input', function () {
            previewTitle.innerText = this.value || 'Judul Konten Anda';
            slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        });

        synopsisInput.addEventListener('input', function () {
            previewSynopsis.innerText = this.value || 'Sinopsis judul konten akan tampil di sini saat penonton membuka katalog mobile.';
        });

        // Genre buttons
        document.querySelectorAll('.genre-tag').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.genre-tag').forEach(b => {
                    b.classList.remove('btn-humora-primary');
                    b.classList.add('btn-light', 'border', 'text-muted');
                });
                this.classList.remove('btn-light', 'border', 'text-muted');
                this.classList.add('btn-humora-primary');
                previewBadge.innerText = this.dataset.genre;
                if (genreInput) {
                    genreInput.value = this.dataset.genre;
                }
            });
        });

        // Status radio change
        document.querySelectorAll('input[name="status_radio"]').forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'published') {
                    previewStatusBadge.className = 'badge-pill-success position-absolute top-0 start-0 m-2';
                    previewStatusBadge.innerHTML = '<i class="bi bi-check-circle"></i> Terpublikasi';
                } else {
                    previewStatusBadge.className = 'badge-pill-warning position-absolute top-0 start-0 m-2';
                    previewStatusBadge.innerHTML = '<i class="bi bi-clock"></i> Draft';
                }
            });
        });

        // Form submit handler with validation and toast
        document.getElementById('formCreateSeries').addEventListener('submit', function (e) {
            e.preventDefault();
            const title = titleInput.value.trim();
            if (!title) {
                window.showHumoraToast('Judul series wajib diisi!', 'danger');
                titleInput.focus();
                return;
            }
            window.showHumoraToast(`Series "${title}" berhasil disimpan!`);
            setTimeout(() => {
                window.location.href = "{{ url('/admin/series') }}";
            }, 1200);
        });
    });
</script>
@endsection
