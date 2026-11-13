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
        wire:target="gantiTab, buatTagihanHariIni, tanggalAwal, tanggalAkhir, tanggalAwalKas, tanggalAkhirKas, filterStatus, tandaiLunas, batalkanLunas, simpanTransaksiManual, updateBukuKas, hapusBukuKas, hapusTagihanKas, sortirTanggalKas, resetFilterBukuKas, resetFilterLoket">
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
    <div class="row mb-4 g-3">
        <!-- Saldo Akhir -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex flex-column">
                            <span class="badge bg-label-primary mb-2"
                                style="width: fit-content; font-size: 0.65rem;">TOTAL AKUMULASI</span>
                            <span class="fw-semibold text-muted text-truncate" style="font-size: 0.95rem;">Saldo Kas
                                Saat Ini</span>
                        </div>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary"><i
                                    class="bx bx-wallet fs-4"></i></span>
                        </div>
                    </div>
                    <h3 class="card-title text-primary fw-bold mb-1">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                    </h3>
                    <small class="text-primary fw-semibold d-block text-truncate">Dana bersih siap pakai</small>
                </div>
            </div>
        </div>

        <!-- Total Pemasukan -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex flex-column">
                            <span class="badge bg-label-success mb-2"
                                style="width: fit-content; font-size: 0.65rem;">{{ strtoupper(\Carbon\Carbon::now()->locale('id')->translatedFormat('F | Y')) }}</span>
                            <span class="fw-semibold text-muted text-truncate" style="font-size: 0.95rem;">Total Kas
                                Masuk</span>
                        </div>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success"><i
                                    class="bx bx-trending-up fs-4"></i></span>
                        </div>
                    </div>
                    <h4 class="card-title text-success fw-bold mb-1 text-nowrap">+ Rp
                        {{ number_format($masukBulanIni, 0, ',', '.') }}</h4>
                    <small class="text-success fw-semibold d-block text-truncate"><i class="bx bx-chevrons-up"></i>
                        Iuran & Donasi</small>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex flex-column">
                            <span class="badge bg-label-danger mb-2"
                                style="width: fit-content; font-size: 0.65rem;">{{ strtoupper(\Carbon\Carbon::now()->locale('id')->translatedFormat('F | Y')) }}</span>
                            <span class="fw-semibold text-muted text-truncate" style="font-size: 0.95rem;">Total Kas
                                Keluar</span>
                        </div>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-danger"><i
                                    class="bx bx-trending-down fs-4"></i></span>
                        </div>
                    </div>
                    <h4 class="card-title text-danger fw-bold mb-1 text-nowrap">- Rp
                        {{ number_format($keluarBulanIni, 0, ',', '.') }}</h4>
                    <small class="text-danger fw-semibold d-block text-truncate"><i class="bx bx-chevrons-down"></i>
                        Belanja & Keperluan</small>
                </div>
            </div>
        </div>

        <!-- Total Tunggakan Anggota -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex flex-column">
                            <span class="badge bg-label-warning mb-2"
                                style="width: fit-content; font-size: 0.65rem;">STATUS SAAT INI</span>
                            <span class="fw-semibold text-muted text-truncate" style="font-size: 0.95rem;">Belum
                                Terbayar</span>
                        </div>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning"><i
                                    class="bx bx-dollar fs-4"></i></span>
                        </div>
                    </div>
                    <h4 class="card-title text-warning fw-bold mb-1 text-nowrap">Rp
                        {{ number_format($totalTunggakanNominal, 0, ',', '.') }}</h4>
                    <small class="text-warning fw-semibold d-block text-truncate">{{ $totalTunggakanOrang }} Tagihan
                        Menunggak</small>
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
                @if ($totalTunggakanOrang > 0)
                    <span
                        class="badge rounded-circle bg-danger ms-2 d-inline-flex align-items-center justify-content-center"
                        style="width: 22px; height: 22px; padding: 0; font-size: 0.7rem; line-height: 1; flex-shrink: 0;">
                        {{ $totalTunggakanOrang }}
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
         TAB 1: LOKET KAS KUMPULAN (MALAM INI / TANGGAL TERTENTU)
         ========================================== -->
    @if ($activeTab === 'loket')
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom">
                @if ($this->isPengelola)
                    <!-- BAGIAN BUKA TAGIHAN BARU (Khusus Bendahara) -->
                    <div
                        class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4 pb-3 border-bottom border-light">
                        <div>
                            <h5 class="mb-0 fw-bold">Absensi & Tagihan Kas Kumpulan</h5>
                            <small class="text-muted">Semua anggota (Hadir atau Tidak Hadir) wajib membayar iuran
                                kumpulan.</small>
                        </div>
                        <div class="d-flex flex-wrap align-items-end gap-2">
                            <div>
                                <label class="form-label small text-muted mb-1">Tanggal Kumpulan</label>
                                <input type="date" wire:model="tanggalBukaTagihan"
                                    class="form-control form-control-sm" style="width: 140px;">
                            </div>
                            <div>
                                <label class="form-label small text-muted mb-1">Nominal (Rp)</label>
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" inputmode="numeric" wire:model="nominalDefault"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                        class="form-control @error('nominalDefault') is-invalid @enderror"
                                        placeholder="Misal : 5.000">
                                </div>
                            </div>
                            <button type="button" wire:click="buatTagihanHariIni" class="btn btn-sm btn-primary"
                                wire:loading.attr="disabled">
                                <i class="bx bx-plus-circle me-1"></i> Buat Tagihan
                            </button>
                        </div>
                    </div>
                @endif

                <!-- BAGIAN FILTER TANGGAL & STATUS -->
                <div class="row g-2 align-items-end">
                    <!-- Filter Tanggal S/D -->
                    <div class="col-lg-5 col-md-12">
                        <label class="form-label small text-muted mb-1">Rentang Tanggal Kumpulan</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                <input type="date" wire:model.live="tanggalAwal" class="form-control">
                            </div>
                            <span class="text-muted small">s/d</span>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                <input type="date" wire:model.live="tanggalAkhir" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Cari Nama -->
                    <div class="col-lg-3 col-md-4">
                        <label class="form-label small text-muted mb-1">Cari Nama Anggota</label>
                        <input type="text" wire:model.live.debounce.300ms="cariAnggota"
                            class="form-control form-control-sm" placeholder="Ketik nama anggota...">
                    </div>

                    <!-- Filter Status -->
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label small text-muted mb-1">Filter Status</label>
                        <div class="dropdown">
                            <button
                                class="form-select form-select-sm text-start d-flex align-items-center justify-content-between"
                                type="button" data-bs-toggle="dropdown">
                                <span class="d-flex align-items-center">
                                    @if ($filterStatus === 'belum_bayar')
                                        <i class="bx bx-x-circle text-danger me-2"></i> Belum Bayar
                                    @elseif($filterStatus === 'lunas')
                                        <i class="bx bx-check-circle text-success me-2"></i> Sudah Lunas
                                    @else
                                        <i class="bx bx-filter text-primary me-2"></i> Semua Status
                                    @endif
                                </span>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li><a class="dropdown-item py-2 {{ $filterStatus === '' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', '')"><i
                                            class="bx bx-filter text-primary me-2"></i> Semua Status</a></li>
                                <li><a class="dropdown-item py-2 {{ $filterStatus === 'belum_bayar' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', 'belum_bayar')"><i
                                            class="bx bx-x-circle text-danger me-2"></i> Belum Bayar</a></li>
                                <li><a class="dropdown-item py-2 {{ $filterStatus === 'lunas' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', 'lunas')"><i
                                            class="bx bx-check-circle text-success me-2"></i> Sudah Lunas</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tombol Reset Filter -->
                    <div class="col-lg-2 col-md-4">
                        <button type="button" wire:click="resetFilterLoket"
                            class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bx bx-history me-1"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Anggota</th>
                            <th>Kehadiran (Tanggal Terpilih)</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            @if ($this->isPengelola)
                                <th class="text-center">Aksi Bendahara</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($daftarLoket as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item->anggota->nama ?? '-' }}</div>
                                    <small class="text-muted">{{ $item->anggota->jabatan ?? 'Anggota' }}</small>
                                </td>
                                <td>
                                    @if (in_array($item->karang_taruna_id, $idAnggotaHadir))
                                        <span class="badge bg-label-success"><i class="bx bx-check me-1"></i>
                                            Hadir</span>
                                    @else
                                        <span class="badge bg-label-secondary">Tidak / Belum Hadir</span>
                                    @endif
                                </td>
                                <td
                                    class="fw-semibold {{ $item->status === 'belum_bayar' ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($item->status === 'lunas')
                                        <span class="badge bg-success"><i class="bx bx-check-double"></i> LUNAS</span>
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
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bx bx-folder-open fs-1 d-block mb-2"></i>
                                    Belum ada data tagihan untuk rentang tanggal atau filter ini.<br>
                                    @if ($this->isPengelola)
                                        Silakan isi nominal dan klik tombol <strong>"Buka Tagihan Tanggal Ini"</strong>
                                        di pojok kanan atas.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif


    <!-- ==========================================
         TAB 2: DAFTAR BELUM BAYAR (TUNGGAKAN)
         ========================================== -->
    @if ($activeTab === 'tunggakan')
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom">

                <!-- JUDUL & FILTER TUNGGAKAN -->
                <div
                    class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4 pb-3 border-bottom border-light">
                    <div>
                        <h5 class="mb-0 fw-bold text-danger">Daftar Anggota Belum Bayar Kas</h5>
                        <small class="text-muted">Daftar ini otomatis dibawa ke kumpulan selanjutnya sampai anggota
                            melunasi tagihannya.</small>
                    </div>
                </div>

                <!-- BAGIAN FILTER TANGGAL & STATUS -->
                <div class="row g-2 align-items-end">
                    <!-- Filter Tanggal S/D -->
                    <div class="col-lg-5 col-md-12">
                        <label class="form-label small text-muted mb-1">Rentang Tanggal Kumpulan</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                <input type="date" wire:model.live="tanggalAwal" class="form-control">
                            </div>
                            <span class="text-muted small">s/d</span>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                <input type="date" wire:model.live="tanggalAkhir" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Cari Nama -->
                    <div class="col-lg-3 col-md-4">
                        <label class="form-label small text-muted mb-1">Cari Nama Anggota</label>
                        <input type="text" wire:model.live.debounce.300ms="cariAnggota"
                            class="form-control form-control-sm" placeholder="Ketik nama anggota...">
                    </div>

                    <!-- Filter Status -->
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label small text-muted mb-1">Filter Status</label>
                        <div class="dropdown">
                            <button
                                class="form-select form-select-sm text-start d-flex align-items-center justify-content-between"
                                type="button" data-bs-toggle="dropdown">
                                <span class="d-flex align-items-center">
                                    @if ($filterStatus === 'belum_bayar')
                                        <i class="bx bx-x-circle text-danger me-2"></i> Belum Bayar
                                    @elseif($filterStatus === 'lunas')
                                        <i class="bx bx-check-circle text-success me-2"></i> Sudah Lunas
                                    @else
                                        <i class="bx bx-filter text-primary me-2"></i> Semua Status
                                    @endif
                                </span>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li><a class="dropdown-item py-2 {{ $filterStatus === '' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', '')"><i
                                            class="bx bx-filter text-primary me-2"></i> Semua Status</a></li>
                                <li><a class="dropdown-item py-2 {{ $filterStatus === 'belum_bayar' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', 'belum_bayar')"><i
                                            class="bx bx-x-circle text-danger me-2"></i> Belum Bayar</a></li>
                                <li><a class="dropdown-item py-2 {{ $filterStatus === 'lunas' ? 'active' : '' }}"
                                        href="javascript:void(0);" wire:click="$set('filterStatus', 'lunas')"><i
                                            class="bx bx-check-circle text-success me-2"></i> Sudah Lunas</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tombol Reset Filter -->
                    <div class="col-lg-2 col-md-4">
                        <button type="button" wire:click="resetFilterLoket"
                            class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bx bx-history me-1"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Anggota</th>
                            <th>Nunggak Sejak Tanggal</th>
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
                                <td>
                                    <div class="fw-semibold">{{ $tunggakan->anggota->nama ?? '-' }}</div>
                                    <small class="text-muted">{{ $tunggakan->anggota->jabatan ?? 'Anggota' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-label-warning">
                                        <i class="bx bx-calendar me-1"></i> KUMPULAN
                                        {{ strtoupper(date('d F Y', strtotime($tunggakan->tanggal_kumpulan))) }}
                                    </span>
                                </td>
                                <td class="fw-bold text-danger">
                                    Rp {{ number_format($tunggakan->nominal, 0, ',', '.') }}
                                </td>
                                <td><span class="badge bg-danger">BELUM BAYAR</span></td>
                                @if ($this->isPengelola)
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <button wire:click="tandaiLunas({{ $tunggakan->id }})"
                                                class="btn btn-sm btn-primary">
                                                <i class="bx bx-check-circle me-1"></i> Lunasi Sekarang
                                            </button>
                                            <button type="button"
                                                wire:click="konfirmasiHapusTagihan({{ $tunggakan->id }})"
                                                data-bs-toggle="modal" data-bs-target="#modalHapusTagihan"
                                                class="btn btn-sm btn-icon btn-danger" title="Hapus Tunggakan">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-success fw-semibold">
                                    <i class="bx bx-check-circle fs-1 d-block mb-2"></i>
                                    Tidak ada anggota yang memiliki catatan tunggakan kas pada rentang tanggal ini.
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
                                <div class="mb-4 rounded"
                                    style="background-color: #fef8c4; border-left: 4px solid #facc15; padding: 12px;">
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="flex-shrink: 0;">
                                            <!-- Ukuran ikon dikunci paksa ke 20px -->
                                            <svg style="width: 20px; height: 20px; color: #eab308;"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div style="margin-left: 12px;">
                                            <h3 style="font-size: 14px; font-weight: 600; color: #854d0e; margin: 0;">
                                                Data Susulan?
                                            </h3>
                                            <p style="text-align: justify; font-size: 12px; color: #a16207; margin-top: 4px; margin-bottom: 0; line-height: 1.6;">
                                                Ubah <b>"TANGGAL TRANSAKSI"</b> sesuai dengan <i><b>tanggal saat kumpulan berlangsung</b></i>, agar rekap total kas di Kotak Informasi Atas masuk ke bulan yang tepat.
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
                            @if ($tanggalAwalKas !== date('Y-m-01') || $tanggalAkhirKas !== date('Y-m-d') || $sortirTanggalKas !== 'desc')
                                <button type="button" wire:click="resetFilterBukuKas"
                                    class="btn btn-xs btn-outline-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset Filter
                                </button>
                            @endif
                        </div>

                        <!-- Filter S/D Tanggal & Urutkan -->
                        <div class="row g-2 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label small text-muted mb-1">Rentang Tanggal Mutasi</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                        <input type="date" wire:model.live="tanggalAwalKas" class="form-control">
                                    </div>
                                    <span class="text-muted small">s/d</span>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                        <input type="date" wire:model.live="tanggalAkhirKas" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                        <li><a class="dropdown-item d-flex align-items-center {{ $sortirTanggalKas === 'desc' ? 'active' : '' }}"
                                                href="javascript:void(0);"
                                                wire:click="$set('sortirTanggalKas', 'desc')"><i
                                                    class="bx bx-down-arrow-alt me-2"></i> Terbaru ke Terlama</a></li>
                                        <li><a class="dropdown-item d-flex align-items-center {{ $sortirTanggalKas === 'asc' ? 'active' : '' }}"
                                                href="javascript:void(0);"
                                                wire:click="$set('sortirTanggalKas', 'asc')"><i
                                                    class="bx bx-up-arrow-alt me-2"></i> Terlama ke Terbaru</a></li>
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

    <!-- INCLUDE FILE MODAL -->
    @include('livewire.manajemen-kas.modal-kas')

</div>
