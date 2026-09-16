<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <!-- LOGO GAMBAR -->
            <img src="{{ asset('assets/img/logo-ksm-new.png') }}" alt="Logo KSM" class="app-brand-logo-mini" width="45">
            <!-- TEKS KSM -->
            <span class="app-brand-text demo menu-text fw-bold ms-3 text-uppercase fs-3">KSM 10</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base bx bx-chevron-left"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

   @php
        $currentRoute = Route::currentRouteName();
        // Cek siapa yang login untuk render sidebar
        if (Auth::guard('pembina')->check()) {
            $userRole = 'Pembina';
        } else {
            $userRole = Auth::guard('karangtaruna')->user()->role ?? 'User'; 
        }
    @endphp

    <ul class="menu-inner py-1">
        
        <!-- MENU GLOBAL: DASHBOARD -->
        <li class="menu-item {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('dashboard') }}" wire:navigate>
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate">Dashboard</div>
            </a>
        </li>

        <!-- KHUSUS ADMIN & PEMBINA: KEANGGOTAAN -->
        @if ($userRole === 'Admin' || $userRole === 'Pembina')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Keanggotaan KSM 10</span></li>

            <li class="menu-item {{ $currentRoute === 'karangtaruna' ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('karangtaruna') }}" wire:navigate>
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div class="text-truncate">Data Karang Taruna</div>
                </a>
            </li>
            <li class="menu-item {{ $currentRoute === 'pembina' ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('pembina') }}" wire:navigate>
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div class="text-truncate">Data Pembina</div>
                </a>
            </li>
        @endif
        

        <!-- ==========================================
             MENU PRESENSI (Admin = Scan, User = QR Code)
             ========================================== -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Presensi</span></li>

        @if ($userRole === 'Admin' || $userRole === 'Pembina')
            <li class="menu-item {{ $currentRoute === 'presensi.scan' ? 'active' : '' }}">
                <a href="{{ route('presensi.scan') }}" class="menu-link" wire:navigate>
                    <i class="menu-icon tf-icons bx bx-scan"></i>
                    <div class="text-truncate">Scan Presensi</div>
                </a>
            </li>
        @endif

        <!-- QR Code berlaku untuk semuanya (termasuk Admin/Pembina kalau mau absen) -->
        <li class="menu-item {{ $currentRoute === 'presensi.qrcode' ? 'active' : '' }}">
            <a href="{{ route('presensi.qrcode') }}" class="menu-link" wire:navigate>
                <i class="menu-icon tf-icons bx bx-qr"></i>
                <div class="text-truncate">QR Code Saya</div>
            </a>
        </li>

        <!-- MENU GLOBAL: LAPORAN -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Laporan</span></li>

        <li class="menu-item {{ $currentRoute === 'rekap-kehadiran' ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('rekap-kehadiran') }}" wire:navigate>
                <i class="menu-icon tf-icons bx bx-file-report"></i>
                <div class="text-truncate">Rekap Kehadiran</div>
            </a>
        </li>

        <!-- MENU GLOBAL: LOGOUT -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Aksi</span></li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link" data-bs-toggle="modal" data-bs-target="#modalLogout">
                <i class="menu-icon tf-icons bx-arrow-left-circle"></i>
                <div class="text-truncate">Logout</div>
            </a>
        </li>
    </ul>
</aside>

@livewire('logout-livewire')