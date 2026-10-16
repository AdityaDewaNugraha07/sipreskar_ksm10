<div class="container-xxl flex-grow-1 container-p-y">

    <!-- =========================================================
         STYLE KHUSUS BACKDROP GELAP & ANIMASI LOADING KAS
         (Bebas bentrok dengan class !important bawaan Bootstrap)
         ========================================================= -->
    <style>
        .kas-backdrop-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(22, 29, 49, 0.45);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kas-spinner-box {
            background: #ffffff;
            padding: 1.5rem 2.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 0.75rem 2rem rgba(22, 29, 49, 0.25);
            text-align: center;
            min-width: 240px;
        }
    </style>

    <!-- =========================================================
         GLOBAL LOADING BACKDROP (MUNCUL SAAT PINDAH TAB / PROSES DATA)
         Catatan: Jangan gunakan class d-flex pada tag wire:loading!
         ========================================================= -->
    <div wire:loading
        wire:target="gantiTab, buatTagihanMalamIni, tanggalKumpulan, filterStatus, tandaiLunas, batalkanLunas, simpanTransaksiManual, updateBukuKas, hapusBukuKas, hapusTagihanKas, filterBulanKas, filterTahunKas, sortirTanggalKas, resetFilterBukuKas">
        <div class="kas-backdrop-overlay">
            <div class="kas-spinner-box">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 2.75rem; height: 2.75rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">Memproses Data...</h6>
                <small class="text-muted">Mohon tunggu sebentar</small>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center py-3 mb-2">
        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Keuangan /</span> Manajemen Kas KSM 10</h4>
    </div>

    <!-- 1. EMPAT KARTU RINGKASAN SALDO -->
    <div class="row">
        <!-- Saldo Akhir -->
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold text-muted">Saldo Kas Saat Ini</span>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary"><i
                                    class="bx bx-wallet fs-4"></i></span>
                        </div>
                    </div>
                    <h3 class="card-title text-primary fw-bold mb-1">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                    </h3>
                    <small class="text-primary fw-semibold">Dana bersih siap pakai</small>
                </div>
            </div>
        </div>

        <!-- Total Pemasukan -->
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold text-muted">Total Kas Masuk</span>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success"><i
                                    class="bx bx-trending-up fs-4"></i></span>
                        </div>
                    </div>
                    <h4 class="card-title text-success fw-bold mb-1">+ Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                    </h4>
                    <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Iuran & Donasi</small>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran -->
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold text-muted">Total Kas Keluar</span>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-danger"><i
                                    class="bx bx-trending-down fs-4"></i></span>
                        </div>
                    </div>
                    <h4 class="card-title text-danger fw-bold mb-1">- Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                    </h4>
                    <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> Belanja &
                        Keperluan</small>
                </div>
            </div>
        </div>

        <!-- Total Tunggakan Anggota -->
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold text-muted">Belum Terbayar</span>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-dollar fs-4"></i>
                            </span>
                        </div>
                    </div>
                    <h4 class="card-title text-warning fw-bold mb-1">Rp
                        {{ number_format($totalTunggakan, 0, ',', '.') }}</h4>
                    <small class="text-warning fw-semibold">{{ $daftarTunggakan->count() }} Tagihan Menunggak</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. NAVIGASI PILLS 3 MENU UTAMA -->
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 pt-2">
        @if ($this->isPengelola)
            <li class="nav-item">
                <button type="button" wire:click="gantiTab('loket')"
                    class="nav-link w-100 d-flex align-items-center justify-content-start text-start {{ $activeTab === 'loket' ? 'active' : '' }}">
                    <i class="bx bx-check-circle fs-5 me-2" style="width: 22px; text-align: center;"></i>
                    <span>Loket Kas Kumpulan</span>
                </button>
            </li>
        @endif

        <li class="nav-item">
            <button type="button" wire:click="gantiTab('tunggakan')"
                class="nav-link w-100 d-flex align-items-center justify-content-start text-start {{ $activeTab === 'tunggakan' ? 'active' : '' }}">
                <i class="bx bx-bell fs-5 me-2" style="width: 22px; text-align: center;"></i>
                <span>Daftar Belum Bayar (Tunggakan)</span>
                @if ($daftarTunggakan->count() > 0)
                    <span
                        class="badge rounded-circle bg-danger ms-2 d-inline-flex align-items-center justify-content-center"
                        style="width: 22px; height: 22px; padding: 0; font-size: 0.7rem; line-height: 1; flex-shrink: 0;">
                        {{ $daftarTunggakan->count() }}
                    </span>
                @endif
            </button>
        </li>

        @if ($this->isPengelola)
            <li class="nav-item">
                <button type="button" wire:click="gantiTab('bukukas')"
                    class="nav-link w-100 d-flex align-items-center justify-content-start text-start {{ $activeTab === 'bukukas' ? 'active' : '' }}">
                    <i class="bx bx-book-bookmark fs-5 me-2" style="width: 22px; text-align: center;"></i>
                    <span>Buku Kas (Masuk & Keluar)</span>
                </button>
            </li>
        @endif
    </ul>

    <!-- ALERT SUKSES / PERINGATAN (DILETAKKAN TEPAT DI ATAS KARTU TAB AGAR LANGSUNG TERLIHAT) -->
    @if (session()->has('success_kas'))
        <div class="alert alert-success alert-dismissible mb-3 d-flex align-items-center shadow-sm" role="alert">
            <i class="bx bx-check-circle fs-4 me-2"></i>
            <div>{{ session('success_kas') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error_kas'))
        <div class="alert alert-danger alert-dismissible mb-3 d-flex align-items-center shadow-sm" role="alert">
            <i class="bx bx-x-circle fs-4 me-2"></i>
            <div class="fw-semibold">{{ session('error_kas') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ==========================================
         TAB 1: LOKET KAS KUMPULAN (MALAM INI)
         ========================================== -->
    @if ($activeTab === 'loket' && $this->isPengelola)
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h5 class="mb-0 fw-bold">Absensi & Tagihan Kas Kumpulan</h5>
                    <small class="text-muted">Semua anggota (Hadir maupun Tidak Hadir) wajib membayar iuran
                        kumpulan.</small>
                </div>

                @if ($this->isPengelola)
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="input-group input-group-sm" style="width: 165px;">
                                <span class="input-group-text">Rp</span>
                                <input type="text" inputmode="numeric" wire:model="nominalDefault"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                    class="form-control @error('nominalDefault') is-invalid @enderror"
                                    placeholder="Misal : 5.000">
                            </div>

                            <!-- Tombol Buka Tagihan (Bebas Bug Double Text) -->
                            <button type="button" wire:click="buatTagihanMalamIni" class="btn btn-sm btn-primary"
                                wire:loading.attr="disabled" wire:target="buatTagihanMalamIni">
                                <span wire:loading.remove wire:target="buatTagihanMalamIni">
                                    <i class="bx bx-plus-circle me-1"></i> Buka Tagihan Tanggal Ini
                                </span>
                                <span wire:loading wire:target="buatTagihanMalamIni">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"
                                        aria-hidden="true"></span>
                                    Membuat Tagihan...
                                </span>
                            </button>
                        </div>
                        @error('nominalDefault')
                            <small class="text-danger fw-semibold d-block mt-1">
                                <i class="bx bx-x-circle"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>
                @endif
            </div>

            <div class="card-body pt-3">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Pilih Tanggal Kumpulan</label>
                        <input type="date" wire:model.live="tanggalKumpulan" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Cari Nama Anggota</label>
                        <input type="text" wire:model.live.debounce.300ms="cariAnggota" class="form-control"
                            placeholder="Ketik nama anggota...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Filter Status Bayar</label>
                        <div class="dropdown">
                            <button class="form-select text-start d-flex align-items-center justify-content-between"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    @if ($filterStatus === 'belum_bayar')
                                        <i class="bx bx-x-circle text-danger fs-4 me-2"></i>
                                        <span class="fw-semibold">Belum Bayar</span>
                                    @elseif($filterStatus === 'lunas')
                                        <i class="bx bx-check-circle text-success fs-4 me-2"></i>
                                        <span class="fw-semibold">Sudah Lunas</span>
                                    @else
                                        <i class="bx bx-filter text-primary fs-4 me-2"></i>
                                        <span class="fw-semibold">Semua Status</span>
                                    @endif
                                </span>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2 {{ $filterStatus === '' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', '')">
                                        <i
                                            class="bx bx-filter {{ $filterStatus === '' ? 'text-white' : 'text-primary' }} fs-4 me-2"></i>
                                        <span>Semua Status</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2 {{ $filterStatus === 'belum_bayar' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', 'belum_bayar')">
                                        <i
                                            class="bx bx-x-circle {{ $filterStatus === 'belum_bayar' ? 'text-white' : 'text-danger' }} fs-4 me-2"></i>
                                        <span>Belum Bayar</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2 {{ $filterStatus === 'lunas' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', 'lunas')">
                                        <i
                                            class="bx bx-check-circle {{ $filterStatus === 'lunas' ? 'text-white' : 'text-success' }} fs-4 me-2"></i>
                                        <span>Sudah Lunas</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Anggota</th>
                                <th>Kehadiran Malam Ini</th>
                                <th>Nominal Kas</th>
                                <th>Status Kas</th>
                                @if ($this->isPengelola)
                                    <th class="text-center">Aksi Bendahara</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($daftarLoket as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->anggota->nama ?? 'Anggota Terhapus' }}
                                        </div>
                                        <small class="text-muted">{{ $item->anggota->jabatan ?? 'Anggota' }}</small>
                                    </td>
                                    <td>
                                        @if (in_array($item->karang_taruna_id, $idAnggotaHadir))
                                            <span class="badge bg-label-success"><i class="bx bx-check me-1"></i>
                                                Hadir Kumpulan</span>
                                        @else
                                            <span class="badge bg-label-secondary">Tidak / Belum Hadir</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($item->status === 'lunas')
                                            <span class="badge bg-success"><i
                                                    class="bx bx-check-double"></i>LUNAS</span>
                                        @else
                                            <span class="badge bg-danger">BELUM BAYAR</span>
                                        @endif
                                    </td>
                                    @if ($this->isPengelola)
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                @if ($item->status === 'belum_bayar')
                                                    <button wire:click="tandaiLunas({{ $item->id }})"
                                                        class="btn btn-sm btn-success">
                                                        <i class="bx bx-check-circle me-1"></i> Terima Uang
                                                    </button>
                                                    <button type="button"
                                                        wire:click="konfirmasiHapusTagihan({{ $item->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#modalHapusTagihan"
                                                        class="btn btn-sm btn-icon btn-danger" title="Hapus Tagihan">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                @else
                                                    <button wire:click="batalkanLunas({{ $item->id }})"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="bx bx-undo me-1"></i> Batalkan
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bx bx-folder-open fs-1 d-block mb-2"></i>
                                        Belum ada daftar tagihan kas untuk tanggal
                                        <strong>{{ date('d/m/Y', strtotime($tanggalKumpulan)) }}</strong>.<br>
                                        @if ($this->isPengelola)
                                            Silakan isi nominal dan klik tombol <strong>"Buka Tagihan Tanggal
                                                Ini"</strong> di pojok kanan atas.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- ==========================================
         TAB 2: DAFTAR PENUNGGAK KAS (LINTAS TANGGAL)
         ========================================== -->
    @if ($activeTab === 'tunggakan')
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="mb-0 fw-bold text-danger">Daftar Anggota Belum Bayar Kas</h5>
                <small class="text-muted">Daftar ini otomatis dibawa ke kumpulan selanjutnya sampai anggota melunasi
                    tagihannya.</small>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Anggota</th>
                            <th>Tanggal Kumpulan</th>
                            <th>Nominal Tunggakan</th>
                            <th>Status</th>
                            @if ($this->isPengelola)
                                <th class="text-center">Aksi Penagihan</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($daftarTunggakan as $tunggakan)
                            <tr>
                                <td class="fw-semibold">{{ $tunggakan->anggota->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-label-warning">
                                        <i class="bx bx-calendar me-1"></i> KUMPULAN
                                        {{ strtoupper(date('d F Y', strtotime($tunggakan->tanggal_kumpulan))) }}
                                    </span>
                                </td>
                                <td class="fw-bold text-danger">Rp
                                    {{ number_format($tunggakan->nominal, 0, ',', '.') }}</td>
                                <td><span class="badge bg-danger">BELUM BAYAR</span></td>
                                @if ($this->isPengelola)
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <button wire:click="tandaiLunas({{ $tunggakan->id }})"
                                                class="btn btn-sm btn-primary">
                                                <i class="bx bx-check-circle me-1"></i> Lunasi Sekarang
                                            </button>

                                            <button type="button"
                                                wire:click="konfirmasiHapusTagihan({{ $tunggakan->id }})"
                                                data-bs-toggle="modal" data-bs-target="#modalHapusTagihan"
                                                class="btn btn-sm btn-icon btn-danger"
                                                title="Hapus Tagihan Anggota Pasif">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-success fw-semibold">
                                    <i class="bx bx-check-circle fs-1 d-block mb-1"></i>
                                    Tidak ada anggota yang menunggak uang kas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- ==========================================
         TAB 3: BUKU KAS UTAMA (MASUK & KELUAR)
         ========================================== -->
    @if ($activeTab === 'bukukas' && $this->isPengelola)
        <div class="row">
            @if ($this->isPengelola)
                <!-- Kolom Kiri: Form Catat Kas Masuk / Keluar -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-bottom">
                            <h5 class="mb-0 fw-bold">Catat Arus Kas</h5>
                            <small class="text-muted">Pemasukan luar iuran & pengeluaran</small>
                        </div>
                        <div class="card-body pt-3">
                            <form wire:submit.prevent="simpanTransaksiManual">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Transaksi</label>
                                    <input type="date" wire:model="tanggalTransaksi" class="form-control"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jenis Arus Kas</label>
                                    <div class="dropdown">
                                        <button
                                            class="form-select text-start d-flex align-items-center justify-content-between"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="d-flex align-items-center">
                                                @if ($jenisTransaksi === 'masuk')
                                                    <i class="bx bx-dollar text-success fs-4 me-2"></i>
                                                    <span class="fw-semibold">Uang Kas Masuk</span>
                                                @else
                                                    <i class="bx bx-dollar text-danger fs-4 me-2"></i>
                                                    <span class="fw-semibold">Uang Kas Keluar</span>
                                                @endif
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu w-100 shadow-sm">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center py-2 {{ $jenisTransaksi === 'masuk' ? 'active' : '' }}"
                                                    href="javascript:void(0);"
                                                    wire:click="$set('jenisTransaksi', 'masuk')">
                                                    <i
                                                        class="bx bx-dollar {{ $jenisTransaksi === 'masuk' ? 'text-white' : 'text-success' }} fs-4 me-2"></i>
                                                    <span>Uang Kas Masuk</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center py-2 {{ $jenisTransaksi === 'keluar' ? 'active' : '' }}"
                                                    href="javascript:void(0);"
                                                    wire:click="$set('jenisTransaksi', 'keluar')">
                                                    <i
                                                        class="bx bx-dollar {{ $jenisTransaksi === 'keluar' ? 'text-white' : 'text-danger' }} fs-4 me-2"></i>
                                                    <span>Uang Kas Keluar</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Sumber Dana / Keperluan</label>
                                    <input type="text" wire:model="sumberDana"
                                        class="form-control @error('sumberDana') is-invalid @enderror"
                                        placeholder="Misal: Pembelian Seragam">
                                    @error('sumberDana')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nominal (Rp)</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" inputmode="numeric" wire:model="nominalTransaksi"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                            class="form-control @error('nominalTransaksi') is-invalid @enderror"
                                            placeholder="Contoh: 1.000.000">
                                        @error('nominalTransaksi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keterangan Detail (Opsional)</label>
                                    <textarea wire:model="keteranganTransaksi" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bx bx-save me-2"></i> Simpan ke Buku Kas
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Kolom Kanan: Tabel Riwayat Buku Kas Lengkap dengan Filter & Sorting -->
            <div class="{{ $this->isPengelola ? 'col-md-8' : 'col-12' }}">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h5 class="mb-0 fw-bold">Riwayat Mutasi Buku Kas</h5>
                            @if ($filterBulanKas !== '' || $filterTahunKas !== '' || $sortirTanggalKas !== 'desc')
                                <button type="button" wire:click="resetFilterBukuKas"
                                    class="btn btn-xs btn-outline-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset Filter
                                </button>
                            @endif
                        </div>

                        <!-- BARIS FILTER BULAN, TAHUN & SORTING TANGGAL -->
                        <div class="row g-2">
                            @php
                                $namaBulan = [
                                    '01' => 'Januari',
                                    '02' => 'Februari',
                                    '03' => 'Maret',
                                    '04' => 'April',
                                    '05' => 'Mei',
                                    '06' => 'Juni',
                                    '07' => 'Juli',
                                    '08' => 'Agustus',
                                    '09' => 'September',
                                    '10' => 'Oktober',
                                    '11' => 'November',
                                    '12' => 'Desember',
                                ];
                            @endphp

                            <!-- Filter Bulan -->
                            <div class="col-sm-4">
                                <label class="form-label small text-muted mb-1">Filter Bulan</label>
                                <div class="dropdown">
                                    <button
                                        class="form-select form-select-sm text-start d-flex align-items-center justify-content-between"
                                        type="button" data-bs-toggle="dropdown">
                                        <span class="d-flex align-items-center">
                                            <i class="bx bx-calendar text-primary me-2"></i>
                                            <span>{{ $filterBulanKas ? $namaBulan[$filterBulanKas] : 'Semua Bulan' }}</span>
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu w-100 shadow-sm"
                                        style="max-height: 240px; overflow-y: auto;">
                                        <li>
                                            <a class="dropdown-item {{ $filterBulanKas === '' ? 'active' : '' }}"
                                                href="javascript:void(0);"
                                                wire:click="$set('filterBulanKas', '')">Semua Bulan</a>
                                        </li>
                                        @foreach ($namaBulan as $kode => $bulan)
                                            <li>
                                                <a class="dropdown-item {{ $filterBulanKas === $kode ? 'active' : '' }}"
                                                    href="javascript:void(0);"
                                                    wire:click="$set('filterBulanKas', '{{ $kode }}')">{{ $bulan }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Filter Tahun -->
                            <div class="col-sm-4">
                                <label class="form-label small text-muted mb-1">Filter Tahun</label>
                                <div class="dropdown">
                                    <button
                                        class="form-select form-select-sm text-start d-flex align-items-center justify-content-between"
                                        type="button" data-bs-toggle="dropdown">
                                        <span class="d-flex align-items-center">
                                            <i class="bx bx-calendar text-primary me-2"></i>
                                            <span>{{ $filterTahunKas ?: 'Semua Tahun' }}</span>
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu w-100 shadow-sm">
                                        <li>
                                            <a class="dropdown-item {{ $filterTahunKas === '' ? 'active' : '' }}"
                                                href="javascript:void(0);"
                                                wire:click="$set('filterTahunKas', '')">Semua Tahun</a>
                                        </li>
                                        @foreach ($daftarTahun as $thn)
                                            <li>
                                                <a class="dropdown-item {{ (string) $filterTahunKas === (string) $thn ? 'active' : '' }}"
                                                    href="javascript:void(0);"
                                                    wire:click="$set('filterTahunKas', '{{ $thn }}')">{{ $thn }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Sorting Urutan Tanggal -->
                            <div class="col-sm-4">
                                <label class="form-label small text-muted mb-1">Urutkan Tanggal</label>
                                <div class="dropdown">
                                    <button
                                        class="form-select form-select-sm text-start d-flex align-items-center justify-content-between"
                                        type="button" data-bs-toggle="dropdown">
                                        <span class="d-flex align-items-center">
                                            <i class="bx bx-filter text-primary me-2"></i>
                                            <span>{{ $sortirTanggalKas === 'desc' ? 'Terbaru ke Terlama' : 'Terlama ke Terbaru' }}</span>
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu w-100 shadow-sm">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center {{ $sortirTanggalKas === 'desc' ? 'active' : '' }}"
                                                href="javascript:void(0);"
                                                wire:click="$set('sortirTanggalKas', 'desc')">
                                                <i class="bx bx-down-arrow-alt me-2"></i> Terbaru ke Terlama
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center {{ $sortirTanggalKas === 'asc' ? 'active' : '' }}"
                                                href="javascript:void(0);"
                                                wire:click="$set('sortirTanggalKas', 'asc')">
                                                <i class="bx bx-up-arrow-alt me-2"></i> Terlama ke Terbaru
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Sumber Dana / Keperluan</th>
                                    <th>Jenis</th>
                                    <th>Nominal</th>
                                    @if ($this->isPengelola)
                                        <th class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse ($riwayatBukuKas as $kas)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($kas->tanggal)) }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $kas->sumber_dana }}</div>
                                            @if ($kas->keterangan)
                                                <small class="text-muted">{{ $kas->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($kas->jenis === 'masuk')
                                                <span class="badge bg-label-success">
                                                    <i class="bx bx-dollar me-1"></i> KAS MASUK
                                                </span>
                                            @else
                                                <span class="badge bg-label-danger">
                                                    <i class="bx bx-dollar me-1"></i> KAS KELUAR
                                                </span>
                                            @endif
                                        </td>
                                        <td
                                            class="fw-bold {{ $kas->jenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                                            {{ $kas->jenis === 'masuk' ? '+' : '-' }} Rp
                                            {{ number_format($kas->nominal, 0, ',', '.') }}
                                        </td>
                                        @if ($this->isPengelola)
                                            <td class="text-center">
                                                <div class="d-inline-flex gap-1">
                                                    <button type="button"
                                                        wire:click="editBukuKas({{ $kas->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#modalEditKas"
                                                        class="btn btn-sm btn-icon btn-warning" title="Edit Catatan">
                                                        <i class="bx bx-edit"></i>
                                                    </button>

                                                    <button type="button"
                                                        wire:click="konfirmasiHapus({{ $kas->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#modalHapusKas"
                                                        class="btn btn-sm btn-icon btn-danger" title="Hapus Catatan">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Belum ada catatan mutasi kas yang sesuai dengan filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- =====================================================
         MODAL 1: FORM EDIT DATA BUKU KAS
         ===================================================== -->
    <div wire:ignore.self class="modal fade" id="modalEditKas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">
                        <i class="bx bx-edit text-primary me-1"></i> Edit Catatan Buku Kas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updateBukuKas">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Transaksi</label>
                            <input type="date" wire:model="editTanggal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Arus Kas</label>
                            <div class="dropdown">
                                <button
                                    class="form-select text-start d-flex align-items-center justify-content-between"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="d-flex align-items-center">
                                        @if ($editJenis === 'masuk')
                                            <i class="bx bx-dollar text-success fs-4 me-2"></i>
                                            <span class="fw-semibold text-dark">Uang Kas Masuk</span>
                                        @else
                                            <i class="bx bx-dollar text-danger fs-4 me-2"></i>
                                            <span class="fw-semibold text-dark">Uang Kas Keluar</span>
                                        @endif
                                    </span>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2 {{ $editJenis === 'masuk' ? 'active' : '' }}"
                                            href="javascript:void(0);" wire:click="$set('editJenis', 'masuk')">
                                            <i
                                                class="bx bx-dollar {{ $editJenis === 'masuk' ? 'text-white' : 'text-success' }} fs-4 me-2"></i>
                                            <span>Uang Kas Masuk</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2 {{ $editJenis === 'keluar' ? 'active' : '' }}"
                                            href="javascript:void(0);" wire:click="$set('editJenis', 'keluar')">
                                            <i
                                                class="bx bx-dollar {{ $editJenis === 'keluar' ? 'text-white' : 'text-danger' }} fs-4 me-2"></i>
                                            <span>Uang Kas Keluar</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sumber Dana / Keperluan</label>
                            <input type="text" wire:model="editSumberDana"
                                class="form-control @error('editSumberDana') is-invalid @enderror"
                                placeholder="Misal: Pembelian Seragam">
                            @error('editSumberDana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nominal (Rp)</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">Rp</span>
                                <input type="text" inputmode="numeric" wire:model="editNominal"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                    class="form-control @error('editNominal') is-invalid @enderror">
                                @error('editNominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Keterangan Detail (Opsional)</label>
                            <textarea wire:model="editKeterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <i class="bx bx-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =====================================================
         MODAL 2: VALIDASI KONFIRMASI HAPUS BUKU KAS
         ===================================================== -->
    <div wire:ignore.self class="modal fade" id="modalHapusKas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-danger">
                            <i class="bx bx-trash fs-1"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">Hapus Catatan Kas?</h5>
                    <p class="text-muted small mb-3">
                        Apakah kamu yakin ingin menghapus catatan
                        <strong class="text-dark">{{ $hapusSumberDana }}</strong>
                        senilai
                        <strong class="{{ $hapusJenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($hapusNominal, 0, ',', '.') }}
                        </strong>?
                    </p>
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" wire:click="hapusBukuKas" class="btn btn-danger w-100"
                            wire:loading.attr="disabled">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =====================================================
         MODAL 3: VALIDASI HAPUS TAGIHAN TUNGGAKAN ANGGOTA PASIF
         ===================================================== -->
    <div wire:ignore.self class="modal fade" id="modalHapusTagihan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-danger">
                            <i class="bx bx-trash fs-1"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">Hapus Tagihan Anggota?</h5>
                    <p class="text-muted small mb-3">
                        Hapus tagihan kas atas nama <strong class="text-dark">{{ $hapusTagihanNama }}</strong>
                        pada kumpulan <strong class="text-dark">{{ $hapusTagihanTanggal }}</strong>
                        (<strong>Rp {{ number_format($hapusTagihanNominal, 0, ',', '.') }}</strong>)?
                    </p>
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" wire:click="hapusTagihanKas" class="btn btn-danger w-100"
                            wire:loading.attr="disabled">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        window.addEventListener('close-kas-modal', () => {
            if (typeof resetModalEnvironment === 'function') {
                resetModalEnvironment();
            } else {
                document.querySelectorAll('.modal.show').forEach(el => {
                    const instance = bootstrap.Modal.getInstance(el);
                    if (instance) instance.hide();
                });
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            }
        });

        // Listener Pop-up Peringatan / Notifikasi SweetAlert2
        window.addEventListener('kas-alert', (event) => {
            const data = event.detail[0] || event.detail;
            if (typeof Swal !== 'undefined' && data) {
                Swal.fire({
                    icon: data.icon || 'info',
                    title: data.title || 'Perhatian',
                    text: data.text || '',
                    confirmButtonColor: '#696cff',
                    confirmButtonText: 'Mengerti'
                });
            }
        });
    </script>
@endpush
