<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_kas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('sumber_dana', 255);
            $table->text('keterangan')->nullable();
            $table->bigInteger('nominal');
            $table->unsignedBigInteger('tagihan_kas_id')->nullable();
            
            // Otomatis bikin created_at dan updated_at
            $table->timestamps();

            // (Opsional) Bikin relasi ke tabel tagihan_kas
            // $table->foreign('tagihan_kas_id')->references('id')->on('tagihan_kas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_kas');
    }
};