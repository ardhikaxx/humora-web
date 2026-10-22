@extends('layouts.admin')

@section('title', 'Judul Konten & Series - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs & Header -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i> <span class="text-dark fw-semibold">Judul Konten</span>
    </small>
</div>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">Judul Konten & Series</h1>
        <p class="page-subtitle mb-0">Kelola seluruh judul konten sketsa komedi dan episode yang tampil di aplikasi mobile HUMORA.</p>
    </div>
    <a href="{{ route('admin.series.create') }}" class="btn btn-humora-primary">
        <i data-lucide="plus"></i>
        <span>Tambah Judul Baru</span>
    </a>
</div>

<!-- 4 Real Stat Cards -->
@php
    $totalCount = \App\Models\Series::count();
    $publishedCount = \App\Models\Series::where('status', 'published')->count();
    $draftCount = \App\Models\Series::where('status', 'draft')->count();
    $featuredCount = \App\Models\Series::where('is_featured', true)->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Total Judul" 
            :value="$totalCount" 
            icon="tv" 
            iconBg="purple" 
            subtext="Seluruh katalog lokal" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Judul Terbit" 
            :value="$publishedCount" 
            icon="check-circle" 
            iconBg="green" 
            subtext="Tampil di mobile app" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Series Draf" 
            :value="$draftCount" 
            icon="clock" 
            iconBg="orange" 
            subtext="Belum dipublikasikan" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Series Unggulan" 
            :value="$featuredCount" 
            icon="sparkles" 
            iconBg="yellow" 
            subtext="Banner spotlight mobile" 
        />
    </div>
</div>

<!-- Search & Filter Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-3">
        <form method="GET" action="{{ route('admin.series.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="search" style="width: 16px; height: 16px;"></i></span>
                    <input type="text" name="search" class="form-control-custom border-start-0" placeholder="Cari judul series atau sinopsis..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="category_id" class="form-select-custom">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select-custom">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Terpublikasi</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draf</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-humora-primary w-100 justify-content-center">
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['search', 'category_id', 'status']))
                <a href="{{ route('admin.series.index') }}" class="btn btn-outline-secondary px-2" title="Reset Filter">
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
                        <th>Judul & Thumbnail (16:9)</th>
                        <th>Kategori</th>
                        <th>Total Episode</th>
                        <th>Status</th>
                        <th>Unggulan</th>
                        <th>Jumlah Suka</th>
                        <th class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($seriesList as $series)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $series->thumbnail_url }}" alt="{{ $series->title }}" class="rounded object-fit-cover shadow-sm" style="width: 72px; height: 40px; aspect-ratio: 16/9;" onerror="this.src='{{ asset('images/logo-humora.png') }}'">
                                <div class="overflow-hidden" style="max-width: 260px;">
                                    <a href="{{ route('admin.series.show', $series->id) }}" class="text-decoration-none fw-bold text-dark d-block text-truncate">
                                        {{ $series->title }}
                                    </a>
                                    <small class="text-muted text-truncate d-block" style="font-size: 0.72rem;">/{{ $series->slug }} &bull; Thn {{ $series->release_year }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1" style="max-width: 180px;">
                                @forelse ($series->categories as $c)
                                    <span class="badge-pill-tag">{{ $c->name }}</span>
                                @empty
                                    <span class="text-muted" style="font-size: 0.75rem;">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $series->episodes_count }} Ep</span>
                            <small class="d-block text-muted" style="font-size: 0.72rem;">{{ $series->published_episodes_count }} Terbit</small>
                        </td>
                        <td>
                            @if ($series->status === 'published')
                                <span class="badge-pill-success"><i data-lucide="check-circle" style="width: 12px; height: 12px;"></i> Terpublikasi</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill" style="font-size: 0.72rem;">Draf</span>
                            @endif
                        </td>
                        <td>
                            @if ($series->is_featured)
                                <span class="badge bg-warning text-dark py-1 px-2 rounded-pill" style="font-size: 0.72rem;">
                                    <i data-lucide="sparkles" style="width: 12px; height: 12px;" class="me-1"></i>Featured
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.75rem;">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-danger fw-semibold" style="font-size: 0.82rem;">
                                <i data-lucide="heart" style="width: 13px; height: 13px;" class="me-1"></i>{{ number_format($series->likes_count, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.series.show', $series->id) }}" class="btn-humora-icon" title="Lihat Episode">
                                    <i data-lucide="eye"></i>
                                </a>
                                <a href="{{ route('admin.series.edit', $series->id) }}" class="btn-humora-icon" title="Edit Series">
                                    <i data-lucide="pencil"></i>
                                </a>
                                <button type="button" class="btn-humora-icon danger" title="Hapus Series" onclick="confirmDeleteSeries({{ $series->id }}, '{{ addslashes($series->title) }}')">
                                    <i data-lucide="trash-2"></i>
                                </button>
                                <form id="delete-series-{{ $series->id }}" action="{{ route('admin.series.destroy', $series->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <x-empty-state 
                        colspan="7"
                        icon="film"
                        title="Belum Ada Judul Series"
                        subtitle="Tidak ada katalog series sketsa komedi yang sesuai dengan filter pencarian atau belum ada data yang ditambahkan."
                        :actionUrl="route('admin.series.create')"
                        actionText="Tambah Series Baru"
                        actionIcon="plus"
                        :resetUrl="request()->hasAny(['search', 'category_id', 'status']) ? route('admin.series.index') : null"
                        resetText="Reset Filter"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($seriesList->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $seriesList->links('vendor.pagination.humora') }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function confirmDeleteSeries(id, title) {
    Swal.fire({
        title: 'Hapus Series?',
        text: `Apakah Anda yakin ingin menghapus series '${title}'? Seluruh episode dan data terkait akan ikut terhapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Hapus Permanen',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-series-${id}`).submit();
        }
    });
}
</script>
@endsection
