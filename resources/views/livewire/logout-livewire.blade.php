<div>
    <!-- Modal Logout -->
    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalLogoutLabel">
                        <i class="bx bx-log-out-circle me-1"></i> Konfirmasi Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="mb-3 fs-6">Apakah Anda yakin ingin keluar dari sistem?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-label" data-bs-dismiss="modal">Batal</button>
                        <!-- Eksekusi fungsi logout Livewire -->
                        <button type="button" wire:click="logout" class="btn btn-danger">
                            <i class="bx bx-log-out me-1"></i> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
