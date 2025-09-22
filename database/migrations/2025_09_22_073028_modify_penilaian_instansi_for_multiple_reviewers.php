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
        Schema::table('penilaian_instansi', function (Blueprint $table) {
            // 1. Hapus foreign key yang ada terlebih dahulu untuk melepas ketergantungan
            $table->dropForeign(['alumni_id']);
            $table->dropForeign(['instansi_id']);

            // 2. Hapus index unik yang lama
            $table->dropUnique('penilaian_instansi_alumni_id_instansi_id_unique');

            // 3. PERBAIKAN: Periksa apakah kolom sudah ada sebelum menambahkannya
            if (!Schema::hasColumn('penilaian_instansi', 'penilai_user_id')) {
                $table->foreignId('penilai_user_id')->after('instansi_id')->constrained('users')->onDelete('cascade');
            }

            // 4. Buat batasan unik yang baru
            $table->unique(['alumni_id', 'penilai_user_id']);

            // 5. Tambahkan kembali foreign key yang tadi dihapus
            $table->foreign('alumni_id')->references('id')->on('alumni')->onDelete('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_instansi', function (Blueprint $table) {
            // Lakukan proses kebalikannya untuk rollback
            $table->dropForeign(['alumni_id']);
            $table->dropForeign(['instansi_id']);
            
            if (Schema::hasColumn('penilaian_instansi', 'penilai_user_id')) {
                $table->dropForeign(['penilai_user_id']);
                $table->dropUnique(['alumni_id', 'penilai_user_id']);
                $table->dropColumn('penilai_user_id');
            }

            $table->unique(['alumni_id', 'instansi_id']);
            $table->foreign('alumni_id')->references('id')->on('alumni')->onDelete('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansi')->onDelete('cascade');
        });
    }
};

