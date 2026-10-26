@php
    $currentAdmin = Auth::guard('admin')->user();
    $roleLabel = 'Admin';
    $initials = $currentAdmin?->initials ?? 'AH';
    $name = $currentAdmin?->name ?? 'Admin HUMORA';
@endphp

<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand Logo -->
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-link text-decoration-none d-block">
            <img src="{{ asset('images/logo-humora-komersial.png') }}" alt="HUMORA Commercial" class="sidebar-logo-expanded img-fluid" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
            <img src="{{ asset('images/logo-humora.png') }}" alt="HUMORA" class="sidebar-logo-collapsed img-fluid" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="sidebar-nav-container">
        <!-- OVERVIEW -->
        <div class="sidebar-section-title">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-humora-link {{ Request::is('admin/dashboard') || Request::is('admin') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
            <i data-lucide="layout-grid"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="{{ route('admin.analytics') }}" class="nav-humora-link {{ Request::is('admin/analytics*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Analytics">
            <i data-lucide="bar-chart-2"></i>
            <span class="sidebar-text">Analytics</span>
        </a>

        <!-- MANAJEMEN KONTEN -->
        <div class="sidebar-section-title">Manajemen Konten</div>
        <a href="{{ route('admin.series.index') }}" class="nav-humora-link {{ Request::is('admin/series*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Judul Konten">
            <i data-lucide="tv"></i>
            <span class="sidebar-text">Judul Konten</span>
        </a>
        <a href="{{ route('admin.videos.index') }}" class="nav-humora-link {{ Request::is('admin/videos*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Kelola Video">
            <i data-lucide="video"></i>
            <span class="sidebar-text">Kelola Video</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-humora-link {{ Request::is('admin/categories*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Kategori">
            <i data-lucide="tag"></i>
            <span class="sidebar-text">Kategori</span>
        </a>

        <!-- AUDIENS & PENDAPATAN -->
        <div class="sidebar-section-title">Audiens & Pendapatan</div>
        <a href="{{ route('admin.users.index') }}" class="nav-humora-link {{ Request::is('admin/users*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pengguna">
            <i data-lucide="users"></i>
            <span class="sidebar-text">Pengguna</span>
        </a>
        <a href="{{ route('admin.subscribers.index') }}" class="nav-humora-link {{ Request::is('admin/subscribers*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pelanggan Premium">
            <i data-lucide="gem"></i>
            <span class="sidebar-text">Pelanggan Premium</span>
        </a>
        <a href="{{ route('admin.transactions.index') }}" class="nav-humora-link {{ Request::is('admin/transactions*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Transaksi">
            <i data-lucide="banknote"></i>
            <span class="sidebar-text">Transaksi</span>
        </a>

        <!-- SISTEM -->
        <div class="sidebar-section-title">Sistem</div>
        <a href="{{ route('admin.admins.index') }}" class="nav-humora-link {{ Request::is('admin/admins*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Kelola Admin">
            <i data-lucide="shield-check"></i>
            <span class="sidebar-text">Kelola Admin</span>
        </a>
        <a href="{{ route('admin.profile.index') }}" class="nav-humora-link {{ Request::is('admin/profile*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Profil Admin">
            <i data-lucide="user"></i>
            <span class="sidebar-text">Profil Admin</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="nav-humora-link {{ Request::is('admin/settings*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pengaturan">
            <i data-lucide="settings"></i>
            <span class="sidebar-text">Pengaturan</span>
        </a>
    </div>

    <!-- Bottom User Profile Card -->
    <div class="sidebar-footer">
        <div class="sidebar-profile-card">
            <div class="sidebar-avatar-circle" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $name }}">
                {{ $initials }}
            </div>
            <div class="overflow-hidden flex-grow-1 sidebar-user-info">
                <div class="sidebar-user-name text-truncate">{{ $name }}</div>
                <div class="sidebar-user-role">
                    <i data-lucide="sparkles"></i>
                    <span>{{ $roleLabel }}</span>
                </div>
            </div>
            <a href="{{ route('admin.profile.index') }}" class="text-muted text-decoration-none sidebar-profile-arrow">
                <i data-lucide="chevron-right" style="font-size: 0.8rem;"></i>
            </a>
        </div>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
