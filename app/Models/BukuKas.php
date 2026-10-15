<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKas extends Model
{
    protected $table = 'buku_kas';
    protected $fillable = [
        'tanggal', 'jenis', 'sumber_dana', 'keterangan', 'nominal', 'tagihan_kas_id'
    ];
}