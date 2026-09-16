<div>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Karang Taruna /</span> List Anggota Karang Taruna
        </h4>

        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0">List Anggota Karang Taruna</h5>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <span class="icon-base bx bx-plus" style="font-size: 15px;"></span>
                </button>
            </div>

            {{-- Modal Tambah --}}
            <div wire:ignore.self class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data Karang Taruna</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="store">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nama" class="form-control" placeholder="Nama Lengkap" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <select wire:model="jabatan" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Ketua">Ketua</option>
                                        <option value="Wakil Ketua">Wakil Ketua</option>
                                        <option value="Sekretaris">Sekretaris</option>
                                        <option value="Bendahara">Bendahara</option>
                                        <option value="Seksi Humas">Seksi Humas</option>
                                        <option value="Seksi Dokumentasi & Publikasi">Seksi Dokumentasi & Publikasi</option>
                                        <option value="Anggota">Anggota</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nomor_telepon" class="form-control" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="tanggal_lahir" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select wire:model="jenis_kelamin" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select wire:model="role" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Admin">Admin</option>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="store">
                                    <span wire:loading.remove wire:target="store">Simpan</span>
                                    <span wire:loading wire:target="store">Menyimpan...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Edit --}}
            <div wire:ignore.self class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Karang Taruna</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="update">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nama" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <select wire:model="jabatan" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Ketua">Ketua</option>
                                        <option value="Wakil Ketua">Wakil Ketua</option>
                                        <option value="Sekretaris">Sekretaris</option>
                                        <option value="Bendahara">Bendahara</option>
                                        <option value="Seksi Humas">Seksi Humas</option>
                                        <option value="Seksi Dokumentasi & Publikasi">Seksi Dokumentasi & Publikasi</option>
                                        <option value="Anggota">Anggota</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="nomor_telepon" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="tanggal_lahir" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select wire:model="jenis_kelamin" class="form-select" required>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select wire:model="role" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Admin">Admin</option>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password (Opsional)</label>
                                    <input type="text" wire:model="password" class="form-control" placeholder="Isi jika ingin ganti password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="update">
                                    <span wire:loading.remove wire:target="update">Update</span>
                                    <span wire:loading wire:target="update">Memproses...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Delete --}}
            <div wire:ignore.self class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus data ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" wire:click="delete" class="btn btn-danger" wire:loading.attr="disabled" wire:target="delete">
                                <span wire:loading.remove wire:target="delete">Hapus</span>
                                <span wire:loading wire:target="delete">Menghapus...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="table-responsive text-nowrap" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Nomor Telepon</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    @php
                                        $badgeColors = [
                                            'Ketua' => 'primary',
                                            'Wakil Ketua' => 'purple',
                                            'Sekretaris' => 'success',
                                            'Bendahara' => 'warning',
                                            'Seksi Humas' => 'danger',
                                            'Seksi Dokumentasi & Publikasi' => 'info',
                                            'Anggota' => 'dark',
                                        ];
                                        $color = $badgeColors[$item->jabatan] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-label-{{ $color }}">{{ $item->jabatan }}</span>
                                </td>
                                <td>
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $item->nomor_telepon) }}" target="_blank" class="badge bg-label-success text-decoration-none d-inline-flex align-items-center gap-1">
                                        <i class="bx bxl-whatsapp" style="font-size: 18px !important;"></i> {{ $item->nomor_telepon }}
                                    </a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                                <td>{{ $item->jenis_kelamin }}</td>
                                <td>{{ $item->role }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit" wire:click="edit({{ $item->id }})">
                                        <span class="bx bx-edit" style="font-size: 15px;"></span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete" wire:click="confirmDelete({{ $item->id }})">
                                        <span class="bx bx-trash" style="font-size: 15px;"></span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('close-modal', () => {
        let modals = ['modalCreate', 'modalEdit', 'modalDelete'];
        modals.forEach(id => {
            let modalEl = document.getElementById(id);
            if (modalEl) {
                let modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.hide();
            }
        });
    });
</script>
@endpush