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
        Schema::create('penilaian_instansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->foreignId('instansi_id')->constrained('instansi')->onDelete('cascade');

            // SECTION 1: IDENTITAS PENILAI
            $table->string('nama_penilai');
            $table->string('no_hp_penilai');
            $table->string('email_penilai')->nullable();
            $table->string('jabatan_penilai');
            $table->string('website_instansi')->nullable();
            $table->string('bidang_usaha');
            $table->string('bidang_usaha_lainnya')->nullable();

            // SECTION 2: PENILAIAN KOMPETENSI (skala 1-4: 1=kurang, 2=cukup, 3=baik, 4=sangat baik)
            $table->tinyInteger('integritas');
            $table->tinyInteger('bahasa_inggris');
            $table->tinyInteger('tik');
            $table->tinyInteger('leadership');
            $table->tinyInteger('komunikasi');
            $table->tinyInteger('kerjasama_tim');
            $table->tinyInteger('pengembangan_diri');
            $table->tinyInteger('kedisiplinan');
            $table->tinyInteger('kejujuran');
            $table->tinyInteger('motivasi_kerja');
            $table->tinyInteger('etos_kerja');
            $table->tinyInteger('inovasi');
            $table->tinyInteger('problem_solving');
            $table->tinyInteger('wawasan_antar_bidang');
            $table->string('kinerja_keseluruhan'); // Sangat baik, Baik, Cukup baik, Buruk

            // SECTION 3: UMPAN BALIK UMUM
            $table->text('bidang_pekerjaan_ditekuni')->nullable();
            $table->text('posisi_dicapai')->nullable();
            $table->string('kesesuaian_ilmu'); // Sudah / Belum
            $table->text('ilmu_tambahan_sesuai')->nullable();
            $table->text('ilmu_diperlukan_belum_sesuai')->nullable();

            $table->timestamps();

            // Satu instansi hanya bisa menilai satu alumni sekali
            $table->unique(['alumni_id', 'instansi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_instansi');
    }
};
