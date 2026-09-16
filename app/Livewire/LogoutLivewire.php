<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LogoutLivewire extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Redirect ke halaman login setelah sesi dihapus
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.logout-livewire');
    }
}
