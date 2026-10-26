<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - HUMORA')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-humora-rounded.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-humora-rounded.png') }}">
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Lucide Icons CDN (Semua icon website admin wajib menggunakan Lucide) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Humora Custom Admin Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/humora-admin.css') }}?v={{ file_exists(public_path('css/humora-admin.css')) ? filemtime(public_path('css/humora-admin.css')) : '1.0' }}">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.HUMORA_DEFAULT_IMG = "{{ asset('images/default-img.png') }}";
    </script>
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Wrapper -->
        <div class="admin-main-wrapper" id="adminMainWrapper">
            <!-- Topbar -->
            @include('partials.topbar')

            <!-- Main Content -->
            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modals Container -->
    @yield('modals')

    <!-- Bootstrap 5.3 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Humora Admin Interactive Script -->
    <script src="{{ asset('js/humora-admin.js') }}?v={{ file_exists(public_path('js/humora-admin.js')) ? filemtime(public_path('js/humora-admin.js')) : '1.0' }}"></script>

    <!-- Sidebar Toggle & Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Lucide Icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggle');

            // Restore collapsed state on desktop
            if (window.innerWidth >= 992 && localStorage.getItem('humora_sidebar_collapsed') === '1') {
                document.body.classList.add('sidebar-collapsed');
            }

            function toggleSidebar() {
                if (window.innerWidth < 992) {
                    if (sidebar) sidebar.classList.toggle('show');
                    if (overlay) overlay.classList.toggle('show');
                } else {
                    document.body.classList.toggle('sidebar-collapsed');
                    const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                    localStorage.setItem('humora_sidebar_collapsed', isCollapsed ? '1' : '0');
                }
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }
            if (overlay) {
                overlay.addEventListener('click', function () {
                    if (sidebar) sidebar.classList.remove('show');
                    if (overlay) overlay.classList.remove('show');
                });
            }

            // Initialize Bootstrap Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <!-- SweetAlert2 Flash Messages -->
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
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
                title: 'Pemberitahuan!',
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
