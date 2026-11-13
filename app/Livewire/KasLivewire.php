<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BukuKas;
use App\Models\TagihanKas;
use App\Models\KarangTaruna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class KasLivewire extends Component
{
    // Navigasi Tab: 'loket', 'tunggakan', 'bukukas'
    public $activeTab = 'loket';

    // Filter Global (Tab 1 & 2)
    public $tanggalAwal;
    public $tanggalAkhir;
    public $cariAnggota = '';
    public $filterStatus = '';

    // Setup Buka Tagihan Baru (Tab 1)
    public $tanggalBukaTagihan;
    public $nominalDefault = '';

    // Filter & Sorting Riwayat Mutasi (Tab 3)
    public $tanggalAwalKas;
    public $tanggalAkhirKas;
    public $sortirTanggalKas = 'desc'; // 'desc' = Terbaru, 'asc' = Terlama

    // Form Buku Kas Manual (Tambah Baru)
    public $tanggalTransaksi;
    public $jenisTransaksi = 'masuk';
    public $sumberDana = '';
    public $nominalTransaksi = '';
    public $keteranganTransaksi = '';

    // Properti untuk Modal Edit Buku Kas
    public $editId, $editTanggal, $editJenis = 'masuk', $editSumberDana = '', $editNominal = '', $editKeterangan = '';

    // Properti untuk Modal Validasi Hapus Buku Kas
    public $hapusId, $hapusSumberDana = '', $hapusNominal = 0, $hapusJenis = '';

    // Properti untuk Modal Validasi Hapus Tagihan/Tunggakan Anggota
    public $hapusTagihanId, $hapusTagihanNama = '', $hapusTagihanTanggal = '', $hapusTagihanNominal = 0;

    public function mount()
    {
        $hariIni = date('Y-m-d');
        // Default filter rentang tanggal (awal bulan s/d hari ini)
        $awalBulan = date('Y-m-01');

        $this->tanggalAwal = $awalBulan;
        $this->tanggalAkhir = $hariIni;
        $this->tanggalAwalKas = $awalBulan;
        $this->tanggalAkhirKas = $hariIni;

        $this->tanggalBukaTagihan = $hariIni;
        $this->tanggalTransaksi = $hariIni;

        $this->activeTab = $this->isPengelola ? 'loket' : 'tunggakan';
    }

    public function getIsPengelolaProperty()
    {
        $user = Auth::user();
        if (!$user) return false;

        return in_array($user->jabatan ?? '', ['Bendahara', 'Ketua'])
            || in_array($user->role ?? '', ['Admin', 'Pembina'])
            || Auth::guard('pembina')->check();
    }

    public function gantiTab($tab)
    {
        $this->resetErrorBag();
        if (!$this->isPengelola && $tab !== 'tunggakan') {
            $this->activeTab = 'tunggakan';
            return;
        }
        $this->activeTab = $tab;
    }

    public function resetFilterBukuKas()
    {
        $this->tanggalAwalKas = date('Y-m-01');
        $this->tanggalAkhirKas = date('Y-m-d');
        $this->sortirTanggalKas = 'desc';
    }

    public function resetFilterLoket()
    {
        $this->tanggalAwal = date('Y-m-01');
        $this->tanggalAkhir = date('Y-m-d');
        $this->cariAnggota = '';
        $this->filterStatus = '';
    }

    // 1. GENERATE TAGIHAN UNTUK SEMUA ANGGOTA PADA TANGGAL YANG DIPILIH
    public function buatTagihanHariIni()
    {
        if (!$this->isPengelola) return;

        $this->resetErrorBag();
        $nominalBersih = str_replace('.', '', trim((string) $this->nominalDefault));

        if ($nominalBersih === '' || !is_numeric($nominalBersih)) {
            $pesan = 'Mohon isi nominal uang kas terlebih dahulu (contoh: 5.000) sebelum membuka tagihan !';
            $this->addError('nominalDefault', 'Nominal kas wajib diisi!');
            session()->flash('error_kas', $pesan);
            $this->dispatch('kas-alert', icon: 'warning', title: 'Nominal Belum Diisi !', text: $pesan);
            return;
        }

        if ((int) $nominalBersih < 500) {
            $pesan = 'Nominal uang kas minimal adalah Rp 500.';
            $this->addError('nominalDefault', 'Minimal Rp 500.');
            session()->flash('error_kas', $pesan);
            $this->dispatch('kas-alert', icon: 'warning', title: 'Nominal Terlalu Kecil !', text: $pesan);
            return;
        }

        if (empty($this->tanggalBukaTagihan)) {
            $this->tanggalBukaTagihan = date('Y-m-d');
        }

        $semuaIdAnggota = KarangTaruna::pluck('id')->toArray();
        $idSudahAda = TagihanKas::where('tanggal_kumpulan', $this->tanggalBukaTagihan)
            ->pluck('karang_taruna_id')
            ->toArray();

        $idBelumAda = array_diff($semuaIdAnggota, $idSudahAda);
        $dataInsert = [];
        $now = now();

        foreach ($idBelumAda as $idAnggota) {
            $dataInsert[] = [
                'karang_taruna_id' => $idAnggota,
                'tanggal_kumpulan' => $this->tanggalBukaTagihan,
                'nominal' => (int) $nominalBersih,
                'status' => 'belum_bayar',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $tglFormat = date('d/m/Y', strtotime($this->tanggalBukaTagihan));
        $nominalFormat = number_format((int) $nominalBersih, 0, ',', '.');

        if (count($dataInsert) > 0) {
            TagihanKas::insert($dataInsert);
            $pesanSukses = 'Berhasil membuka sesi kas tanggal ' . $tglFormat . '! ' . count($dataInsert) . ' tagihan anggota telah disiapkan.';
            session()->flash('success_kas', $pesanSukses);
            $this->dispatch('kas-alert', icon: 'success', title: 'Tagihan Dibuka !', text: $pesanSukses);

            // Set rentang filter agar tagihan yang baru dibuat langsung terlihat
            $this->tanggalAwal = $this->tanggalBukaTagihan;
            $this->tanggalAkhir = $this->tanggalBukaTagihan;
        } else {
            $diupdate = TagihanKas::where('tanggal_kumpulan', $this->tanggalBukaTagihan)
                ->where('status', 'belum_bayar')
                ->update(['nominal' => (int) $nominalBersih]);

            if ($diupdate > 0) {
                $pesanUpdate = "Tagihan tanggal {$tglFormat} sudah ada. Nominal untuk {$diupdate} anggota yang belum bayar berhasil diperbarui menjadi Rp {$nominalFormat}.";
                session()->flash('success_kas', $pesanUpdate);
                $this->dispatch('kas-alert', icon: 'info', title: 'Nominal Diperbarui !', text: $pesanUpdate);
            } else {
                $pesanInfo = "Seluruh tagihan pada tanggal {$tglFormat} sudah dibuka dan lunas.";
                session()->flash('error_kas', $pesanInfo);
                $this->dispatch('kas-alert', icon: 'info', title: 'Informasi', text: $pesanInfo);
            }
        }
        $this->nominalDefault = $nominalFormat;
    }

    public function tandaiLunas($tagihanId)
    {
        if (!$this->isPengelola) return;
        $tagihan = TagihanKas::with('anggota')->find($tagihanId);
        if ($tagihan && $tagihan->status === 'belum_bayar') {
            $tagihan->update(['status' => 'lunas', 'tanggal_lunas' => date('Y-m-d')]);
            $namaAnggota = $tagihan->anggota->nama ?? 'Anggota';
            $tglKumpulan = date('d/m/Y', strtotime($tagihan->tanggal_kumpulan));
            BukuKas::create([
                'tanggal' => date('Y-m-d'),
                'jenis' => 'masuk',
                'sumber_dana' => 'Iuran Rutin Kumpulan',
                'keterangan' => "Kas an. {$namaAnggota} (Kumpulan {$tglKumpulan})",
                'nominal' => $tagihan->nominal,
                'tagihan_kas_id' => $tagihan->id,
            ]);
        }
    }

    public function batalkanLunas($tagihanId)
    {
        if (!$this->isPengelola) return;
        $tagihan = TagihanKas::find($tagihanId);
        if ($tagihan && $tagihan->status === 'lunas') {
            $tagihan->update(['status' => 'belum_bayar', 'tanggal_lunas' => null]);
            BukuKas::where('tagihan_kas_id', $tagihan->id)->delete();
        }
    }

    public function konfirmasiHapusTagihan($id)
    {
        if (!$this->isPengelola) return;
        $tagihan = TagihanKas::with('anggota')->find($id);
        if ($tagihan) {
            $this->hapusTagihanId = $tagihan->id;
            $this->hapusTagihanNama = $tagihan->anggota->nama ?? 'Anggota';
            $this->hapusTagihanTanggal = date('d F Y', strtotime($tagihan->tanggal_kumpulan));
            $this->hapusTagihanNominal = $tagihan->nominal;
        }
    }

    public function hapusTagihanKas()
    {
        if (!$this->isPengelola) return;
        $tagihan = TagihanKas::find($this->hapusTagihanId);
        if ($tagihan) {
            BukuKas::where('tagihan_kas_id', $tagihan->id)->delete();
            $tagihan->delete();
            $this->reset(['hapusTagihanId', 'hapusTagihanNama', 'hapusTagihanTanggal', 'hapusTagihanNominal']);
            session()->flash('success_kas', 'Data tagihan anggota berhasil dihapus.');
            $this->dispatch('close-kas-modal');
        }
    }

    public function simpanTransaksiManual()
    {
        if (!$this->isPengelola) return;
        $this->nominalTransaksi = str_replace('.', '', trim((string) $this->nominalTransaksi));
        $this->validate([
            'tanggalTransaksi' => 'required|date',
            'jenisTransaksi' => 'required|in:masuk,keluar',
            'sumberDana' => 'required|string|max:255',
            'nominalTransaksi' => 'required|numeric|min:500',
        ], [
            'sumberDana.required' => 'Sumber dana / Keperluan wajib diisi.',
            'nominalTransaksi.required' => 'Nominal wajib diisi.',
            'nominalTransaksi.min' => 'Minimal Rp 500.',
        ]);

        BukuKas::create([
            'tanggal' => $this->tanggalTransaksi,
            'jenis' => $this->jenisTransaksi,
            'sumber_dana' => $this->sumberDana,
            'keterangan' => $this->keteranganTransaksi,
            'nominal' => (int) $this->nominalTransaksi,
        ]);

        $this->reset(['sumberDana', 'nominalTransaksi', 'keteranganTransaksi']);
        session()->flash('success_kas', 'Catatan Buku Kas berhasil disimpan!');
    }

    public function editBukuKas($id)
    {
        if (!$this->isPengelola) return;
        $this->resetErrorBag();
        $kas = BukuKas::find($id);
        if ($kas) {
            $this->editId = $kas->id;
            $this->editTanggal = $kas->tanggal;
            $this->editJenis = $kas->jenis;
            $this->editSumberDana = $kas->sumber_dana;
            $this->editNominal = number_format($kas->nominal, 0, ',', '.');
            $this->editKeterangan = $kas->keterangan;
        }
    }

    public function updateBukuKas()
    {
        if (!$this->isPengelola) return;
        $nominalBersih = str_replace('.', '', trim((string) $this->editNominal));
        $this->validate(['editTanggal' => 'required|date', 'editJenis' => 'required|in:masuk,keluar', 'editSumberDana' => 'required|string|max:255']);
        if (!is_numeric($nominalBersih) || (int) $nominalBersih < 500) {
            $this->addError('editNominal', 'Nominal minimal Rp 500.');
            return;
        }
        $kas = BukuKas::find($this->editId);
        if ($kas) {
            $kas->update(['tanggal' => $this->editTanggal, 'jenis' => $this->editJenis, 'sumber_dana' => $this->editSumberDana, 'nominal' => (int) $nominalBersih, 'keterangan' => $this->editKeterangan]);
            if ($kas->tagihan_kas_id) TagihanKas::where('id', $kas->tagihan_kas_id)->update(['nominal' => (int) $nominalBersih]);
            session()->flash('success_kas', 'Perubahan Buku Kas disimpan!');
            $this->dispatch('close-kas-modal');
        }
    }

    public function konfirmasiHapus($id)
    {
        if (!$this->isPengelola) return;
        $kas = BukuKas::find($id);
        if ($kas) {
            $this->hapusId = $kas->id;
            $this->hapusSumberDana = $kas->sumber_dana;
            $this->hapusNominal = $kas->nominal;
            $this->hapusJenis = $kas->jenis;
        }
    }

    public function hapusBukuKas()
    {
        if (!$this->isPengelola) return;
        $kas = BukuKas::find($this->hapusId);
        if ($kas) {
            if ($kas->tagihan_kas_id) TagihanKas::where('id', $kas->tagihan_kas_id)->update(['status' => 'belum_bayar', 'tanggal_lunas' => null]);
            $kas->delete();
            session()->flash('success_kas', 'Data Buku Kas dihapus.');
            $this->dispatch('close-kas-modal');
        }
    }

    public function render()
    {
        // 1. Saldo Akhir (Tetap ALL-TIME)
        $totalMasukAllTime = BukuKas::where('jenis', 'masuk')->sum('nominal');
        $totalKeluarAllTime = BukuKas::where('jenis', 'keluar')->sum('nominal');
        $saldoAkhir = $totalMasukAllTime - $totalKeluarAllTime;

        // 2. Tunggakan (Tetap ALL-TIME yang statusnya belum_bayar)
        $querySemuaTunggakan = TagihanKas::where('status', 'belum_bayar');
        $totalTunggakanNominal = $querySemuaTunggakan->sum('nominal');
        $totalTunggakanOrang = $querySemuaTunggakan->count();

        // 3. Kas Masuk & Keluar (KHUSUS BULAN INI / BULAN BERJALAN)
        $bulanIni = date('m');
        $tahunIni = date('Y');

        $masukBulanIni = BukuKas::where('jenis', 'masuk')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('nominal');

        $keluarBulanIni = BukuKas::where('jenis', 'keluar')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('nominal');

        // Nama bulan untuk ditampilkan di UI (misal: "Oktober 2026")
        $namaBulanIni = \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y');

        /* === (KODE QUERY UNTUK TAB 1, 2, DAN 3 TETAP SAMA SEPERTI SEBELUMNYA DI BAWAH SINI) === */
        $queryBase = TagihanKas::with('anggota')
            ->whereBetween('tanggal_kumpulan', [$this->tanggalAwal, $this->tanggalAkhir]);
        // ... (biarkan sisa kode ke bawah tidak berubah)
        // Pastikan di return view() mempassing variabel barunya:

        if ($this->cariAnggota) {
            $queryBase->whereHas('anggota', function ($q) {
                $q->where('nama', 'like', '%' . $this->cariAnggota . '%');
            });
        }
        if ($this->filterStatus) {
            $queryBase->where('status', $this->filterStatus);
        }

        // Eksekusi Query Tab 1
        $daftarLoket = (clone $queryBase)->orderBy('tanggal_kumpulan', 'desc')->get();

        // Eksekusi Query Tab 2 (Dikhususkan untuk yang Belum Bayar saja)
        $daftarTunggakan = (clone $queryBase)->where('status', 'belum_bayar')->orderBy('tanggal_kumpulan', 'asc')->get();

        // Cek Kehadiran (Untuk Indikator Tab 1)
        $idAnggotaHadir = [];
        $namaTabelPresensi = Schema::hasTable('presensi') ? 'presensi' : (Schema::hasTable('presensis') ? 'presensis' : null);
        if ($namaTabelPresensi && $daftarLoket->count() > 0) {
            $kolomTanggal = Schema::hasColumn($namaTabelPresensi, 'tanggal') ? 'tanggal' : 'created_at';
            $idAnggotaHadir = DB::table($namaTabelPresensi)
                ->whereBetween($kolomTanggal, [$this->tanggalAwal, $this->tanggalAkhir])
                ->pluck('karang_taruna_id', 'id') // pluck id to ensure array format
                ->toArray();
        }

        // Query Tab 3: Riwayat Buku Kas (Filter s/d Tanggal)
        $arahSortir = $this->sortirTanggalKas === 'asc' ? 'asc' : 'desc';
        $riwayatBukuKas = BukuKas::whereBetween('tanggal', [$this->tanggalAwalKas, $this->tanggalAkhirKas])
            ->orderBy('tanggal', $arahSortir)
            ->orderBy('id', $arahSortir)
            ->get();

        return view('livewire.manajemen-kas.index', compact(
            'saldoAkhir', 'totalTunggakanNominal', 'totalTunggakanOrang',
            'masukBulanIni', 'keluarBulanIni', 'namaBulanIni',
            'daftarLoket', 'idAnggotaHadir', 'daftarTunggakan', 'riwayatBukuKas'
        ));
    }
}
