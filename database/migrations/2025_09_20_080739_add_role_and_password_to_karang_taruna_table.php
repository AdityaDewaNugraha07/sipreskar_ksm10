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
            $table->string('role')->after('jenis_kelamin');
            $table->string('password')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karang_taruna', function (Blueprint $table) {
            $table->dropColumn(['role', 'password']);
        });
    }
};
