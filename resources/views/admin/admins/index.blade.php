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

<!-- Header & Action Button -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Kelola Administrator HUMORA</h1>
        <p class="page-subtitle mb-0">Kelola akun pengelola platform HUMORA.</p>
    </div>
    <button type="button" class="btn btn-humora-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
        <i data-lucide="user-plus"></i>
        <span>Tambah Admin Baru</span>
    </button>
</div>

<!-- 3 Real Stat Cards -->
@php
    $totalAdmins = \App\Models\Admin::count();
    $activeAdmins = \App\Models\Admin::where('is_active', true)->count();
    $inactiveAdmins = \App\Models\Admin::where('is_active', false)->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <x-stat-card 
            label="Total Administrator" 
            :value="$totalAdmins" 
            icon="users" 
            iconBg="purple" 
            subtext="Seluruh pengelola terdaftar" 
        />
    </div>
    <div class="col-12 col-md-4">
        <x-stat-card 
            label="Administrator Aktif" 
            :value="$activeAdmins" 
            icon="shield-check" 
            iconBg="green" 
            subtext="Memiliki akses login sistem" 
        />
    </div>
    <div class="col-12 col-md-4">
        <x-stat-card 
            label="Administrator Nonaktif" 
            :value="$inactiveAdmins" 
            icon="user-x" 
            iconBg="blue" 
            subtext="Akses login dinonaktifkan" 
        />
    </div>
</div>

<!-- Table Card -->
<div class="humora-card">
    <div class="humora-card-header d-flex justify-content-between align-items-center">
        <div>
            <div class="humora-card-title">DAFTAR AKUN ADMINISTRATOR</div>
            <div class="humora-card-subtitle">Identitas visual menggunakan inisial nama otomatis tanpa upload foto.</div>
        </div>
    </div>
    <div class="humora-card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>Administrator & Inisial</th>
                        <th>Email & Kontak</th>
                        <th>Role Akses</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $adm)
                    @php
                        $isSelf = Auth::guard('admin')->id() === $adm->id;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="sidebar-avatar-circle" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                    {{ $adm->initials }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                                        {{ $adm->name }}
                                        @if ($isSelf)
                                            <span class="badge bg-purple-subtle text-purple ms-1" style="font-size: 0.65rem;">Akun Anda</span>
                                        @endif
                                    </div>
                                    <small class="text-muted" style="font-size: 0.72rem;">ID #{{ $adm->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.8rem;">
                                <div class="text-dark">{{ $adm->email }}</div>
                                <small class="text-muted">{{ $adm->phone ?: 'Tidak ada no telepon' }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge-role-admin">
                                <i data-lucide="shield-check"></i>
                                <span>Admin</span>
                            </span>
                        </td>
                        <td>
                            @if ($adm->is_active)
                                <span class="badge-pill-success" style="font-size: 0.72rem;">Aktif</span>
                            @else
                                <span class="badge bg-secondary text-white px-2 py-1 rounded-pill" style="font-size: 0.72rem;">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ $adm->created_at?->format('d M Y') }}</small>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn-humora-icon" title="Edit Admin" onclick="openEditAdminModal({{ json_encode([
                                    'id' => $adm->id,
                                    'name' => $adm->name,
                                    'email' => $adm->email,
                                    'phone' => $adm->phone ?? '',
                                    'is_active' => (bool)$adm->is_active,
                                    'is_self' => $isSelf
                                ]) }})">
                                    <i data-lucide="pencil"></i>
                                </button>
                                @if (!$isSelf)
                                <button type="button" class="btn-humora-icon danger" title="Hapus Admin" onclick="confirmDeleteAdmin({{ $adm->id }}, '{{ addslashes($adm->name) }}')">
                                    <i data-lucide="trash-2"></i>
                                </button>
                                <form id="del-admin-{{ $adm->id }}" action="{{ route('admin.admins.destroy', $adm->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <x-empty-state 
                        colspan="6"
                        icon="shield"
                        title="Belum Ada Administrator Lain"
                        subtitle="Belum ada administrator atau staf pengelola lain yang terdaftar dalam sistem HUMORA."
                        actionTarget="#addAdminModal"
                        actionText="Tambah Administrator"
                        actionIcon="plus"
                        :resetUrl="request()->has('search') ? route('admin.admins.index') : null"
                        resetText="Reset Filter"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($admins->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $admins->links('vendor.pagination.humora') }}
    </div>
    @endif
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.admins.store') }}" method="POST" class="modal-content modal-content-humora">
            @csrf
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora">Tambah Administrator Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="text" name="name" id="addAdminName" class="form-control-custom" placeholder=" " required>
                        <label for="addAdminName" class="humora-floating-label">Nama Lengkap <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="email" name="email" id="addAdminEmail" class="form-control-custom" placeholder=" " required>
                        <label for="addAdminEmail" class="humora-floating-label">Email Resmi <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="text" name="phone" id="addAdminPhone" class="form-control-custom" placeholder=" ">
                        <label for="addAdminPhone" class="humora-floating-label">Nomor Telepon</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-custom">Role Hak Akses</label>
                    <div class="d-flex align-items-center gap-2 p-2 rounded border bg-light">
                        <span class="badge-role-admin">
                            <i data-lucide="shield-check"></i>
                            <span>Admin</span>
                        </span>
                        <span class="text-muted" style="font-size: 0.8rem;">Akses penuh ke seluruh modul sistem</span>
                    </div>
                    <input type="hidden" name="role" value="admin">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="humora-field-group mb-0">
                            <div class="humora-field">
                                <input type="password" name="password" id="addAdminPassword" class="form-control-custom" placeholder=" " required minlength="8">
                                <label for="addAdminPassword" class="humora-floating-label">Password <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="humora-field-group mb-0">
                            <div class="humora-field">
                                <input type="password" name="password_confirmation" id="addAdminPasswordConfirm" class="form-control-custom" placeholder=" " required minlength="8">
                                <label for="addAdminPasswordConfirm" class="humora-floating-label">Konfirmasi <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="addAdminActive" checked style="cursor: pointer;">
                    <label class="form-check-label fw-bold text-dark" for="addAdminActive" style="font-size: 0.85rem; cursor: pointer;">
                        Akun Aktif (Dapat Login)
                    </label>
                </div>
            </div>
            <div class="modal-footer modal-footer-humora">
                <button type="button" class="btn btn-sm btn-humora-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-humora-primary">Simpan Admin</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Admin -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editAdminForm" action="" method="POST" class="modal-content modal-content-humora">
            @csrf
            @method('PUT')
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora">Edit Administrator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="text" id="editAdminName" name="name" class="form-control-custom" placeholder=" " required>
                        <label for="editAdminName" class="humora-floating-label">Nama Lengkap <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="email" id="editAdminEmail" name="email" class="form-control-custom" placeholder=" " required>
                        <label for="editAdminEmail" class="humora-floating-label">Email Resmi <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="humora-field-group mb-3">
                    <div class="humora-field">
                        <input type="text" id="editAdminPhone" name="phone" class="form-control-custom" placeholder=" ">
                        <label for="editAdminPhone" class="humora-floating-label">Nomor Telepon</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-custom">Role Hak Akses</label>
                    <div class="d-flex align-items-center gap-2 p-2 rounded border bg-light">
                        <span class="badge-role-admin">
                            <i data-lucide="shield-check"></i>
                            <span>Admin</span>
                        </span>
                        <span class="text-muted" style="font-size: 0.8rem;">Akses penuh ke seluruh modul sistem</span>
                    </div>
                    <input type="hidden" name="role" value="admin">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="humora-field-group mb-0">
                            <div class="humora-field">
                                <input type="password" name="password" id="editAdminPassword" class="form-control-custom" placeholder=" " minlength="8">
                                <label for="editAdminPassword" class="humora-floating-label">Password Baru</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="humora-field-group mb-0">
                            <div class="humora-field">
                                <input type="password" name="password_confirmation" id="editAdminPasswordConfirm" class="form-control-custom" placeholder=" " minlength="8">
                                <label for="editAdminPasswordConfirm" class="humora-floating-label">Konfirmasi</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editAdminActive" style="cursor: pointer;">
                    <label class="form-check-label fw-bold text-dark" for="editAdminActive" style="font-size: 0.85rem; cursor: pointer;">
                        Akun Aktif (Dapat Login)
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
function openEditAdminModal(admin) {
    const form = document.getElementById('editAdminForm');
    form.action = `/admin/admins/${admin.id}`;
    document.getElementById('editAdminName').value = admin.name;
    document.getElementById('editAdminEmail').value = admin.email;
    document.getElementById('editAdminPhone').value = admin.phone || '';
    document.getElementById('editAdminActive').checked = admin.is_active;

    ['editAdminName', 'editAdminEmail', 'editAdminPhone'].forEach(fieldId => {
        const el = document.getElementById(fieldId);
        if (el) el.dispatchEvent(new Event('input', { bubbles: true }));
    });

    if (admin.is_self) {
        document.getElementById('editAdminActive').disabled = true;
    } else {
        document.getElementById('editAdminActive').disabled = false;
    }

    const modal = new bootstrap.Modal(document.getElementById('editAdminModal'));
    modal.show();
}

function confirmDeleteAdmin(id, name) {
    Swal.fire({
        title: 'Hapus Administrator?',
        text: `Apakah Anda yakin ingin menghapus akun admin '${name}'?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((res) => {
        if (res.isConfirmed) {
            document.getElementById(`del-admin-${id}`).submit();
        }
    });
}
</script>
@endsection
