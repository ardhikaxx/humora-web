@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi - HUMORA')

@section('content')
<h1 class="auth-title">Lupa Kata Sandi</h1>
<p class="auth-subtitle">Masukkan email administrator Anda untuk verifikasi akun pada basis data sistem.</p>

<form action="{{ route('admin.password.email') }}" method="POST" class="text-start">
    @csrf

    <div class="mb-2">
        <label for="email" class="form-label-custom">Alamat Email Administrator</label>
        <input type="email" id="email" name="email" class="form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan email administrator" required autofocus autocomplete="off">
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-4">
        <small class="text-muted" style="font-size: 0.74rem;">
            Sistem akan memvalidasi apakah akun terdaftar pada basis data sistem portal admin.
        </small>
    </div>

    <button type="submit" class="btn btn-humora-primary w-100 py-2 justify-content-center mb-3">
        <span>Verifikasi Email</span>
        <i data-lucide="arrow-right"></i>
    </button>

    <div class="text-center">
        <a href="{{ route('admin.login') }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.82rem; font-weight: 600; color: #4B5563;">
            <i data-lucide="arrow-left"></i>
            <span>Kembali ke Halaman Login</span>
        </a>
    </div>
</form>
@endsection
