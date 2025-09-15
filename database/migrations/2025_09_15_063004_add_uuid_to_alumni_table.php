<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            // Tambahkan kolom uuid setelah 'id' dan pastikan unik
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });

        // Isi kolom uuid untuk data yang sudah ada
        $alumni = DB::table('alumni')->whereNull('uuid')->get();
        foreach ($alumni as $item) {
            DB::table('alumni')->where('id', $item->id)->update(['uuid' => Str::uuid()]);
        }
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};