<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KarangTaruna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfilLivewire extends Component
{
    public $userId;
    public $nama, $jabatan, $nomor_telepon, $tanggal_lahir, $jenis_kelamin;
    
    // Variabel untuk menampung gambar hasil Crop (Base64)
    public $fotoCrop; 
    public $fotoLama;

    public $passwordBaru, $konfirmasiPassword;

    public function mount()
    {
        $user = KarangTaruna::find(Auth::id());
        
        if ($user) {
            $this->userId = $user->id;
            $this->nama = $user->nama;
            $this->jabatan = $user->jabatan;
            $this->nomor_telepon = $user->nomor_telepon;
            $this->tanggal_lahir = $user->tanggal_lahir;
            $this->jenis_kelamin = $user->jenis_kelamin;
            $this->fotoLama = $user->foto ?? null;
        }
    }

    public function simpanProfil()
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
        ];

        $this->validate($rules);

        $user = KarangTaruna::find($this->userId);
        
        if ($user) {
            $dataUpdate = [
                'nama' => $this->nama,
                'jabatan' => $this->jabatan,
                'nomor_telepon' => $this->nomor_telepon,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
            ];

            // Jika ada foto hasil crop yang dikirim dari JS
            if ($this->fotoCrop) {
                // 1. Hapus foto lama
                if ($user->foto && File::exists(public_path($user->foto))) {
                    File::delete(public_path($user->foto));
                }
                
                // 2. Buat folder otomatis
                $pathFolder = public_path('foto_profil');
                if (!File::isDirectory($pathFolder)) {
                    File::makeDirectory($pathFolder, 0777, true, true);
                }
                
                // 3. Terjemahkan Base64 jadi File Gambar
                $image_parts = explode(";base64,", $this->fotoCrop);
                $image_base64 = base64_decode($image_parts[1]);
                
                // 4. Simpan langsung jadi format PNG
                $namaFile = time() . '_profil.png';
                File::put($pathFolder . '/' . $namaFile, $image_base64);
                
                $dataUpdate['foto'] = 'foto_profil/' . $namaFile;
                $this->fotoLama = $dataUpdate['foto'];
                $this->fotoCrop = null;
            }

            $user->update($dataUpdate);

            $urlFotoBaru = isset($dataUpdate['foto']) ? asset($dataUpdate['foto']) : ($user->foto ? asset($user->foto) : asset('assets/img/avatars/user.png'));
            $this->dispatch('foto-diperbarui', url: $urlFotoBaru);

            session()->flash('success_profil', 'Yeay! Profil dan foto berhasil diperbarui.');
            $this->dispatch('close-modal');
        }
    }

    public function batalFoto()
    {
        $this->fotoCrop = null;
    }

    public function gantiPassword()
    {
        $this->validate([
            'passwordBaru' => 'required|min:6',
            'konfirmasiPassword' => 'required|same:passwordBaru',
        ]);

        $user = KarangTaruna::find($this->userId);
        if ($user) {
            $user->update(['password' => Hash::make($this->passwordBaru)]);
            $this->reset(['passwordBaru', 'konfirmasiPassword']);
            session()->flash('success_password', 'Keamanan ditingkatkan! Password berhasil diubah.');
        }
    }

    public function render()
    {
        return view('livewire.profil-livewire');
    }
}