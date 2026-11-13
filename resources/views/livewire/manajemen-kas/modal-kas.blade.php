<div>
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
                                <button class="form-select text-start d-flex align-items-center justify-content-between"
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
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
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

</div>
