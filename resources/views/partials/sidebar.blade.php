<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand Logo (Dual Logo: Commercial when expanded, Icon when collapsed) -->
    <div class="sidebar-brand">
        <a href="{{ url('/admin/dashboard') }}" class="sidebar-brand-link text-decoration-none d-block">
            <!-- Expanded Logo: Komersial (Direct large rounded logo, no outer yellow box) -->
            <img src="{{ asset('images/logo-humora-komersial.png') }}" alt="HUMORA Commercial" class="sidebar-logo-expanded img-fluid">
            <!-- Collapsed Logo: Standalone Icon -->
            <img src="{{ asset('images/logo-humora.png') }}" alt="HUMORA" class="sidebar-logo-collapsed img-fluid">
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="sidebar-nav-container">
        <!-- OVERVIEW -->
        <div class="sidebar-section-title">Overview</div>
        <a href="{{ url('/admin/dashboard') }}" class="nav-humora-link {{ Request::is('admin/dashboard') || Request::is('admin') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
            <i class="bi bi-grid-fill"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="{{ url('/admin/analytics') }}" class="nav-humora-link {{ Request::is('admin/analytics*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Analytics">
            <i class="bi bi-bar-chart-line"></i>
            <span class="sidebar-text">Analytics</span>
        </a>

        <!-- MANAJEMEN KONTEN -->
        <div class="sidebar-section-title">Manajemen Konten</div>
        <a href="{{ url('/admin/series') }}" class="nav-humora-link {{ Request::is('admin/series*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Judul Konten">
            <i class="bi bi-collection-play"></i>
            <span class="sidebar-text">Judul Konten</span>
        </a>
        <a href="{{ url('/admin/videos') }}" class="nav-humora-link {{ Request::is('admin/videos*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Kelola Video">
            <i class="bi bi-camera-video"></i>
            <span class="sidebar-text">Kelola Video</span>
        </a>
        <a href="{{ url('/admin/categories') }}" class="nav-humora-link {{ Request::is('admin/categories*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Kategori">
            <i class="bi bi-tag"></i>
            <span class="sidebar-text">Kategori</span>
        </a>

        <!-- AUDIENS & PENDAPATAN -->
        <div class="sidebar-section-title">Audiens & Pendapatan</div>
        <a href="{{ url('/admin/users') }}" class="nav-humora-link {{ Request::is('admin/users*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pengguna">
            <i class="bi bi-people"></i>
            <span class="sidebar-text">Pengguna</span>
        </a>
        <a href="{{ url('/admin/subscribers') }}" class="nav-humora-link {{ Request::is('admin/subscribers*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pelanggan Premium">
            <i class="bi bi-gem"></i>
            <span class="sidebar-text">Pelanggan Premium</span>
        </a>
        <a href="{{ url('/admin/transactions') }}" class="nav-humora-link {{ Request::is('admin/transactions*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Transaksi">
            <i class="bi bi-cash-stack"></i>
            <span class="sidebar-text">Transaksi</span>
        </a>

        <!-- SISTEM -->
        <div class="sidebar-section-title">Sistem</div>
        <a href="{{ url('/admin/admins') }}" class="nav-humora-link {{ Request::is('admin/admins*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Kelola Admin">
            <i class="bi bi-shield-check"></i>
            <span class="sidebar-text">Kelola Admin</span>
        </a>
        <a href="{{ url('/admin/profile') }}" class="nav-humora-link {{ Request::is('admin/profile*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Profil Admin">
            <i class="bi bi-person"></i>
            <span class="sidebar-text">Profil Admin</span>
        </a>
        <a href="{{ url('/admin/settings') }}" class="nav-humora-link {{ Request::is('admin/settings*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pengaturan">
            <i class="bi bi-gear"></i>
            <span class="sidebar-text">Pengaturan</span>
        </a>
    </div>

    <!-- Bottom User Profile Card -->
    <div class="sidebar-footer">
        <div class="sidebar-profile-card">
            <div class="sidebar-avatar-circle" data-bs-toggle="tooltip" data-bs-placement="right" title="Admin HUMORA">
                AH
            </div>
            <div class="overflow-hidden flex-grow-1 sidebar-user-info">
                <div class="sidebar-user-name">Admin HUMORA</div>
                <div class="sidebar-user-role">
                    <i class="bi bi-stars"></i>
                    <span>Super Admin</span>
                </div>
            </div>
            <a href="{{ url('/admin/profile') }}" class="text-muted text-decoration-none sidebar-profile-arrow">
                <i class="bi bi-chevron-right" style="font-size: 0.8rem;"></i>
            </a>
        </div>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
