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
            // Tambahkan kolom prodi_id setelah kolom 'role'
            // Kolom ini bisa null karena hanya admin prodi yang akan punya nilai
            $table->foreignId('prodi_id')->nullable()->after('role')->constrained('prodi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['prodi_id']);
            // Hapus kolomnya
            $table->dropColumn('prodi_id');
        });
    }
};
