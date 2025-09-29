<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            // Menambahkan kolom baru setelah kolom f502 yang sudah ada
            $table->integer('f502_wiraswasta')->nullable()->after('f502');
        });
    }

    public function down(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            $table->dropColumn('f502_wiraswasta');
        });
    }
};