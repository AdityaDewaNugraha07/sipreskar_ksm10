<?php

namespace App\Models;

// WAJIB pakai Authenticatable karena model ini sekarang jadi pintu masuk (Login)
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembina extends Authenticatable
{
    use HasFactory;

    // Menyesuaikan log SQL-mu, tabelnya bernama 'pembina'
    protected $table = 'pembina';

    // INI KUNCINYA: Daftarkan 'password' agar diizinkan masuk ke database
    protected $fillable = [
        'nama',
        'password', // <--- Jangan sampai ketinggalan bro!
        'nomor_telepon',
    ];

    // Sembunyikan password saat data ditarik
    protected $hidden = [
        'password',
    ];
}