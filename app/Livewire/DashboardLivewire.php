<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KarangTaruna;
use App\Models\Presensi;
use Carbon\Carbon;

class DashboardLivewire extends Component
{
    public function render()
    {
        // 1. Hitung total semua anggota Karang Taruna
        $totalAnggota = KarangTaruna::count();

        // 2. Hitung jumlah presensi khusus HARI INI dengan zona waktu WIB
        $hariIni = Carbon::now('Asia/Jakarta')->toDateString();
        $hadirHariIni = Presensi::where('tanggal', $hariIni)
                                ->where('status', 'Hadir')
                                ->count();

        // Kirim datanya (passing data) ke halaman view
        return view('livewire.dashboard-livewire', [
            'totalAnggota' => $totalAnggota,
            'hadirHariIni' => $hadirHariIni
        ]);
    }
}