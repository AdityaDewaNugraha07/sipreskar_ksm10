@php
    use App\Models\KarangTaruna;
    use Illuminate\Support\Facades\Route;

    $user = Auth::user();
    $namaKarangTaruna = $user ? $user->nama : 'User';

    // 👇 LOGIKA FOTO DINAMIS
    $fotoProfil = $user && $user->foto ? asset($user->foto) : asset('assets/img/avatars/user.png');
    $currentRoute = Route::currentRouteName();
    $pageTitle = match ($currentRoute) {
        'dashboard' => 'Dashboard',
        'karangtaruna' => 'Data Karang Taruna',
        'pembina' => 'Data Pembina',
        'presensi.scan' => 'Scan Presensi',
        'presensi.qrcode' => 'QR Code Saya',
        'rekap-kehadiran' => 'Rekap Kehadiran',
        'profil' => 'Profil Saya',
        default => 'Manajemen KSM 10',
    };
@endphp

<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">

    <!-- Tombol Toggle Mobile/Tablet -->
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-between w-100" id="navbar-collapse">

        <!-- JUDUL HALAMAN DINAMIS (PENGGANTI SEARCH BAR) -->
        <div class="navbar-nav align-items-center me-auto">
            <div class="nav-item d-flex align-items-center">
                <h5 class="mb-0 fw-bold fs-5 text-primary text-uppercase">
                    {{ $pageTitle }}
                </h5>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="#" role="button"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ $fotoProfil }}" alt="User"
                            class="w-px-40 h-px-40 rounded-circle object-fit-cover foto-profil-dinamis" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ $fotoProfil }}" alt="User"
                                            class="w-px-40 h-px-40 rounded-circle object-fit-cover foto-profil-dinamis" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ Auth::user()->nama ?? 'User' }}</h6>
                                    <small class="text-body-secondary">{{ Auth::user()->role ?? 'Pembina' }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profil') }}" wire:navigate>
                            <i class="bx bx-user fs-5 me-3"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>

                    {{-- <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bx bx-cog fs-5 me-3"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li> --}}

                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center"
                            data-bs-toggle="modal" data-bs-target="#modalLogout">
                            <i class="bx bx-arrow-left-circle fs-5 me-3"></i>
                            <span class="align-middle">Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
