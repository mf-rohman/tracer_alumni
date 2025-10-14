<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->char('code', 4)->unique();
            $table->char('province_code', 2);
            $table->string('name');
            // Menambahkan foreign key constraint
            $table->foreign('province_code')->references('code')->on('provinces')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('regencies');
    }
};
