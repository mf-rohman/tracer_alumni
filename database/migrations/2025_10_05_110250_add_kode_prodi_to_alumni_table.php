<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            // Menambahkan kolom string baru untuk kode_prodi setelah kolom prodi_id
            $table->string('kode_prodi')->nullable()->after('prodi_id');
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn('kode_prodi');
        });
    }
};
