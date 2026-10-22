@extends('layouts.admin')

@section('title', 'Pengguna Aplikasi Mobile - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Pengguna</span>
    </small>
</div>

<!-- Header -->
<div class="mb-4">
    <h1 class="page-title">Pengguna Aplikasi Mobile</h1>
    <p class="page-subtitle mb-0">Kelola akun mobile HUMORA, pantau status langganan VIP, dan kelola penangguhan akun.</p>
</div>

<!-- 4 Real Database Stat Cards -->
@php
    $totalUsersCount = \App\Models\User::count();
    $freeUsersCount = \App\Models\User::where('tier', 'free')->count();
    $activeVipCount = \App\Models\User::where('tier', 'premium')->where('valid_until', '>=', now())->count();
    $suspendedCount = \App\Models\User::whereIn('status', ['suspended', 'banned'])->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Total Pengguna" 
            :value="$totalUsersCount" 
            icon="users" 
            iconBg="purple" 
            subtext="Akun mobile terdaftar" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Pengguna Free" 
            :value="$freeUsersCount" 
            icon="user" 
            iconBg="blue" 
            subtext="Akses preview gratis" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Pelanggan VIP Aktif" 
            :value="$activeVipCount" 
            badge="VIP"
            icon="gem" 
            iconBg="yellow" 
            subtext="Akses episode premium" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Ditangguhkan / Blokir" 
            :value="$suspendedCount" 
            icon="shield-alert" 
            iconBg="orange" 
            subtext="Pelanggaran ketentuan" 
        />
    </div>
</div>

<!-- Search & Filter Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="search" style="width: 16px; height: 16px;"></i></span>
                    <input type="text" name="search" class="form-control-custom border-start-0" placeholder="Cari nama, email, atau telepon..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="tier" class="form-select-custom">
                    <option value="">Semua Status Tier</option>
                    <option value="free" {{ request('tier') === 'free' ? 'selected' : '' }}>Free Member</option>
                    <option value="premium" {{ request('tier') === 'premium' ? 'selected' : '' }}>Premium VIP Aktif</option>
                    <option value="expired" {{ request('tier') === 'expired' ? 'selected' : '' }}>VIP Expired</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select-custom">
                    <option value="">Semua Status Akun</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Diblokir (Banned)</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-humora-primary w-100 justify-content-center">Filter</button>
                @if (request()->hasAny(['search', 'tier', 'status']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-2" title="Reset">
                    <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="humora-card">
    <div class="humora-card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>Pengguna & Inisial</th>
                        <th>Kontak</th>
                        <th>Gender</th>
                        <th>Tier Langganan</th>
                        <th>Masa Aktif VIP</th>
                        <th>Status Akun</th>
                        <th>Terdaftar</th>
                        <th class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="sidebar-avatar-circle" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                    {{ $u->initials }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="fw-bold text-dark text-decoration-none d-block" style="font-size: 0.85rem;">
                                        {{ $u->name }}
                                    </a>
                                    <small class="text-muted" style="font-size: 0.72rem;">ID #{{ $u->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.8rem;">
                                <div class="text-dark">{{ $u->email ?: '-' }}</div>
                                <small class="text-muted">{{ $u->phone ?: '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 0.8rem;">{{ $u->gender ?: '-' }}</span>
                        </td>
                        <td>
                            @if ($u->effective_tier === 'premium')
                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i data-lucide="gem" style="width: 11px; height: 11px;" class="me-1"></i>VIP Aktif
                                </span>
                            @elseif ($u->effective_tier === 'expired')
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill" style="font-size: 0.72rem;">VIP Expired</span>
                            @else
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-pill" style="font-size: 0.72rem;">Free Member</span>
                            @endif
                        </td>
                        <td>
                            @if ($u->hasActiveVip() && $u->valid_until)
                                <span class="fw-semibold text-dark" style="font-size: 0.8rem;">{{ $u->valid_until->format('d M Y') }}</span>
                                <small class="d-block text-success fw-medium" style="font-size: 0.7rem;">Sisa {{ $u->remaining_days }} hari</small>
                            @else
                                <span class="text-muted" style="font-size: 0.78rem;">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($u->status === 'active')
                                <span class="badge-pill-success" style="font-size: 0.72rem;">Aktif</span>
                            @elseif ($u->status === 'suspended')
                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.72rem;" title="{{ $u->status_reason }}">Ditangguhkan</span>
                            @else
                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 0.72rem;" title="{{ $u->status_reason }}">Diblokir</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted" style="font-size: 0.78rem;">{{ $u->created_at?->format('d M Y') }}</small>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="btn-humora-icon" title="Detail Pengguna">
                                    <i data-lucide="eye"></i>
                                </a>
                                @if (Auth::guard('admin')->user()->isSuperAdmin())
                                <button type="button" class="btn-humora-icon" title="Ubah Status Akun" onclick="openStatusModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->status }}', '{{ addslashes($u->status_reason ?? '') }}')">
                                    <i data-lucide="sliders"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <x-empty-state 
                        colspan="8"
                        icon="users"
                        title="Belum Ada Pengguna Ditemukan"
                        subtitle="Tidak ada akun penonton aplikasi mobile yang sesuai dengan parameter pencarian atau filter status yang dipilih."
                        :resetUrl="request()->hasAny(['search', 'tier', 'status']) ? route('admin.users.index') : null"
                        resetText="Reset Filter"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($users->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $users->links('vendor.pagination.humora') }}
    </div>
    @endif
</div>

<!-- Modal Ubah Status Akun -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="statusForm" action="" method="POST" class="modal-content modal-content-humora">
            @csrf
            @method('PUT')
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora">Ubah Status Akun: <span id="statusUserName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="mb-3">
                    <label class="form-label-custom">Pilih Status Baru <span class="text-danger">*</span></label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="status" id="stActive" value="active">
                        <label class="form-check-label text-dark fw-semibold" for="stActive">
                            Aktif (Normal)
                        </label>
                        <small class="d-block text-muted" style="font-size: 0.74rem;">Pengguna dapat login dan menggunakan seluruh fitur sesuai tier.</small>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="status" id="stSuspended" value="suspended">
                        <label class="form-check-label text-warning-emphasis fw-semibold" for="stSuspended">
                            Ditangguhkan Sementara (Suspended)
                        </label>
                        <small class="d-block text-muted" style="font-size: 0.74rem;">Token dicabut seketika, pengguna tidak dapat mengakses konten terlindungi.</small>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="status" id="stBanned" value="banned">
                        <label class="form-check-label text-danger fw-semibold" for="stBanned">
                            Diblokir Permanen (Banned)
                        </label>
                        <small class="d-block text-muted" style="font-size: 0.74rem;">Akun dinonaktifkan total dari platform HUMORA.</small>
                    </div>
                </div>

                <div class="mb-3" id="reasonGroup" style="display: none;">
                    <label class="form-label-custom">Alasan Penangguhan / Pemblokiran <span class="text-danger">*</span></label>
                    <textarea name="status_reason" id="statusReason" rows="3" class="form-control-custom" placeholder="Tuliskan alasan penangguhan akun..."></textarea>
                </div>
            </div>
            <div class="modal-footer modal-footer-humora">
                <button type="button" class="btn btn-sm btn-humora-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-humora-primary">Simpan Status</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openStatusModal(id, name, status, reason) {
    const form = document.getElementById('statusForm');
    form.action = `/admin/users/${id}/status`;
    document.getElementById('statusUserName').innerText = name;
    document.getElementById('statusReason').value = reason;

    if (status === 'suspended') {
        document.getElementById('stSuspended').checked = true;
    } else if (status === 'banned') {
        document.getElementById('stBanned').checked = true;
    } else {
        document.getElementById('stActive').checked = true;
    }

    toggleReason();

    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function toggleReason() {
    const isSuspendedOrBanned = document.getElementById('stSuspended').checked || document.getElementById('stBanned').checked;
    document.getElementById('reasonGroup').style.display = isSuspendedOrBanned ? 'block' : 'none';
}

document.querySelectorAll('input[name="status"]').forEach(r => {
    r.addEventListener('change', toggleReason);
});
</script>
@endsection
