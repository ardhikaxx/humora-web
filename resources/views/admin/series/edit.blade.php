@extends('layouts.admin')

@section('title', 'Edit Series: Tipe Pembeli di Toko Kelontong - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ url('/admin/series') }}" class="text-decoration-none text-muted">Series</a> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ url('/admin/series/1') }}" class="text-decoration-none text-muted">Tipe Pembeli di Toko Kelontong</a> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Edit</span>
    </small>
</div>

<!-- Header & Top Action Buttons -->
<form id="formEditSeries" action="{{ url('/admin/series/1') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title mb-1">Edit Series: Tipe Pembeli di Toko Kelontong</h1>
            <p class="page-subtitle mb-0">Perbarui informasi sinopsis, thumbnail, genre, dan detail judul konten.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/admin/series/1') }}" class="btn btn-humora-secondary">
                <i class="bi bi-arrow-left"></i>
                <span>Batal</span>
            </a>
            <button type="submit" class="btn btn-humora-primary">
                <i class="bi bi-floppy"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-lg-8">
            <!-- INFORMASI SERIES -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 0.95rem;">INFORMASI SERIES</h5>

                    <div class="mb-3">
                        <label for="series_title" class="form-label-custom">Judul Series</label>
                        <input type="text" id="series_title" name="title" class="form-control-custom" value="Tipe Pembeli di Toko Kelontong" required>
                    </div>

                    <div class="mb-3">
                        <label for="series_slug" class="form-label-custom">Slug URL</label>
                        <input type="text" id="series_slug" name="slug" class="form-control-custom" value="tipe-pembeli-di-toko-kelontong" required>
                    </div>

                    <div class="mb-4">
                        <label for="series_synopsis" class="form-label-custom">Sinopsis</label>
                        <textarea id="series_synopsis" name="synopsis" rows="4" class="form-control-custom">Toko kelontong Madura buka 24 jam jadi saksi bisu berbagai keajaiban dan tingkah nyeleneh para pelanggan setia di tengah malam.</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Pilih Genre</label>
                        <input type="hidden" name="genre" id="editGenreInput" value="Komedi">
                        <div class="d-flex flex-wrap gap-2" id="editGenreBtns">
                            <button type="button" class="btn btn-sm btn-humora-primary rounded-pill px-3 genre-pill-edit" data-genre="Komedi">Komedi</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-pill-edit" data-genre="Romantis">Romantis</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-pill-edit" data-genre="Drama">Drama</button>
                            <button type="button" class="btn btn-sm btn-humora-primary rounded-pill px-3 genre-pill-edit" data-genre="Slice of Life">Slice of Life</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-pill-edit" data-genre="Persahabatan">Persahabatan</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 text-muted genre-pill-edit" data-genre="Keluarga">Keluarga</button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="release_year" class="form-label-custom">Tahun Rilis</label>
                            <input type="text" id="release_year" name="release_year" class="form-control-custom" value="2026">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="catalog_order" class="form-label-custom">Urutan Katalog</label>
                            <input type="number" id="catalog_order" name="catalog_order" class="form-control-custom" value="6">
                        </div>
                    </div>
                </div>
            </div>

            <!-- THUMBNAIL KONTEN (16:9) -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">THUMBNAIL KONTEN (16:9)</h5>
                    <label class="form-label-custom mb-2">Thumbnail Utama (16:9)</label>

                    <div id="existingThumbnailWrap" class="rounded-3 p-3 position-relative" style="background-color: #FAF8FD; border: 2px dashed #A7F3D0;">
                        <div class="mb-3 overflow-hidden rounded-3" style="max-height: 240px;">
                            <img src="{{ asset('images/thumbnails/tipe-pembeli-di-toko-kelontong.jpg') }}" alt="Tipe Pembeli" class="w-100 object-fit-cover" id="currentThumbImg">
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded-3 border">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-image text-success fs-5"></i>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.82rem;">tipe-pembeli-di-toko-kelontong.jpg</div>
                                    <small class="text-muted" style="font-size: 0.72rem;">Aset bawaan (16:9)</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" id="btnRemoveExistingThumb" title="Ganti foto">
                                <i class="bi bi-arrow-repeat me-1"></i> Ganti
                            </button>
                        </div>
                    </div>

                    <div class="humora-dropzone d-none mt-3" id="editDropzone">
                        <div class="humora-dropzone-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">
                            Tarik file baru ke sini, atau <span class="text-decoration-underline" style="color: #43135C;">pilih dari komputer</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.76rem;">Format gambar rasio 16:9 (JPG/PNG/WEBP)</small>
                        <input type="file" name="thumbnail" class="d-none" id="editThumbnailInput" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="col-12 col-lg-4">
            <div class="humora-card mb-4">
                <div class="humora-card-body p-3">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.04em;">Pengaturan Publikasi</h6>
                    <label class="form-label-custom">Status Publikasi</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="status_pub" id="status1" value="published" checked>
                        <label class="btn btn-outline-info text-dark fw-semibold py-2" for="status1" style="background-color: #E0F2FE; border-color: #BAE6FD; font-size: 0.82rem;">Terpublikasi</label>

                        <input type="radio" class="btn-check" name="status_pub" id="status2" value="draft">
                        <label class="btn btn-outline-secondary py-2" for="status2" style="font-size: 0.82rem;">Draft</label>
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
        const btnRemoveThumb = document.getElementById('btnRemoveExistingThumb');
        const existingWrap = document.getElementById('existingThumbnailWrap');
        const editDropzone = document.getElementById('editDropzone');
        const editThumbInput = document.getElementById('editThumbnailInput');
        const genreInput = document.getElementById('editGenreInput');

        if (btnRemoveThumb && editDropzone) {
            btnRemoveThumb.addEventListener('click', function () {
                existingWrap.classList.add('d-none');
                editDropzone.classList.remove('d-none');
                editThumbInput.click();
            });
        }

        document.querySelectorAll('.genre-pill-edit').forEach(btn => {
            btn.addEventListener('click', function () {
                this.classList.toggle('btn-humora-primary');
                this.classList.toggle('btn-light');
                this.classList.toggle('border');
                this.classList.toggle('text-muted');

                const selected = Array.from(document.querySelectorAll('.genre-pill-edit.btn-humora-primary')).map(b => b.dataset.genre);
                if (genreInput) {
                    genreInput.value = selected.join(', ');
                }
            });
        });

        document.getElementById('formEditSeries').addEventListener('submit', function (e) {
            e.preventDefault();
            const title = document.getElementById('series_title').value.trim();
            window.showHumoraToast(`Series "${title}" berhasil diperbarui!`);
            setTimeout(() => {
                window.location.href = "{{ url('/admin/series/1') }}";
            }, 1200);
        });
    });
</script>
@endsection
