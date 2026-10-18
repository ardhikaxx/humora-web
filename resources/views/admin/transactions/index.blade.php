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
    <p class="page-subtitle mb-0">Periksa bukti foto pembayaran langganan dari pengguna, verifikasi keabsahan dana, dan setujui atau tolak transaksi langganan.</p>
</div>

<!-- 4 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardPending"
            label="Menunggu Verifikasi" 
            value="2" 
            icon="clock" 
            iconBg="orange" 
            subtext="Bukti bayar perlu diperiksa" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardSuccess"
            label="Diverifikasi / Sukses" 
            value="5" 
            icon="check-circle" 
            iconBg="green" 
            subtext="Langganan pengguna aktif" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            id="cardRejected"
            label="Ditolak / Tidak Valid" 
            value="1" 
            icon="alert-circle" 
            iconBg="orange" 
            subtext="Bukti palsu / kurang bayar" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Total Pendapatan Terverifikasi" 
            value="Rp 249.200" 
            icon="banknote" 
            iconBg="purple" 
            subtext="Dari transaksi disetujui" 
        />
    </div>
</div>

<!-- Alert Attention Banner -->
<div class="alert-banner-humora" id="pendingAlertBanner">
    <div class="alert-banner-content">
        <div class="alert-banner-icon">
            <i data-lucide="alert-triangle"></i>
        </div>
        <div>
            <div class="fw-bold text-dark" id="pendingBannerTitle" style="font-size: 0.85rem;">ADA 2 PEMBAYARAN MENUNGGU VERIFIKASI ADMIN</div>
            <div class="text-muted" style="font-size: 0.78rem;">Pengguna telah mengunggah foto bukti pembayaran. Harap verifikasi keabsahan bukti struk sebelum menyetujui hak akses Premium.</div>
        </div>
    </div>
    <button class="btn btn-humora-primary flex-shrink-0" id="btnFilterPending" style="font-size: 0.8rem; padding: 0.45rem 1rem;">
        Filter Menunggu (<span id="pendingBtnCount">2</span>)
    </button>
</div>

<!-- Search & Filters -->
<div class="humora-card mb-4 p-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-6 col-lg-8">
            <div class="search-input-wrap">
                <i data-lucide="search"></i>
                <input type="text" id="trxSearchInput" class="form-control-custom bg-light" placeholder="Cari ID transaksi (TRX-...), nama pengguna, atau no referensi...">
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <select class="form-select-custom" id="trxStatusFilter">
                <option selected>Semua Status</option>
                <option>Menunggu Verifikasi</option>
                <option>Diverifikasi</option>
                <option>Ditolak</option>
            </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <select class="form-select-custom" id="trxMethodFilter">
                <option selected>Semua Metode</option>
                <option>BCA Virtual Account</option>
                <option>Transfer Bank BCA</option>
                <option>QRIS</option>
                <option>ShopeePay</option>
                <option>Mandiri VA</option>
                <option>GoPay</option>
            </select>
        </div>
    </div>
</div>

<!-- Transactions Table Card -->
<div class="humora-card mb-0">
    <div class="table-responsive">
        <table class="table-humora" id="transactionsTable">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Paket Langganan</th>
                    <th>Metode Bayar</th>
                    <th>Total Tagihan</th>
                    <th>Status Pembayaran</th>
                    <th>Bukti Pembayaran</th>
                    <th class="text-end">Tindakan Admin</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1 -->
                <tr class="trx-row" 
                    data-trx-id="TRX-2026-1050" 
                    data-user-name="Dewi Lestari" 
                    data-user-email="dewi.l***@outlook.com"
                    data-package="Paket 3 Bulan (90 Hari)"
                    data-method="BCA Virtual Account"
                    data-amount="Rp 49.000"
                    data-status="Menunggu Verifikasi"
                    data-date="16 Okt 2026, 09:12 WIB">
                    <td><strong class="text-dark">TRX-2026-1050</strong></td>
                    <td>
                        <div class="fw-bold text-dark user-name">Dewi Lestari</div>
                        <small class="text-muted">dewi.l***@outlook.com</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">Paket 3 Bulan</div>
                        <small class="text-muted">90 Hari Akses</small>
                    </td>
                    <td><span class="badge-pill-gray">BCA Virtual Account</span></td>
                    <td class="fw-bold text-dark">Rp 49.000</td>
                    <td class="status-cell"><span class="badge-pill-warning"><i data-lucide="history"></i> Menunggu Verifikasi</span></td>
                    <td>
                        <button class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 btn-open-verify" style="color: #7C3AED; font-size: 0.78rem;">
                            <i data-lucide="eye"></i> Lihat Foto Bukti
                        </button>
                    </td>
                    <td class="text-end action-cell">
                        <button class="btn btn-sm btn-humora-primary py-1 px-2 btn-open-verify" style="font-size: 0.76rem;">
                            <i data-lucide="shield-check"></i> Periksa & Verifikasi
                        </button>
                    </td>
                </tr>

                <!-- 2 -->
                <tr class="trx-row" 
                    data-trx-id="TRX-2026-1049" 
                    data-user-name="Fikri Haikal" 
                    data-user-email="fikri.h***@gmail.com"
                    data-package="Paket 3 Bulan (90 Hari)"
                    data-method="Transfer Bank BCA"
                    data-amount="Rp 49.000"
                    data-status="Menunggu Verifikasi"
                    data-date="16 Okt 2026, 08:45 WIB">
                    <td><strong class="text-dark">TRX-2026-1049</strong></td>
                    <td>
                        <div class="fw-bold text-dark user-name">Fikri Haikal</div>
                        <small class="text-muted">fikri.h***@gmail.com</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">Paket 3 Bulan</div>
                        <small class="text-muted">90 Hari Akses</small>
                    </td>
                    <td><span class="badge-pill-gray">Transfer Bank BCA</span></td>
                    <td class="fw-bold text-dark">Rp 49.000</td>
                    <td class="status-cell"><span class="badge-pill-warning"><i data-lucide="history"></i> Menunggu Verifikasi</span></td>
                    <td>
                        <button class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 btn-open-verify" style="color: #7C3AED; font-size: 0.78rem;">
                            <i data-lucide="eye"></i> Lihat Foto Bukti
                        </button>
                    </td>
                    <td class="text-end action-cell">
                        <button class="btn btn-sm btn-humora-primary py-1 px-2 btn-open-verify" style="font-size: 0.76rem;">
                            <i data-lucide="shield-check"></i> Periksa & Verifikasi
                        </button>
                    </td>
                </tr>

                <!-- 3 -->
                <tr class="trx-row" 
                    data-trx-id="TRX-2026-1048" 
                    data-user-name="Budi Pratama" 
                    data-user-email="budi.p***@gmail.com"
                    data-package="Paket Bulanan (30 Hari)"
                    data-method="QRIS"
                    data-amount="Rp 19.000"
                    data-status="Diverifikasi"
                    data-date="15 Okt 2026, 21:10 WIB">
                    <td><strong class="text-dark">TRX-2026-1048</strong></td>
                    <td>
                        <div class="fw-bold text-dark user-name">Budi Pratama</div>
                        <small class="text-muted">budi.p***@gmail.com</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">Paket Bulanan</div>
                        <small class="text-muted">30 Hari Akses</small>
                    </td>
                    <td><span class="badge-pill-gray">QRIS</span></td>
                    <td class="fw-bold text-dark">Rp 19.000</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Diverifikasi</span></td>
                    <td>
                        <button class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 btn-open-verify" style="color: #7C3AED; font-size: 0.78rem;">
                            <i data-lucide="eye"></i> Lihat Foto Bukti
                        </button>
                    </td>
                    <td class="text-end action-cell">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-verify" style="font-size: 0.74rem;">Detail Bukti</button>
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-kuitansi" style="font-size: 0.74rem;">Kuitansi</button>
                        </div>
                    </td>
                </tr>

                <!-- 4 -->
                <tr class="trx-row" 
                    data-trx-id="TRX-2026-1047" 
                    data-user-name="Anisa Rahma" 
                    data-user-email="anisa.r***@gmail.com"
                    data-package="Paket 3 Bulan (90 Hari)"
                    data-method="ShopeePay"
                    data-amount="Rp 49.000"
                    data-status="Diverifikasi"
                    data-date="15 Okt 2026, 17:30 WIB">
                    <td><strong class="text-dark">TRX-2026-1047</strong></td>
                    <td>
                        <div class="fw-bold text-dark user-name">Anisa Rahma</div>
                        <small class="text-muted">anisa.r***@gmail.com</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">Paket 3 Bulan</div>
                        <small class="text-muted">90 Hari Akses</small>
                    </td>
                    <td><span class="badge-pill-gray">ShopeePay</span></td>
                    <td class="fw-bold text-dark">Rp 49.000</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Diverifikasi</span></td>
                    <td><span class="text-muted" style="font-size: 0.78rem;">Tidak ada foto</span></td>
                    <td class="text-end action-cell">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-verify" style="font-size: 0.74rem;">Detail Bukti</button>
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-kuitansi" style="font-size: 0.74rem;">Kuitansi</button>
                        </div>
                    </td>
                </tr>

                <!-- 5 -->
                <tr class="trx-row" 
                    data-trx-id="TRX-2026-1046" 
                    data-user-name="Dimas Anggara" 
                    data-user-email="dimas.a***@gmail.com"
                    data-package="Paket Bulanan (30 Hari)"
                    data-method="Mandiri VA"
                    data-amount="Rp 19.000"
                    data-status="Diverifikasi"
                    data-date="15 Okt 2026, 14:15 WIB">
                    <td><strong class="text-dark">TRX-2026-1046</strong></td>
                    <td>
                        <div class="fw-bold text-dark user-name">Dimas Anggara</div>
                        <small class="text-muted">dimas.a***@gmail.com</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">Paket Bulanan</div>
                        <small class="text-muted">30 Hari Akses</small>
                    </td>
                    <td><span class="badge-pill-gray">Mandiri VA</span></td>
                    <td class="fw-bold text-dark">Rp 19.000</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Diverifikasi</span></td>
                    <td><span class="text-muted" style="font-size: 0.78rem;">Tidak ada foto</span></td>
                    <td class="text-end action-cell">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-verify" style="font-size: 0.74rem;">Detail Bukti</button>
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-kuitansi" style="font-size: 0.74rem;">Kuitansi</button>
                        </div>
                    </td>
                </tr>

                <!-- 6 -->
                <tr class="trx-row" 
                    data-trx-id="TRX-2026-1044" 
                    data-user-name="Putri Marino" 
                    data-user-email="putri.m***@gmail.com"
                    data-package="Paket Bulanan (30 Hari)"
                    data-method="GoPay"
                    data-amount="Rp 19.000"
                    data-status="Ditolak"
                    data-date="14 Okt 2026, 11:20 WIB">
                    <td><strong class="text-dark">TRX-2026-1044</strong></td>
                    <td>
                        <div class="fw-bold text-dark user-name">Putri Marino</div>
                        <small class="text-muted">putri.m***@gmail.com</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">Paket Bulanan</div>
                        <small class="text-muted">30 Hari Akses</small>
                    </td>
                    <td><span class="badge-pill-gray">GoPay</span></td>
                    <td class="fw-bold text-dark">Rp 19.000</td>
                    <td class="status-cell"><span class="badge-pill-danger"><i data-lucide="x-circle"></i> Ditolak</span></td>
                    <td><span class="text-muted" style="font-size: 0.78rem;">Tidak ada foto</span></td>
                    <td class="text-end action-cell">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-verify" style="font-size: 0.74rem;">Detail Bukti</button>
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-kuitansi" style="font-size: 0.74rem;">Kuitansi</button>
                        </div>
                    </td>
                </tr>

                <!-- 7 -->
                <tr class="trx-row" 
                    data-trx-id="TRX-2026-1043" 
                    data-user-name="Siti Nurhaliza" 
                    data-user-email="siti.n***@yahoo.com"
                    data-package="Paket Tahunan (365 Hari)"
                    data-method="BCA Virtual Account"
                    data-amount="Rp 143.200"
                    data-status="Diverifikasi"
                    data-date="13 Okt 2026, 16:40 WIB">
                    <td><strong class="text-dark">TRX-2026-1043</strong></td>
                    <td>
                        <div class="fw-bold text-dark user-name">Siti Nurhaliza</div>
                        <small class="text-muted">siti.n***@yahoo.com</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">Paket Tahunan</div>
                        <small class="text-muted">365 Hari Akses</small>
                    </td>
                    <td><span class="badge-pill-gray">BCA Virtual Account</span></td>
                    <td class="fw-bold text-dark">Rp 143.200</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Diverifikasi</span></td>
                    <td><span class="text-muted" style="font-size: 0.78rem;">Tidak ada foto</span></td>
                    <td class="text-end action-cell">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-verify" style="font-size: 0.74rem;">Detail Bukti</button>
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-kuitansi" style="font-size: 0.74rem;">Kuitansi</button>
                        </div>
                    </td>
                </tr>

                <!-- 8 -->
                <tr class="trx-row" 
                    data-trx-id="TRX-2026-1042" 
                    data-user-name="Reza Rahardian" 
                    data-user-email="reza.r***@gmail.com"
                    data-package="Paket Bulanan (30 Hari)"
                    data-method="GoPay"
                    data-amount="Rp 19.000"
                    data-status="Diverifikasi"
                    data-date="12 Okt 2026, 10:05 WIB">
                    <td><strong class="text-dark">TRX-2026-1042</strong></td>
                    <td>
                        <div class="fw-bold text-dark user-name">Reza Rahardian</div>
                        <small class="text-muted">reza.r***@gmail.com</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">Paket Bulanan</div>
                        <small class="text-muted">30 Hari Akses</small>
                    </td>
                    <td><span class="badge-pill-gray">GoPay</span></td>
                    <td class="fw-bold text-dark">Rp 19.000</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="check-circle"></i> Diverifikasi</span></td>
                    <td><span class="text-muted" style="font-size: 0.78rem;">Tidak ada foto</span></td>
                    <td class="text-end action-cell">
                        <div class="d-flex justify-content-end gap-1">
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-verify" style="font-size: 0.74rem;">Detail Bukti</button>
                            <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-kuitansi" style="font-size: 0.74rem;">Kuitansi</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top gap-2">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted" id="trxPaginationCounter" style="font-size: 0.8rem;">Menampilkan <strong>1 - 8</strong> dari <strong>8</strong> data</small>
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
<!-- Verify Proof Modal -->
<div class="modal fade" id="verifyProofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora mb-0">Periksa Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="p-3 bg-light rounded-3 mb-3 text-center border">
                    <div class="d-inline-flex flex-column align-items-center p-3 bg-white rounded-3 shadow-sm border w-100">
                        <i data-lucide="receipt" class="fs-1 text-primary mb-2" style="color: #43135C !important;"></i>
                        <div class="fw-bold text-dark">Struk Transfer / Mutasi Rekening</div>
                        <small class="text-muted mb-2" id="modalTrxIdUser">TRX-2026-1050 &bull; Dewi Lestari</small>
                        <div class="badge-pill-success py-1 px-3 fs-6" id="modalTrxAmount">Rp 49.000</div>
                    </div>
                </div>
                <div class="row g-2 mb-2" style="font-size: 0.82rem;">
                    <div class="col-6 text-muted">Metode Pembayaran:</div>
                    <div class="col-6 fw-bold text-dark text-end" id="modalTrxMethod">BCA Virtual Account</div>
                    <div class="col-6 text-muted">Paket Langganan:</div>
                    <div class="col-6 fw-bold text-dark text-end" id="modalTrxPackage">Paket 3 Bulan (90 Hari)</div>
                    <div class="col-6 text-muted">Waktu Pengunggahan:</div>
                    <div class="col-6 text-dark text-end" id="modalTrxDate">16 Okt 2026, 09:12 WIB</div>
                    <div class="col-6 text-muted">Status Saat Ini:</div>
                    <div class="col-6 fw-bold text-end" id="modalTrxStatus">Menunggu Verifikasi</div>
                </div>
            </div>
            <div class="modal-footer modal-footer-humora justify-content-between" id="modalActionFooter">
                <button type="button" class="btn btn-outline-danger" id="btnRejectTrx">
                    <i data-lucide="x-circle" class="me-1"></i> Tolak Transaksi
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-humora-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-humora-primary" id="btnApproveTrx">
                        <i data-lucide="check-circle" class="me-1"></i> Setujui & Berikan Akses
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let activeRow = null;
        let pendingCount = 2;
        let successCount = 5;
        let rejectedCount = 1;

        const modalTrxIdUser = document.getElementById('modalTrxIdUser');
        const modalTrxAmount = document.getElementById('modalTrxAmount');
        const modalTrxMethod = document.getElementById('modalTrxMethod');
        const modalTrxPackage = document.getElementById('modalTrxPackage');
        const modalTrxDate = document.getElementById('modalTrxDate');
        const modalTrxStatus = document.getElementById('modalTrxStatus');
        const btnApproveTrx = document.getElementById('btnApproveTrx');
        const btnRejectTrx = document.getElementById('btnRejectTrx');
        const verifyModalEl = document.getElementById('verifyProofModal');
        const bsVerifyModal = new bootstrap.Modal(verifyModalEl);

        const cardPendingVal = document.querySelector('#cardPending .stat-value');
        const cardSuccessVal = document.querySelector('#cardSuccess .stat-value');
        const cardRejectedVal = document.querySelector('#cardRejected .stat-value');
        const pendingBtnCount = document.getElementById('pendingBtnCount');
        const pendingBannerTitle = document.getElementById('pendingBannerTitle');
        const pendingAlertBanner = document.getElementById('pendingAlertBanner');

        // Open modal and bind row details
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-open-verify');
            if (btn) {
                e.preventDefault();
                activeRow = btn.closest('tr');
                if (!activeRow) return;

                const trxId = activeRow.dataset.trxId;
                const userName = activeRow.dataset.userName;
                const amount = activeRow.dataset.amount;
                const method = activeRow.dataset.method;
                const pkg = activeRow.dataset.package;
                const date = activeRow.dataset.date;
                const status = activeRow.dataset.status;

                modalTrxIdUser.innerText = `${trxId} • ${userName}`;
                modalTrxAmount.innerText = amount;
                modalTrxMethod.innerText = method;
                modalTrxPackage.innerText = pkg;
                modalTrxDate.innerText = date;
                modalTrxStatus.innerText = status;

                if (status === 'Menunggu Verifikasi') {
                    btnApproveTrx.disabled = false;
                    btnRejectTrx.disabled = false;
                    btnApproveTrx.classList.remove('d-none');
                    btnRejectTrx.classList.remove('d-none');
                } else {
                    btnApproveTrx.classList.add('d-none');
                    btnRejectTrx.classList.add('d-none');
                }

                bsVerifyModal.show();
            }

            // Kuitansi button handler
            const kuitansiBtn = e.target.closest('.btn-kuitansi');
            if (kuitansiBtn) {
                e.preventDefault();
                const row = kuitansiBtn.closest('tr');
                const trxId = row ? row.dataset.trxId : 'TRX';
                window.showHumoraToast(`Kuitansi resmi ${trxId} berhasil diunduh.`, 'info');
            }
        });

        // Approve action
        btnApproveTrx.addEventListener('click', function () {
            if (!activeRow) return;
            const trxId = activeRow.dataset.trxId;
            const statusCell = activeRow.querySelector('.status-cell');
            const actionCell = activeRow.querySelector('.action-cell');

            activeRow.dataset.status = 'Diverifikasi';
            if (statusCell) {
                statusCell.innerHTML = '<span class="badge-pill-success"><i data-lucide="check-circle"></i> Diverifikasi</span>';
            }
            if (actionCell) {
                actionCell.innerHTML = `
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-verify" style="font-size: 0.74rem;">Detail Bukti</button>
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-kuitansi" style="font-size: 0.74rem;">Kuitansi</button>
                    </div>
                `;
            }

            pendingCount = Math.max(0, pendingCount - 1);
            successCount += 1;
            updateCounters();

            bsVerifyModal.hide();
            window.showHumoraToast(`Transaksi ${trxId} berhasil disetujui! Hak akses Premium telah diaktifkan.`);
        });

        // Reject action
        btnRejectTrx.addEventListener('click', function () {
            if (!activeRow) return;
            const trxId = activeRow.dataset.trxId;
            const statusCell = activeRow.querySelector('.status-cell');
            const actionCell = activeRow.querySelector('.action-cell');

            activeRow.dataset.status = 'Ditolak';
            if (statusCell) {
                statusCell.innerHTML = '<span class="badge-pill-danger"><i data-lucide="x-circle"></i> Ditolak</span>';
            }
            if (actionCell) {
                actionCell.innerHTML = `
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-verify" style="font-size: 0.74rem;">Detail Bukti</button>
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-kuitansi" style="font-size: 0.74rem;">Kuitansi</button>
                    </div>
                `;
            }

            pendingCount = Math.max(0, pendingCount - 1);
            rejectedCount += 1;
            updateCounters();

            bsVerifyModal.hide();
            window.showHumoraToast(`Transaksi ${trxId} telah ditolak.`, 'warning');
        });

        function updateCounters() {
            if (cardPendingVal) cardPendingVal.innerText = pendingCount;
            if (cardSuccessVal) cardSuccessVal.innerText = successCount;
            if (cardRejectedVal) cardRejectedVal.innerText = rejectedCount;
            if (pendingBtnCount) pendingBtnCount.innerText = pendingCount;
            if (pendingBannerTitle) {
                pendingBannerTitle.innerText = pendingCount > 0 
                    ? `ADA ${pendingCount} PEMBAYARAN MENUNGGU VERIFIKASI ADMIN`
                    : 'SELURUH PEMBAYARAN TELAH DIVERIFIKASI';
            }
            if (pendingCount === 0 && pendingAlertBanner) {
                pendingAlertBanner.classList.add('opacity-75');
            }
        }

        // Quick filter button
        const btnFilterPending = document.getElementById('btnFilterPending');
        const trxStatusFilter = document.getElementById('trxStatusFilter');
        if (btnFilterPending && trxStatusFilter) {
            btnFilterPending.addEventListener('click', function () {
                trxStatusFilter.value = 'Menunggu Verifikasi';
                trxStatusFilter.dispatchEvent(new Event('change'));
                document.getElementById('transactionsTable').scrollIntoView({ behavior: 'smooth' });
                window.showHumoraToast('Menampilkan transaksi Menunggu Verifikasi');
            });
        }
    });
</script>
@endsection
