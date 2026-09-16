<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register Card -->
            <div class="card border-0 shadow-sm px-sm-6 px-0">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4 mt-2">
                        <a href="#" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <!-- Gunakan Logo KSM mu di sini -->
                                <img src="{{ asset('assets/img/logo-ksm-new.png') }}" width="50" alt="Logo">
                            </span>
                            <span class="app-brand-text demo text-body fw-bold text-uppercase">| KSM 10</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    
                    <h4 class="mb-2">Register Sekarang! 🚀</h4>
                    <p class="mb-4">Buat akun barumu untuk bergabung dengan Karang Taruna KSM 10!</p>

                    <form wire:submit.prevent="register" class="mb-3">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" wire:model="nama" id="nama" placeholder="Masukkan nama kamu" autofocus>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor WhatsApp</label>
                            <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" wire:model="nomor_telepon" id="nomor_telepon" placeholder="08xxxxxxxxxx">
                            @error('nomor_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" wire:model="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password_confirmation" class="form-control" wire:model="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <button class="btn btn-primary d-grid w-100 mt-4" type="submit" wire:loading.attr="disabled" wire:target="register">
                            <span wire:loading.remove wire:target="register">Daftar Sekarang</span>
                            <span wire:loading wire:target="register">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...
                            </span>
                        </button>
                    </form>

                    <p class="text-center mt-4">
                        <span>Sudah punya akun?</span>
                        <a href="/login" wire:navigate>
                            <span>Masuk di sini</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Register Card -->
        </div>
    </div>
</div>