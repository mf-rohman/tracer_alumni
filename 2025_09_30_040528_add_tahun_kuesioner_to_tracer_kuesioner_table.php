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
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            // 1. Hapus foreign key yang ada terlebih dahulu untuk melepas ketergantungan
            $table->dropForeign(['alumni_id']);

            // 2. Hapus index unik yang lama (ini yang menyebabkan error)
            $table->dropUnique('tracer_kuesioner_alumni_id_unique');

            // 3. Tambahkan kolom baru untuk tahun kuesioner
            $table->year('tahun_kuesioner')->after('alumni_id');

            // 4. Buat batasan unik yang baru: satu alumni hanya bisa mengisi sekali per tahun.
            $table->unique(['alumni_id', 'tahun_kuesioner']);

            // 5. Tambahkan kembali foreign key yang tadi dihapus
            $table->foreign('alumni_id')->references('id')->on('alumni')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            // Lakukan proses kebalikannya untuk rollback
            $table->dropForeign(['alumni_id']);
            $table->dropUnique(['alumni_id', 'tahun_kuesioner']);
            $table->dropColumn('tahun_kuesioner');
            
            $table->unique('alumni_id', 'tracer_kuesioner_alumni_id_unique');
            $table->foreign('alumni_id')->references('id')->on('alumni')->onDelete('cascade');
        });
    }
};
