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
    public $nominalDefault = '5.000';
    public $cariAnggota = '';
    public $filterStatus = '';

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

    public function mount()
    {
        $this->tanggalKumpulan = date('Y-m-d');
        $this->tanggalTransaksi = date('Y-m-d');
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
        $this->activeTab = $tab;
    }

    // 1. GENERATE TAGIHAN UNTUK SEMUA ANGGOTA PADA MALAM KUMPULAN
    public function buatTagihanMalamIni()
    {
        if (!$this->isPengelola) return;

        $nominalBersih = str_replace('.', '', $this->nominalDefault);

        $this->validate([
            'tanggalKumpulan' => 'required|date',
        ]);

        if (!is_numeric($nominalBersih) || $nominalBersih < 500) {
            return;
        }

        $semuaAnggota = KarangTaruna::all();
        $jumlahDibuat = 0;

        foreach ($semuaAnggota as $anggota) {
            $cek = TagihanKas::where('karang_taruna_id', $anggota->id)
                ->where('tanggal_kumpulan', $this->tanggalKumpulan)
                ->first();

            if (!$cek) {
                TagihanKas::create([
                    'karang_taruna_id' => $anggota->id,
                    'tanggal_kumpulan' => $this->tanggalKumpulan,
                    'nominal' => $nominalBersih,
                    'status' => 'belum_bayar',
                ]);
                $jumlahDibuat++;
            }
        }

        $this->nominalDefault = number_format($nominalBersih, 0, ',', '.');
        session()->flash('success_kas', "Berhasil membuka sesi kas! $jumlahDibuat tagihan anggota telah disiapkan.");
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

    // 4. SIMPAN TRANSAKSI BUKU KAS MANUAL
    public function simpanTransaksiManual()
    {
        if (!$this->isPengelola) return;

        $this->nominalTransaksi = str_replace('.', '', $this->nominalTransaksi);

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
            'nominal' => $this->nominalTransaksi,
        ]);

        $this->reset(['sumberDana', 'nominalTransaksi', 'keteranganTransaksi']);
        session()->flash('success_kas', 'Catatan Buku Kas berhasil disimpan!');
    }

    // 5. AMBIL DATA UNTUK FORM EDIT BUKU KAS
    public function editBukuKas($id)
    {
        if (!$this->isPengelola) return;

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

    // 6. SIMPAN PERUBAHAN EDIT BUKU KAS
    public function updateBukuKas()
    {
        if (!$this->isPengelola) return;

        $nominalBersih = str_replace('.', '', $this->editNominal);

        $this->validate([
            'editTanggal' => 'required|date',
            'editJenis' => 'required|in:masuk,keluar',
            'editSumberDana' => 'required|string|max:255',
        ], [
            'editSumberDana.required' => 'Sumber dana / Keperluan wajib diisi.',
        ]);

        if (!is_numeric($nominalBersih) || $nominalBersih < 500) {
            $this->addError('editNominal', 'Nominal wajib diisi minimal Rp 500.');
            return;
        }

        $kas = BukuKas::find($this->editId);
        if ($kas) {
            $kas->update([
                'tanggal' => $this->editTanggal,
                'jenis' => $this->editJenis,
                'sumber_dana' => $this->editSumberDana,
                'nominal' => $nominalBersih,
                'keterangan' => $this->editKeterangan,
            ]);

            // Jika catatan ini berasal dari tagihan rutin, sesuaikan juga nominal di tagihan_kas
            if ($kas->tagihan_kas_id) {
                TagihanKas::where('id', $kas->tagihan_kas_id)->update([
                    'nominal' => $nominalBersih
                ]);
            }

            session()->flash('success_kas', 'Perubahan data Buku Kas berhasil disimpan!');
            $this->dispatch('close-kas-modal');
        }
    }

    // 7. PERSIAPAN VALIDASI HAPUS (TAMPILKAN DI MODAL)
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

    // 8. EKSEKUSI HAPUS BUKU KAS DARI MODAL
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

        $idAnggotaHadir = [];
        $namaTabelPresensi = Schema::hasTable('presensi') ? 'presensi' : (Schema::hasTable('presensis') ? 'presensis' : null);
        if ($namaTabelPresensi) {
            $kolomTanggal = Schema::hasColumn($namaTabelPresensi, 'tanggal') ? 'tanggal' : 'created_at';
            $idAnggotaHadir = DB::table($namaTabelPresensi)
                ->whereDate($kolomTanggal, $this->tanggalKumpulan)
                ->pluck('karang_taruna_id')
                ->toArray();
        }

        $daftarTunggakan = TagihanKas::with('anggota')
            ->where('status', 'belum_bayar')
            ->orderBy('tanggal_kumpulan', 'asc')
            ->get();

        $riwayatBukuKas = BukuKas::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        return view('livewire.kas-livewire', compact(
            'totalMasuk', 'totalKeluar', 'saldoAkhir', 'totalTunggakan',
            'daftarLoket', 'idAnggotaHadir', 'daftarTunggakan', 'riwayatBukuKas'
        ));
    }
}