<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah belum login di kedua pintu
        if (!Auth::guard('karangtaruna')->check() && !Auth::guard('pembina')->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Tentukan identitas & role user yang sedang aktif
        $userRole = 'User'; // Default terendah
        
        if (Auth::guard('pembina')->check()) {
            $userRole = 'Pembina'; // Jika yang login Pembina
        } elseif (Auth::guard('karangtaruna')->check()) {
            $userRole = Auth::guard('karangtaruna')->user()->role; // Jika yang login Karang Taruna
        }

        // Blokir jika role tidak ada di daftar yang diizinkan route
        if (!in_array($userRole, $roles)) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}