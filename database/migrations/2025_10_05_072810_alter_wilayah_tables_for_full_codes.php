<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Langkah 1: Hapus foreign key secara eksplisit
        Schema::table('regencies', function (Blueprint $table) {
            // Menggunakan nama constraint dari pesan error agar lebih pasti
            $table->dropForeign('regencies_province_code_foreign');
        });

        // Langkah 2: Ubah tipe data di tabel provinces terlebih dahulu
        Schema::table('provinces', function (Blueprint $table) {
            $table->string('code', 10)->change();
        });

        // Langkah 3: Ubah tipe data di tabel regencies
        Schema::table('regencies', function (Blueprint $table) {
            $table->string('code', 10)->change();
            $table->string('province_code', 10)->change();

            // Langkah 4: Tambahkan kembali foreign key setelah semua tipe data sesuai
            $table->foreign('province_code')
                  ->references('code')
                  ->on('provinces')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Logika untuk mengembalikan jika perlu (opsional)
        Schema::table('regencies', function (Blueprint $table) {
            $table->dropForeign(['province_code']);
        });
        
        // ...dan seterusnya
    }
};
