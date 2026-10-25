@extends('layouts.admin')

@section('title', 'Kelola Video Episode - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs & Header -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i> <span class="text-dark fw-semibold">Kelola Video</span>
    </small>
</div>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Kelola Video Episode</h1>
        <p class="page-subtitle mb-0">Kelola episode sketsa komedi (~2 menit), file master video, durasi presisi, dan aturan akses preview vs VIP.</p>
    </div>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-humora-primary">
        <i data-lucide="plus"></i>
        <span>Tambah Episode Baru</span>
    </a>
</div>

<!-- 4 Real Database Stat Cards -->
@php
    $totalVid = \App\Models\Episode::count();
    $freeVid = \App\Models\Episode::where('access_type', 'free')->count();
    $premVid = \App\Models\Episode::where('access_type', 'premium')->count();
    $pubVid = \App\Models\Episode::where('status', 'published')->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Total Episode" 
            :value="$totalVid" 
            icon="video" 
            iconBg="purple" 
            subtext="Seluruh episode terdaftar" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Free Preview" 
            :value="$freeVid" 
            icon="sparkles" 
            iconBg="green" 
            subtext="Ep 1 & 2 (Bebas tonton)" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Episode Premium" 
            :value="$premVid" 
            badge="VIP"
            icon="gem" 
            iconBg="purple" 
            subtext="Ep 3+ (Khusus Subscriber)" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Status Terpublikasi" 
            :value="$pubVid" 
            icon="check-circle" 
            iconBg="yellow" 
            subtext="Tampil di katalog mobile" 
        />
    </div>
</div>

<!-- Filter Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-3">
        <form method="GET" action="{{ route('admin.videos.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="search" style="width: 16px; height: 16px;"></i></span>
                    <input type="text" name="search" class="form-control-custom border-start-0" placeholder="Cari judul episode..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-4">
                <select name="series_id" class="form-select-custom">
                    <option value="">Semua Series Induk</option>
                    @foreach ($seriesList as $s)
                        <option value="{{ $s->id }}" {{ request('series_id') == $s->id ? 'selected' : '' }}>{{ $s->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="access_type" class="form-select-custom">
                    <option value="">Semua Akses</option>
                    <option value="free" {{ request('access_type') === 'free' ? 'selected' : '' }}>Gratis (Free)</option>
                    <option value="premium" {{ request('access_type') === 'premium' ? 'selected' : '' }}>Premium VIP</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-humora-primary w-100 justify-content-center">
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['search', 'series_id', 'access_type']))
                <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary px-2" title="Reset">
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
                        <th style="width: 80px;">No Ep</th>
                        <th>Series Induk</th>
                        <th>Judul Episode</th>
                        <th>Durasi</th>
                        <th>Tipe Akses</th>
                        <th>Jumlah Suka</th>
                        <th>Status</th>
                        <th class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($episodes as $ep)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1 fs-6">#{{ $ep->episode_number }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="max-width: 220px;">
                                <img src="{{ $ep->series?->thumbnail_url }}" alt="" loading="lazy" class="rounded object-fit-cover shadow-sm" style="width: 44px; height: 26px; aspect-ratio: 16/9;" onerror="this.src='{{ asset('images/logo-humora.png') }}'">
                                <span class="fw-semibold text-dark text-truncate d-block" style="font-size: 0.82rem;">{{ $ep->series?->title ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 250px; font-size: 0.85rem;">{{ $ep->title }}</div>
                            <small class="text-muted text-truncate d-block" style="max-width: 250px; font-size: 0.72rem;">{{ $ep->synopsis ?? 'Tidak ada sinopsis' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border" style="font-size: 0.74rem;">
                                <i data-lucide="clock" class="me-1" style="width: 11px; height: 11px;"></i>{{ $ep->duration }}
                            </span>
                        </td>
                        <td>
                            @if ($ep->is_free)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i data-lucide="unlock" class="me-1" style="width: 11px; height: 11px;"></i>Gratis
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i data-lucide="lock" class="me-1" style="width: 11px; height: 11px;"></i>Premium VIP
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-danger fw-semibold" style="font-size: 0.78rem;">
                                <i data-lucide="heart" style="width: 12px; height: 12px;" class="me-1"></i>{{ number_format($ep->likes_count ?? 0, 0, ',', '.') }} Suka
                            </span>
                        </td>
                        <td>
                            @if ($ep->status === 'published')
                                <span class="badge-pill-success" style="font-size: 0.72rem;">Terpublikasi</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill" style="font-size: 0.72rem;">Draf</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.videos.edit', $ep->id) }}" class="btn-humora-icon" title="Edit Episode">
                                    <i data-lucide="pencil"></i>
                                </a>
                                <button type="button" class="btn-humora-icon danger" title="Hapus Episode" onclick="confirmDeleteEp({{ $ep->id }}, {{ $ep->episode_number }}, '{{ addslashes($ep->title) }}')">
                                    <i data-lucide="trash-2"></i>
                                </button>
                                <form id="del-ep-{{ $ep->id }}" action="{{ route('admin.videos.destroy', $ep->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <x-empty-state 
                        colspan="8"
                        icon="video"
                        title="Belum Ada Episode Video"
                        subtitle="Belum ada berkas video sketsa yang diunggah ke katalog series HUMORA atau sesuai dengan filter pencarian."
                        :actionUrl="route('admin.videos.create')"
                        actionText="Tambah Episode Baru"
                        actionIcon="plus"
                        :resetUrl="request()->hasAny(['search', 'series_id', 'status', 'is_free']) ? route('admin.videos.index') : null"
                        resetText="Reset Filter"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($episodes->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $episodes->links('vendor.pagination.humora') }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function confirmDeleteEp(id, num, title) {
    Swal.fire({
        title: `Hapus Episode #${num}?`,
        text: `Apakah Anda yakin ingin menghapus episode '${title}'? Berkas video terkait akan dibersihkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`del-ep-${id}`).submit();
        }
    });
}
</script>
@endsection
