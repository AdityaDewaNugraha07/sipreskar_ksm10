<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Login Card -->
            <div class="card border-0 shadow-sm px-sm-6 px-0">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4 mt-2">
                        <a href="#" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <!-- Menggunakan Logo KSM 10 -->
                                <img src="{{ asset('assets/img/logo-ksm-new.png') }}" width="50" alt="Logo KSM 10">
                            </span>
                            <span class="app-brand-text demo text-body fw-bold text-uppercase">| KSM 10</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    
                    <h4 class="mb-2">Selamat Datang! 👋</h4>
                    <p class="mb-4">Silakan masuk ke akunmu untuk mengakses dashbord Karang Taruna.</p>

                    <form wire:submit.prevent="login">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input wire:model="nama" type="text" id="nama" class="form-control" placeholder="Masukkan nama kamu" autofocus>
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input wire:model="password" type="password" id="password" class="form-control" placeholder="" aria-describedby="password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <!-- Alert Jika Login Gagal -->
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible mb-3" role="alert">
                                <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-3 mt-4">
                            <button class="btn btn-primary d-grid w-100" type="submit" wire:loading.attr="disabled" wire:target="login">
                                <span wire:loading.remove wire:target="login">Masuk Sekarang</span>
                                <span wire:loading wire:target="login">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...
                                </span>
                            </button>
                        </div>
                    </form>

                    <p class="text-center mt-4">
                        <span>Belum punya akun?</span>
                        <!-- Menghubungkan ke halaman register yang sudah kita buat tadi -->
                        <a href="{{ route('register') ?? '/register' }}" wire:navigate>
                            <span>Daftar di sini</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Login Card -->
        </div>
    </div>
</div>