<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanKas extends Model
{
    protected $table = 'tagihan_kas';
    protected $fillable = [
        'karang_taruna_id', 'tanggal_kumpulan', 'nominal', 'status', 'tanggal_lunas'
    ];

    public function anggota()
    {
        return $this->belongsTo(KarangTaruna::class, 'karang_taruna_id');
    }
}