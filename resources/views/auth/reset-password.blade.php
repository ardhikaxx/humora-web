@extends('layouts.auth')

@section('title', 'Ubah Kata Sandi Baru - HUMORA')

@section('content')
<h1 class="auth-title">Ubah Kata Sandi Baru</h1>
<p class="auth-subtitle mb-2">Buat kata sandi baru yang aman untuk akun administrator Anda.</p>

<div class="mb-3">
    <span class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill" style="background-color: #F3E8FF; color: #6B21A8; font-size: 0.78rem; font-weight: 600;">
        <i data-lucide="check-circle"></i>
        <span>Akun Terverifikasi: {{ $email }}</span>
    </span>
</div>

<form action="{{ route('admin.password.update') }}" method="POST" class="text-start">
    @csrf

    <div class="humora-field-group mb-3">
        <div class="humora-field has-suffix @error('password') is-error @enderror">
            <input type="password" id="new_password" name="password" class="form-control-custom @error('password') is-invalid @enderror" placeholder=" " required autocomplete="off">
            <label for="new_password" class="humora-floating-label">Kata Sandi Baru</label>
            <button type="button" class="btn btn-link humora-field-suffix text-muted text-decoration-none p-0" onclick="toggleVisibility('new_password', 'eye1')">
                <i data-lucide="eye" id="eye1"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="humora-field-group mb-3">
        <div class="humora-field has-suffix @error('password_confirmation') is-error @enderror">
            <input type="password" id="confirm_password" name="password_confirmation" class="form-control-custom @error('password_confirmation') is-invalid @enderror" placeholder=" " required autocomplete="off">
            <label for="confirm_password" class="humora-floating-label">Konfirmasi Kata Sandi Baru</label>
            <button type="button" class="btn btn-link humora-field-suffix text-muted text-decoration-none p-0" onclick="toggleVisibility('confirm_password', 'eye2')">
                <i data-lucide="eye" id="eye2"></i>
            </button>
        </div>
        @error('password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <small class="text-muted" style="font-size: 0.74rem;">
            Pastikan kedua kata sandi yang Anda masukkan sama persis (minimal 6 karakter).
        </small>
    </div>

    <button type="submit" class="btn btn-humora-primary w-100 py-2 justify-content-center mb-3">
        <span>Simpan Kata Sandi Baru</span>
        <i data-lucide="arrow-right"></i>
    </button>

    <div class="text-center">
        <a href="{{ route('admin.login') }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.82rem; font-weight: 600; color: #4B5563;">
            <i data-lucide="arrow-left"></i>
            <span>Batal dan Kembali ke Login</span>
        </a>
    </div>
</form>
@endsection

@section('scripts')
<script>
    function toggleVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
</script>
@endsection
