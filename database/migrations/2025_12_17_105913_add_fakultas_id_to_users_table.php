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
            // PERBAIKAN: Gunakan unsignedBigInteger agar cocok dengan tipe data id standar Laravel
            // Jika tabel fakultas Anda menggunakan id() (bigIncrements), maka ini wajib unsignedBigInteger
            $table->unsignedBigInteger('fakultas_id')->nullable()->after('prodi_id');
            
            // Definisikan foreign key (opsional, tapi bagus untuk integritas data)
            // Pastikan nama tabel referensinya benar ('fakultas')
            $table->foreign('fakultas_id')
                  ->references('id')
                  ->on('fakultas')
                  ->nullOnDelete(); // Jika fakultas dihapus, set user.fakultas_id jadi null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key dulu sebelum hapus kolom
            // Nama constraint biasanya: nama_tabel_nama_kolom_foreign
            $table->dropForeign(['fakultas_id']); 
            $table->dropColumn('fakultas_id');
        });
    }
};
