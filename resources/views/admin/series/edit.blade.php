@extends('layouts.admin')

@section('title', 'Edit Series: ' . $series->title . ' - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ route('admin.series.index') }}" class="text-decoration-none text-muted">Judul Konten</a> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ route('admin.series.show', $series->id) }}" class="text-decoration-none text-muted">{{ $series->title }}</a> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Edit</span>
    </small>
</div>

<!-- Header & Form -->
<form id="formEditSeries" action="{{ route('admin.series.update', $series->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title mb-1">Edit Series: {{ $series->title }}</h1>
            <p class="page-subtitle mb-0">Perbarui informasi judul, kategori, sinopsis, dan thumbnail 16:9.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.series.show', $series->id) }}" class="btn btn-humora-secondary">
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
            <!-- 1. INFORMASI SERIES -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 0.95rem;">1. INFORMASI JUDUL KONTEN</h5>

                    <div class="mb-3">
                        <label for="title" class="form-label-custom">Judul Series <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control-custom @error('title') is-invalid @enderror" value="{{ old('title', $series->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label-custom">Slug URL <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0" style="font-size: 0.85rem; border-radius: 10px 0 0 10px;">/series/</span>
                            <input type="text" id="slug" name="slug" class="form-control-custom border-start-0 @error('slug') is-invalid @enderror" value="{{ old('slug', $series->slug) }}" style="border-radius: 0 10px 10px 0;" required>
                        </div>
                        @error('slug')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="synopsis" class="form-label-custom">Sinopsis & Ringkasan Konten <span class="text-danger">*</span></label>
                        <textarea id="synopsis" name="synopsis" rows="4" class="form-control-custom @error('synopsis') is-invalid @enderror" required>{{ old('synopsis', $series->synopsis) }}</textarea>
                        @error('synopsis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Kategori / Genre (Minimal 1) <span class="text-danger">*</span></label>
                        @php
                            $selectedCategoryIds = old('categories', $series->categories->pluck('id')->toArray());
                        @endphp
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($categories as $cat)
                            <div class="form-check form-check-inline border rounded-pill px-3 py-1 bg-light">
                                <input class="form-check-input" type="checkbox" name="categories[]" id="cat_{{ $cat->id }}" value="{{ $cat->id }}" {{ in_array($cat->id, $selectedCategoryIds) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark fw-medium" for="cat_{{ $cat->id }}" style="font-size: 0.85rem; cursor: pointer;">{{ $cat->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('categories')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="release_year" class="form-label-custom">Tahun Rilis <span class="text-danger">*</span></label>
                            <input type="text" id="release_year" name="release_year" class="form-control-custom @error('release_year') is-invalid @enderror" value="{{ old('release_year', $series->release_year) }}" required>
                            @error('release_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="catalog_order" class="form-label-custom">Urutan Katalog</label>
                            <input type="number" id="catalog_order" name="catalog_order" class="form-control-custom @error('catalog_order') is-invalid @enderror" value="{{ old('catalog_order', $series->catalog_order) }}" min="0">
                            @error('catalog_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. THUMBNAIL KONTEN (16:9) -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">2. THUMBNAIL KONTEN (16:9)</h5>
                        <span class="badge py-1 px-2" style="background: rgba(67, 19, 92, 0.1); color: #43135C; font-size: 0.75rem;">Auto-Kompres & WebP</span>
                    </div>

                    <label class="form-label-custom">Ganti File Thumbnail (Opsional, Maks 10MB)</label>
                    <input type="file" name="thumbnail" id="thumbnailUpload" class="form-control-custom @error('thumbnail') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                        <i data-lucide="zap" class="text-warning me-1" style="width: 13px; height: 13px;"></i>
                        Gambar baru akan otomatis dikompresi dan dikonversi ke format <strong>WebP (.webp)</strong> berkualitas tinggi.
                    </small>
                    @error('thumbnail')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="col-12 col-lg-4">
            <!-- Pengaturan Status & Unggulan -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.85rem; text-transform: uppercase;">Pengaturan Publikasi</h6>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Status</label>
                        <select name="status" class="form-select-custom">
                            <option value="published" {{ old('status', $series->status) === 'published' ? 'selected' : '' }}>Terpublikasi (Tampil di App)</option>
                            <option value="draft" {{ old('status', $series->status) === 'draft' ? 'selected' : '' }}>Draf (Hanya Admin)</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $series->is_featured) ? 'checked' : '' }} style="cursor: pointer;">
                        <label class="form-check-label fw-bold text-dark" for="is_featured" style="font-size: 0.85rem; cursor: pointer;">
                            Jadikan Series Unggulan (Spotlight Hero)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Pratinjau Thumbnail 16:9 -->
            <div class="humora-card mb-0">
                <div class="humora-card-header">
                    <span class="humora-card-title" style="font-size: 0.82rem; text-transform: uppercase;">PRATINJAU KARTU 16:9</span>
                    <i data-lucide="image" class="text-purple"></i>
                </div>
                <div class="humora-card-body p-3">
                    <div class="rounded-3 p-2 text-center mb-2 position-relative d-flex flex-column align-items-center justify-content-center overflow-hidden bg-light" style="aspect-ratio: 16/9; border: 1px dashed #CBD5E1;">
                        <img id="imagePreview" src="{{ $series->thumbnail_url }}" alt="Preview" class="w-100 h-100 object-fit-cover rounded" onerror="this.src='{{ asset('images/logo-humora.png') }}'">
                    </div>
                    <small class="text-muted d-block text-center" style="font-size: 0.75rem;">Thumbnail saat ini tersimpan di storage lokal</small>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
{{-- Script kompresi WebP client-side dihandle otomatis oleh humora-admin.js --}}
@endsection
