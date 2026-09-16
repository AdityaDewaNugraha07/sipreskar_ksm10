<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Laporan /</span> Rekap Kehadiran
    </h4>

    <div class="card border-0 shadow-sm rounded-3">
        <!-- HEADER DENGAN FILTER & TOMBOL EXPORT -->
        <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 border-bottom">
            <h5 class="mb-0">Data Rekap Kehadiran</h5>
            
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                
                <!-- Filter Tanggal (Responsif: Numpuk di HP, Sejajar di PC) -->
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2">
                    <!-- Input Tanggal Awal -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="date" wire:model.live="tanggalAwal" class="form-control" style="min-width: 140px;" title="Tanggal Awal">
                    </div>
                    
                    <!-- Teks s/d (Sekarang muncul di HP juga di tengah-tengah) -->
                    <span class="text-muted fw-bold text-center">s/d</span>
                    
                    <!-- Input Tanggal Akhir -->
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="date" wire:model.live="tanggalAkhir" class="form-control" style="min-width: 140px;" title="Tanggal Akhir">
                    </div>
                </div>
                
                <!-- Tombol Export PDF (Otomatis pendek di Laptop, full width di HP) -->
                <button wire:click="exportPDF" class="btn btn-danger text-nowrap" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="exportPDF">
                        <i class="bx bx-file-report align-middle fs-5 me-1"></i> <span class="align-middle">Export PDF</span>
                    </span>
                    <span wire:loading wire:target="exportPDF">
                        <span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span> <span class="align-middle">Memproses...</span>
                    </span>
                </button>
                
            </div>
        </div>

        <!-- MUNCULKAN ERROR DISINI JIKA PDF GAGAL DICETAK -->
        @if (session()->has('error_pdf'))
            <div class="alert alert-danger alert-dismissible mx-4 mt-3 mb-0 shadow-sm" role="alert">
                <strong><i class="bx bx-error-circle me-1"></i> Gagal!</strong> {{ session('error_pdf') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="table-responsive text-nowrap" style="max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Nama Anggota</th>
                        <th>Tanggal</th>
                        <th>Jam Hadir</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($presensi as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <i class="bx bx-user"></i>
                                        </span>
                                    </div>
                                    <span class="fw-medium text-heading">{{ $p->anggota->nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <i class="bx bx-calendar text-primary me-1"></i>
                                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td>
                                <i class="bx bx-time-five text-info me-1"></i>
                                <span class="fw-medium">{{ $p->jam_hadir }}</span> WIB
                            </td>
                            <td>
                                @if(strtolower($p->status) == 'hadir')
                                    <span class="badge bg-label-success px-2 py-1">
                                        <i class="bx bx-check-circle me-1"></i> Hadir
                                    </span>
                                @else
                                    <span class="badge bg-label-secondary px-2 py-1">
                                        {{ $p->status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="bx bx-folder-open text-muted mb-2" style="font-size: 3.5rem;"></i>
                                    <h6 class="text-muted mb-0">Belum ada data presensi pada rentang tanggal ini.</h6>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>