<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan Akun /</span> Profil Saya</h4>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Profil
                        Anggota</a>
                </li>
            </ul>

            <div class="card mb-4 border-0 shadow-sm">
                <h5 class="card-header border-bottom">Detail Identitas</h5>

                @if (session()->has('success_profil'))
                    <div class="card-body pb-0 pt-3">
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success_profil') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="simpanProfil">
                    <div class="card-body pt-4">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <div class="position-relative">
                                @if ($fotoCrop)
                                    <img src="{{ $fotoCrop }}" alt="Preview Foto"
                                        class="d-block rounded object-fit-cover" height="100" width="100"
                                        id="previewFoto" />
                                @elseif ($fotoLama)
                                    <img src="{{ asset($fotoLama) }}" alt="Foto Profil"
                                        class="d-block rounded object-fit-cover" height="100" width="100"
                                        id="previewFoto" />
                                @else
                                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Default Avatar"
                                        class="d-block rounded object-fit-cover" height="100" width="100"
                                        id="previewFoto" />
                                @endif
                            </div>

                            <div class="button-wrapper">
                                <!-- Tombol Pilih Foto (Tanpa wire:model) -->
                                <label for="uploadFile"
                                    class="btn btn-primary me-2 mb-2 d-inline-flex align-items-center" tabindex="0">
                                    <i class="bx bx-upload me-2"></i>
                                    <span>Pilih & Potong Foto</span>
                                    <input type="file" id="uploadFile" class="account-file-input" hidden
                                        accept="image/png, image/jpeg" />
                                </label>

                                @if ($fotoCrop)
                                    <button type="button" wire:click="batalFoto"
                                        class="btn btn-outline-danger mb-2 d-inline-flex align-items-center">
                                        <i class="bx bx-x me-1"></i> Batal
                                    </button>
                                @endif
                                <p class="text-muted mb-0 mt-1">Format JPG, PNG. Maksimal 2MB. Akan diubah jadi persegi
                                    (1:1).</p>
                            </div>
                        </div>

                        <!-- MODAL CROPPER.JS -->
                        <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true"
                            data-bs-backdrop="static" wire:ignore>
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sesuaikan Posisi Foto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0" style="background-color: #000; min-height: 300px;">
                                        <img id="imageToCrop" src="" style="max-width: 100%; display: block;" />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-primary" id="btnCropApply">Potong &
                                            Terapkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />

                    <div class="card-body">
                        <div class="row">

                            <div class="mb-3 col-md-6">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input class="form-control @error('nama') is-invalid @enderror" type="text"
                                    id="nama" wire:model="nama" placeholder="Masukkan nama lengkap..." />
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-3 col-md-6">
                                <label for="jabatan" class="form-label">Jabatan (Role)</label>
                                <select id="jabatan" wire:model="jabatan" class="form-select @error('jabatan') is-invalid @enderror">
                                    <option value="">-- Pilih Jabatan --</option>
                                    <option value="Ketua">Ketua</option>
                                    <option value="Wakil Ketua">Wakil Ketua</option>
                                    <option value="Sekretaris">Sekretaris</option>
                                    <option value="Bendahara">Bendahara</option>
                                    <option value="Seksi Humas">Seksi Humas</option>
                                    <option value="Seksi Dokumentasi & Publikasi">Seksi Dokumentasi & Publikasi</option>
                                    <option value="Anggota">Anggota</option>
                                </select>
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="nomor_telepon">Nomor Telepon (WhatsApp)</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">ID (+62)</span>
                                    <input type="text" id="nomor_telepon" wire:model="nomor_telepon"
                                        class="form-control @error('nomor_telepon') is-invalid @enderror"
                                        placeholder="812 3456 7890" />
                                </div>
                                @error('nomor_telepon')
                                    <span class="text-danger small d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    type="date" id="tanggal_lahir" wire:model="tanggal_lahir" />
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select id="jenis_kelamin" wire:model="jenis_kelamin"
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2" wire:loading.attr="disabled"
                                wire:target="simpanProfil, foto">
                                <span wire:loading.remove wire:target="simpanProfil">
                                    <i class="bx bx-save me-1"></i> Simpan Perubahan
                                </span>
                                <span wire:loading wire:target="simpanProfil">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"
                                        aria-hidden="true"></span> Menyimpan...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card border-0 shadow-sm">
                <h5 class="card-header border-bottom">Ganti Password</h5>
                <div class="card-body pt-4">

                    @if (session()->has('success_password'))
                        <div class="alert alert-success alert-dismissible mb-4" role="alert">
                            {{ session('success_password') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-3 col-12">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1"><i class="bx bx-shield-quarter me-1"></i> Keamanan
                                Akun</h6>
                            <p class="mb-0">Pastikan password baru yang kamu buat kuat dan tidak mudah ditebak oleh
                                orang lain.</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="gantiPassword">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="passwordBaru" class="form-label">Password Baru</label>
                                <input class="form-control @error('passwordBaru') is-invalid @enderror"
                                    type="password" id="passwordBaru" wire:model="passwordBaru"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                @error('passwordBaru')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="konfirmasiPassword" class="form-label">Konfirmasi Password Baru</label>
                                <input class="form-control @error('konfirmasiPassword') is-invalid @enderror"
                                    type="password" id="konfirmasiPassword" wire:model="konfirmasiPassword"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                @error('konfirmasiPassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger" wire:loading.attr="disabled"
                            wire:target="gantiPassword">
                            <span wire:loading.remove wire:target="gantiPassword">Perbarui Password</span>
                            <span wire:loading wire:target="gantiPassword">
                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                    aria-hidden="true"></span> Memproses...
                            </span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        let cropper;
        const uploadFile = document.getElementById('uploadFile');
        const imageToCrop = document.getElementById('imageToCrop');
        const cropModalEl = document.getElementById('cropModal');
        let cropModal;

        document.addEventListener('DOMContentLoaded', function() {
            cropModal = new bootstrap.Modal(cropModalEl);
        });

        document.addEventListener('livewire:navigated', function() {
            cropModal = new bootstrap.Modal(cropModalEl);
        });

        uploadFile.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                // Cek ukuran maksimal 2MB (2048 * 1024 bytes)
                if (files[0].size > 2097152) {
                    alert("Ukuran foto terlalu besar! Maksimal 2MB.");
                    uploadFile.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    imageToCrop.src = event.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(files[0]);
                uploadFile.value = '';
            }
        });

        cropModalEl.addEventListener('shown.bs.modal', function() {
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1, // Memaksa rasio kotak 1:1
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        });

        cropModalEl.addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        document.getElementById('btnCropApply').addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 500, // Kualitas resolusi hasil potongan
                    height: 500,
                });

                // Ubah canvas jadi Base64
                const base64data = canvas.toDataURL('image/png');

                // Lempar datanya ke properti $fotoCrop di Livewire
                @this.set('fotoCrop', base64data);

                cropModal.hide();
            }
        });
    </script>
@endpush
