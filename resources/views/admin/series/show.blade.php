@extends('layouts.admin')

@section('title', 'Detail Series: ' . $series->title . ' - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ route('admin.series.index') }}" class="text-decoration-none text-muted">Series</a> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">{{ $series->title }}</span>
    </small>
</div>

<!-- Header & Action Buttons -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">{{ $series->title }}</h1>
        @if ($series->status === 'published')
            <span class="badge-pill-success"><i data-lucide="check-circle"></i> Terpublikasi</span>
        @else
            <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill" style="font-size: 0.75rem;">Draf</span>
        @endif
        @if ($series->is_featured)
            <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                <i data-lucide="sparkles" class="me-1"></i>Featured
            </span>
        @endif
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.series.index') }}" class="btn btn-humora-icon" title="Kembali ke Daftar">
            <i data-lucide="arrow-left"></i>
        </a>
        <a href="{{ route('admin.series.edit', $series->id) }}" class="btn btn-humora-secondary">
            <i data-lucide="pencil"></i>
            <span>Edit Series</span>
        </a>
        @if ($series->episodes->count() > 0)
            <button type="button" class="btn btn-humora-icon text-muted" style="cursor: not-allowed; opacity: 0.65;" title="Series memiliki {{ $series->episodes->count() }} episode video (tidak dapat dihapus)" onclick="warnCannotDeleteSeries('{{ addslashes($series->title) }}', {{ $series->episodes->count() }})">
                <i data-lucide="lock"></i>
            </button>
        @else
            <button type="button" class="btn btn-humora-icon danger" title="Hapus Series" onclick="confirmDeleteSeries()">
                <i data-lucide="trash-2"></i>
            </button>
            <form id="deleteSeriesForm" action="{{ route('admin.series.destroy', $series->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endif
        <a href="{{ route('admin.videos.create', ['series_id' => $series->id]) }}" class="btn btn-humora-primary">
            <i data-lucide="plus"></i>
            <span>Tambah Episode</span>
        </a>
    </div>
</div>

<!-- Hero Banner Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-4">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-md-5 col-lg-4">
                <img src="{{ $series->thumbnail_url }}" alt="{{ $series->title }}" class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover" style="aspect-ratio: 16/9;" onerror="this.onerror=null; this.src='{{ asset('images/default-img.png') }}';">
            </div>
            <div class="col-12 col-md-7 col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    @foreach ($series->categories as $c)
                        <span class="badge-pill-tag">{{ $c->name }}</span>
                    @endforeach
                    <span class="text-muted" style="font-size: 0.8rem;">&bull; Tahun {{ $series->release_year }}</span>
                    <span class="text-danger fw-semibold table-stat-likes" style="font-size: 0.8rem;">
                        <i data-lucide="heart"></i> {{ number_format($series->likes_count, 0, ',', '.') }} Suka
                    </span>
                </div>
                <h2 class="fw-bold text-dark mb-1" style="font-size: 1.4rem;">{{ $series->title }}</h2>
                <div class="text-muted mb-3" style="font-size: 0.82rem;">Slug URL: <code>/series/{{ $series->slug }}</code></div>
                <p class="text-muted mb-4" style="font-size: 0.88rem; line-height: 1.6;">
                    {{ $series->synopsis }}
                </p>

                <div class="row g-2">
                    <div class="col-6 col-sm-3">
                        <div class="p-2 border rounded-3 bg-light text-center">
                            <small class="text-muted d-block" style="font-size: 0.72rem;">Total Episode</small>
                            <span class="fw-bold text-dark fs-6">{{ $series->episodes->count() }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 border rounded-3 bg-light text-center">
                            <small class="text-muted d-block" style="font-size: 0.72rem;">Episode Free</small>
                            <span class="fw-bold text-success fs-6">{{ $series->episodes->where('access_type', 'free')->count() }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 border rounded-3 bg-light text-center">
                            <small class="text-muted d-block" style="font-size: 0.72rem;">Episode VIP</small>
                            <span class="fw-bold text-warning-emphasis fs-6">{{ $series->episodes->where('access_type', 'premium')->count() }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 border rounded-3 bg-light text-center">
                            <small class="text-muted d-block" style="font-size: 0.72rem;">Urutan Katalog</small>
                            <span class="fw-bold text-dark fs-6">#{{ $series->catalog_order }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Episode List in this Series -->
<div class="humora-card">
    <div class="humora-card-header d-flex justify-content-between align-items-center">
        <div>
            <div class="humora-card-title">DAFTAR EPISODE DALAM SERIES INI</div>
            <div class="humora-card-subtitle">Episode 1 & 2 adalah preview gratis. Episode 3+ memerlukan langganan VIP aktif.</div>
        </div>
        <a href="{{ route('admin.videos.create', ['series_id' => $series->id]) }}" class="btn btn-sm btn-humora-primary">
            <i data-lucide="plus"></i>
            <span>Tambah Episode Baru</span>
        </a>
    </div>
    <div class="humora-card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">No Ep</th>
                        <th>Judul Episode</th>
                        <th>Durasi</th>
                        <th>Hak Akses</th>
                        <th>Jumlah Suka</th>
                        <th>Status</th>
                        <th>Berkas Video</th>
                        <th class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($series->episodes as $ep)
                    <tr>
                        <td class="fw-bold text-center">
                            <span class="badge bg-light text-dark border px-2 py-1 fs-6">#{{ $ep->episode_number }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $ep->title }}</div>
                            <small class="text-muted text-truncate d-block" style="max-width: 300px; font-size: 0.74rem;">{{ $ep->synopsis ?? 'Tidak ada sinopsis' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                <i data-lucide="clock" class="me-1" style="width: 12px; height: 12px;"></i>{{ $ep->duration }}
                            </span>
                        </td>
                        <td>
                            @if ($ep->is_free)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i data-lucide="unlock" class="me-1" style="width: 12px; height: 12px;"></i>Gratis
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i data-lucide="lock" class="me-1" style="width: 12px; height: 12px;"></i>Premium VIP
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
                        <td>
                            <small class="text-muted d-block font-monospace" style="font-size: 0.72rem;">
                                {{ $ep->video_file ?? '-' }}
                            </small>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.videos.edit', $ep->id) }}" class="btn-humora-icon" title="Edit Episode">
                                    <i data-lucide="pencil"></i>
                                </a>
                                <button type="button" class="btn-humora-icon danger" title="Hapus Episode" onclick="confirmDeleteEpisode({{ $ep->id }}, {{ $ep->episode_number }}, '{{ addslashes($ep->title) }}')">
                                    <i data-lucide="trash-2"></i>
                                </button>
                                <form id="delete-ep-{{ $ep->id }}" action="{{ route('admin.videos.destroy', $ep->id) }}" method="POST" class="d-none">
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
                        title="Belum Ada Episode di Series Ini"
                        subtitle="Series sketsa komedi ini belum memiliki daftar video episode. Mulai unggah episode pertama sekarang."
                        :actionUrl="route('admin.videos.create', ['series_id' => $series->id])"
                        actionText="Tambah Episode Baru"
                        actionIcon="plus"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDeleteSeries() {
    Swal.fire({
        title: 'Hapus Series?',
        text: 'Apakah Anda yakin ingin menghapus series ini secara permanen?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Hapus Series',
        cancelButtonText: 'Batal'
    }).then((res) => {
        if (res.isConfirmed) {
            document.getElementById('deleteSeriesForm').submit();
        }
    });
}

function warnCannotDeleteSeries(title, count) {
    Swal.fire({
        icon: 'warning',
        title: 'Series Tidak Dapat Dihapus',
        html: `Series <strong>'${title}'</strong> tidak dapat dihapus karena masih memiliki <strong>${count} episode video</strong>.<br><br><span class="text-muted" style="font-size:0.84rem;">Untuk menjaga integritas konten, Anda harus menghapus seluruh episode video di dalamnya terlebih dahulu sebelum series ini dapat dihapus.</span>`,
        confirmButtonColor: '#43135C',
        confirmButtonText: 'Saya Mengerti'
    });
}

function confirmDeleteEpisode(id, epNumber, title) {
    Swal.fire({
        title: `Hapus Episode #${epNumber}?`,
        text: `Apakah Anda yakin ingin menghapus episode '${title}'? File video terkait akan dibersihkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((res) => {
        if (res.isConfirmed) {
            document.getElementById(`delete-ep-${id}`).submit();
        }
    });
}
</script>
@endsection
