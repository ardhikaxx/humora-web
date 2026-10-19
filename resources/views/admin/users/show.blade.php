@extends('layouts.admin')

@section('title', 'Detail Pengguna: ' . $user->name . ' - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted">Pengguna</a> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">{{ $user->name }}</span>
    </small>
</div>

<!-- Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title mb-1">Detail Akun: {{ $user->name }}</h1>
        <p class="page-subtitle mb-0">Informasi profil mobile, histori langganan aktif, dan riwayat transaksi.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-humora-secondary">
            <i data-lucide="arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<!-- User Profile Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-4">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-md-auto text-center">
                <div class="sidebar-avatar-circle mx-auto" style="width: 72px; height: 72px; font-size: 1.6rem;">
                    {{ $user->initials }}
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h2 class="fw-bold text-dark mb-0 fs-4">{{ $user->name }}</h2>
                    @if ($user->effective_tier === 'premium')
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                            <i data-lucide="gem" class="me-1"></i>Premium VIP Aktif
                        </span>
                    @elseif ($user->effective_tier === 'expired')
                        <span class="badge bg-secondary text-white px-2 py-1 rounded-pill" style="font-size: 0.75rem;">VIP Expired</span>
                    @else
                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill" style="font-size: 0.75rem;">Free Member</span>
                    @endif

                    @if ($user->status === 'active')
                        <span class="badge-pill-success" style="font-size: 0.75rem;">Akun Aktif</span>
                    @elseif ($user->status === 'suspended')
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.75rem;">Ditangguhkan</span>
                    @else
                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 0.75rem;">Diblokir</span>
                    @endif
                </div>

                <div class="d-flex flex-wrap align-items-center gap-3 text-muted mt-2" style="font-size: 0.85rem;">
                    <span><i data-lucide="mail" class="me-1" style="width: 14px; height: 14px;"></i>{{ $user->email ?: 'Tidak ada email' }}</span>
                    <span>&bull;</span>
                    <span><i data-lucide="phone" class="me-1" style="width: 14px; height: 14px;"></i>{{ $user->phone ?: 'Tidak ada telepon' }}</span>
                    <span>&bull;</span>
                    <span>Gender: {{ $user->gender ?: '-' }}</span>
                    <span>&bull;</span>
                    <span>Terdaftar: {{ $user->created_at?->isoFormat('D MMMM Y') }}</span>
                </div>

                @if ($user->valid_until)
                <div class="mt-2 text-dark fw-semibold" style="font-size: 0.82rem;">
                    Masa Berlaku VIP: <span class="text-purple">{{ $user->valid_until->isoFormat('D MMMM Y') }}</span> (Sisa {{ $user->remaining_days }} hari)
                </div>
                @endif

                @if ($user->status_reason)
                <div class="mt-2 alert alert-warning py-1 px-3 mb-0" style="font-size: 0.78rem;">
                    <strong>Alasan Status:</strong> {{ $user->status_reason }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tabs for History: Subscriptions & Transactions -->
<div class="row g-4">
    <!-- Left Column: Histori Langganan -->
    <div class="col-12 col-lg-6">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div class="humora-card-title">HISTORI PAKET LANGGANAN</div>
            </div>
            <div class="humora-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Periode Aktif</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->subscriptions as $sub)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.84rem;">{{ $sub->plan?->name ?? 'Paket' }}</div>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $sub->plan?->duration_days }} hari</small>
                                </td>
                                <td>
                                    <div style="font-size: 0.78rem;">
                                        <div>Mulai: {{ $sub->start_date?->format('d/m/Y') }}</div>
                                        <div class="text-muted">Berakhir: {{ $sub->end_date?->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if ($sub->status === 'active' && $sub->end_date >= now())
                                        <span class="badge bg-success text-white rounded-pill px-2 py-1" style="font-size: 0.7rem;">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-2 py-1" style="font-size: 0.7rem;">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <x-empty-state 
                                colspan="3"
                                icon="gem"
                                title="Belum Pernah Berlangganan"
                                subtitle="Pengguna ini belum pernah mengaktifkan paket langganan VIP HUMORA."
                                :compact="true"
                            />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Histori Transaksi -->
    <div class="col-12 col-lg-6">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div class="humora-card-title">HISTORI TRANSAKSI PEMBAYARAN</div>
            </div>
            <div class="humora-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Kode & Tanggal</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->transactions as $trx)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.82rem;">{{ $trx->trx_code }}</div>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $trx->created_at?->format('d M Y H:i') }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark" style="font-size: 0.82rem;">{{ $trx->formatted_amount }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.7rem;">{{ $trx->payment_method }}</span>
                                </td>
                                <td>
                                    @if ($trx->status === 'success')
                                        <span class="badge-pill-success" style="font-size: 0.7rem;">Berhasil</span>
                                    @elseif ($trx->status === 'pending')
                                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Pending</span>
                                    @else
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <x-empty-state 
                                colspan="4"
                                icon="banknote"
                                title="Belum Ada Transaksi"
                                subtitle="Belum ada riwayat transaksi pembayaran paket yang tercatat untuk pengguna ini."
                                :compact="true"
                            />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
