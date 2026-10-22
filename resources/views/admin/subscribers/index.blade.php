@extends('layouts.admin')

@section('title', 'Pelanggan Premium (Subscribers) - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Pelanggan Premium</span>
    </small>
</div>

<!-- Header -->
<div class="mb-4">
    <h1 class="page-title">Pelanggan Premium VIP</h1>
    <p class="page-subtitle mb-0">Kelola dan pantau data pelanggan yang memiliki akses aktif ke seluruh episode premium HUMORA.</p>
</div>

<!-- Real Database Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <x-stat-card 
            label="Subscriber VIP Aktif" 
            :value="$activeCount" 
            badge="VIP"
            icon="gem" 
            iconBg="purple" 
            subtext="Masa aktif masih berlaku" 
        />
    </div>
    <div class="col-6 col-md-4">
        <x-stat-card 
            label="Subscriber Kedaluwarsa" 
            :value="$expiredCount" 
            icon="clock" 
            iconBg="orange" 
            subtext="Masa aktif telah lewat" 
        />
    </div>
    <div class="col-12 col-md-4">
        <x-stat-card 
            label="Total Catatan Langganan" 
            :value="$subscriptions->total()" 
            icon="shield-check" 
            iconBg="green" 
            subtext="Historis keseluruhan" 
        />
    </div>
</div>

<!-- Search & Filter Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-3">
        <form method="GET" action="{{ route('admin.subscribers.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="search" style="width: 16px; height: 16px;"></i></span>
                    <input type="text" name="search" class="form-control-custom border-start-0" placeholder="Cari nama subscriber, email, atau no telepon..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-4">
                <select name="status" class="form-select-custom">
                    <option value="">Semua Status Langganan</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif (Belum Kedaluwarsa)</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa (Expired)</option>
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-humora-primary w-100 justify-content-center">Filter</button>
                @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-outline-secondary px-2" title="Reset">
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
                        <th>Subscriber & Inisial</th>
                        <th>Paket Langganan</th>
                        <th>Periode Aktif</th>
                        <th>Sisa Waktu</th>
                        <th>Status</th>
                        <th>Transaksi Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscriptions as $sub)
                    @php
                        $isActive = $sub->status === 'active' && $sub->end_date && $sub->end_date->greaterThanOrEqualTo(now());
                        $remainingDays = $sub->end_date ? max(0, (int) ceil(now()->diffInDays($sub->end_date, false))) : 0;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="sidebar-avatar-circle" style="width: 36px; height: 36px; font-size: 0.82rem;">
                                    {{ $sub->user?->initials ?? 'US' }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $sub->user_id) }}" class="fw-bold text-dark text-decoration-none d-block" style="font-size: 0.85rem;">
                                        {{ $sub->user?->name ?? 'Pengguna' }}
                                    </a>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $sub->user?->email ?? $sub->user?->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.78rem;">
                                {{ $sub->plan?->name ?? 'Paket' }}
                            </span>
                            <small class="d-block text-muted" style="font-size: 0.7rem;">{{ $sub->plan?->duration_days }} Hari</small>
                        </td>
                        <td>
                            <div style="font-size: 0.8rem;">
                                <div class="text-dark">{{ $sub->start_date?->format('d M Y') }} s/d</div>
                                <div class="fw-semibold text-dark">{{ $sub->end_date?->format('d M Y') }}</div>
                            </div>
                        </td>
                        <td>
                            @if ($isActive)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    Sisa {{ $remainingDays }} hari
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    Berakhir
                                </span>
                            @endif
                        </td>
                        <td>
                            @if ($isActive)
                                <span class="badge-pill-success" style="font-size: 0.72rem;">Aktif</span>
                            @else
                                <span class="badge bg-secondary text-white px-2 py-1 rounded-pill" style="font-size: 0.72rem;">Expired</span>
                            @endif
                        </td>
                        <td>
                            @if ($sub->transaction)
                                <a href="{{ route('admin.transactions.index', ['search' => $sub->transaction->trx_code]) }}" class="font-monospace text-decoration-none text-purple fw-semibold" style="font-size: 0.78rem;">
                                    {{ $sub->transaction->trx_code }}
                                </a>
                            @else
                                <span class="text-muted" style="font-size: 0.75rem;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <x-empty-state 
                        colspan="6"
                        icon="gem"
                        title="Belum Ada Pelanggan VIP"
                        subtitle="Belum ada riwayat langganan VIP aktif atau sesuai dengan kriteria filter yang Anda pilih."
                        :resetUrl="request()->hasAny(['search', 'status', 'plan_id']) ? route('admin.subscribers.index') : null"
                        resetText="Reset Filter"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($subscriptions->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $subscriptions->links('vendor.pagination.humora') }}
    </div>
    @endif
</div>
@endsection
