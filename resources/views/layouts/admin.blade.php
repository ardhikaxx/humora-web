<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - HUMORA')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-humora.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-humora.png') }}">
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Humora Custom Admin Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/humora-admin.css') }}?v={{ file_exists(public_path('css/humora-admin.css')) ? filemtime(public_path('css/humora-admin.css')) : '1.0' }}">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <!-- Global Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content modal-content-humora">
                <div class="modal-header modal-header-humora border-0 pb-0">
                    <h5 class="modal-title-humora mb-0 text-danger d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <i data-lucide="alert-triangle"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Batal"></button>
                </div>
                <div class="modal-body modal-body-humora py-3">
                    <p class="mb-0 text-muted" id="confirmDeleteText" style="font-size: 0.85rem; line-height: 1.5;">
                        Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="modal-footer modal-footer-humora border-0 pt-0 justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-humora-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm btn-danger px-3" id="confirmDeleteBtn">
                        <i data-lucide="trash-2" class="me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Toast Container -->
    <div id="humoraToastContainer" class="humora-toast-container toast-container"></div>

    <!-- Bootstrap 5.3 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

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
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
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
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            // Initialize Bootstrap Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
