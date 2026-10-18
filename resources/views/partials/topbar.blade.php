<header class="admin-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="topbar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Sidebar">
            <i data-lucide="panel-left"></i>
        </button>
        <span class="d-none d-md-inline-block text-muted" style="font-size: 0.82rem; font-weight: 500;">
            Portal Administrator HUMORA
        </span>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Notification Dropdown -->
        <div class="dropdown">
            <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                <i data-lucide="bell"></i>
                <span class="notification-dot"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="width: 300px;">
                <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="font-size: 0.85rem;">Notifikasi Baru</span>
                    <span class="badge rounded-pill bg-danger" style="font-size: 0.68rem;">2 Baru</span>
                </li>
                <li>
                    <a class="dropdown-item p-2 rounded-2 d-flex gap-2 align-items-start mt-1" href="{{ url('/admin/transactions') }}">
                        <div class="badge-pill-warning p-2 rounded-circle"><i data-lucide="history"></i></div>
                        <div>
                            <div class="fw-semibold" style="font-size: 0.8rem;">2 Bukti bayar menunggu</div>
                            <small class="text-muted" style="font-size: 0.72rem;">Dewi Lestari & Fikri Haikal</small>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item p-2 rounded-2 d-flex gap-2 align-items-start" href="{{ url('/admin/videos') }}">
                        <div class="badge-pill-success p-2 rounded-circle"><i data-lucide="play-circle"></i></div>
                        <div>
                            <div class="fw-semibold" style="font-size: 0.8rem;">Video Terbit Baru</div>
                            <small class="text-muted" style="font-size: 0.72rem;">Ep 1: Tipe Bawel dipublikasikan</small>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <button class="topbar-avatar-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar-circle primary">
                    AH
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="min-width: 200px;">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">Admin HUMORA</div>
                    <small class="text-muted" style="font-size: 0.75rem;">admin@humora.id</small>
                </li>
                <li>
                    <a class="dropdown-item p-2 rounded-2 mt-1 d-flex align-items-center gap-2" href="{{ url('/admin/profile') }}">
                        <i data-lucide="user" class="text-muted"></i>
                        <span style="font-size: 0.84rem;">Profil Admin</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item p-2 rounded-2 d-flex align-items-center gap-2" href="{{ url('/admin/settings') }}">
                        <i data-lucide="settings" class="text-muted"></i>
                        <span style="font-size: 0.84rem;">Pengaturan</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <a class="dropdown-item p-2 rounded-2 d-flex align-items-center gap-2 text-danger" href="{{ url('/login') }}">
                        <i data-lucide="log-out"></i>
                        <span style="font-size: 0.84rem;">Keluar Portal</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
