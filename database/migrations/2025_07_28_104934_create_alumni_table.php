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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('prodi_id');

            $table->foreign('prodi_id')->references('kode_prodi')->on('prodi')->onDelete('cascade');


            // Kolom Identitas Utama
            $table->string('npm')->unique();
            $table->string('kode_pt')->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->year('tahun_lulus');
            $table->string('nama_lengkap');
            $table->string('photo_path')->nullable(); // Untuk foto profil

            // Kolom Kontak & Data Diri
            $table->string('no_hp')->nullable();
            $table->string('nik')->nullable();
            $table->string('npwp')->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->text('alamat')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
