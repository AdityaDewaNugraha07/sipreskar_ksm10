<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KarangTaruna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KarangTarunaLivewire extends Component
{
    public $nama, $jabatan, $nomor_telepon, $tanggal_lahir, $jenis_kelamin, $role, $password;
    public $data, $selectedId;

    public function render()
    {
        $this->data = KarangTaruna::all();
        return view('livewire.karang-taruna-livewire');
    }

    public function resetForm()
    {
        $this->nama = null;
        $this->jabatan = null;
        $this->nomor_telepon = null;
        $this->tanggal_lahir = null;
        $this->jenis_kelamin = null;
        $this->role = null;
        $this->password = null;
        $this->selectedId = null;
    }

    public function store()
    {
        KarangTaruna::create([
            'nama'          => $this->nama,
            'jabatan'       => $this->jabatan,
            'nomor_telepon' => $this->nomor_telepon,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'role'          => $this->role,
            'password'      => Hash::make($this->password),
            'kode_qr' => Str::uuid(), // generate kode unik
        ]);

        $this->resetForm();
        $this->dispatch('close-modal');
        session()->flash('success', 'Data berhasil ditambahkan.');
        $this->dispatch('close-modal');
    }

    public function edit($id)
    {
        $item = KarangTaruna::findOrFail($id);

        $this->selectedId   = $id;
        $this->nama         = $item->nama;
        $this->jabatan      = $item->jabatan;
        $this->nomor_telepon= $item->nomor_telepon;
        $this->tanggal_lahir= $item->tanggal_lahir;
        $this->jenis_kelamin= $item->jenis_kelamin;
        $this->role         = $item->role;
        // password tidak ditampilkan kembali ke input demi keamanan
    }

    public function update()
    {
        if ($this->selectedId) {
            $item = KarangTaruna::findOrFail($this->selectedId);

            $updateData = [
                'nama'          => $this->nama,
                'jabatan'       => $this->jabatan,
                'nomor_telepon' => $this->nomor_telepon,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
                'role'          => $this->role,
            ];

            // hanya update password kalau user isi field password
            if (!empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $item->update($updateData);

            $this->resetForm();
            $this->dispatch('close-modal');
            session()->flash('success', 'Data berhasil diupdate.');
            $this->dispatch('close-modal');
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
    }

    public function delete()
    {
        if ($this->selectedId) {
            KarangTaruna::destroy($this->selectedId);
            $this->resetForm();
            $this->dispatch('close-modal');
            session()->flash('success', 'Data berhasil dihapus.');
            $this->dispatch('close-modal');
        }
    }
}
