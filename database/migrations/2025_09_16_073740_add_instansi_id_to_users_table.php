<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('instansi_id')->nullable()->constrained('instansi')->onDelete('cascade');
            // Menambahkan 'instansi' ke dalam pilihan role
            DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM('superadmin', 'bak', 'admin_prodi', 'alumni', 'instansi') NOT NULL DEFAULT 'alumni'");
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
