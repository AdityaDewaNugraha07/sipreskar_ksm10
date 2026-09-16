<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\KarangTaruna;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.login-layout')]
class RegisterLivewire extends Component
{
    public $nama;
    public $nomor_telepon;
    public $password;
    public $password_confirmation;

    public function register()
    {
        // 1. Validasi Inputan
        $this->validate([
            'nama' => 'required|string|max:255|unique:karang_taruna,nama',
            'nomor_telepon' => 'required|string|max:20',
            'password' => 'required|min:6|confirmed', // Harus cocok dgn password_confirmation
        ], [
            'nama.unique' => 'Nama ini sudah terdaftar bro, pakai nama lain ya.',
            'password.min' => 'Password minimal 6 karakter biar aman.',
            'password.confirmed' => 'Oops, konfirmasi password tidak sama!',
        ]);

        // 2. Simpan User Baru ke Database
        $user = KarangTaruna::create([
            'nama' => $this->nama,
            'nomor_telepon' => $this->nomor_telepon,
            'password' => Hash::make($this->password),
            
            // Set Default Value agar tidak error saat Insert DB
            'jabatan' => 'Anggota', 
            'role' => 'User',
            'tanggal_lahir' => date('Y-m-d'), // Default hari ini
            'jenis_kelamin' => 'Laki-laki',   // Default 
        ]);

        // 3. Langsung Otomatis Login setelah daftar
        Auth::login($user);

        // 4. Arahkan ke Dashboard
        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return view('livewire.register-livewire');
    }
}