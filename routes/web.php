<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\LoginLivewire;
use App\Livewire\RegisterLivewire;
use App\Livewire\DashboardLivewire;
use App\Livewire\KarangTarunaLivewire;
use App\Livewire\PembinaLivewire;
use App\Livewire\ProfilLivewire;
use App\Livewire\RekapKehadiranLivewire;
use App\Livewire\ScanPresensiLivewire;
use App\Livewire\QrCodeSayaLivewire;
use App\Http\Middleware\RoleMiddleware;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginLivewire::class)->name('login');
    Route::get('/register', RegisterLivewire::class)->name('register');
});

Route::middleware('auth:karangtaruna,pembina')->group(function () {

    Route::get('/dashboard', DashboardLivewire::class)->name('dashboard');
    Route::get('/profil', ProfilLivewire::class)->name('profil');
    Route::get('/rekap-kehadiran', RekapKehadiranLivewire::class)->name('rekap-kehadiran');
    Route::get('/presensi/qrcode', QrCodeSayaLivewire::class)->name('presensi.qrcode');

    // AKSES ADMIN & PEMBINA (Digabung karena hak aksesnya disamakan)
    Route::middleware([RoleMiddleware::class . ':Admin,Pembina'])->group(function () {
        Route::get('/presensi/scan', ScanPresensiLivewire::class)->name('presensi.scan');
        Route::get('/karangtaruna', KarangTarunaLivewire::class)->name('karangtaruna');
        Route::get('/pembina', PembinaLivewire::class)->name('pembina');
    });

    // Logout dari SEMUA pintu
    Route::post('/logout', function () {
        Auth::guard('karangtaruna')->logout();
        Auth::guard('pembina')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

});