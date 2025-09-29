<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            $table->integer('f5c_wiraswasta')->nullable()->after('f5c');
        });
    }

    public function down(): void
    {
        Schema::table('tracer_kuesioner', function (Blueprint $table) {
            $table->dropColumn('f5c_wiraswasta');
        });
    }
};