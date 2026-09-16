<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class KarangTaruna extends Authenticatable
{
    use HasFactory;

    protected $table = 'karang_taruna'; // Sesuaikan nama tabel
    protected $fillable = [
        'nama',
        'jabatan',
        'nomor_telepon',
        'tanggal_lahir',
        'jenis_kelamin',
        'role',
        'password',
        'kode_qr',
        'foto'
    ];
    protected $hidden = ['password'];
}
