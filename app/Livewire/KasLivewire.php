<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BukuKas;
use App\Models\TagihanKas;
use App\Models\KarangTaruna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KasLivewire extends Component
{
    // Navigasi Tab: 'loket', 'tunggakan', 'bukukas'
    public $activeTab = 'loket';

    // Filter & Setup Loket Kumpulan
    public $tanggalKumpulan;
    public $nominalDefault = '';
    public $cariAnggota = '';
    public $filterStatus = '';

    // Filter & Sorting Riwayat Mutasi Buku Kas
    public $filterBulanKas = '';
    public $filterTahunKas = '';
    public $sortirTanggalKas = 'desc';

    // Form Buku Kas Manual (Tambah Baru)
    public $tanggalTransaksi;
    public $jenisTransaksi = 'masuk';
    public $sumberDana = '';
    public $nominalTransaksi = '';
    public $keteranganTransaksi = '';

    // Properti untuk Modal Edit Buku Kas
    public $editId;
    public $editTanggal;
    public $editJenis = 'masuk';
    public $editSumberDana = '';
    public $editNominal = '';
    public $editKeterangan = '';

    // Properti untuk Modal Validasi Hapus Buku Kas
    public $hapusId;
    public $hapusSumberDana = '';
    public $hapusNominal = 0;
    public $hapusJenis = '';

    // Properti untuk Modal Validasi Hapus Tagihan/Tunggakan Anggota
    public $hapusTagihanId;
    public $hapusTagihanNama = '';
    public $hapusTagihanTanggal = '';
    public $hapusTagihanNominal = 0;

    public function mount()
    {
        $this->tanggalKumpulan = date('Y-m-d');
        $this->tanggalTransaksi = date('Y-m-d');

        // Jika yang login BUKAN pengelola (Anggota biasa), otomatis buka tab 'tunggakan'
        $this->activeTab = $this->isPengelola ? 'loket' : 'tunggakan';
    }

    // Cek hak akses pengelola kas (Bendahara / Ketua / Admin / Pembina)
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
        // Kunci agar Anggota biasa tidak bisa pindah ke tab selain 'tunggakan'
        if (!$this->isPengelola && $tab !== 'tunggakan') {
            $this->activeTab = 'tunggakan';
            return;
        }
        $this->activeTab = $tab;
    }

    public function resetFilterBukuKas()
    {
        $this->filterBulanKas = '';
        $this->filterTahunKas = '';
        $this->sortirTanggalKas = 'desc';
    }

    // 1. GENERATE TAGIHAN UNTUK SEMUA ANGGOTA PADA MALAM KUMPULAN
    public function buatTagihanMalamIni()
    {
        if (!$this->isPengelola) return;

        $this->resetErrorBag();
        $nominalBersih = str_replace('.', '', trim((string) $this->nominalDefault));

        // Validasi jika Bendahara belum mengisi nominal kas
        if ($nominalBersih === '' || !is_numeric($nominalBersih)) {
            $pesan = 'Mohon isi nominal uang kas terlebih dahulu (contoh: 5.000) sebelum membuka tagihan !';
            $this->addError('nominalDefault', 'Nominal kas wajib diisi !');
            session()->flash('error_kas', $pesan);
            $this->dispatch('kas-alert', icon: 'warning', title: 'Nominal Belum Diisi!', text: $pesan);
            return;
        }

        if ((int) $nominalBersih < 500) {
            $pesan = 'Nominal uang kas minimal adalah Rp 500.';
            $this->addError('nominalDefault', 'Minimal Rp 500.');
            session()->flash('error_kas', $pesan);
            $this->dispatch('kas-alert', icon: 'warning', title: 'Nominal Terlalu Kecil!', text: $pesan);
            return;
        }

        if (empty($this->tanggalKumpulan)) {
            $this->tanggalKumpulan = date('Y-m-d');
        }

        $semuaIdAnggota = KarangTaruna::pluck('id')->toArray();
        $idSudahAda = TagihanKas::where('tanggal_kumpulan', $this->tanggalKumpulan)
            ->pluck('karang_taruna_id')
            ->toArray();

        $idBelumAda = array_diff($semuaIdAnggota, $idSudahAda);
        $dataInsert = [];
        $now = now();

        foreach ($idBelumAda as $idAnggota) {
            $dataInsert[] = [
                'karang_taruna_id' => $idAnggota,
                'tanggal_kumpulan' => $this->tanggalKumpulan,
                'nominal' => (int) $nominalBersih,
                'status' => 'belum_bayar',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $tglFormat = date('d/m/Y', strtotime($this->tanggalKumpulan));
        $nominalFormat = number_format((int) $nominalBersih, 0, ',', '.');

        if (count($dataInsert) > 0) {
            TagihanKas::insert($dataInsert);
            $pesanSukses = 'Berhasil membuka sesi kas tanggal ' . $tglFormat . '! ' . count($dataInsert) . ' tagihan anggota (Rp ' . $nominalFormat . ') telah disiapkan.';
            session()->flash('success_kas', $pesanSukses);
            $this->dispatch('kas-alert', icon: 'success', title: 'Tagihan Dibuka!', text: $pesanSukses);
        } else {
            // Jika tagihan di tanggal ini sudah ada, perbarui nominal untuk yang masih belum_bayar
            $diupdate = TagihanKas::where('tanggal_kumpulan', $this->tanggalKumpulan)
                ->where('status', 'belum_bayar')
                ->update(['nominal' => (int) $nominalBersih]);

            if ($diupdate > 0) {
                $pesanUpdate = "Tagihan tanggal {$tglFormat} sudah ada. Nominal untuk {$diupdate} anggota yang belum bayar berhasil diperbarui menjadi Rp {$nominalFormat}.";
                session()->flash('success_kas', $pesanUpdate);
                $this->dispatch('kas-alert', icon: 'info', title: 'Nominal Diperbarui!', text: $pesanUpdate);
            } else {
                $pesanInfo = "Seluruh tagihan pada tanggal {$tglFormat} sudah dibuka dan lunas.";
                session()->flash('error_kas', $pesanInfo);
                $this->dispatch('kas-alert', icon: 'info', title: 'Informasi', text: $pesanInfo);
            }
        }

        $this->nominalDefault = $nominalFormat;
    }

    // 2. KLIK LUNAS (OTOMATIS MASUK BUKU KAS UTAMA)
    public function tandaiLunas($tagihanId)
    {
        if (!$this->isPengelola) return;

        $tagihan = TagihanKas::with('anggota')->find($tagihanId);
        if ($tagihan && $tagihan->status === 'belum_bayar') {
            $tagihan->update([
                'status' => 'lunas',
                'tanggal_lunas' => date('Y-m-d'),
            ]);

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

    // 3. BATALKAN LUNAS
    public function batalkanLunas($tagihanId)
    {
        if (!$this->isPengelola) return;

        $tagihan = TagihanKas::find($tagihanId);
        if ($tagihan && $tagihan->status === 'lunas') {
            $tagihan->update([
                'status' => 'belum_bayar',
                'tanggal_lunas' => null,
            ]);

            BukuKas::where('tagihan_kas_id', $tagihan->id)->delete();
        }
    }

    // 4. KONFIRMASI & HAPUS TAGIHAN TUNGGAKAN (UNTUK ANGGOTA PASIF)
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
            session()->flash('success_kas', 'Data tagihan tunggakan anggota berhasil dihapus.');
            $this->dispatch('close-kas-modal');
        }
    }

    // 5. SIMPAN TRANSAKSI BUKU KAS MANUAL
    public function simpanTransaksiManual()
    {
        if (!$this->isPengelola) return;

        $this->nominalTransaksi = str_replace('.', '', trim((string) $this->nominalTransaksi));

        $this->validate([
            'tanggalTransaksi' => 'required|date',
            'jenisTransaksi' => 'required|in:masuk,keluar',
            'sumberDana' => 'required|string|max:255',
            'nominalTransaksi' => 'required|numeric|min:500',
            'keteranganTransaksi' => 'nullable|string',
        ], [
            'sumberDana.required' => 'Sumber dana / Keperluan wajib diisi (misal: Pembelian Seragam).',
            'nominalTransaksi.required' => 'Nominal uang wajib diisi.',
            'nominalTransaksi.numeric' => 'Nominal harus berupa angka.',
            'nominalTransaksi.min' => 'Nominal minimal Rp 500.',
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

    // 6. AMBIL DATA UNTUK FORM EDIT BUKU KAS
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

    // 7. SIMPAN PERUBAHAN EDIT BUKU KAS
    public function updateBukuKas()
    {
        if (!$this->isPengelola) return;

        $nominalBersih = str_replace('.', '', trim((string) $this->editNominal));

        $this->validate([
            'editTanggal' => 'required|date',
            'editJenis' => 'required|in:masuk,keluar',
            'editSumberDana' => 'required|string|max:255',
        ], [
            'editSumberDana.required' => 'Sumber dana / Keperluan wajib diisi.',
        ]);

        if (!is_numeric($nominalBersih) || (int) $nominalBersih < 500) {
            $this->addError('editNominal', 'Nominal wajib diisi minimal Rp 500.');
            return;
        }

        $kas = BukuKas::find($this->editId);
        if ($kas) {
            $kas->update([
                'tanggal' => $this->editTanggal,
                'jenis' => $this->editJenis,
                'sumber_dana' => $this->editSumberDana,
                'nominal' => (int) $nominalBersih,
                'keterangan' => $this->editKeterangan,
            ]);

            if ($kas->tagihan_kas_id) {
                TagihanKas::where('id', $kas->tagihan_kas_id)->update([
                    'nominal' => (int) $nominalBersih
                ]);
            }

            session()->flash('success_kas', 'Perubahan data Buku Kas berhasil disimpan!');
            $this->dispatch('close-kas-modal');
        }
    }

    // 8. PERSIAPAN VALIDASI HAPUS BUKU KAS
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

    // 9. EKSEKUSI HAPUS BUKU KAS
    public function hapusBukuKas()
    {
        if (!$this->isPengelola) return;

        $kas = BukuKas::find($this->hapusId);
        if ($kas) {
            if ($kas->tagihan_kas_id) {
                TagihanKas::where('id', $kas->tagihan_kas_id)->update([
                    'status' => 'belum_bayar',
                    'tanggal_lunas' => null,
                ]);
            }
            $kas->delete();
            $this->reset(['hapusId', 'hapusSumberDana', 'hapusNominal', 'hapusJenis']);
            session()->flash('success_kas', 'Data transaksi Buku Kas berhasil dihapus.');
            $this->dispatch('close-kas-modal');
        }
    }

    public function render()
    {
        $totalMasuk = BukuKas::where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = BukuKas::where('jenis', 'keluar')->sum('nominal');
        $saldoAkhir = $totalMasuk - $totalKeluar;
        $totalTunggakan = TagihanKas::where('status', 'belum_bayar')->sum('nominal');

        // Query Tab 1: Loket Kumpulan
        $queryLoket = TagihanKas::with('anggota')
            ->where('tanggal_kumpulan', $this->tanggalKumpulan);

        if ($this->cariAnggota) {
            $queryLoket->whereHas('anggota', function ($q) {
                $q->where('nama', 'like', '%' . $this->cariAnggota . '%');
            });
        }
        if ($this->filterStatus) {
            $queryLoket->where('status', $this->filterStatus);
        }
        $daftarLoket = $queryLoket->get();

        // Cek kehadiran
        $idAnggotaHadir = [];
        $namaTabelPresensi = Schema::hasTable('presensi') ? 'presensi' : (Schema::hasTable('presensis') ? 'presensis' : null);
        if ($namaTabelPresensi) {
            $kolomTanggal = Schema::hasColumn($namaTabelPresensi, 'tanggal') ? 'tanggal' : 'created_at';
            $idAnggotaHadir = DB::table($namaTabelPresensi)
                ->whereDate($kolomTanggal, $this->tanggalKumpulan)
                ->pluck('karang_taruna_id')
                ->toArray();
        }

        // Query Tab 2: Daftar Tunggakan
        $daftarTunggakan = TagihanKas::with('anggota')
            ->where('status', 'belum_bayar')
            ->orderBy('tanggal_kumpulan', 'asc')
            ->get();

        // Query Tab 3: Riwayat Buku Kas
        $queryBukuKas = BukuKas::query();

        if ($this->filterBulanKas !== '') {
            $queryBukuKas->whereMonth('tanggal', $this->filterBulanKas);
        }
        if ($this->filterTahunKas !== '') {
            $queryBukuKas->whereYear('tanggal', $this->filterTahunKas);
        }

        $arahSortir = $this->sortirTanggalKas === 'asc' ? 'asc' : 'desc';
        $riwayatBukuKas = $queryBukuKas
            ->orderBy('tanggal', $arahSortir)
            ->orderBy('id', $arahSortir)
            ->get();

        $tahunSekarang = (int) date('Y');
        $daftarTahun = range($tahunSekarang - 2, $tahunSekarang + 1);

        return view('livewire.kas-livewire', compact(
            'totalMasuk', 'totalKeluar', 'saldoAkhir', 'totalTunggakan',
            'daftarLoket', 'idAnggotaHadir', 'daftarTunggakan', 'riwayatBukuKas', 'daftarTahun'
        ));
    }
}