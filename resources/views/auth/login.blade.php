@extends('layouts.auth')

@section('title', 'Masuk ke Akun Admin - HUMORA')

@section('content')
<h1 class="auth-title">Masuk ke Akun Admin</h1>
<p class="auth-subtitle">Masukkan kredensial administrator Anda untuk mengelola portal HUMORA.</p>

<form action="{{ route('admin.login.submit') }}" method="POST" class="text-start">
    @csrf

    <div class="humora-field-group mb-3">
        <div class="humora-field @error('email') is-error @enderror {{ !empty(old('email', $rememberedEmail ?? '')) ? 'has-value' : '' }}">
            <input type="email" id="email" name="email" class="form-control-custom @error('email') is-invalid @enderror" value="{{ old('email', $rememberedEmail ?? '') }}" placeholder=" " required autofocus autocomplete="email">
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
            <input type="password" id="password" name="password" class="form-control-custom @error('password') is-invalid @enderror" placeholder=" " required autocomplete="current-password">
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
        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" {{ old('remember', !empty($rememberMe)) ? 'checked' : '' }} style="accent-color: #43135C; cursor: pointer;">
        <label class="form-check-label" for="remember" style="font-size: 0.82rem; color: #374151; cursor: pointer;">
            Ingat Saya di Perangkat Ini
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
    const emailInput = document.getElementById('email');
    const rememberCheckbox = document.getElementById('remember');
    const loginForm = document.querySelector('form');

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

    // Helper to update floating label styling on input change
    function updateFieldState(input) {
        if (!input) return;
        const field = input.closest('.humora-field');
        if (field) {
            if (input.value && input.value.trim().length > 0) {
                field.classList.add('has-value');
            } else {
                field.classList.remove('has-value');
            }
        }
    }

    // Restore remembered email from localStorage if not already provided by server cookie
    try {
        if (emailInput && (!emailInput.value || emailInput.value.trim() === '')) {
            const savedEmail = localStorage.getItem('humora_admin_remembered_email');
            const savedRemember = localStorage.getItem('humora_admin_remember_me');
            if (savedEmail && savedRemember === '1') {
                emailInput.value = savedEmail;
                if (rememberCheckbox) {
                    rememberCheckbox.checked = true;
                }
            }
        }
    } catch (e) {
        // localStorage might be unavailable in restricted sandbox
    }

    if (emailInput) {
        updateFieldState(emailInput);
        emailInput.addEventListener('input', function () {
            updateFieldState(emailInput);
        });
        emailInput.addEventListener('blur', function () {
            updateFieldState(emailInput);
        });
    }

    // Persist or clean up localStorage on submit
    if (loginForm && emailInput && rememberCheckbox) {
        loginForm.addEventListener('submit', function () {
            try {
                if (rememberCheckbox.checked && emailInput.value.trim().length > 0) {
                    localStorage.setItem('humora_admin_remembered_email', emailInput.value.trim());
                    localStorage.setItem('humora_admin_remember_me', '1');
                } else if (!rememberCheckbox.checked) {
                    localStorage.removeItem('humora_admin_remembered_email');
                    localStorage.setItem('humora_admin_remember_me', '0');
                }
            } catch (e) {}
        });
    }
</script>
@endsection
