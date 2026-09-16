<!doctype html>
<html lang="en" class="layout-menu-fixed layout-compact">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @php
        $currentRoute = Route::currentRouteName();
        $tabTitle = match ($currentRoute) {
            'dashboard' => 'Dashboard',
            'karangtaruna' => 'Data Karang Taruna',
            'pembina' => 'Data Pembina',
            'presensi.scan' => 'Scan Presensi',
            'presensi.qrcode' => 'QR Code Saya',
            'rekap-kehadiran' => 'Rekap Kehadiran',
            'profil' => 'Profil Saya',
            default => 'Manajemen',
        };
    @endphp
    <title>{{ $tabTitle }} - KSM 10</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    @livewireStyles

    <style>
        /* 1. TRANSISI MASTER UNTUK SEMUA LAYAR (HP & DESKTOP) */
        .layout-menu,
        .layout-menu .app-brand,
        .layout-menu .menu-inner,
        .layout-page {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        .layout-menu {
            z-index: 1100 !important;
        }

        .layout-menu .app-brand {
            position: relative !important;
            width: 100% !important;
            overflow: visible !important;
        }

        /* 2. RAHASIA ANIMASI "MEMANJANG DOANG" (CURTAIN EFFECT) */
        .layout-menu .menu-link,
        .layout-menu .text-truncate,
        .layout-menu .menu-header-text,
        .layout-menu .app-brand-text {
            white-space: nowrap !important;
            transition: opacity 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        .layout-menu .menu-inner {
            overflow: hidden !important;
        }

        /* 3. TOMBOL KROAK (NOTCH) BAWAANMU - BERLAKU GLOBAL */
        .layout-menu-toggle.menu-link {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            right: -22px !important;
            width: 44px !important;
            height: 44px !important;
            min-width: 44px !important;
            min-height: 44px !important;
            max-width: 44px !important;
            max-height: 44px !important;
            flex-shrink: 0 !important;
            border: 6px solid #f5f5f9 !important;
            background-color: #696cff !important;
            color: #ffffff !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: none !important;
            z-index: 1101 !important;
            transition: opacity 0.3s ease, background-color 0.3s ease !important;
            cursor: pointer;
        }

        .layout-menu-toggle.menu-link i {
            font-size: 1.25rem !important;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
            transform: translateX(0px) !important;
        }

        .layout-menu-toggle.menu-link:hover {
            background-color: #5f61e6 !important;
        }

        /* ==================================================
           4. KONDISI KHUSUS MOBILE (HP) < 1200px
           ================================================== */
        @media (max-width: 1199.98px) {

            /* HILANGKAN TOTAL tombol garis tiga bawaan Sneat di Navbar */
            .navbar .layout-menu-toggle.d-xl-none {
                display: none !important;
            }

            /* Saat HP tertutup, benjolan ngintip di kiri, panah menghadap kanan */
            .layout-menu-toggle.menu-link i {
                transform: rotate(180deg) !important;
            }

            /* Saat HP terbuka (expanded), panah menghadap kiri untuk nutup */
            html.layout-menu-expanded .layout-menu-toggle.menu-link i {
                transform: rotate(0deg) translateX(4px) translateY(4px) !important;
            }
        }

        /* ==================================================
           5. KONDISI KHUSUS DESKTOP (COLLAPSED & HOVER)
           ================================================== */
        @media (min-width: 1200px) {
            html.layout-menu-collapsed:not(.layout-menu-hover) .layout-menu {
                width: 5rem !important;
            }

            html.layout-menu-collapsed:not(.layout-menu-hover) .layout-page {
                padding-left: 5rem !important;
            }

            html.layout-menu-collapsed:not(.layout-menu-hover) .layout-menu-toggle.menu-link {
                opacity: 0 !important;
                pointer-events: none !important;
            }

            /* FIX IKON SIMETRIS: Hanya diputar, tanpa translateX yg bikin lari ke kanan */
            html.layout-menu-collapsed .layout-menu-toggle.menu-link i {
                transform: rotate(180deg) translateX(9px) !important;
            }

            html.layout-menu-collapsed:not(.layout-menu-hover) .app-brand-text,
            html.layout-menu-collapsed:not(.layout-menu-hover) .text-truncate,
            html.layout-menu-collapsed:not(.layout-menu-hover) .menu-header-text {
                opacity: 0 !important;
                visibility: hidden !important;
            }

            html.layout-menu-collapsed:not(.layout-menu-hover) .app-brand-logo-mini {
                display: block !important;
                margin-left: 0 !important;
                /* Matikan auto-center */
                transform: translateX(-12px) !important;
                /* Tarik logo ke kiri */
            }

            html.layout-menu-collapsed:not(.layout-menu-hover) .menu-item {
                width: 100% !important;
                margin-bottom: 0.25rem !important;
            }

            html.layout-menu-collapsed:not(.layout-menu-hover) .menu-link {
                width: calc(100% - 2rem) !important;
                margin: 0 1rem !important;
                padding: 0.625rem 1rem !important;
                justify-content: flex-start !important;
                border-radius: 0.375rem !important;
            }

            html.layout-menu-collapsed:not(.layout-menu-hover) .menu-icon {
                margin-right: 0.5rem !important;
            }

            /* HOVER EXPAND DESKTOP */
            html.layout-menu-collapsed.layout-menu-hover .layout-menu {
                width: 16.25rem !important;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            }

            html.layout-menu-collapsed.layout-menu-hover .app-brand {
                width: 100% !important;
            }

            html.layout-menu-collapsed.layout-menu-hover .layout-menu-toggle.menu-link {
                opacity: 1 !important;
                pointer-events: auto !important;
            }

            html.layout-menu-collapsed.layout-menu-hover .text-truncate,
            html.layout-menu-collapsed.layout-menu-hover .menu-header-text,
            html.layout-menu-collapsed.layout-menu-hover .app-brand-text {
                opacity: 1 !important;
                visibility: visible !important;
            }

            html.layout-menu-collapsed.layout-menu-hover .menu-link {
                justify-content: flex-start !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                margin-left: 1rem !important;
                margin-right: 1rem !important;
            }

            html.layout-menu-collapsed.layout-menu-hover .menu-icon {
                margin-right: 0.5rem !important;
            }
        }
    </style>
    <!-- CROPPER JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
</head>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('components.layouts.sidebar')

            <div class="layout-page">
                @include('components.layouts.navbar')

                {{ $slot }}

                @include('components.layouts.footer')
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- TAMBAHKAN data-navigate-once DI SEMUA SCRIPT BAWAAN TEMPLATE -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}" data-navigate-once></script>

    @livewireScripts

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/js/main.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}" data-navigate-once></script>
    <script async defer src="https://buttons.github.io/buttons.js" data-navigate-once></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-navigate-once></script>

    <script>
        function destroyAllBackdrops() {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }

        function closeAllModals() {
            document.querySelectorAll('.modal.show').forEach(el => {
                const instance = bootstrap.Modal.getInstance(el);
                if (instance) instance.hide();
                el.classList.remove('show');
                el.style.display = 'none';
            });
        }

        function resetModalEnvironment() {
            closeAllModals();
            destroyAllBackdrops();
            setTimeout(destroyAllBackdrops, 200);
        }

        function observeBackdrop() {
            const observer = new MutationObserver(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 1) {
                    for (let i = 0; i < backdrops.length - 1; i++) {
                        backdrops[i].remove();
                    }
                }
            });
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            return observer;
        }

        document.addEventListener('livewire:load', () => {
            resetModalEnvironment();
            observeBackdrop();
        });
        document.addEventListener('livewire:navigated', () => {
            resetModalEnvironment();
            observeBackdrop();
        });
        document.addEventListener('hidden.bs.modal', destroyAllBackdrops);
    </script>

    <script>
        let psSidebarInstance = null;

        function reinitSidebarScrollbar() {
            const menuInner = document.querySelector('.menu-inner');
            if (menuInner && typeof PerfectScrollbar !== 'undefined') {
                if (psSidebarInstance) {
                    psSidebarInstance.destroy();
                    psSidebarInstance = null;
                }
                psSidebarInstance = new PerfectScrollbar(menuInner, {
                    wheelPropagation: false,
                    suppressScrollX: true
                });
            }
        }
        document.addEventListener('DOMContentLoaded', reinitSidebarScrollbar);
        document.addEventListener('livewire:navigated', reinitSidebarScrollbar);

        function reinitBootstrap() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
            // Kode Dropdown.getInstance yang kemaren hapus aja, udah gak butuh trik kotor!
        };
        document.addEventListener('livewire:navigated', reinitBootstrap);
        document.addEventListener('DOMContentLoaded', reinitBootstrap);
    </script>

    <script>
        // 1. Fungsi ini cuma bertugas mereset ukuran sidebar saat halaman baru dimuat
        function initSidebarState() {
            const htmlTag = document.documentElement;
            if (window.innerWidth >= 1200) {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    htmlTag.classList.add('layout-menu-collapsed');
                } else {
                    htmlTag.classList.remove('layout-menu-collapsed');
                }
            } else {
                // Di HP: Pastikan menu selalu tertutup setiap kali masuk halaman baru
                htmlTag.classList.remove('layout-menu-expanded');
            }
        }

        // Panggil fungsi reset di atas setiap kali Livewire selesai memuat halaman
        document.addEventListener('DOMContentLoaded', initSidebarState);
        document.addEventListener('livewire:navigated', initSidebarState);

        // 2. KUMPULAN EVENT KLIK (Hanya didaftarkan 1x di akar website agar kebal Livewire)
        if (!window.sidebarEventsRegistered) {
            window.sidebarEventsRegistered = true;

            document.addEventListener('click', function(e) {
                const htmlTag = document.documentElement;

                // A. Deteksi jika yang diklik adalah Tombol Benjolan Biru
                const toggleBtn = e.target.closest('.layout-menu-toggle.menu-link');
                if (toggleBtn) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    if (window.innerWidth >= 1200) {
                        // Logika Desktop
                        htmlTag.classList.toggle('layout-menu-collapsed');
                        localStorage.setItem('sidebarCollapsed', htmlTag.classList.contains(
                            'layout-menu-collapsed'));
                        htmlTag.classList.remove('layout-menu-hover');
                    } else {
                        // Logika Mobile (HP): Tarik sidebar muncul
                        htmlTag.classList.toggle('layout-menu-expanded');
                    }
                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 300);
                    return;
                }

                // B. Deteksi jika yang diklik adalah Area Gelap di HP (Untuk menutup)
                const overlay = e.target.closest('.layout-overlay');
                if (overlay) {
                    e.preventDefault();
                    htmlTag.classList.remove('layout-menu-expanded');
                    return;
                }

                // C. Deteksi jika Menu diklik (Biar di HP sidebarnya nutup otomatis)
                const menuLink = e.target.closest('.menu-link:not(.layout-menu-toggle)');
                if (menuLink && window.innerWidth < 1200) {
                    htmlTag.classList.remove('layout-menu-expanded');
                }
            });

            // 3. Efek Animasi "Mengintip" saat di-Hover (Khusus Desktop)
            document.addEventListener('mouseover', function(e) {
                const layoutMenu = e.target.closest('#layout-menu');
                const htmlTag = document.documentElement;
                if (layoutMenu && window.innerWidth >= 1200 && htmlTag.classList.contains(
                        'layout-menu-collapsed')) {
                    htmlTag.classList.add('layout-menu-hover');
                }
            });

            document.addEventListener('mouseout', function(e) {
                const layoutMenu = e.target.closest('#layout-menu');
                const htmlTag = document.documentElement;
                if (layoutMenu && window.innerWidth >= 1200) {
                    // Pastikan kursor benar-benar keluar dari sidebar
                    if (!layoutMenu.contains(e.relatedTarget)) {
                        htmlTag.classList.remove('layout-menu-hover');
                    }
                }
            });
        }
    </script>

    <!-- Script penangkap sinyal perubahan foto dari Livewire -->
    <script>
        window.addEventListener('foto-diperbarui', (event) => {
            // Livewire 3 menaruh data di event.detail.url atau event.detail[0].url
            const newUrl = event.detail.url || (event.detail[0] && event.detail[0].url);
            if (newUrl) {
                document.querySelectorAll('.foto-profil-dinamis').forEach(img => {
                    img.src = newUrl;
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
