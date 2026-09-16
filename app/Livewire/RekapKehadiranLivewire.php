<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Presensi;
use Barryvdh\DomPDF\Facade\Pdf; // 👈 Jangan lupa di-import

class RekapKehadiranLivewire extends Component
{
    public $presensi;
    public $tanggalAwal;
    public $tanggalAkhir;

    protected $listeners = ['presensiUpdated' => 'refreshData'];

    public function mount()
    {
        // Default set ke bulan ini (opsional, bisa dikosongkan)
        $this->tanggalAwal = date('Y-m-01');
        $this->tanggalAkhir = date('Y-m-t');
        $this->refreshData();
    }

    // Fungsi ini akan otomatis terpanggil tiap kali user milih tanggal
    public function updatedTanggalAwal() { $this->refreshData(); }
    public function updatedTanggalAkhir() { $this->refreshData(); }

    public function refreshData()
    {
        $query = Presensi::with('anggota')->latest();

        // Logika Filter Tanggal
        if ($this->tanggalAwal && $this->tanggalAkhir) {
            $query->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir]);
        } elseif ($this->tanggalAwal) {
            $query->whereDate('tanggal', '>=', $this->tanggalAwal);
        } elseif ($this->tanggalAkhir) {
            $query->whereDate('tanggal', '<=', $this->tanggalAkhir);
        }

        $this->presensi = $query->get();
    }

    public function exportPDF()
    {
        try {
            // Ambil data yang sudah terfilter
            $data = $this->presensi;
            $periode = ($this->tanggalAwal && $this->tanggalAkhir) ? $this->tanggalAwal . ' s/d ' . $this->tanggalAkhir : 'Semua Waktu';

            // Load view khusus PDF dan passing datanya
            $pdf = Pdf::loadView('pdf.rekap-kehadiran', [
                'presensi' => $data,
                'periode'  => $periode
            ]);

            // Download file PDF-nya
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'Rekap_Kehadiran_' . date('YmdHis') . '.pdf');

        } catch (\Throwable $th) {
            // JIKA GAGAL: Berhenti muter dan tampilkan pesan error aslinya!
            session()->flash('error_pdf', 'Gagal cetak PDF: ' . $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.rekap-kehadiran-livewire')
            ->layout('components.layouts.app');
    }
}