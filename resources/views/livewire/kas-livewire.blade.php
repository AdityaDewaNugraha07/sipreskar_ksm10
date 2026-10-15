<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center py-3 mb-2">
        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Keuangan /</span> Manajemen Kas KSM 10</h4>
    </div>

    @if (session()->has('success_kas'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success_kas') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 1. EMPAT KARTU RINGKASAN SALDO (SNEAT STYLE) -->
    <div class="row">
        <!-- Saldo Akhir -->
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold text-muted">Saldo Kas Saat Ini</span>
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary"><i
                                    class="bx bx-dollar fs-4"></i></span>
                        </div>
                    </div>
                    <h3 class="card-title text-primary fw-bold mb-1">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                    </h3>
                    <small class="text-muted">Dana bersih siap pakai</small>
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
                    <small class="text-success fw-semibold"><i class="bx bx-chevrons-up"></i> Iuran & Dana Lain</small>
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
                    <small class="text-danger fw-semibold"><i class="bx bx-chevrons-down"></i> Belanja &
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
                            <span class="avatar-initial rounded bg-label-warning"><i
                                    class="bx bx-receipt fs-4"></i></span>
                        </div>
                    </div>
                    <h4 class="card-title text-warning fw-bold mb-1">Rp
                        {{ number_format($totalTunggakan, 0, ',', '.') }}</h4>
                    <small class="text-muted">{{ $daftarTunggakan->count() }} Tagihan Menunggak</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. NAVIGASI PILLS 3 MENU UTAMA -->
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2">
        <li class="nav-item">
            <button type="button" wire:click="gantiTab('loket')"
                class="nav-link {{ $activeTab === 'loket' ? 'active' : '' }}">
                <i class="bx bx-check-circle me-1"></i>&nbsp; Kas Kumpulan
            </button>
        </li>
        <li class="nav-item">
            <button type="button" wire:click="gantiTab('tunggakan')"
                class="nav-link {{ $activeTab === 'tunggakan' ? 'active' : '' }}">
                <i class="bx bx-bell me-1"></i>&nbsp; Daftar Belum Bayar (Tunggakan)&nbsp;
                @if ($daftarTunggakan->count() > 0)
                    <span class="badge rounded-pill bg-danger ms-1">{{ $daftarTunggakan->count() }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item">
            <button type="button" wire:click="gantiTab('bukukas')"
                class="nav-link {{ $activeTab === 'bukukas' ? 'active' : '' }}">
                <i class="bx bx-book-bookmark me-1"></i>&nbsp; Buku Kas (Masuk & Keluar)
            </button>
        </li>
    </ul>

    <!-- ==========================================
         TAB 1: LOKET KAS KUMPULAN (MALAM INI)
         ========================================== -->
    @if ($activeTab === 'loket')
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="mb-0 fw-bold">Absensi & Tagihan Kas Kumpulan</h5>
                    <small class="text-muted">Semua anggota (Hadir maupun Tidak Hadir) wajib membayar iuran
                        kumpulan.</small>
                </div>

                @if ($this->isPengelola)
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <span class="input-group-text">Rp</span>
                            <input type="text" inputmode="numeric" wire:model="nominalDefault"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                class="form-control" placeholder="Misal 5.000">
                        </div>
                        <button wire:click="buatTagihanMalamIni" class="btn btn-sm btn-primary"
                            wire:loading.attr="disabled">
                            <i class="bx bx-plus-circle me-1"></i> Buka Tagihan Tanggal Ini
                        </button>
                    </div>
                @endif
            </div>

            <div class="card-body pt-3">
                <!-- Baris Filter -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Pilih Tanggal Kumpulan</label>
                        <input type="date" wire:model.live="tanggalKumpulan" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Cari Nama Anggota</label>
                        <input type="text" wire:model.live="cariAnggota" class="form-control"
                            placeholder="Ketik nama anggota...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Filter Status Bayar</label>
                        <select wire:model.live="filterStatus" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar">🔴 Belum Bayar</option>
                            <option value="lunas">🟢 Sudah Lunas</option>
                        </select>
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
                                        <div class="fw-bold text-dark">
                                            {{ $item->anggota->nama ?? 'Anggota Terhapus' }}</div>
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
                                            <span class="badge bg-success"><i class="bx bx-check-double me-1"></i>
                                                LUNAS</span>
                                        @else
                                            <span class="badge bg-danger">BELUM BAYAR</span>
                                        @endif
                                    </td>
                                    @if ($this->isPengelola)
                                        <td class="text-center">
                                            @if ($item->status === 'belum_bayar')
                                                <button wire:click="tandaiLunas({{ $item->id }})"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bx bx-check me-1"></i> Terima Uang
                                                </button>
                                            @else
                                                <button wire:click="batalkanLunas({{ $item->id }})"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="bx bx-undo me-1"></i> Batalkan
                                                </button>
                                            @endif
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
                                            Silakan klik tombol <strong>"Buka Tagihan Tanggal Ini"</strong> di pojok
                                            kanan atas.
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
                <h5 class="mb-0 fw-bold text-danger"><i class="bx bx-bell me-1"></i>&nbsp;Daftar Anggota Belum Bayar
                    Kas</h5>
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
                                <td class="fw-bold">{{ $tunggakan->anggota->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-label-warning">
                                        <i class="bx bx-calendar me-1"></i> Kumpulan
                                        {{ date('d M Y', strtotime($tunggakan->tanggal_kumpulan)) }}
                                    </span>
                                </td>
                                <td class="fw-bold text-danger">Rp
                                    {{ number_format($tunggakan->nominal, 0, ',', '.') }}</td>
                                <td><span class="badge bg-danger">Belum Bayar</span></td>
                                @if ($this->isPengelola)
                                    <td class="text-center">
                                        <button wire:click="tandaiLunas({{ $tunggakan->id }})"
                                            class="btn btn-sm btn-primary">
                                            <i class="bx bx-check-circle me-1"></i> Lunasi Sekarang
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-success fw-semibold">
                                    <i class="bx bx-party fs-1 d-block mb-1"></i>Tidak ada anggota yang menunggak uang kas.
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
    @if ($activeTab === 'bukukas')
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
                                    <select wire:model="jenisTransaksi" class="form-select">
                                        <option value="masuk">🟢 Uang Kas Masuk</option>
                                        <option value="keluar">🔴 Uang Kas Keluar</option>
                                    </select>
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
                                    <i class="bx bx-save me-1"></i> Simpan ke Buku Kas
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Kolom Kanan: Tabel Riwayat Buku Kas -->
            <div class="{{ $this->isPengelola ? 'col-md-8' : 'col-12' }}">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom">
                        <h5 class="mb-0 fw-bold">Riwayat Mutasi Buku Kas</h5>
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
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse ($riwayatBukuKas as $kas)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($kas->tanggal)) }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $kas->sumber_dana }}</div>
                                            @if ($kas->keterangan)
                                                <small class="text-muted">{{ $kas->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($kas->jenis === 'masuk')
                                                <span class="badge bg-label-success">Kas Masuk</span>
                                            @else
                                                <span class="badge bg-label-danger">Kas Keluar</span>
                                            @endif
                                        </td>
                                        <td
                                            class="fw-bold {{ $kas->jenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                                            {{ $kas->jenis === 'masuk' ? '+' : '-' }} Rp
                                            {{ number_format($kas->nominal, 0, ',', '.') }}
                                        </td>
                                        @if ($this->isPengelola)
                                            <td>
                                                <button wire:click="hapusBukuKas({{ $kas->id }})"
                                                    onclick="confirm('Yakin hapus catatan kas ini?') || event.stopImmediatePropagation()"
                                                    class="btn btn-sm btn-icon text-danger">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada catatan
                                            pemasukan atau pengeluaran kas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
