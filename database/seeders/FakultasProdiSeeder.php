<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\Prodi;

class FakultasProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data contoh untuk fakultas dan prodi
        // Anda bisa menambah atau mengubah data ini sesuai kebutuhan
        $data = [
            [
                'kode_fakultas' => 'FST',
                'nama_fakultas' => 'Fakultas Sains dan Teknologi',
                'prodi' => [
                    ['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi'],
                    ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika'],
                    ['kode_prodi' => 'TE', 'nama_prodi' => 'Teknik Elektro'],
                ]
            ],
            [
                'kode_fakultas' => 'FEB',
                'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis',
                'prodi' => [
                    ['kode_prodi' => 'AKT', 'nama_prodi' => 'Akuntansi'],
                    ['kode_prodi' => 'MAN', 'nama_prodi' => 'Manajemen'],
                ]
            ],
            [
                'kode_fakultas' => 'FISIP',
                'nama_fakultas' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'prodi' => [
                    ['kode_prodi' => 'IKOM', 'nama_prodi' => 'Ilmu Komunikasi'],
                    ['kode_prodi' => 'HI', 'nama_prodi' => 'Hubungan Internasional'],
                ]
            ],
        ];

        // Looping untuk memasukkan data
        foreach ($data as $fakultasData) {
            // Membuat record fakultas baru
            $fakultas = Fakultas::create([
                'kode_fakultas' => $fakultasData['kode_fakultas'],
                'nama_fakultas' => $fakultasData['nama_fakultas'],
            ]);

            // Looping untuk memasukkan data prodi yang terkait dengan fakultas di atas
            foreach ($fakultasData['prodi'] as $prodiData) {
                Prodi::create([
                    'fakultas_id' => $fakultas->id, // Mengambil ID dari fakultas yang baru saja dibuat
                    'kode_prodi' => $prodiData['kode_prodi'],
                    'nama_prodi' => $prodiData['nama_prodi'],
                ]);
            }
        }
    }
}
