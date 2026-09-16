<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pembina;
use Illuminate\Support\Facades\Hash; // PENTING: Import Hash untuk enkripsi password

class PembinaLivewire extends Component
{
    public $nama, $nomor_telepon, $password; // Tambahkan variabel password
    public $data, $selectedId;

    public function render()
    {
        $this->data = Pembina::all();
        return view('livewire.pembina-livewire');
    }

    public function resetForm()
    {
        $this->nama = $this->nomor_telepon = $this->password = null;
        $this->selectedId = null;
    }

    public function store()
    {
        Pembina::create([
            'nama' => $this->nama,
            'nomor_telepon' => $this->nomor_telepon,
            'password' => Hash::make($this->password), // Enkripsi password saat tambah data
        ]);
        
        $this->resetForm();
        $this->dispatch('close-modal');
        session()->flash('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Pembina::findOrFail($id);
        $this->selectedId = $id;
        $this->nama = $item->nama;
        $this->nomor_telepon = $item->nomor_telepon;
        
        // Password sengaja dikosongkan saat edit agar aman dan tidak membocorkan hash
        $this->password = null;
    }

    public function update()
    {
        if ($this->selectedId) {
            $item = Pembina::findOrFail($this->selectedId);
            
            // Siapkan data dasar yang pasti diupdate
            $dataUpdate = [
                'nama' => $this->nama,
                'nomor_telepon' => $this->nomor_telepon,
            ];

            // Cek apakah admin mengisi kolom password di modal edit
            // Jika diisi, berarti mau ganti password. Jika kosong, abaikan (password lama tetap aman).
            if (!empty($this->password)) {
                $dataUpdate['password'] = Hash::make($this->password);
            }

            $item->update($dataUpdate);
            
            $this->resetForm();
            $this->dispatch('close-modal');
            session()->flash('success', 'Data berhasil diupdate.');
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
    }

    public function delete()
    {
        if ($this->selectedId) {
            Pembina::destroy($this->selectedId);
            $this->resetForm();
            $this->dispatch('close-modal');
            session()->flash('success', 'Data berhasil dihapus.');
        }
    }
}