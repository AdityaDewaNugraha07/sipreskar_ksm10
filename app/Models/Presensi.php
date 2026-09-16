<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
     protected $table = 'presensi';
    protected $fillable = ['karang_taruna_id', 'tanggal', 'jam_hadir', 'status'];

    public function anggota()
    {
        return $this->belongsTo(KarangTaruna::class, 'karang_taruna_id');
    }
}
