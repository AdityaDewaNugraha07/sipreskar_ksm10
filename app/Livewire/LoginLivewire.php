<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.login-layout')]
class LoginLivewire extends Component
{
    public $nama;
    public $password;

    public function login()
    {
        $credentials = [
            'nama' => $this->nama,
            'password' => $this->password,
        ];

        // 1. Coba ketok pintu Karang Taruna
        if (Auth::guard('karangtaruna')->attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }

        // 2. Kalau di Karang Taruna nggak ada, coba ketok pintu Pembina
        if (Auth::guard('pembina')->attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }

        // Kalau dua-duanya gagal
        session()->flash('error', 'Nama atau password salah.');
    }

    public function render()
    {
        return view('livewire.login-livewire');
    }
}