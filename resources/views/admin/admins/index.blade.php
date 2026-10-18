@extends('layouts.admin')

@section('title', 'Kelola Tim Admin - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-muted">Sistem</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Kelola Admin</span>
    </small>
</div>

<!-- Header & Top Action Button -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Kelola Tim Admin</h1>
        <p class="page-subtitle mb-0">Kelola akun administrator HUMORA. Data yang dikelola meliputi Nama Lengkap, Email, Password, dan Nomor Telfon.</p>
    </div>
    <button type="button" class="btn btn-humora-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
        <i data-lucide="user-plus"></i>
        <span>Tambah Admin Baru</span>
    </button>
</div>

<!-- 3 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <x-stat-card 
            id="cardTotalAdmin"
            label="Total Admin" 
            value="3" 
            icon="users" 
            iconBg="purple" 
            subtext="Seluruh akun terdaftar" 
        />
    </div>
    <div class="col-12 col-md-4">
        <x-stat-card 
            label="Admin Pertama (Utama)" 
            value="1" 
            icon="shield-check" 
            iconBg="green" 
            subtext="Akun pengelola pertama" 
        />
    </div>
    <div class="col-12 col-md-4">
        <x-stat-card 
            id="cardAdditionalAdmin"
            label="Admin Tambahan" 
            value="2" 
            icon="user-plus" 
            iconBg="blue" 
            subtext="Admin baru yang ditambahkan" 
        />
    </div>
</div>

<!-- Search Bar -->
<div class="humora-card mb-4 p-3">
    <div class="search-input-wrap">
        <i data-lucide="search"></i>
        <input type="text" id="adminSearchInput" class="form-control-custom bg-light" placeholder="Cari berdasarkan nama lengkap, email, atau nomor telfon...">
    </div>
</div>

<!-- Admins Table Card -->
<div class="humora-card mb-0">
    <div class="table-responsive">
        <table class="table-humora" id="adminsTable">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Nomor Telfon</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Admin 1 (Admin Utama) -->
                <tr class="admin-row"
                    data-name="Admin HUMORA"
                    data-email="admin@humora.id"
                    data-phone="0812-8899-7711"
                    data-initials="AH"
                    data-is-main="1">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="sidebar-avatar-circle" style="width: 36px; height: 36px;">
                                AH
                            </div>
                            <div>
                                <span class="fw-bold text-dark me-2 admin-name">Admin HUMORA</span>
                                <span class="badge-pill-tag">Admin Pertama</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i data-lucide="mail"></i>
                            <span class="admin-email">admin@humora.id</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i data-lucide="phone"></i>
                            <span class="admin-phone">0812-8899-7711</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-admin" title="Edit"><i data-lucide="pencil"></i></button>
                            <button class="btn-humora-icon" disabled style="opacity: 0.3;" title="Admin utama tidak dapat dihapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Admin 2 -->
                <tr class="admin-row"
                    data-name="Sarah Wijaya"
                    data-email="sarah.w@humora.id"
                    data-phone="0813-7722-1144"
                    data-initials="SW"
                    data-is-main="0">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="sidebar-avatar-circle" style="width: 36px; height: 36px; background-color: #6366F1;">
                                SW
                            </div>
                            <span class="fw-bold text-dark admin-name">Sarah Wijaya</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i data-lucide="mail"></i>
                            <span class="admin-email">sarah.w@humora.id</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i data-lucide="phone"></i>
                            <span class="admin-phone">0813-7722-1144</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-admin" title="Edit"><i data-lucide="pencil"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Admin Sarah Wijaya" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- Admin 3 -->
                <tr class="admin-row"
                    data-name="Rian Pratama"
                    data-email="rian.p@humora.id"
                    data-phone="0818-5544-2200"
                    data-initials="RP"
                    data-is-main="0">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="sidebar-avatar-circle" style="width: 36px; height: 36px; background-color: #8B5CF6;">
                                RP
                            </div>
                            <span class="fw-bold text-dark admin-name">Rian Pratama</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i data-lucide="mail"></i>
                            <span class="admin-email">rian.p@humora.id</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i data-lucide="phone"></i>
                            <span class="admin-phone">0818-5544-2200</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn-humora-icon btn-edit-admin" title="Edit"><i data-lucide="pencil"></i></button>
                            <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Admin Rian Pratama" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah Admin Baru -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora mb-0">Tambah Admin Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAddAdmin" action="{{ url('/admin/admins') }}" method="POST">
                @csrf
                <div class="modal-body modal-body-humora">
                    <div class="mb-3">
                        <label for="admin_name" class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" id="admin_name" name="name" class="form-control-custom" placeholder="Nama Lengkap Administrator" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_email" class="form-label-custom">Email <span class="text-danger">*</span></label>
                        <input type="email" id="admin_email" name="email" class="form-control-custom" placeholder="nama@humora.id" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_phone" class="form-label-custom">Nomor Telfon <span class="text-danger">*</span></label>
                        <input type="text" id="admin_phone" name="phone" class="form-control-custom" placeholder="+62 812-xxxx-xxxx" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_pass" class="form-label-custom">Password Sementara <span class="text-danger">*</span></label>
                        <input type="password" id="admin_pass" name="password" class="form-control-custom" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="modal-footer modal-footer-humora">
                    <button type="button" class="btn btn-humora-secondary" data-bs-dismiss="modal">
                        <i data-lucide="x"></i>
                        <span>Batal</span>
                    </button>
                    <button type="submit" class="btn btn-humora-primary">
                        <i data-lucide="user-plus"></i>
                        <span>Simpan Admin</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Admin -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora mb-0">Edit Informasi Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditAdmin" action="{{ url('/admin/admins') }}" method="POST">
                @csrf
                <div class="modal-body modal-body-humora">
                    <div class="mb-3">
                        <label for="edit_admin_name" class="form-label-custom">Nama Lengkap</label>
                        <input type="text" id="edit_admin_name" name="name" class="form-control-custom" value="Admin HUMORA" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_admin_email" class="form-label-custom">Email</label>
                        <input type="email" id="edit_admin_email" name="email" class="form-control-custom" value="admin@humora.id" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_admin_phone" class="form-label-custom">Nomor Telfon</label>
                        <input type="text" id="edit_admin_phone" name="phone" class="form-control-custom" value="+62 812-8899-7711" required>
                    </div>
                </div>
                <div class="modal-footer modal-footer-humora">
                    <button type="button" class="btn btn-humora-secondary" data-bs-dismiss="modal">
                        <i data-lucide="x"></i>
                        <span>Batal</span>
                    </button>
                    <button type="submit" class="btn btn-humora-primary">
                        <i data-lucide="save"></i>
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
        let activeEditAdminRow = null;
        let totalAdmins = 3;
        let additionalAdmins = 2;

        const addAdminModalEl = document.getElementById('addAdminModal');
        const editAdminModalEl = document.getElementById('editAdminModal');
        const bsAddAdminModal = new bootstrap.Modal(addAdminModalEl);
        const bsEditAdminModal = new bootstrap.Modal(editAdminModalEl);

        const editNameInput = document.getElementById('edit_admin_name');
        const editEmailInput = document.getElementById('edit_admin_email');
        const editPhoneInput = document.getElementById('edit_admin_phone');

        const cardTotalAdmin = document.querySelector('#cardTotalAdmin .stat-value');
        const cardAdditionalAdmin = document.querySelector('#cardAdditionalAdmin .stat-value');
        const adminsTableBody = document.querySelector('#adminsTable tbody');

        // Edit Admin Click
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-edit-admin');
            if (btn) {
                e.preventDefault();
                activeEditAdminRow = btn.closest('tr');
                if (!activeEditAdminRow) return;

                editNameInput.value = activeEditAdminRow.dataset.name;
                editEmailInput.value = activeEditAdminRow.dataset.email;
                editPhoneInput.value = activeEditAdminRow.dataset.phone;

                bsEditAdminModal.show();
            }
        });

        // Add Admin Submit
        document.getElementById('formAddAdmin').addEventListener('submit', function (e) {
            e.preventDefault();
            const name = document.getElementById('admin_name').value.trim();
            const email = document.getElementById('admin_email').value.trim();
            const phone = document.getElementById('admin_phone').value.trim();

            const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

            totalAdmins += 1;
            additionalAdmins += 1;
            if (cardTotalAdmin) cardTotalAdmin.innerText = totalAdmins;
            if (cardAdditionalAdmin) cardAdditionalAdmin.innerText = additionalAdmins;

            const tr = document.createElement('tr');
            tr.className = 'admin-row';
            tr.dataset.name = name;
            tr.dataset.email = email;
            tr.dataset.phone = phone;
            tr.dataset.initials = initials;
            tr.dataset.isMain = '0';
            tr.innerHTML = `
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="sidebar-avatar-circle" style="width: 36px; height: 36px; background-color: #059669;">
                            ${initials}
                        </div>
                        <span class="fw-bold text-dark admin-name">${name}</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2 text-muted">
                        <i data-lucide="mail"></i>
                        <span class="admin-email">${email}</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2 text-muted">
                        <i data-lucide="phone"></i>
                        <span class="admin-phone">${phone}</span>
                    </div>
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn-humora-icon btn-edit-admin" title="Edit"><i data-lucide="pencil"></i></button>
                        <button class="btn-humora-icon danger" data-confirm-delete data-item-name="Admin ${name}" title="Hapus"><i data-lucide="trash-2"></i></button>
                    </div>
                </td>
            `;
            adminsTableBody.appendChild(tr);

            // Reset and close
            this.reset();
            bsAddAdminModal.hide();
            window.showHumoraToast(`Admin "${name}" berhasil ditambahkan!`);
        });

        // Edit Admin Submit
        document.getElementById('formEditAdmin').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!activeEditAdminRow) return;

            const name = editNameInput.value.trim();
            const email = editEmailInput.value.trim();
            const phone = editPhoneInput.value.trim();

            activeEditAdminRow.dataset.name = name;
            activeEditAdminRow.dataset.email = email;
            activeEditAdminRow.dataset.phone = phone;

            const nameEl = activeEditAdminRow.querySelector('.admin-name');
            const emailEl = activeEditAdminRow.querySelector('.admin-email');
            const phoneEl = activeEditAdminRow.querySelector('.admin-phone');
            const delBtn = activeEditAdminRow.querySelector('[data-confirm-delete]');

            if (nameEl) nameEl.innerText = name;
            if (emailEl) emailEl.innerText = email;
            if (phoneEl) phoneEl.innerText = phone;
            if (delBtn) delBtn.setAttribute('data-item-name', `Admin ${name}`);

            bsEditAdminModal.hide();
            window.showHumoraToast(`Data admin "${name}" berhasil diperbarui!`);
        });
    });
</script>
@endsection
