<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodi', function (Blueprint $table) {
            // Kunci utama sekarang adalah string 'kode_prodi'
            $table->string('kode_prodi')->primary();
            
            $table->foreignId('fakultas_id')->constrained('fakultas')->onDelete('cascade');
            $table->string('nama_prodi');
            $table->string('singkatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodi');
    }
};

