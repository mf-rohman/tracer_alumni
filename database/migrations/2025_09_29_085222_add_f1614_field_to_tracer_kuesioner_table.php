<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            // Menambahkan kolom teks baru setelah kolom f1613
            $table->string('f1614')->nullable()->after('f1613');
        });
    }

    public function down(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            $table->dropColumn('f1614');
        });
    }
};