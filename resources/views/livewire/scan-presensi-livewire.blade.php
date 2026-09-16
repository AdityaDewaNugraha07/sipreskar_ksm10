<div>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Presensi /</span> Scan Presensi</h4>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white text-center rounded-top">
                        <h5 class="card-title text-white mb-0"><i class="bx bx-scan me-2"></i> Arahkan QR Code ke Kamera</h5>
                    </div>
                    
                    <div class="card-body p-4 text-center">
                        
                        <!-- Area Kamera -->
                        <div id="reader-container" class="{{ $pesan ? 'd-none' : 'd-block' }}">
                            <div id="reader" class="mx-auto border border-2 border-primary rounded-3" style="width: 100%; max-width: 350px;"></div>
                        </div>

                        <!-- Area Hasil Pesan -->
                        @if ($pesan)
                            <div class="mt-2">
                                @if ($status === 'success')
                                    <div class="mb-3">
                                        <i class="bx bx-check-circle text-success" style="font-size: 5rem;"></i>
                                    </div>
                                    <h5 class="text-success fw-bold">{{ $pesan }}</h5>
                                    <p class="text-dark mb-1 fw-semibold fs-5">{{ $anggota->nama }}</p>
                                    <p class="text-muted mb-4">
                                        Hadir pada: <br>
                                        <span class="badge bg-label-success fs-6 mt-1">
                                            {{ \Carbon\Carbon::parse($jamPresensi)->translatedFormat('d F Y') }} - {{ $jamPresensi }} WIB
                                        </span>
                                    </p>
                                @else
                                    <div class="mb-3">
                                        <i class="bx bx-x-circle text-danger" style="font-size: 5rem;"></i>
                                    </div>
                                    <h5 class="text-danger fw-bold">{{ $pesan }}</h5>
                                    <p class="text-muted mb-4">Silakan coba dengan QR Code yang valid.</p>
                                @endif

                                <!-- Tombol Refresh Kamera (Murni dikontrol JS) -->
                                <button type="button" class="btn btn-primary px-4" onclick="resumeScanner()">
                                    <i class="bx bx-refresh me-2"></i> Scan Lagi
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
        <script>
            let html5QrCode = null;
            let isScanning = false;

            // FUNGSI MENYALAKAN KAMERA
            function startScanner() {
                if(html5QrCode) return; // Mencegah dobel inisialisasi

                html5QrCode = new Html5Qrcode("reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText) => {
                        if (isScanning) return;
                        isScanning = true;
                        
                        // Efek Suara Berhasil
                        new Audio("https://actions.google.com/sounds/v1/alarms/beep_short.ogg").play();
                        
                        // Matikan kamera Gak perlu render ulang Livewire
                        pauseScanner();

                        // Kirim data ke PHP Livewire
                        @this.prosesScan(decodedText);
                    },
                    (errorMessage) => { /* Abaikan error loop */ }
                ).catch((err) => { console.log("Gagal menyalakan kamera", err); });
            }

            // FUNGSI MEMBERHENTIKAN KAMERA SEMENTARA (Saat berhasil scan)
            function pauseScanner() {
                if (html5QrCode && html5QrCode.isScanning) {
                    html5QrCode.stop().then(() => {
                        html5QrCode.clear();
                        html5QrCode = null;
                    }).catch(err => console.log(err));
                }
            }

            // FUNGSI MEMATIKAN TOTAL SAMPAI KE HARDWARE (Bug 1 Fix)
            function stopScannerTotal() {
                pauseScanner();
                const video = document.querySelector('#reader video');
                if (video && video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                }
            }

            // FUNGSI TOMBOL "SCAN LAGI" (Bug 3 Fix)
            window.resumeScanner = function() {
                isScanning = false;
                @this.resetPesan(); // Hapus pesan UI di Livewire
                setTimeout(() => { startScanner(); }, 300); // Nyalakan lagi
            }

            // JALANKAN KAMERA SAAT MASUK HALAMAN
            document.addEventListener("livewire:navigated", () => {
                if(document.getElementById('reader')) {
                    startScanner();
                }
            });

            // MATIKAN KAMERA SAAT PINDAH HALAMAN SPA LIVEWIRE (Bug 1 Fix)
            document.addEventListener("livewire:navigating", stopScannerTotal);
        </script>
    @endpush
</div>