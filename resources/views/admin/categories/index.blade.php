@extends('layouts.admin')

@section('title', 'Manajemen Kategori & Genre - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Kategori</span>
    </small>
</div>

<!-- Header & Top Action Buttons -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Manajemen Kategori & Genre</h1>
        <p class="page-subtitle mb-0">Atur kategori dan genre sketsa komedi untuk filter eksplorasi di aplikasi mobile.</p>
    </div>
    <button type="button" class="btn btn-humora-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i data-lucide="plus"></i>
        <span>Tambah Kategori</span>
    </button>
</div>

<!-- Search Bar -->
<div class="humora-card mb-4 p-3">
    <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex gap-2">
        <div class="input-group flex-grow-1">
            <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="search" style="width: 16px; height: 16px;"></i></span>
            <input type="text" name="search" class="form-control-custom border-start-0" placeholder="Cari nama kategori atau deskripsi..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-humora-primary px-3">Cari</button>
        @if (request('search'))
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-2" title="Reset">
                <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
            </a>
        @endif
    </form>
</div>

<!-- Category Table Card -->
<div class="humora-card mb-0">
    <div class="humora-card-header d-flex justify-content-between align-items-center">
        <div>
            <div class="humora-card-title">DAFTAR KATEGORI ({{ $categories->total() }})</div>
            <div class="humora-card-subtitle">Kategori aktif langsung tersedia sebagai filter genre di aplikasi Flutter</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Series Terkait</th>
                    <th>Status</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $cat)
                <tr>
                    <td>
                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $cat->name }}</span>
                    </td>
                    <td>
                        <code style="font-size: 0.78rem;">/{{ $cat->slug }}</code>
                    </td>
                    <td>
                        <span class="text-muted text-truncate d-inline-block" style="max-width: 280px; font-size: 0.8rem;">
                            {{ $cat->description ?: '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1 fs-6">
                            {{ $cat->series_count }} Series
                        </span>
                    </td>
                    <td>
                        @if ($cat->is_active)
                            <span class="badge-pill-success" style="font-size: 0.72rem;">Aktif</span>
                        @else
                            <span class="badge bg-secondary text-white px-2 py-1 rounded-pill" style="font-size: 0.72rem;">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button type="button" class="btn-humora-icon" title="Edit Kategori" 
                                onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->slug) }}', '{{ addslashes($cat->description ?? '') }}', {{ $cat->is_active ? 1 : 0 }})">
                                <i data-lucide="pencil"></i>
                            </button>
                            <button type="button" class="btn-humora-icon danger" title="Hapus Kategori" 
                                onclick="confirmDeleteCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->series_count }})">
                                <i data-lucide="trash-2"></i>
                            </button>
                            <form id="delete-cat-{{ $cat->id }}" action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <x-empty-state 
                    colspan="6"
                    icon="tag"
                    title="Belum Ada Kategori"
                    subtitle="Belum ada kategori sketsa komedi yang terdaftar. Tambahkan kategori baru untuk mengelompokkan series komedi."
                    actionTarget="#addCategoryModal"
                    actionText="Tambah Kategori Baru"
                    actionIcon="plus"
                    :resetUrl="request()->has('search') ? route('admin.categories.index') : null"
                    resetText="Reset Filter"
                />
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($categories->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $categories->links('vendor.pagination.humora') }}
    </div>
    @endif
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="modal-content modal-content-humora">
            @csrf
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="text" name="name" id="addCategoryName" class="form-control-custom" placeholder=" " required>
                        <label for="addCategoryName" class="humora-floating-label">Nama Kategori <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="text" name="slug" id="addCategorySlug" class="form-control-custom" placeholder=" ">
                        <label for="addCategorySlug" class="humora-floating-label">Slug (Opsional)</label>
                    </div>
                </div>
                <div class="humora-field-group mb-3">
                    <div class="humora-field is-textarea">
                        <textarea name="description" id="addCategoryDesc" rows="3" class="form-control-custom" placeholder=" "></textarea>
                        <label for="addCategoryDesc" class="humora-floating-label">Deskripsi</label>
                    </div>
                </div>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="addIsActive" checked style="cursor: pointer;">
                    <label class="form-check-label fw-bold text-dark" for="addIsActive" style="font-size: 0.85rem; cursor: pointer;">
                        Kategori Aktif (Tampil di App)
                    </label>
                </div>
            </div>
            <div class="modal-footer modal-footer-humora">
                <button type="button" class="btn btn-sm btn-humora-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-humora-primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editCategoryForm" action="" method="POST" class="modal-content modal-content-humora">
            @csrf
            @method('PUT')
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="text" id="editName" name="name" class="form-control-custom" placeholder=" " required>
                        <label for="editName" class="humora-floating-label">Nama Kategori <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="text" id="editSlug" name="slug" class="form-control-custom" placeholder=" " required>
                        <label for="editSlug" class="humora-floating-label">Slug URL <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="humora-field-group mb-3">
                    <div class="humora-field is-textarea">
                        <textarea id="editDesc" name="description" rows="3" class="form-control-custom" placeholder=" "></textarea>
                        <label for="editDesc" class="humora-floating-label">Deskripsi</label>
                    </div>
                </div>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editIsActive" style="cursor: pointer;">
                    <label class="form-check-label fw-bold text-dark" for="editIsActive" style="font-size: 0.85rem; cursor: pointer;">
                        Kategori Aktif
                    </label>
                </div>
            </div>
            <div class="modal-footer modal-footer-humora">
                <button type="button" class="btn btn-sm btn-humora-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-humora-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openEditModal(id, name, slug, desc, isActive) {
    const form = document.getElementById('editCategoryForm');
    form.action = `/admin/categories/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editSlug').value = slug;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editIsActive').checked = isActive === 1;

    ['editName', 'editSlug', 'editDesc'].forEach(fieldId => {
        const el = document.getElementById(fieldId);
        if (el) el.dispatchEvent(new Event('input', { bubbles: true }));
    });

    const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
    modal.show();
}

function confirmDeleteCategory(id, name, count) {
    if (count > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Tidak Dapat Dihapus',
            text: `Kategori '${name}' sedang digunakan oleh ${count} series aktif. Harap ubah kategori series tersebut terlebih dahulu.`,
            confirmButtonColor: '#43135C'
        });
        return;
    }

    Swal.fire({
        title: 'Hapus Kategori?',
        text: `Apakah Anda yakin ingin menghapus kategori '${name}'?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-cat-${id}`).submit();
        }
    });
}
</script>
@endsection
