@extends('layouts.admin')

@section('title', 'Profil Administrator - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Profil Admin</span>
    </small>
</div>

<!-- Header -->
<div class="mb-4">
    <h1 class="page-title">Profil Administrator</h1>
    <p class="page-subtitle mb-0">Kelola informasi akun Anda: Nama Lengkap, Email, Nomor Telepon, dan Penggantian Password.</p>
</div>

<!-- Top Hero Profile Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-4">
        <div class="d-flex align-items-center gap-3">
            <div class="sidebar-avatar-circle" style="width: 58px; height: 58px; font-size: 1.3rem;">
                {{ $admin->initials }}
            </div>
            <div>
                <h2 class="fw-bold text-dark mb-1" style="font-size: 1.35rem;">{{ $admin->name }}</h2>
                <div class="d-flex flex-wrap align-items-center gap-2 text-muted" style="font-size: 0.82rem;">
                    <span class="d-flex align-items-center gap-1">
                        <i data-lucide="mail" style="width: 14px; height: 14px;"></i> <span>{{ $admin->email }}</span>
                    </span>
                    <span>&bull;</span>
                    <span class="d-flex align-items-center gap-1">
                        <i data-lucide="phone" style="width: 14px; height: 14px;"></i> <span>{{ $admin->phone ?: 'Belum diisi' }}</span>
                    </span>
                    <span>&bull;</span>
                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle rounded-pill px-2 py-0" style="font-size: 0.72rem;">
                        {{ ucwords(str_replace('_', ' ', $admin->role)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Data Profil Administrator -->
    <div class="col-12 col-lg-7">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">DATA PROFIL ADMINISTRATOR</div>
                    <div class="humora-card-subtitle">Perbarui Nama Lengkap, Email resmi, dan Nomor Telepon akun Anda.</div>
                </div>
                <div class="text-muted"><i data-lucide="user"></i></div>
            </div>
            <div class="humora-card-body p-4">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control-custom @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Email Administrator <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control-custom @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control-custom @error('phone') is-invalid @enderror" value="{{ old('phone', $admin->phone) }}" placeholder="0812-xxxx-xxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-humora-primary">
                            <i data-lucide="save"></i>
                            <span>Simpan Profil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Ganti Password -->
    <div class="col-12 col-lg-5">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">GANTI PASSWORD</div>
                    <div class="humora-card-subtitle">Perbarui kata sandi akun administrator untuk menjaga keamanan portal.</div>
                </div>
                <div class="text-muted"><i data-lucide="lock"></i></div>
            </div>
            <div class="humora-card-body p-4">
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label-custom">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control-custom @error('current_password') is-invalid @enderror" required placeholder="••••••••">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Password Baru (Min. 8 karakter) <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control-custom @error('password') is-invalid @enderror" required minlength="8" placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control-custom" required minlength="8" placeholder="••••••••">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-outline-danger">
                            <i data-lucide="key"></i>
                            <span>Perbarui Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
