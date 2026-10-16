<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_kas', function (Blueprint $table) {
            $table->id();
            // unsignedBigInteger untuk nyambung ke ID tabel karang_tarunas
            $table->unsignedBigInteger('karang_taruna_id'); 
            $table->date('tanggal_kumpulan');
            $table->bigInteger('nominal')->default(5000);
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->date('tanggal_lunas')->nullable();
            
            // Otomatis bikin created_at dan updated_at
            $table->timestamps(); 

            // (Opsional tapi disarankan) Bikin relasi / Foreign Key
            // Artinya: Kalau data anggota dihapus, tagihan kasnya ikut terhapus
            // $table->foreign('karang_taruna_id')->references('id')->on('karang_tarunas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_kas');
    }
};