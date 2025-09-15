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
        Schema::create('tracer_kuesioner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');

            // Kuesioner Wajib
            $table->tinyInteger('f8')->nullable()->comment('Status saat ini');
            $table->integer('f502')->nullable()->comment('Bulan dapat kerja/wiraswasta');
            $table->integer('f505')->nullable()->comment('Pendapatan per bulan');
            $table->string('f5a1')->nullable()->comment('Provinsi tempat kerja');
            $table->string('f5a2')->nullable()->comment('Kota/Kabupaten tempat kerja');
            $table->tinyInteger('f1101')->nullable()->comment('Jenis perusahaan');
            $table->string('f5b')->nullable()->comment('Nama perusahaan');
            $table->string('f5c')->nullable()->comment('Jabatan wiraswasta');
            $table->string('f5d')->nullable()->comment('Tingkat tempat kerja');

            // Studi Lanjut
            $table->string('f18a')->nullable()->comment('Sumber biaya studi lanjut');
            $table->string('f18b')->nullable()->comment('Perguruan tinggi lanjut');
            $table->string('f18c')->nullable()->comment('Prodi studi lanjut');
            $table->date('f18d')->nullable()->comment('Tanggal masuk studi lanjut');

            $table->tinyInteger('f1201')->nullable()->comment('Sumber dana kuliah S1');
            $table->string('f1202')->nullable()->comment('Sumber dana lainnya');

            $table->tinyInteger('f14')->nullable()->comment('Hubungan bidang studi');
            $table->tinyInteger('f15')->nullable()->comment('Tingkat pendidikan yang sesuai');

            // Kompetensi (f17)
            $table->tinyInteger('f1761')->nullable(); $table->tinyInteger('f1762')->nullable();
            $table->tinyInteger('f1763')->nullable(); $table->tinyInteger('f1764')->nullable();
            $table->tinyInteger('f1765')->nullable(); $table->tinyInteger('f1766')->nullable();
            $table->tinyInteger('f1767')->nullable(); $table->tinyInteger('f1768')->nullable();
            $table->tinyInteger('f1769')->nullable(); $table->tinyInteger('f1770')->nullable();
            $table->tinyInteger('f1771')->nullable(); $table->tinyInteger('f1772')->nullable();
            $table->tinyInteger('f1773')->nullable(); $table->tinyInteger('f1774')->nullable();

            // Metode Pembelajaran (f2)
            $table->tinyInteger('f21')->nullable(); $table->tinyInteger('f22')->nullable();
            $table->tinyInteger('f23')->nullable(); $table->tinyInteger('f24')->nullable();
            $table->tinyInteger('f25')->nullable(); $table->tinyInteger('f26')->nullable();
            $table->tinyInteger('f27')->nullable();

            // Mencari Pekerjaan
            $table->tinyInteger('f301')->nullable()->comment('Kapan mulai cari kerja');
            $table->integer('f302')->nullable()->comment('Bulan sebelum lulus');
            $table->integer('f303')->nullable()->comment('Bulan sesudah lulus');

            // Cara Mencari Pekerjaan (f4) - Menggunakan boolean (0/1)
            for ($i = 1; $i <= 15; $i++) {
                $table->boolean('f4' . str_pad($i, 2, '0', STR_PAD_LEFT))->default(false);
            }
            $table->string('f416')->nullable()->comment('Cara lain mencari kerja');

            $table->integer('f6')->nullable()->comment('Jumlah perusahaan dilamar');
            $table->integer('f7')->nullable()->comment('Jumlah respons lamaran');
            $table->integer('f7a')->nullable()->comment('Jumlah undangan wawancara');

            $table->tinyInteger('f1001')->nullable()->comment('Aktif mencari kerja 4 minggu terakhir');
            $table->string('f1002')->nullable()->comment('Alasan lain tidak aktif');

            // Alasan mengambil pekerjaan tidak sesuai (f16)
            for ($i = 1; $i <= 13; $i++) {
                $table->boolean('f16' . str_pad($i, 2, '0', STR_PAD_LEFT))->default(false);
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_kuesioner');
    }
};
