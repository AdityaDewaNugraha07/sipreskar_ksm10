<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KarangTaruna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QrCodeSayaLivewire extends Component
{
    public $nama;
    public $kodeQr;

    public function mount()
    {
        $user = Auth::user();
        $anggota = KarangTaruna::where('nama', $user->nama)->first();

        if ($anggota) {
            if (empty($anggota->kode_qr)) {
                $anggota->kode_qr = 'KT-' . strtoupper(Str::random(8));
                $anggota->save();
            }
            $this->nama = $anggota->nama;
            $this->kodeQr = $anggota->kode_qr;
        } else {
            $this->nama = 'Data tidak ditemukan';
            $this->kodeQr = null;
        }
    }

    public function render()
    {
        return view('livewire.qr-code-saya-livewire')->layout('components.layouts.app');
    }
}