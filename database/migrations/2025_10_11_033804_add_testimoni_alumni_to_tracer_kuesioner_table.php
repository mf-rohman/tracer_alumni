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
            $table->text('testimoni_alumni')->nullable();
            $table->string('url_linkedin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            $table->dropColumn('testimoni_alumni');
            $table->dropColumn('url_linkedin');
        });
    }
};
