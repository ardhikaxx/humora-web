@extends('layouts.admin')

@section('title', 'Profil Admin & Akun Pengelola - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Profil Admin</span>
    </small>
</div>

<!-- Header -->
<div class="mb-4">
    <h1 class="page-title">Profil Admin & Akun Pengelola</h1>
    <p class="page-subtitle mb-0">Kelola informasi akun administrator HUMORA: Nama Lengkap, Email, Password, dan Nomor Telfon.</p>
</div>

<!-- Top Hero Profile Card -->
<div class="humora-card mb-4">
    <div class="humora-card-body p-4">
        <div class="d-flex align-items-center gap-3">
            <div class="sidebar-avatar-circle" id="profileHeroAvatar" style="width: 58px; height: 58px; font-size: 1.3rem;">
                AH
            </div>
            <div>
                <h2 class="fw-bold text-dark mb-1" id="profileHeroName" style="font-size: 1.35rem;">Admin HUMORA</h2>
                <div class="d-flex flex-wrap align-items-center gap-2 text-muted" style="font-size: 0.82rem;">
                    <span class="d-flex align-items-center gap-1">
                        <i class="bi bi-envelope"></i> <span id="profileHeroEmail">admin@humora.id</span>
                    </span>
                    <span>&bull;</span>
                    <span class="d-flex align-items-center gap-1">
                        <i class="bi bi-telephone"></i> <span id="profileHeroPhone">+62 812-8899-7711</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Data Profil Administrator -->
    <div class="col-12 col-lg-7">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">DATA PROFIL ADMINISTRATOR</div>
                    <div class="humora-card-subtitle">Perbarui Nama Lengkap, Email, dan Nomor Telfon akun Anda.</div>
                </div>
                <div class="text-muted"><i class="bi bi-person fs-5"></i></div>
            </div>
            <div class="humora-card-body p-4">
                <form id="formUpdateProfile" action="{{ url('/admin/profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                            <input type="text" id="profNameInput" name="name" class="form-control-custom border-start-0" value="Admin HUMORA" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" id="profEmailInput" name="email" class="form-control-custom border-start-0" value="admin@humora.id" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Nomor Telfon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-telephone"></i></span>
                            <input type="text" id="profPhoneInput" name="phone" class="form-control-custom border-start-0" value="+62 812-8899-7711" required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-humora-primary">
                            <i class="bi bi-floppy"></i>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Password Akun -->
    <div class="col-12 col-lg-5">
        <div class="humora-card h-100">
            <div class="humora-card-header">
                <div>
                    <div class="humora-card-title">PASSWORD AKUN</div>
                    <div class="humora-card-subtitle">Perbarui kata sandi login admin Anda.</div>
                </div>
                <div class="text-muted"><i class="bi bi-key fs-5"></i></div>
            </div>
            <div class="humora-card-body p-4">
                <form id="formUpdatePassword" action="{{ url('/admin/profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="cur_pass" class="form-label-custom">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" id="cur_pass" name="current_password" class="form-control-custom pe-5" placeholder="Kata sandi saat ini" required>
                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePass('cur_pass', 'cur_eye')">
                                <i class="bi bi-eye" id="cur_eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_pass_prof" class="form-label-custom">Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" id="new_pass_prof" name="new_password" class="form-control-custom pe-5" placeholder="Minimal 4 karakter" required minlength="4">
                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePass('new_pass_prof', 'new_eye')">
                                <i class="bi bi-eye" id="new_eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="conf_pass_prof" class="form-label-custom">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" id="conf_pass_prof" name="new_password_confirmation" class="form-control-custom pe-5" placeholder="Ulangi kata sandi baru" required>
                            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none pe-3" onclick="togglePass('conf_pass_prof', 'conf_eye')">
                                <i class="bi bi-eye" id="conf_eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-humora-primary w-100 justify-content-center">
                        <i class="bi bi-lock"></i>
                        <span>Perbarui Kata Sandi</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePass(id, eyeId) {
        const input = document.getElementById(id);
        const eye = document.getElementById(eyeId);
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            eye.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const formProfile = document.getElementById('formUpdateProfile');
        const formPassword = document.getElementById('formUpdatePassword');

        const profNameInput = document.getElementById('profNameInput');
        const profEmailInput = document.getElementById('profEmailInput');
        const profPhoneInput = document.getElementById('profPhoneInput');

        const profileHeroName = document.getElementById('profileHeroName');
        const profileHeroEmail = document.getElementById('profileHeroEmail');
        const profileHeroPhone = document.getElementById('profileHeroPhone');
        const profileHeroAvatar = document.getElementById('profileHeroAvatar');

        formProfile.addEventListener('submit', function (e) {
            e.preventDefault();
            const name = profNameInput.value.trim();
            const email = profEmailInput.value.trim();
            const phone = profPhoneInput.value.trim();

            profileHeroName.innerText = name;
            profileHeroEmail.innerText = email;
            profileHeroPhone.innerText = phone;
            profileHeroAvatar.innerText = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

            window.showHumoraToast('Data profil administrator berhasil disimpan!');
        });

        formPassword.addEventListener('submit', function (e) {
            e.preventDefault();
            const newPass = document.getElementById('new_pass_prof').value;
            const confPass = document.getElementById('conf_pass_prof').value;

            if (newPass !== confPass) {
                window.showHumoraToast('Konfirmasi kata sandi baru tidak cocok!', 'danger');
                return;
            }

            this.reset();
            window.showHumoraToast('Kata sandi administrator berhasil diperbarui!');
        });
    });
</script>
@endsection
