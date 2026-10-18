@extends('layouts.admin')

@section('title', 'Manajemen Kategori & Genre - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Kategori</span>
    </small>
</div>

<!-- Header & Top Action Buttons -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Manajemen Kategori & Genre</h1>
        <p class="page-subtitle mb-0">Atur kategori dan genre cerita untuk klasifikasi series dan filter eksplorasi di aplikasi mobile.</p>
    </div>
    <button type="button" class="btn btn-humora-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Kategori</span>
    </button>
</div>

<!-- Search Bar -->
<div class="humora-card mb-4 p-3">
    <div class="search-input-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="categorySearchInput" class="form-control-custom bg-light" placeholder="Cari nama kategori, slug url, atau deskripsi genre...">
    </div>
</div>

<!-- Category Table Card -->
<div class="humora-card mb-0">
    <div class="humora-card-header">
        <div>
            <div class="humora-card-title" id="categoryHeaderCount">DAFTAR KATEGORI (6)</div>
            <div class="humora-card-subtitle">Kategori yang aktif langsung tersedia pada form pembuatan series dan episode.</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table-humora" id="categoriesTable">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Series</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1 -->
                <tr class="category-row"
                    data-name="Komedi"
                    data-slug="komedi"
                    data-desc="Cerita jenaka, tingkah kocak sehari-hari, dan situasi absurd yang mengocok perut."
                    data-status="1">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="fw-bold text-dark cat-name">Komedi</span>
                        </div>
                    </td>
                    <td><code class="cat-slug">/komedi</code></td>
                    <td>
                        <span class="text-muted text-truncate d-inline-block cat-desc" style="max-width: 320px;">
                            Cerita jenaka, tingkah kocak sehari-hari, dan situasi absurd yang mengocok perut.
                        </span>
                    </td>
                    <td>
                        <span class="badge-pill-tag">6 Series</span>
                    </td>
                    <td class="cat-status-cell">
                        <span class="badge-pill-success"><i class="bi bi-check-circle"></i> Aktif</span>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">10 Jan 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-category" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Kategori Komedi" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 2 -->
                <tr class="category-row"
                    data-name="Romantis"
                    data-slug="romantis"
                    data-desc="Kisah cinta hangat, manis, baper, dan penuh dinamika hubungan anak muda."
                    data-status="1">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="fw-bold text-dark cat-name">Romantis</span>
                        </div>
                    </td>
                    <td><code class="cat-slug">/romantis</code></td>
                    <td>
                        <span class="text-muted text-truncate d-inline-block cat-desc" style="max-width: 320px;">
                            Kisah cinta hangat, manis, baper, dan penuh dinamika hubungan anak muda.
                        </span>
                    </td>
                    <td>
                        <span class="badge-pill-tag">2 Series</span>
                    </td>
                    <td class="cat-status-cell">
                        <span class="badge-pill-success"><i class="bi bi-check-circle"></i> Aktif</span>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">10 Jan 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-category" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Kategori Romantis" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 3 -->
                <tr class="category-row"
                    data-name="Drama"
                    data-slug="drama"
                    data-desc="Konflik emosional, rahasia keluarga, dan intrik personal yang menyentuh hati."
                    data-status="1">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="fw-bold text-dark cat-name">Drama</span>
                        </div>
                    </td>
                    <td><code class="cat-slug">/drama</code></td>
                    <td>
                        <span class="text-muted text-truncate d-inline-block cat-desc" style="max-width: 320px;">
                            Konflik emosional, rahasia keluarga, dan intrik personal yang menyentuh hati.
                        </span>
                    </td>
                    <td>
                        <span class="badge-pill-tag">0 Series</span>
                    </td>
                    <td class="cat-status-cell">
                        <span class="badge-pill-success"><i class="bi bi-check-circle"></i> Aktif</span>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">12 Jan 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-category" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Kategori Drama" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 4 -->
                <tr class="category-row"
                    data-name="Slice of Life"
                    data-slug="slice-of-life"
                    data-desc="Potret realistis kehidupan anak kos, pekerja urban, dan tetangga komplek."
                    data-status="1">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="fw-bold text-dark cat-name">Slice of Life</span>
                        </div>
                    </td>
                    <td><code class="cat-slug">/slice-of-life</code></td>
                    <td>
                        <span class="text-muted text-truncate d-inline-block cat-desc" style="max-width: 320px;">
                            Potret realistis kehidupan anak kos, pekerja urban, dan tetangga komplek.
                        </span>
                    </td>
                    <td>
                        <span class="badge-pill-tag">3 Series</span>
                    </td>
                    <td class="cat-status-cell">
                        <span class="badge-pill-success"><i class="bi bi-check-circle"></i> Aktif</span>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">15 Jan 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-category" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Kategori Slice of Life" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 5 -->
                <tr class="category-row"
                    data-name="Persahabatan"
                    data-slug="persahabatan"
                    data-desc="Kompak, heboh, dan saling mendukung di antara sahabat seperjuangan."
                    data-status="1">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="fw-bold text-dark cat-name">Persahabatan</span>
                        </div>
                    </td>
                    <td><code class="cat-slug">/persahabatan</code></td>
                    <td>
                        <span class="text-muted text-truncate d-inline-block cat-desc" style="max-width: 320px;">
                            Kompak, heboh, dan saling mendukung di antara sahabat seperjuangan.
                        </span>
                    </td>
                    <td>
                        <span class="badge-pill-tag">0 Series</span>
                    </td>
                    <td class="cat-status-cell">
                        <span class="badge-pill-success"><i class="bi bi-check-circle"></i> Aktif</span>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">18 Jan 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-category" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Kategori Persahabatan" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- 6 -->
                <tr class="category-row"
                    data-name="Keluarga"
                    data-slug="keluarga"
                    data-desc="Dinamika grup WhatsApp keluarga besar, arisan heboh, dan petuah orang tua."
                    data-status="1">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                            <span class="fw-bold text-dark cat-name">Keluarga</span>
                        </div>
                    </td>
                    <td><code class="cat-slug">/keluarga</code></td>
                    <td>
                        <span class="text-muted text-truncate d-inline-block cat-desc" style="max-width: 320px;">
                            Dinamika grup WhatsApp keluarga besar, arisan heboh, dan petuah orang tua.
                        </span>
                    </td>
                    <td>
                        <span class="badge-pill-tag">0 Series</span>
                    </td>
                    <td class="cat-status-cell">
                        <span class="badge-pill-success"><i class="bi bi-check-circle"></i> Aktif</span>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">20 Jan 2026</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-category" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Kategori Keluarga" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah Kategori Baru -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora mb-0" id="addCategoryModalLabel">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAddCategory" action="{{ url('/admin/categories') }}" method="POST">
                @csrf
                <div class="modal-body modal-body-humora">
                    <div class="mb-3">
                        <label for="new_category_name" class="form-label-custom">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" id="new_category_name" name="name" class="form-control-custom" placeholder="Misal: Horor Komedi, Petualangan..." required>
                    </div>
                    <div class="mb-3">
                        <label for="new_category_slug" class="form-label-custom">Slug URL <span class="text-danger">*</span></label>
                        <input type="text" id="new_category_slug" name="slug" class="form-control-custom" placeholder="horor-komedi" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_category_desc" class="form-label-custom">Deskripsi Kategori</label>
                        <textarea id="new_category_desc" name="description" rows="3" class="form-control-custom" placeholder="Penjelasan ringkas mengenai genre ini..."></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeInCatalog" checked style="accent-color: #43135C; cursor: pointer;">
                        <label class="form-check-label" for="activeInCatalog" style="font-size: 0.82rem; color: #374151; cursor: pointer;">
                            Aktifkan kategori ini di filter katalog
                        </label>
                    </div>
                </div>
                <div class="modal-footer modal-footer-humora">
                    <button type="button" class="btn btn-humora-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        <span>Batal</span>
                    </button>
                    <button type="submit" class="btn btn-humora-primary">
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Kategori</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora mb-0" id="editCategoryModalLabel">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditCategory" action="{{ url('/admin/categories') }}" method="POST">
                @csrf
                <div class="modal-body modal-body-humora">
                    <div class="mb-3">
                        <label for="edit_category_name" class="form-label-custom">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" id="edit_category_name" name="name" class="form-control-custom" value="Komedi" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_slug" class="form-label-custom">Slug URL <span class="text-danger">*</span></label>
                        <input type="text" id="edit_category_slug" name="slug" class="form-control-custom" value="komedi" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_desc" class="form-label-custom">Deskripsi Kategori</label>
                        <textarea id="edit_category_desc" name="description" rows="3" class="form-control-custom">Cerita jenaka, tingkah kocak sehari-hari, dan situasi absurd yang mengocok perut.</textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editActiveInCatalog" checked style="accent-color: #43135C; cursor: pointer;">
                        <label class="form-check-label" for="editActiveInCatalog" style="font-size: 0.82rem; color: #374151; cursor: pointer;">
                            Aktifkan kategori ini di filter katalog
                        </label>
                    </div>
                </div>
                <div class="modal-footer modal-footer-humora">
                    <button type="button" class="btn btn-humora-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        <span>Batal</span>
                    </button>
                    <button type="submit" class="btn btn-humora-primary">
                        <i class="bi bi-floppy"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let activeEditCategoryRow = null;
        let totalCategories = 6;

        const addModalEl = document.getElementById('addCategoryModal');
        const editModalEl = document.getElementById('editCategoryModal');
        const bsAddModal = new bootstrap.Modal(addModalEl);
        const bsEditModal = new bootstrap.Modal(editModalEl);

        const newNameInput = document.getElementById('new_category_name');
        const newSlugInput = document.getElementById('new_category_slug');
        const newDescInput = document.getElementById('new_category_desc');
        const newActiveInput = document.getElementById('activeInCatalog');

        const editNameInput = document.getElementById('edit_category_name');
        const editSlugInput = document.getElementById('edit_category_slug');
        const editDescInput = document.getElementById('edit_category_desc');
        const editActiveInput = document.getElementById('editActiveInCatalog');

        const categoryHeaderCount = document.getElementById('categoryHeaderCount');
        const categoriesTableBody = document.querySelector('#categoriesTable tbody');

        // Auto-slug on typing new category
        newNameInput.addEventListener('input', function () {
            newSlugInput.value = this.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        });

        // Edit button handler
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-edit-category');
            if (btn) {
                e.preventDefault();
                activeEditCategoryRow = btn.closest('tr');
                if (!activeEditCategoryRow) return;

                const name = activeEditCategoryRow.dataset.name;
                const slug = activeEditCategoryRow.dataset.slug;
                const desc = activeEditCategoryRow.dataset.desc;
                const status = activeEditCategoryRow.dataset.status === '1';

                editNameInput.value = name;
                editSlugInput.value = slug;
                editDescInput.value = desc;
                editActiveInput.checked = status;

                bsEditModal.show();
            }
        });

        // Add Category form submit
        document.getElementById('formAddCategory').addEventListener('submit', function (e) {
            e.preventDefault();
            const name = newNameInput.value.trim();
            const slug = newSlugInput.value.trim();
            const desc = newDescInput.value.trim() || 'Kategori konten sketsa HUMORA.';
            const isActive = newActiveInput.checked;

            totalCategories += 1;
            categoryHeaderCount.innerText = `DAFTAR KATEGORI (${totalCategories})`;

            const tr = document.createElement('tr');
            tr.className = 'category-row';
            tr.dataset.name = name;
            tr.dataset.slug = slug;
            tr.dataset.desc = desc;
            tr.dataset.status = isActive ? '1' : '0';
            tr.innerHTML = `
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #43135C;"></span>
                        <span class="fw-bold text-dark cat-name">${name}</span>
                    </div>
                </td>
                <td><code class="cat-slug">/${slug}</code></td>
                <td>
                    <span class="text-muted text-truncate d-inline-block cat-desc" style="max-width: 320px;">${desc}</span>
                </td>
                <td><span class="badge-pill-tag">0 Series</span></td>
                <td class="cat-status-cell">
                    ${isActive 
                        ? '<span class="badge-pill-success"><i class="bi bi-check-circle"></i> Aktif</span>' 
                        : '<span class="badge-pill-gray">Nonaktif</span>'}
                </td>
                <td class="text-muted" style="font-size: 0.8rem;">Hari ini</td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn-humora-icon btn-edit-category" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Kategori ${name}" title="Hapus"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            `;
            categoriesTableBody.appendChild(tr);

            // Reset and close
            newNameInput.value = '';
            newSlugInput.value = '';
            newDescInput.value = '';
            bsAddModal.hide();

            window.showHumoraToast(`Kategori "${name}" berhasil ditambahkan!`);
        });

        // Edit Category form submit
        document.getElementById('formEditCategory').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!activeEditCategoryRow) return;

            const name = editNameInput.value.trim();
            const slug = editSlugInput.value.trim();
            const desc = editDescInput.value.trim();
            const isActive = editActiveInput.checked;

            activeEditCategoryRow.dataset.name = name;
            activeEditCategoryRow.dataset.slug = slug;
            activeEditCategoryRow.dataset.desc = desc;
            activeEditCategoryRow.dataset.status = isActive ? '1' : '0';

            const nameEl = activeEditCategoryRow.querySelector('.cat-name');
            const slugEl = activeEditCategoryRow.querySelector('.cat-slug');
            const descEl = activeEditCategoryRow.querySelector('.cat-desc');
            const statusCell = activeEditCategoryRow.querySelector('.cat-status-cell');
            const delBtn = activeEditCategoryRow.querySelector('[data-confirm-delete]');

            if (nameEl) nameEl.innerText = name;
            if (slugEl) slugEl.innerText = `/${slug}`;
            if (descEl) descEl.innerText = desc;
            if (delBtn) delBtn.setAttribute('data-item-name', `Kategori ${name}`);
            if (statusCell) {
                statusCell.innerHTML = isActive 
                    ? '<span class="badge-pill-success"><i class="bi bi-check-circle"></i> Aktif</span>' 
                    : '<span class="badge-pill-gray">Nonaktif</span>';
            }

            bsEditModal.hide();
            window.showHumoraToast(`Perubahan kategori "${name}" berhasil disimpan!`);
        });
    });
</script>
@endsection
