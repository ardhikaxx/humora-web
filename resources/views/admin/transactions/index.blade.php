@extends('layouts.admin')

@section('title', 'Verifikasi & Riwayat Transaksi - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Transaksi</span>
    </small>
</div>

<!-- Header -->
<div class="mb-4">
    <h1 class="page-title">Verifikasi & Riwayat Transaksi</h1>
    <p class="page-subtitle mb-0">Periksa foto bukti transfer manual, verifikasi pembayaran langganan, dan setujui atau tolak pesanan pengguna.</p>
</div>

<!-- Banner Kuning Transaksi Pending -->
@if ($pendingCount > 0)
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between p-3 mb-4 rounded-3" style="background-color: #FEF3C7; border-left: 5px solid #F59E0B !important;">
    <div class="d-flex align-items-center gap-3">
        <div class="p-2 bg-warning rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
        </div>
        <div>
            <div class="fw-bold text-dark" style="font-size: 0.92rem;">
                Terdapat {{ $pendingCount }} Transaksi Menunggu Verifikasi
            </div>
            <small class="text-muted" style="font-size: 0.78rem;">
                Pengguna telah mengunggah bukti pembayaran manual. Harap segera periksa mutasi rekening dan setujui aktivasi VIP.
            </small>
        </div>
    </div>
    <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}" class="btn btn-sm btn-dark px-3 fw-semibold" style="font-size: 0.78rem;">
        Periksa Pending
    </a>
</div>
@endif

<!-- 4 Real Database Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Menunggu Verifikasi" 
            :value="$pendingCount" 
            icon="clock" 
            iconBg="orange" 
            subtext="Bukti bayar perlu diproses" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Diverifikasi / Berhasil" 
            :value="$successCount" 
            icon="check-circle" 
            iconBg="green" 
            subtext="VIP pengguna aktif" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Ditolak / Batal" 
            :value="$rejectedCount" 
            icon="alert-circle" 
            iconBg="orange" 
            subtext="Bukti tidak valid" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Total Pendapatan Sukses" 
            :value="'Rp ' . number_format($totalVerifiedRevenue, 0, ',', '.')" 
            icon="banknote" 
            iconBg="purple" 
            subtext="Omzet aktual terverifikasi" 
        />
    </div>
</div>

<!-- Search & Filter Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-3">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="search" style="width: 16px; height: 16px;"></i></span>
                    <input type="text" name="search" class="form-control-custom border-start-0" placeholder="Kode TRX, nama, email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select-custom">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Berhasil</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="payment_method" class="form-select-custom">
                    <option value="">Semua Metode</option>
                    <option value="bca" {{ request('payment_method') === 'bca' ? 'selected' : '' }}>BCA Manual</option>
                    <option value="mandiri" {{ request('payment_method') === 'mandiri' ? 'selected' : '' }}>Mandiri Manual</option>
                    <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <input type="date" name="date_from" class="form-control-custom" value="{{ request('date_from') }}" title="Dari Tanggal">
            </div>
            <div class="col-6 col-md-2">
                <input type="date" name="date_to" class="form-control-custom" value="{{ request('date_to') }}" title="Sampai Tanggal">
            </div>
            <div class="col-12 col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-humora-primary w-100 justify-content-center p-2" title="Cari">
                    <i data-lucide="filter" style="width: 15px; height: 15px;"></i>
                </button>
                @if (request()->hasAny(['search', 'status', 'payment_method', 'date_from', 'date_to']))
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary p-2" title="Reset">
                    <i data-lucide="rotate-ccw" style="width: 15px; height: 15px;"></i>
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
                        <th>Kode Trx & Waktu</th>
                        <th>Pengguna</th>
                        <th>Paket Langganan</th>
                        <th>Nominal</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Bukti Bayar</th>
                        <th class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $trx)
                    <tr>
                        <td>
                            <div class="fw-bold font-monospace text-dark" style="font-size: 0.85rem;">{{ $trx->trx_code }}</div>
                            <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $trx->created_at?->format('d M Y, H:i') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="sidebar-avatar-circle" style="width: 32px; height: 32px; font-size: 0.78rem;">
                                    {{ $trx->user?->initials ?? 'US' }}
                                </div>
                                <div class="text-truncate" style="max-width: 180px;">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.82rem;">{{ $trx->user?->name ?? 'User' }}</div>
                                    <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">{{ $trx->user?->email ?? $trx->user?->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">
                                {{ $trx->plan?->name ?? 'Paket' }}
                            </span>
                            @if ($trx->plan?->video_quota)
                                <small class="d-block text-muted mt-1 fw-semibold" style="font-size: 0.7rem;">
                                    Kuota: {{ $trx->plan->video_quota }} Video ({{ $trx->plan->duration_days > 0 ? $trx->plan->duration_days . ' Hari' : 'Ikuti Paket Mingguan' }})
                                </small>
                            @elseif ($trx->plan?->plan_type === 'full')
                                <small class="d-block text-primary mt-1 fw-semibold" style="font-size: 0.7rem;">
                                    Full Access 30 Hari
                                </small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark" style="font-size: 0.84rem;">{{ $trx->formatted_amount }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.72rem;">
                                {{ $trx->payment_method }}
                            </span>
                        </td>
                        <td>
                            @if ($trx->status === 'pending')
                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i data-lucide="clock" class="me-1" style="width: 11px; height: 11px;"></i>Menunggu
                                </span>
                            @elseif ($trx->status === 'success')
                                <span class="badge-pill-success" style="font-size: 0.72rem;">
                                    <i data-lucide="check-circle" class="me-1" style="width: 11px; height: 11px;"></i>Berhasil
                                </span>
                            @else
                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i data-lucide="x-circle" class="me-1" style="width: 11px; height: 11px;"></i>Ditolak
                                </span>
                            @endif
                        </td>
                        <td>
                            @if ($trx->proof_file)
                                <button type="button" class="btn btn-sm btn-outline-purple py-0 px-2 rounded-pill" style="font-size: 0.72rem;" onclick="viewProofModal('{{ route('admin.proof.view', $trx->proof_file) }}', '{{ $trx->trx_code }}')">
                                    <i data-lucide="image" class="me-1" style="width: 12px; height: 12px;"></i>Lihat Bukti
                                </button>
                            @else
                                <span class="text-muted" style="font-size: 0.72rem;">Belum upload</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-sm btn-humora-primary py-1 px-2" style="font-size: 0.75rem;" onclick="openTrxDetailModal({{ json_encode([
                                    'id' => $trx->id,
                                    'trx_code' => $trx->trx_code,
                                    'user_name' => $trx->user?->name,
                                    'user_contact' => $trx->user?->email ?: $trx->user?->phone,
                                    'plan_name' => $trx->plan?->name,
                                    'amount' => $trx->formatted_amount,
                                    'method' => strtoupper($trx->payment_method),
                                    'status' => $trx->status,
                                    'reject_reason' => $trx->reject_reason,
                                    'proof_url' => $trx->proof_file ? route('admin.proof.view', $trx->proof_file) : null,
                                    'created_at' => $trx->created_at?->format('d M Y, H:i'),
                                    'verified_by' => $trx->verifier?->name,
                                    'verified_at' => $trx->verified_at?->format('d M Y, H:i')
                                ]) }})">
                                    <span>Periksa</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <x-empty-state 
                        colspan="8"
                        icon="banknote"
                        title="Belum Ada Transaksi Pembayaran"
                        subtitle="Tidak ada data transaksi pembayaran langganan yang cocok dengan status atau kata kunci yang dicari."
                        :resetUrl="request()->hasAny(['search', 'status', 'plan_id', 'date_from', 'date_to']) ? route('admin.transactions.index') : null"
                        resetText="Reset Filter"
                    />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($transactions->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
        {{ $transactions->links('vendor.pagination.humora') }}
    </div>
    @endif
</div>

<!-- Modal Bukti Bayar Zoom -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora">Bukti Transfer: <span id="proofModalTrx"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center bg-dark rounded-bottom">
                <img id="proofModalImg" src="" alt="Bukti Transfer" class="img-fluid rounded" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Pemeriksaan & Keputusan Transaksi -->
<div class="modal fade" id="trxDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora">Pemeriksaan Pembayaran: <span id="modalTrxCode" class="font-monospace text-purple"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="row g-4">
                    <!-- Left: Bukti Foto -->
                    <div class="col-12 col-md-5">
                        <label class="form-label-custom mb-1">Foto Bukti Transfer</label>
                        <div class="border rounded-3 p-2 bg-light text-center" style="min-height: 250px;">
                            <img id="detailProofImg" src="" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm" style="max-height: 280px; object-fit: contain; cursor: pointer;" onclick="zoomCurrentProof()">
                            <div id="noProofNotice" class="text-muted py-5" style="display: none;">
                                <i data-lucide="image-off" class="mb-2" style="width: 32px; height: 32px;"></i>
                                <div style="font-size: 0.8rem;">Pengguna belum mengunggah foto bukti pembayaran.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Informasi Pesanan -->
                    <div class="col-12 col-md-7">
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <div class="row g-2" style="font-size: 0.82rem;">
                                <div class="col-5 text-muted">Pengguna:</div>
                                <div class="col-7 fw-bold text-dark" id="modalUserName">-</div>

                                <div class="col-5 text-muted">Kontak:</div>
                                <div class="col-7 text-dark" id="modalUserContact">-</div>

                                <div class="col-5 text-muted">Paket Dipilih:</div>
                                <div class="col-7 fw-bold text-dark" id="modalPlanName">-</div>

                                <div class="col-5 text-muted">Nominal Tagihan:</div>
                                <div class="col-7 fw-bold text-success fs-6" id="modalAmount">-</div>

                                <div class="col-5 text-muted">Metode Pembayaran:</div>
                                <div class="col-7 text-dark fw-semibold" id="modalMethod">-</div>

                                <div class="col-5 text-muted">Waktu Pengajuan:</div>
                                <div class="col-7 text-dark" id="modalCreatedAt">-</div>

                                <div class="col-5 text-muted">Status Saat Ini:</div>
                                <div class="col-7" id="modalStatusBadge">-</div>
                            </div>
                        </div>

                        <!-- Verification History if already processed -->
                        <div id="verifiedHistoryBox" class="p-3 bg-white rounded-3 border mb-3" style="display: none; font-size: 0.8rem;">
                            <div class="fw-bold text-dark mb-1">Riwayat Verifikasi:</div>
                            <div class="text-muted">Diproses oleh: <strong id="modalVerifier" class="text-dark"></strong> pada <span id="modalVerifiedAt"></span></div>
                            <div id="modalRejectReasonBox" class="mt-2 text-danger" style="display: none;">
                                <strong>Alasan Penolakan:</strong> <span id="modalRejectReasonText"></span>
                            </div>
                        </div>

                        <!-- Action Controls (Only available if status is pending) -->
                        <div id="pendingActionControls" style="display: none;">
                            @if (Auth::guard('admin')->user()->canManageFinances())
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success flex-grow-1" id="btnApproveTrx">
                                    <i data-lucide="check-circle" class="me-1"></i> Setujui & Aktifkan VIP
                                </button>
                                <button type="button" class="btn btn-outline-danger flex-grow-1" id="btnRejectTrx">
                                    <i data-lucide="x-circle" class="me-1"></i> Tolak Pembayaran
                                </button>
                            </div>

                            <form id="approveForm" action="" method="POST" class="d-none">
                                @csrf
                            </form>

                            <!-- Hidden Reject Form Container -->
                            <div id="rejectReasonContainer" class="mt-3 p-3 border rounded-3 bg-white" style="display: none;">
                                <form id="rejectForm" action="" method="POST">
                                    @csrf
                                    <label class="form-label-custom text-danger">Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea name="reject_reason" id="rejectReasonInput" rows="2" class="form-control-custom mb-2" placeholder="Contoh: Bukti buram, nominal transfer tidak sesuai..." required></textarea>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="hideRejectForm()">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-danger">Kirim Penolakan</button>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentTrxData = null;

function viewProofModal(url, code) {
    document.getElementById('proofModalTrx').innerText = code;
    document.getElementById('proofModalImg').src = url;
    const modal = new bootstrap.Modal(document.getElementById('proofModal'));
    modal.show();
}

function zoomCurrentProof() {
    if (currentTrxData && currentTrxData.proof_url) {
        viewProofModal(currentTrxData.proof_url, currentTrxData.trx_code);
    }
}

function openTrxDetailModal(data) {
    currentTrxData = data;
    document.getElementById('modalTrxCode').innerText = data.trx_code;
    document.getElementById('modalUserName').innerText = data.user_name || 'Tidak diketahui';
    document.getElementById('modalUserContact').innerText = data.user_contact || '-';
    document.getElementById('modalPlanName').innerText = data.plan_name || 'Paket';
    document.getElementById('modalAmount').innerText = data.amount;
    document.getElementById('modalMethod').innerText = data.method;
    document.getElementById('modalCreatedAt').innerText = data.created_at || '-';

    const proofImg = document.getElementById('detailProofImg');
    const noProof = document.getElementById('noProofNotice');

    if (data.proof_url) {
        proofImg.src = data.proof_url;
        proofImg.style.display = 'inline-block';
        noProof.style.display = 'none';
    } else {
        proofImg.style.display = 'none';
        noProof.style.display = 'block';
    }

    // Status Badge
    let statusBadgeHtml = '';
    if (data.status === 'pending') {
        statusBadgeHtml = '<span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Menunggu Verifikasi</span>';
    } else if (data.status === 'success') {
        statusBadgeHtml = '<span class="badge-pill-success">Berhasil (Diverifikasi)</span>';
    } else {
        statusBadgeHtml = '<span class="badge bg-danger text-white px-2 py-1 rounded-pill">Ditolak</span>';
    }
    document.getElementById('modalStatusBadge').innerHTML = statusBadgeHtml;

    // Verified history
    const historyBox = document.getElementById('verifiedHistoryBox');
    const rejectBox = document.getElementById('modalRejectReasonBox');
    if (data.status !== 'pending' && data.verified_by) {
        historyBox.style.display = 'block';
        document.getElementById('modalVerifier').innerText = data.verified_by;
        document.getElementById('modalVerifiedAt').innerText = data.verified_at || '';
        if (data.status === 'rejected' && data.reject_reason) {
            rejectBox.style.display = 'block';
            document.getElementById('modalRejectReasonText').innerText = data.reject_reason;
        } else {
            rejectBox.style.display = 'none';
        }
    } else {
        historyBox.style.display = 'none';
    }

    // Controls
    const controls = document.getElementById('pendingActionControls');
    const approveForm = document.getElementById('approveForm');
    const rejectForm = document.getElementById('rejectForm');

    if (data.status === 'pending') {
        controls.style.display = 'block';
        if (approveForm) approveForm.action = `/admin/transactions/${data.id}/approve`;
        if (rejectForm) rejectForm.action = `/admin/transactions/${data.id}/reject`;
        hideRejectForm();
    } else {
        controls.style.display = 'none';
    }

    const modal = new bootstrap.Modal(document.getElementById('trxDetailModal'));
    modal.show();
}

function hideRejectForm() {
    const box = document.getElementById('rejectReasonContainer');
    if (box) box.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    const btnApprove = document.getElementById('btnApproveTrx');
    const btnReject = document.getElementById('btnRejectTrx');

    if (btnApprove) {
        btnApprove.addEventListener('click', function () {
            Swal.fire({
                title: 'Setujui Pembayaran?',
                text: `Pastikan dana sebesar ${currentTrxData.amount} telah benar-benar masuk ke rekening. Tindakan ini akan mengaktifkan paket VIP pengguna secara permanen.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16A34A',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, Setujui Pembayaran',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    document.getElementById('approveForm').submit();
                }
            });
        });
    }

    if (btnReject) {
        btnReject.addEventListener('click', function () {
            document.getElementById('rejectReasonContainer').style.display = 'block';
        });
    }
});
</script>
@endsection
