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
            // PERBAIKAN: Mengubah prodi_id menjadi string dan merujuk ke 'kode_prodi'
            $table->string('prodi_id')->nullable()->after('role'); // Buat kolom string

            // Definisikan hubungan foreign key secara manual
            $table->foreign('prodi_id')
                  ->references('kode_prodi')->on('prodi')
                  ->onDelete('set null'); // Jika prodi dihapus, user tidak ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key sebelum menghapus kolom
            $table->dropForeign(['prodi_id']);
            $table->dropColumn('prodi_id');
        });
    }
};