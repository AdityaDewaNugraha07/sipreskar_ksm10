<div>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Presensi /</span> QR Code Saya</h4>

        <!-- SOLUSI MUTLAK: Buang .row & .col, pakai Flexbox Murni -->
        <div class="d-flex justify-content-center w-100">
            <!-- Batasi lebar kotak maksimal 400px agar proporsional -->
            <div class="card text-center border-0 shadow-sm rounded-3 w-100" style="max-width: 400px;">
                <div class="card-body p-5">
                    
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-user fs-3"></i></span>
                    </div>
                    <h4 class="card-title fw-bold text-dark mb-1">{{ $nama }}</h4>
                    <p class="text-muted mb-4">Tunjukkan QR Code ini ke Scanner untuk melakukan presensi</p>

                    @if ($kodeQr)
                        <div class="p-3 bg-white border border-2 border-primary rounded-3 d-inline-block shadow-sm">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->margin(1)->generate($kodeQr) !!}
                        </div>
                        <div class="mt-4">
                            <span class="badge bg-label-dark px-3 py-2 fs-6 letter-spacing-1">{{ $kodeQr }}</span>
                        </div>
                    @else
                        <div class="alert alert-danger mt-3" role="alert">
                            ❌ QR Code tidak tersedia untuk akun ini.
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>