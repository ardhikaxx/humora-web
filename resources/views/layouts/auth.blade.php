<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HUMORA Portal Admin')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-humora.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-humora.png') }}">
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/humora-admin.css') }}?v={{ file_exists(public_path('css/humora-admin.css')) ? filemtime(public_path('css/humora-admin.css')) : '1.0' }}">
    @yield('styles')
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo-banner">
                <img src="{{ asset('images/logo-humora.png') }}" alt="HUMORA Logo" class="img-fluid">
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
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
