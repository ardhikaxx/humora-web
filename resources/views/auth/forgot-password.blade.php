@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi - HUMORA')

@section('content')
<h1 class="auth-title">Lupa Kata Sandi</h1>
<p class="auth-subtitle">Masukkan email administrator Anda untuk verifikasi akun pada basis data sistem.</p>

<form action="{{ url('/reset-password') }}" method="GET" class="text-start">
    <div class="mb-2">
        <label for="email" class="form-label-custom">Alamat Email Administrator</label>
        <input type="email" id="email" name="email" class="form-control-custom" value="admin@gmail.com" placeholder="nama@gmail.com" required>
    </div>
    <div class="mb-4">
        <small class="text-muted" style="font-size: 0.74rem;">
            Sistem akan memvalidasi apakah akun terdaftar pada hak akses portal.
        </small>
    </div>

    <button type="submit" class="btn btn-humora-primary w-100 py-2 justify-content-center mb-3">
        <span>Verifikasi Akun</span>
        <i data-lucide="arrow-right"></i>
    </button>

    <div class="text-center">
        <a href="{{ url('/login') }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.82rem; font-weight: 600; color: #4B5563;">
            <i data-lucide="arrow-left"></i>
            <span>Kembali ke Halaman Login</span>
        </a>
    </div>
</form>
@endsection
