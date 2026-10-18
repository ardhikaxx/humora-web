@extends('layouts.admin')

@section('title', 'Pengguna Aplikasi - HUMORA Portal Admin')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Pengguna</span>
    </small>
</div>

<!-- Header -->
<div class="mb-4">
    <h1 class="page-title">Pengguna Aplikasi</h1>
    <p class="page-subtitle mb-0">Pantau ringkasan profil pengguna aplikasi mobile HUMORA, status langganan, dan status akun.</p>
</div>

<!-- 4 Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Total Pengguna" 
            value="24.850" 
            icon="users" 
            iconBg="purple" 
            subtext="Total akun terdaftar" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Pengguna Aktif" 
            value="18.920" 
            icon="shield-check" 
            iconBg="green" 
            trend="12.4%" 
            subtext="aktif bln ini" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Pengguna Baru" 
            value="3.410" 
            icon="clock" 
            iconBg="orange" 
            trend="8.2%" 
            subtext="vs bln lalu" 
        />
    </div>
    <div class="col-6 col-md-3">
        <x-stat-card 
            label="Pelanggan Premium" 
            value="3.274" 
            icon="gem" 
            iconBg="purple" 
            subtext="Memiliki akses penuh" 
        />
    </div>
</div>

<!-- Search & Filters -->
<div class="humora-card mb-4 p-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-6 col-lg-8">
            <div class="search-input-wrap">
                <i data-lucide="search"></i>
                <input type="text" id="userSearchInput" class="form-control-custom bg-light" placeholder="Cari nama atau email pengguna...">
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <select class="form-select-custom" id="userStatusFilter">
                <option selected>Semua Status Akun</option>
                <option>Aktif</option>
                <option>Ditangguhkan</option>
            </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <select class="form-select-custom" id="userSubFilter">
                <option selected>Semua Langganan</option>
                <option>Premium Aktif</option>
                <option>Akun Gratis</option>
                <option>Kedaluwarsa</option>
            </select>
        </div>
    </div>
</div>

<!-- Users Table Card -->
<div class="humora-card mb-0">
    <div class="table-responsive">
        <table class="table-humora" id="usersTable">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Kontak</th>
                    <th>Bergabung</th>
                    <th>Status Akun</th>
                    <th>Subscription</th>
                    <th>Aktivitas Terakhir</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1 -->
                <tr class="user-row"
                    data-user-id="usr-101"
                    data-user-name="Budi Pratama"
                    data-user-email="budi.p***@gmail.com"
                    data-user-phone="0812-34**-8910"
                    data-user-joined="15 Jan 2026"
                    data-user-status="Aktif"
                    data-user-sub="Premium Aktif (Paket 3 Bulan)"
                    data-user-initials="BP">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                BP
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Budi Pratama</div>
                                <small class="text-muted">ID: usr-101</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><code>budi.p***@gmail.com</code></div>
                        <small class="text-muted">0812-34**-8910</small>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">15 Jan 2026</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span></td>
                    <td><span class="badge-pill-premium"><i data-lucide="gem"></i> Premium Aktif</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">16 Okt 2026, 01:20 WIB</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-user-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 2 -->
                <tr class="user-row"
                    data-user-id="usr-102"
                    data-user-name="Siti Nurhaliza"
                    data-user-email="siti.n***@yahoo.com"
                    data-user-phone="0813-88**-1243"
                    data-user-joined="18 Jan 2026"
                    data-user-status="Aktif"
                    data-user-sub="Premium Aktif (Paket Tahunan)"
                    data-user-initials="SN">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                SN
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Siti Nurhaliza</div>
                                <small class="text-muted">ID: usr-102</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><code>siti.n***@yahoo.com</code></div>
                        <small class="text-muted">0813-88**-1243</small>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">18 Jan 2026</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span></td>
                    <td><span class="badge-pill-premium"><i data-lucide="gem"></i> Premium Aktif</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">16 Okt 2026, 15:15 WIB</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-user-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 3 -->
                <tr class="user-row"
                    data-user-id="usr-103"
                    data-user-name="Reza Rahardian"
                    data-user-email="reza.r***@gmail.com"
                    data-user-phone="0857-12**-7788"
                    data-user-joined="22 Jan 2026"
                    data-user-status="Aktif"
                    data-user-sub="Kedaluwarsa"
                    data-user-initials="RR">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                RR
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Reza Rahardian</div>
                                <small class="text-muted">ID: usr-103</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><code>reza.r***@gmail.com</code></div>
                        <small class="text-muted">0857-12**-7788</small>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">22 Jan 2026</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span></td>
                    <td><span class="badge-pill-warning"><i data-lucide="history"></i> Kedaluwarsa</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">10 Okt 2026, 19:00 WIB</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-user-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 4 -->
                <tr class="user-row"
                    data-user-id="usr-104"
                    data-user-name="Dewi Lestari"
                    data-user-email="dewi.l***@outlook.com"
                    data-user-phone="0821-99**-4321"
                    data-user-joined="1 Feb 2026"
                    data-user-status="Aktif"
                    data-user-sub="Akun Gratis"
                    data-user-initials="DL">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                DL
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Dewi Lestari</div>
                                <small class="text-muted">ID: usr-104</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><code>dewi.l***@outlook.com</code></div>
                        <small class="text-muted">0821-99**-4321</small>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">1 Feb 2026</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span></td>
                    <td><span class="badge-pill-gray">Akun Gratis</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">16 Okt 2026, 05:45 WIB</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-user-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 5 -->
                <tr class="user-row"
                    data-user-id="usr-105"
                    data-user-name="Fikri Haikal"
                    data-user-email="fikri.h***@gmail.com"
                    data-user-phone="0878-55**-6611"
                    data-user-joined="5 Feb 2026"
                    data-user-status="Ditangguhkan"
                    data-user-sub="Akun Gratis"
                    data-user-initials="FH">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                FH
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Fikri Haikal</div>
                                <small class="text-muted">ID: usr-105</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><code>fikri.h***@gmail.com</code></div>
                        <small class="text-muted">0878-55**-6611</small>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">5 Feb 2026</td>
                    <td class="status-cell"><span class="badge-pill-danger"><i data-lucide="ban"></i> Ditangguhkan</span></td>
                    <td><span class="badge-pill-gray">Akun Gratis</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">12 Okt 2026, 08:30 WIB</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-user-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 6 -->
                <tr class="user-row"
                    data-user-id="usr-106"
                    data-user-name="Anisa Rahma"
                    data-user-email="anisa.r***@gmail.com"
                    data-user-phone="0812-11**-3344"
                    data-user-joined="10 Feb 2026"
                    data-user-status="Aktif"
                    data-user-sub="Premium Aktif (Paket 3 Bulan)"
                    data-user-initials="AR">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                AR
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Anisa Rahma</div>
                                <small class="text-muted">ID: usr-106</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><code>anisa.r***@gmail.com</code></div>
                        <small class="text-muted">0812-11**-3344</small>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">10 Feb 2026</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span></td>
                    <td><span class="badge-pill-premium"><i data-lucide="gem"></i> Premium Aktif</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">15 Okt 2026, 17:30 WIB</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-user-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 7 -->
                <tr class="user-row"
                    data-user-id="usr-107"
                    data-user-name="Dimas Anggara"
                    data-user-email="dimas.a***@gmail.com"
                    data-user-phone="0819-22**-8899"
                    data-user-joined="14 Feb 2026"
                    data-user-status="Aktif"
                    data-user-sub="Premium Aktif (Paket Bulanan)"
                    data-user-initials="DA">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                DA
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Dimas Anggara</div>
                                <small class="text-muted">ID: usr-107</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><code>dimas.a***@gmail.com</code></div>
                        <small class="text-muted">0819-22**-8899</small>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">14 Feb 2026</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span></td>
                    <td><span class="badge-pill-premium"><i data-lucide="gem"></i> Premium Aktif</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">15 Okt 2026, 14:15 WIB</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-user-detail" style="font-size: 0.76rem;">
                            <i data-lucide="eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- 8 -->
                <tr class="user-row"
                    data-user-id="usr-108"
                    data-user-name="Putri Marino"
                    data-user-email="putri.m***@gmail.com"
                    data-user-phone="0852-66**-7700"
                    data-user-joined="20 Feb 2026"
                    data-user-status="Aktif"
                    data-user-sub="Akun Gratis"
                    data-user-initials="PM">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle">
                                PM
                            </div>
                            <div>
                                <div class="fw-bold text-dark user-name">Putri Marino</div>
                                <small class="text-muted">ID: usr-108</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><code>putri.m***@gmail.com</code></div>
                        <small class="text-muted">0852-66**-7700</small>
                    </td>
                    <td class="text-muted" style="font-size: 0.8rem;">20 Feb 2026</td>
                    <td class="status-cell"><span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span></td>
                    <td><span class="badge-pill-gray">Akun Gratis</span></td>
                    <td class="text-muted" style="font-size: 0.8rem;">14 Okt 2026, 23:20 WIB</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-humora-secondary py-1 px-2 btn-open-user-detail" style="font-size: 0.76rem;">
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
            <small class="text-muted" style="font-size: 0.8rem;">Menampilkan <strong>1 - 8</strong> dari <strong>8</strong> data</small>
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
<!-- User Detail Modal -->
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-humora">
            <div class="modal-header modal-header-humora">
                <h5 class="modal-title-humora mb-0">Detail Profil Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-humora">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <div class="user-avatar-circle primary" id="modalUserInitials" style="width: 48px; height: 48px; font-size: 1.1rem;">
                        BP
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6" id="modalUserName">Budi Pratama</div>
                        <small class="text-muted" id="modalUserIdJoined">ID: usr-101 &bull; Bergabung 15 Jan 2026</small>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Email</small>
                        <div class="fw-semibold text-dark" id="modalUserEmail" style="font-size: 0.85rem;">budi.pratama@gmail.com</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Nomor Telepon</small>
                        <div class="fw-semibold text-dark" id="modalUserPhone" style="font-size: 0.85rem;">+62 812-3489-8910</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Status Akun</small>
                        <div id="modalUserStatusWrap" class="mt-1">
                            <span class="badge-pill-success" id="modalUserStatus"><i data-lucide="shield-check"></i> Aktif</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Status Langganan</small>
                        <div id="modalUserSubWrap" class="mt-1">
                            <span class="badge-pill-premium" id="modalUserSub"><i data-lucide="gem"></i> Premium Aktif (Paket 3 Bulan)</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-humora justify-content-between">
                <button type="button" class="btn btn-outline-danger btn-sm" id="btnToggleUserStatus">
                    <i data-lucide="ban" class="me-1"></i> Tangguhkan Akun
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
        let activeUserRow = null;
        const userDetailModalEl = document.getElementById('userDetailModal');
        const bsUserModal = new bootstrap.Modal(userDetailModalEl);

        const modalUserName = document.getElementById('modalUserName');
        const modalUserIdJoined = document.getElementById('modalUserIdJoined');
        const modalUserInitials = document.getElementById('modalUserInitials');
        const modalUserEmail = document.getElementById('modalUserEmail');
        const modalUserPhone = document.getElementById('modalUserPhone');
        const modalUserStatusWrap = document.getElementById('modalUserStatusWrap');
        const modalUserSubWrap = document.getElementById('modalUserSubWrap');
        const btnToggleUserStatus = document.getElementById('btnToggleUserStatus');

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-open-user-detail');
            if (btn) {
                e.preventDefault();
                activeUserRow = btn.closest('tr');
                if (!activeUserRow) return;

                const id = activeUserRow.dataset.userId;
                const name = activeUserRow.dataset.userName;
                const email = activeUserRow.dataset.userEmail;
                const phone = activeUserRow.dataset.userPhone;
                const joined = activeUserRow.dataset.userJoined;
                const status = activeUserRow.dataset.userStatus;
                const sub = activeUserRow.dataset.userSub;
                const initials = activeUserRow.dataset.userInitials;

                modalUserName.innerText = name;
                modalUserIdJoined.innerHTML = `ID: ${id} &bull; Bergabung ${joined}`;
                modalUserInitials.innerText = initials;
                modalUserEmail.innerText = email;
                modalUserPhone.innerText = phone;

                if (status === 'Aktif') {
                    modalUserStatusWrap.innerHTML = '<span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span>';
                    btnToggleUserStatus.className = 'btn btn-outline-danger btn-sm';
                    btnToggleUserStatus.innerHTML = '<i data-lucide="ban" class="me-1"></i> Tangguhkan Akun';
                } else {
                    modalUserStatusWrap.innerHTML = '<span class="badge-pill-danger"><i data-lucide="ban"></i> Ditangguhkan</span>';
                    btnToggleUserStatus.className = 'btn btn-outline-success btn-sm';
                    btnToggleUserStatus.innerHTML = '<i data-lucide="check-circle" class="me-1"></i> Aktifkan Kembali';
                }

                if (sub.includes('Premium')) {
                    modalUserSubWrap.innerHTML = `<span class="badge-pill-premium"><i data-lucide="gem"></i> ${sub}</span>`;
                } else if (sub.includes('Kedaluwarsa')) {
                    modalUserSubWrap.innerHTML = `<span class="badge-pill-warning"><i data-lucide="history"></i> ${sub}</span>`;
                } else {
                    modalUserSubWrap.innerHTML = `<span class="badge-pill-gray">${sub}</span>`;
                }

                bsUserModal.show();
            }
        });

        btnToggleUserStatus.addEventListener('click', function () {
            if (!activeUserRow) return;
            const currentStatus = activeUserRow.dataset.userStatus;
            const statusCell = activeUserRow.querySelector('.status-cell');
            const userName = activeUserRow.dataset.userName;

            if (currentStatus === 'Aktif') {
                activeUserRow.dataset.userStatus = 'Ditangguhkan';
                if (statusCell) {
                    statusCell.innerHTML = '<span class="badge-pill-danger"><i data-lucide="ban"></i> Ditangguhkan</span>';
                }
                modalUserStatusWrap.innerHTML = '<span class="badge-pill-danger"><i data-lucide="ban"></i> Ditangguhkan</span>';
                btnToggleUserStatus.className = 'btn btn-outline-success btn-sm';
                btnToggleUserStatus.innerHTML = '<i data-lucide="check-circle" class="me-1"></i> Aktifkan Kembali';
                window.showHumoraToast(`Akun ${userName} berhasil ditangguhkan.`, 'warning');
            } else {
                activeUserRow.dataset.userStatus = 'Aktif';
                if (statusCell) {
                    statusCell.innerHTML = '<span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span>';
                }
                modalUserStatusWrap.innerHTML = '<span class="badge-pill-success"><i data-lucide="shield-check"></i> Aktif</span>';
                btnToggleUserStatus.className = 'btn btn-outline-danger btn-sm';
                btnToggleUserStatus.innerHTML = '<i data-lucide="ban" class="me-1"></i> Tangguhkan Akun';
                window.showHumoraToast(`Akun ${userName} berhasil diaktifkan kembali.`, 'success');
            }
        });
    });
</script>
@endsection
