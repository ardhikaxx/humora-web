<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HUMORA Portal Admin')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-humora-rounded.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-humora-rounded.png') }}">
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Lucide Icons CDN (Semua icon website admin wajib menggunakan Lucide) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/humora-admin.css') }}?v={{ file_exists(public_path('css/humora-admin.css')) ? filemtime(public_path('css/humora-admin.css')) : '1.0' }}">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('styles')
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo-banner">
                <img src="{{ asset('images/logo-humora.png') }}" alt="HUMORA Logo" class="img-fluid rounded-circle">
            </div>
            <div>
                <span class="badge-portal">PORTAL ADMIN</span>
            </div>
            @yield('content')
        </div>
        <div class="text-center mt-4">
            <small class="text-muted" style="font-size: 0.8rem;">
                &copy; 2026 HUMORA Indonesia. Seluruh hak cipta dilindungi undang-undang.
            </small>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: {!! json_encode(session('success')) !!},
                confirmButtonColor: '#43135C'
            });
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Perhatian',
                text: {!! json_encode(session('error')) !!},
                confirmButtonColor: '#43135C'
            });
        });
    </script>
    @endif

    @if (session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: {!! json_encode(session('info')) !!},
                confirmButtonColor: '#43135C'
            });
        });
    </script>
    @endif

    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: {!! json_encode(implode('<br>', $errors->all())) !!},
                confirmButtonColor: '#43135C'
            });
        });
    </script>
    @endif

    @yield('scripts')
</body>
</html>
