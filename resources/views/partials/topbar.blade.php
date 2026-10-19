@php
    $currentAdmin = Auth::guard('admin')->user();
    $initials = $currentAdmin?->initials ?? 'AH';
    $name = $currentAdmin?->name ?? 'Admin HUMORA';
    $email = $currentAdmin?->email ?? 'admin@humora.id';
    $pendingTrxCount = \App\Models\Transaction::where('status', 'pending')->count();
@endphp

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
            <button class="notification-btn position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Transaksi">
                <i data-lucide="bell"></i>
                @if ($pendingTrxCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                        {{ $pendingTrxCount }}
                    </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="width: 300px;">
                <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="font-size: 0.85rem;">Notifikasi Sistem</span>
                    @if ($pendingTrxCount > 0)
                        <span class="badge rounded-pill bg-warning text-dark" style="font-size: 0.68rem;">{{ $pendingTrxCount }} Menunggu</span>
                    @else
                        <span class="badge rounded-pill bg-success" style="font-size: 0.68rem;">Up to date</span>
                    @endif
                </li>
                @if ($pendingTrxCount > 0)
                <li>
                    <a class="dropdown-item p-2 rounded-2 d-flex gap-2 align-items-start mt-1" href="{{ route('admin.transactions.index', ['status' => 'pending']) }}">
                        <div class="badge-pill-warning p-2 rounded-circle"><i data-lucide="clock"></i></div>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size: 0.8rem;">{{ $pendingTrxCount }} Transaksi Pending</div>
                            <small class="text-muted" style="font-size: 0.72rem;">Menunggu verifikasi bukti transfer</small>
                        </div>
                    </a>
                </li>
                @else
                <li class="p-3 text-center text-muted" style="font-size: 0.78rem;">
                    Tidak ada transaksi pending yang perlu diproses saat ini.
                </li>
                @endif
            </ul>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <button class="topbar-avatar-btn dropdown-toggle border-0 bg-transparent p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar-circle primary">
                    {{ $initials }}
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="min-width: 220px;">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;">{{ $name }}</div>
                    <small class="text-muted text-truncate d-block" style="font-size: 0.75rem;">{{ $email }}</small>
                </li>
                <li>
                    <a class="dropdown-item p-2 rounded-2 mt-1 d-flex align-items-center gap-2" href="{{ route('admin.profile.index') }}">
                        <i data-lucide="user" class="text-muted"></i>
                        <span style="font-size: 0.84rem;">Profil Admin</span>
                    </a>
                </li>
                @if ($currentAdmin?->isSuperAdmin())
                <li>
                    <a class="dropdown-item p-2 rounded-2 d-flex align-items-center gap-2" href="{{ route('admin.settings.index') }}">
                        <i data-lucide="settings" class="text-muted"></i>
                        <span style="font-size: 0.84rem;">Pengaturan</span>
                    </a>
                </li>
                @endif
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}" id="topbarLogoutForm" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item p-2 rounded-2 d-flex align-items-center gap-2 text-danger w-100 border-0 bg-transparent text-start">
                            <i data-lucide="log-out"></i>
                            <span style="font-size: 0.84rem;">Keluar Portal</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
