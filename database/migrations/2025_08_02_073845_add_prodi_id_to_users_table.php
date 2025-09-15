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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom prodi_id setelah kolom 'role'
            // Kolom ini bisa null (kosong) karena hanya admin prodi yang akan punya nilai
            $table->foreignId('prodi_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('prodi')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini adalah perintah untuk membatalkan perubahan jika diperlukan
            $table->dropForeign(['prodi_id']);
            $table->dropColumn('prodi_id');
        });
    }
};
