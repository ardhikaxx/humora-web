@extends('layouts.auth')

@section('title', 'Masuk ke Akun Admin - HUMORA')

@section('content')
<h1 class="auth-title">Masuk ke Akun Admin</h1>
<p class="auth-subtitle">Masukkan kredensial administrator Anda untuk mengelola portal HUMORA.</p>

<form action="{{ route('admin.login.submit') }}" method="POST" class="text-start">
    @csrf

    <div class="humora-field-group mb-3">
        <div class="humora-field @error('email') is-error @enderror">
            <input type="email" id="email" name="email" class="form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder=" " required autofocus autocomplete="off">
            <label for="email" class="humora-floating-label">Email Administrator</label>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="humora-field-group mb-3">
        <div class="d-flex justify-content-end mb-1">
            <a href="{{ route('admin.password.request') }}" class="text-decoration-none" style="font-size: 0.82rem; color: #43135C; font-weight: 600;">Lupa Sandi?</a>
        </div>
        <div class="humora-field has-suffix @error('password') is-error @enderror">
            <input type="password" id="password" name="password" class="form-control-custom @error('password') is-invalid @enderror" placeholder=" " required autocomplete="off">
            <label for="password" class="humora-floating-label">Kata Sandi</label>
            <button type="button" class="btn btn-link humora-field-suffix text-muted text-decoration-none p-0" id="togglePassword">
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
