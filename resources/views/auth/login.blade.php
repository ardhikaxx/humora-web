@extends('layouts.auth')

@section('title', 'Masuk ke Akun Admin - HUMORA')

@section('content')
<h1 class="auth-title">Masuk ke Akun Admin</h1>
<p class="auth-subtitle">Masukkan kredensial administrator Anda untuk mengelola portal HUMORA.</p>

<form action="{{ route('admin.login.submit') }}" method="POST" class="text-start">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label-custom">Email Administrator</label>
        <input type="email" id="email" name="email" class="form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan email administrator (contoh: admin@gmail.com)" required autofocus autocomplete="off">
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="form-label-custom mb-0">Kata Sandi</label>
        </div>
        <div class="position-relative">
            <input type="password" id="password" name="password" class="form-control-custom pe-5 @error('password') is-invalid @enderror" placeholder="Masukkan kata sandi (contoh: password)" required autocomplete="off">
            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted text-decoration-none pe-3" id="togglePassword">
                <i data-lucide="eye" id="eyeIcon"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" style="accent-color: #43135C; cursor: pointer;">
        <label class="form-check-label" for="remember" style="font-size: 0.82rem; color: #374151; cursor: pointer;">
            Ingat Sesi di Perangkat Ini
        </label>
    </div>

    <button type="submit" class="btn btn-humora-primary w-100 py-2 justify-content-center">
        <span>Masuk ke Dashboard</span>
        <i data-lucide="arrow-right"></i>
    </button>
</form>
@endsection

@section('scripts')
<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && password && eyeIcon) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            const iconName = type === 'password' ? 'eye' : 'eye-off';
            eyeIcon.setAttribute('data-lucide', iconName);
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    }
</script>
@endsection
