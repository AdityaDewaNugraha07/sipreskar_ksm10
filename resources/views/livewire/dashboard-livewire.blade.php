<div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- =========================================================
         CUSTOM STYLE KHUSUS ANIMASI FLOWCHART & CARD HOVER
         (Hanya dipertahankan yang tidak ada di bawaan Sneat)
         ========================================================= -->
    <style>
        /* Efek angkat ringan untuk Card saat di-hover */
        .card-hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(105, 108, 255, 0.15) !important;
        }

        /* Animasi Flowchart Interaktif */
        .flow-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 0.5rem;
        }
        .flow-node {
            flex: 1;
            min-width: 200px;
            background: #fff;
            border: 2px dashed #e7e7ff;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            position: relative;
        }
        .flow-node .flow-icon {
            width: 60px;
            height: 60px;
            background: #e7e7ff;
            color: #696cff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
            transition: all 0.4s ease;
        }
        
        /* Efek Saat Flowchart Disentuh (Hover) */
        .flow-node:hover {
            border-style: solid;
            border-color: #696cff;
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 20px rgba(105, 108, 255, 0.2);
            z-index: 10;
        }
        .flow-node:hover .flow-icon {
            background: #696cff;
            color: #fff;
            transform: rotateY(360deg);
            box-shadow: 0 0 15px rgba(105, 108, 255, 0.5);
        }

        /* Panah Penghubung Animasi Ngalir */
        .flow-arrow {
            color: #a1acb8;
            font-size: 2rem;
            animation: pulse-arrow 2s infinite;
        }
        @keyframes pulse-arrow {
            0% { transform: translateX(0); opacity: 0.5; }
            50% { transform: translateX(10px); opacity: 1; color: #696cff; }
            100% { transform: translateX(0); opacity: 0.5; }
        }

        /* Responsif HP untuk Flowchart */
        @media (max-width: 991px) {
            .flow-container { flex-direction: column; }
            .flow-arrow { transform: rotate(90deg); margin: 10px 0; }
            @keyframes pulse-arrow {
                0% { transform: rotate(90deg) translateY(0); opacity: 0.5; }
                50% { transform: rotate(90deg) translateY(-10px); opacity: 1; color: #696cff; }
                100% { transform: rotate(90deg) translateY(0); opacity: 0.5; }
            }
        }
    </style>

    <div class="row">
        <!-- ==============================================
             1. WELCOME CARD (MURNI STRUKTUR SNEAT)
             ============================================== -->
        <div class="col-lg-8 mb-4 order-0">
            <div class="card card-hover-lift h-100">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->nama ?? 'Admin' }}! 🎉</h5>
                            <p class="mb-4">
                                Sistem Informasi Keanggotaan dan Presensi <span class="fw-bold">KSM 10</span>. 
                                Pantau kehadiran anggota dan kelola data dengan mudah dan cepat.
                            </p>
                            <a href="{{ route('presensi.qrcode') }}" wire:navigate class="btn btn-sm btn-outline-primary">
                                Lihat QR Code Saya
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <!-- Ilustrasi Bawaan Sneat -->
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="Welcome Image" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==============================================
             2. QUICK STATS CARDS (DATA REAL-TIME)
             ============================================== -->
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <!-- Stat Card 1 -->
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card card-hover-lift h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-group fs-4"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Anggota</span>
                            
                            <!-- Panggil Variabel Total Anggota di Sini -->
                            <h3 class="card-title mb-2">{{ $totalAnggota }}</h3>
                            
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Aktif</small>
                        </div>
                    </div>
                </div>
                <!-- Stat Card 2 -->
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card card-hover-lift h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-calendar-check fs-4"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Kehadiran</span>
                            
                            <!-- Panggil Variabel Hadir Hari Ini di Sini -->
                            <h3 class="card-title text-nowrap mb-1">{{ $hadirHariIni }}</h3>
                            
                            <small class="text-info fw-semibold"><i class="bx bx-check"></i> Hari ini</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- ==============================================
             3. FLOWCHART INTERAKTIF 
             ============================================== -->
        <div class="col-12 order-2 mb-4">
            <div class="card card-hover-lift h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-2">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2"><i class="bx bx-git-branch text-primary me-2"></i>Alur Presensi KSM 10</h5>
                        <small class="text-muted">Arahkan kursor pada kotak untuk melihat detail</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="flow-container">
                        
                        <!-- Node 1 -->
                        <div class="flow-node">
                            <div class="flow-icon"><i class="bx bx-qr-scan"></i></div>
                            <h6 class="fw-bold mb-1">1. Generate QR</h6>
                            <p class="text-muted small mb-0">Anggota membuka menu QR Code Saya untuk melihat kode unik.</p>
                        </div>

                        <!-- Arrow -->
                        <i class="bx bx-chevrons-right flow-arrow"></i>

                        <!-- Node 2 -->
                        <div class="flow-node">
                            <div class="flow-icon"><i class="bx bx-scan"></i></div>
                            <h6 class="fw-bold mb-1">2. Proses Scan</h6>
                            <p class="text-muted small mb-0">Admin / Petugas melakukan scan QR menggunakan kamera di sistem.</p>
                        </div>

                        <!-- Arrow -->
                        <i class="bx bx-chevrons-right flow-arrow"></i>

                        <!-- Node 3 -->
                        <div class="flow-node">
                            <!-- Ganti bx-data menjadi bx-check-shield (sangat cocok untuk "Validasi") -->
                            <div class="flow-icon"><i class="bx bx-check-circle"></i></div>
                            <h6 class="fw-bold mb-1">3. Validasi Data</h6>
                            <p class="text-muted small mb-0">Sistem mencatat identitas dan jam hadir secara otomatis.</p>
                        </div>

                        <!-- Arrow -->
                        <i class="bx bx-chevrons-right flow-arrow"></i>

                        <!-- Node 4 -->
                        <div class="flow-node">
                            <!-- Ganti bx-bar-chart-alt-2 menjadi bx-bar-chart (versi klasik yang pasti ada) -->
                            <div class="flow-icon"><i class="bx bx-bar-chart"></i></div>
                            <h6 class="fw-bold mb-1">4. Laporan Rekap</h6>
                            <p class="text-muted small mb-0">Data kehadiran langsung masuk ke laporan secara Real-time.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>