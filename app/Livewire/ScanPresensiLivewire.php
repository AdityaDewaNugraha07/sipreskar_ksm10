<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KarangTaruna;
use App\Models\Presensi;
use Carbon\Carbon;

class ScanPresensiLivewire extends Component
{
    public $pesan;
    public $status = ''; // 'success' atau 'error'
    public $anggota;
    public $jamPresensi;

    protected $listeners = ['prosesScan' => 'prosesScan'];

    public function prosesScan($kode_qr)
    {
        $anggota = KarangTaruna::where('kode_qr', $kode_qr)->first();

        // Paksa timezone ke WIB (Bug 2 Fix)
        $waktuSekarang = Carbon::now('Asia/Jakarta');

        if (!$anggota) {
            $this->status = 'error';
            $this->pesan = 'QR Code tidak dikenali atau tidak terdaftar.';
            $this->anggota = null;
            $this->jamPresensi = null;
            
            // Perintahkan JS untuk memunculkan pesan error
            $this->dispatch('scanResult');
            return;
        }

        $presensi = Presensi::updateOrCreate(
            [
                'karang_taruna_id' => $anggota->id,
                'tanggal' => $waktuSekarang->toDateString(),
            ],
            [
                'jam_hadir' => $waktuSekarang->format('H:i:s'),
                'status' => 'Hadir',
            ]
        );

        $this->anggota = $anggota;
        $this->jamPresensi = $presensi->jam_hadir;
        $this->status = 'success';
        $this->pesan = 'Presensi berhasil disimpan!';

        // Beritahu JS untuk menampilkan hasil
        $this->dispatch('scanResult');
    }

    public function resetPesan()
    {
        $this->pesan = null;
        $this->status = '';
        $this->anggota = null;
        $this->jamPresensi = null;
    }

    public function render()
    {
        return view('livewire.scan-presensi-livewire')->layout('components.layouts.app');
    }
}