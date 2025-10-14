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
            // Mengubah tipe data kolom f505 menjadi BIGINT agar bisa menampung angka besar
            $table->bigInteger('f505')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            // Mengembalikan tipe data ke tinyInteger jika migrasi di-rollback
            $table->tinyInteger('f505')->nullable()->change();
        });
    }
};