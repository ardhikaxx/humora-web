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
    <h1 class="page-title">Pelanggan Premium (Subscribers)</h1>
    <p class="page-subtitle mb-0">Kelola dan pantau data pelanggan aktif yang memiliki akses tanpa batas ke seluruh episode premium HUMORA.</p>
</div>

<!-- 4 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Subscriber Aktif" 
            value="3.279" 
            icon="gem" 
            iconBg="purple" 
            trend="8.5%" 
            subtext="vs bln lalu" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Subscriber Kedaluwarsa" 
            value="411" 
            icon="clock" 
            iconBg="orange" 
            subtext="Perlu pengingat perpanjangan" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Paket Terfavorit" 
            value="Paket 3 Bulan" 
            icon="sparkles" 
            iconBg="yellow" 
            subtext="57% dari seluruh subscriber" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Rata-rata Nilai" 
            value="Rp56.400" 
            icon="credit-card" 
            iconBg="green" 
            subtext="per transaksi langganan" 
        />
    </div>
</div>

<!-- Search & Filters -->
<div class="humora-card mb-4 p-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-6 col-lg-8">
            <div class="search-input-wrap">
                <i data-lucide="search"></i>
                <input type="text" id="subscriberSearchInput" class="form-control-custom bg-light" placeholder="Cari nama atau email pelanggan...">
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <select class="form-select-custom" id="subscriberStatusFilter">
                <option selected>Semua Status</option>
                <option>Premium Aktif</option>
                <option>Kedaluwarsa</option>
            </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <select class="form-select-custom" id="subscriberPackageFilter">
                <option selected>Semua Paket</option>
                <option>Paket Bulanan</option>
                <option>Paket 3 Bulan</option>
                <option>Paket Tahunan</option>
            </select>
        </div>
    </div>
</div>

<!-- Subscribers Table Card -->
<div class="humora-card mb-0">
    <div class="table-responsive">
        <table class="table-humora" id="subscribersTable">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Paket Langganan</th>
                    <th>Mulai Aktif</th>
                    <th>Berakhir Pada</th>
                    <th>Status</th>
                    <th>Metode Bayar</th>
                    <th>Total Belanja</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1 -->
                <tr class="sub-row"
                    data-sub-name="Budi Pratama"
                    data-sub-email="budi.p***@gmail.com"
                    data-sub-package="Paket 3 Bulan"
                    data-sub-start="15 Agu 2026"
                    data-sub-end="15 Nov 2026"
                    data-sub-status="Premium Aktif"
                    data-sub-method="QRIS"
                    data-sub-total="Rp 98.000"
                    data-sub-initials="BP">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                BP
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Budi Pratama</div>
                                <small class="text-muted">budi.p***@gmail.com</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-tag">Paket 3 Bulan</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">15 Agu 2026</td>
                    <td class="fw-semibold text-dark" style="font-size: 0.8rem;">15 Nov 2026</td>
                    <td><span class="badge-pill-premium"><i data-lucide="gem"></i> Premium Aktif</span></td>
                    <td class="text-muted" style="font-size: 0.82rem;">QRIS</td>
                    <td class="fw-bold text-dark">Rp 98.000</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-sub-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 2 -->
                <tr class="sub-row"
                    data-sub-name="Siti Nurhaliza"
                    data-sub-email="siti.n***@yahoo.com"
                    data-sub-package="Paket Tahunan"
                    data-sub-start="1 Mar 2026"
                    data-sub-end="1 Mar 2027"
                    data-sub-status="Premium Aktif"
                    data-sub-method="BCA Virtual Account"
                    data-sub-total="Rp 179.000"
                    data-sub-initials="SN">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                SN
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Siti Nurhaliza</div>
                                <small class="text-muted">siti.n***@yahoo.com</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-tag">Paket Tahunan</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">1 Mar 2026</td>
                    <td class="fw-semibold text-dark" style="font-size: 0.8rem;">1 Mar 2027</td>
                    <td><span class="badge-pill-premium"><i data-lucide="gem"></i> Premium Aktif</span></td>
                    <td class="text-muted" style="font-size: 0.82rem;">BCA Virtual Account</td>
                    <td class="fw-bold text-dark">Rp 179.000</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-sub-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 3 -->
                <tr class="sub-row"
                    data-sub-name="Reza Rahardian"
                    data-sub-email="reza.r***@gmail.com"
                    data-sub-package="Paket Bulanan"
                    data-sub-start="10 Agu 2026"
                    data-sub-end="10 Sep 2026"
                    data-sub-status="Kedaluwarsa"
                    data-sub-method="GoPay"
                    data-sub-total="Rp 38.000"
                    data-sub-initials="RR">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                RR
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Reza Rahardian</div>
                                <small class="text-muted">reza.r***@gmail.com</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-tag">Paket Bulanan</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">10 Agu 2026</td>
                    <td class="fw-semibold text-dark" style="font-size: 0.8rem;">10 Sep 2026</td>
                    <td><span class="badge-pill-warning"><i data-lucide="history"></i> Kedaluwarsa</span></td>
                    <td class="text-muted" style="font-size: 0.82rem;">GoPay</td>
                    <td class="fw-bold text-dark">Rp 38.000</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-sub-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 4 -->
                <tr class="sub-row"
                    data-sub-name="Anisa Rahma"
                    data-sub-email="anisa.r***@gmail.com"
                    data-sub-package="Paket 3 Bulan"
                    data-sub-start="1 Sep 2026"
                    data-sub-end="1 Des 2026"
                    data-sub-status="Premium Aktif"
                    data-sub-method="ShopeePay"
                    data-sub-total="Rp 49.000"
                    data-sub-initials="AR">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                AR
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Anisa Rahma</div>
                                <small class="text-muted">anisa.r***@gmail.com</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-tag">Paket 3 Bulan</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">1 Sep 2026</td>
                    <td class="fw-semibold text-dark" style="font-size: 0.8rem;">1 Des 2026</td>
                    <td><span class="badge-pill-premium"><i data-lucide="gem"></i> Premium Aktif</span></td>
                    <td class="text-muted" style="font-size: 0.82rem;">ShopeePay</td>
                    <td class="fw-bold text-dark">Rp 49.000</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-sub-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 5 -->
                <tr class="sub-row"
                    data-sub-name="Dimas Anggara"
                    data-sub-email="dimas.a***@gmail.com"
                    data-sub-package="Paket Bulanan"
                    data-sub-start="14 Sep 2026"
                    data-sub-end="14 Okt 2026"
                    data-sub-status="Kedaluwarsa"
                    data-sub-method="Mandiri VA"
                    data-sub-total="Rp 19.000"
                    data-sub-initials="DA">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                DA
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Dimas Anggara</div>
                                <small class="text-muted">dimas.a***@gmail.com</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-pill-tag">Paket Bulanan</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">14 Sep 2026</td>
                    <td class="fw-semibold text-dark" style="font-size: 0.8rem;">14 Okt 2026</td>
                    <td><span class="badge-pill-warning"><i data-lucide="history"></i> Kedaluwarsa</span></td>
                    <td class="text-muted" style="font-size: 0.82rem;">Mandiri VA</td>
                    <td class="fw-bold text-dark">Rp 19.000</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-sub-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top gap-2">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted" style="font-size: 0.8rem;">Menampilkan <strong>1 - 5</strong> dari <strong>5</strong> data</small>
            <div class="d-flex align-items-center gap-1 ms-3">
                <small class="text-muted" style="font-size: 0.8rem;">Baris:</small>
                <select class="form-select-custom py-1 px-2" style="width: 70px; font-size: 0.78rem;">
                    <option selected>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
            </div>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#"><i data-lucide="chevron-left"></i></a></li>
                <li class="page-item active"><a class="page-link" href="#" style="background-color: #43135C; border-color: #43135C;">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#"><i data-lucide="chevron-right"></i></a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@section('modals')
<!-- Subscriber Detail Modal -->
<div class="modal fade" id="subscriberDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora mb-0">Informasi Langganan Subscriber</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <div class="user-avatar-circle primary" id="modalSubInitials" style="width: 48px; height: 48px; font-size: 1.1rem;">
                        BP
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6" id="modalSubName">Budi Pratama</div>
                        <small class="text-muted" id="modalSubEmailPkg">budi.pratama@gmail.com &bull; Paket 3 Bulan</small>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Mulai Aktif</small>
                        <div class="fw-semibold text-dark" id="modalSubStart" style="font-size: 0.85rem;">15 Agu 2026</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Berakhir Pada</small>
                        <div class="fw-semibold text-dark" id="modalSubEnd" style="font-size: 0.85rem;">15 Nov 2026</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Metode Bayar</small>
                        <div class="fw-semibold text-dark" id="modalSubMethod" style="font-size: 0.85rem;">QRIS</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Total Belanja</small>
                        <div class="fw-bold text-success" id="modalSubTotal" style="font-size: 0.95rem;">Rp 98.000</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-humora justify-content-between">
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnSendRenewalReminder">
                    <i data-lucide="bell" class="me-1"></i> Kirim Notifikasi Perpanjangan
                </button>
                <button type="button" class="btn btn-humora-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const subModalEl = document.getElementById('subscriberDetailModal');
        const bsSubModal = new bootstrap.Modal(subModalEl);

        const modalSubName = document.getElementById('modalSubName');
        const modalSubInitials = document.getElementById('modalSubInitials');
        const modalSubEmailPkg = document.getElementById('modalSubEmailPkg');
        const modalSubStart = document.getElementById('modalSubStart');
        const modalSubEnd = document.getElementById('modalSubEnd');
        const modalSubMethod = document.getElementById('modalSubMethod');
        const modalSubTotal = document.getElementById('modalSubTotal');
        const btnSendRenewalReminder = document.getElementById('btnSendRenewalReminder');

        let activeSubName = '';

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-open-sub-detail');
            if (btn) {
                e.preventDefault();
                const row = btn.closest('tr');
                if (!row) return;

                activeSubName = row.dataset.subName;
                const email = row.dataset.subEmail;
                const pkg = row.dataset.subPackage;
                const start = row.dataset.subStart;
                const end = row.dataset.subEnd;
                const method = row.dataset.subMethod;
                const total = row.dataset.subTotal;
                const initials = row.dataset.subInitials;

                modalSubName.innerText = activeSubName;
                modalSubInitials.innerText = initials;
                modalSubEmailPkg.innerText = `${email} • ${pkg}`;
                modalSubStart.innerText = start;
                modalSubEnd.innerText = end;
                modalSubMethod.innerText = method;
                modalSubTotal.innerText = total;

                bsSubModal.show();
            }
        });

        btnSendRenewalReminder.addEventListener('click', function () {
            bsSubModal.hide();
            window.showHumoraToast(`Notifikasi perpanjangan langganan berhasil dikirim ke ${activeSubName}!`, 'info');
        });
    });
</script>
@endsection
