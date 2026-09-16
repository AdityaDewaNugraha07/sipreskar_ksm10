<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('karang_taruna', function (Blueprint $table) {
            // Menambahkan kolom 'foto' yang boleh kosong (nullable)
            $table->string('foto')->nullable()->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karang_taruna', function (Blueprint $table) {
            // Menghapus kolom 'foto' jika migration di-rollback
            $table->dropColumn('foto');
        });
    }
};