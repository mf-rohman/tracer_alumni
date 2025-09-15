<?php

namespace Database\Seeders;

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
        // Data Fakultas dan Prodi yang sudah lengkap
        $data = [
            [
                'kode_fakultas' => 'FKIP',
                'nama_fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'prodi' => [
                    ['kode_prodi' => 'PPKN', 'nama_prodi' => 'S1 Pendidikan Pancasila dan Kewarganegaraan'],
                    ['kode_prodi' => 'PE', 'nama_prodi' => 'S1 Pendidikan Ekonomi'],
                    ['kode_prodi' => 'PBIO', 'nama_prodi' => 'S1 Pendidikan Biologi'],
                    ['kode_prodi' => 'PMAT', 'nama_prodi' => 'S1 Pendidikan Matematika'],
                    ['kode_prodi' => 'PBSI', 'nama_prodi' => 'S1 Pendidikan Bahasa & Sastra Indonesia'],
                    ['kode_prodi' => 'PBI', 'nama_prodi' => 'S1 Pendidikan Bahasa Inggris'],
                    ['kode_prodi' => 'PGPAUD', 'nama_prodi' => 'S1 Pendidikan Guru PAUD (PG-PAUD)'],
                    ['kode_prodi' => 'PGSD', 'nama_prodi' => 'S1 Pendidikan Guru SD (PGSD)'],
                ]
            ],
            [
                'kode_fakultas' => 'FISIP',
                'nama_fakultas' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'prodi' => [
                    ['kode_prodi' => 'IPOL', 'nama_prodi' => 'S1 Ilmu Politik'],
                    ['kode_prodi' => 'IKOM', 'nama_prodi' => 'S1 Ilmu Komunikasi'],
                ]
            ],
            [
                'kode_fakultas' => 'FT',
                'nama_fakultas' => 'Fakultas Teknik',
                'prodi' => [
                    ['kode_prodi' => 'TI', 'nama_prodi' => 'S1 Teknik Industri'],
                    ['kode_prodi' => 'TIF', 'nama_prodi' => 'S1 Teknik Informatika'],
                ]
            ],
            [
                'kode_fakultas' => 'FMIPA',
                'nama_fakultas' => 'Fakultas MIPA dan Kelautan',
                'prodi' => [
                    ['kode_prodi' => 'MAT', 'nama_prodi' => 'S1 Matematika'],
                    ['kode_prodi' => 'BIO', 'nama_prodi' => 'S1 Biologi'],
                    ['kode_prodi' => 'IK', 'nama_prodi' => 'S1 Ilmu Perikanan'],
                    ['kode_prodi' => 'IKL', 'nama_prodi' => 'S1 Ilmu Kelautan'],
                ]
            ],
            // PENAMBAHAN FAKULTAS PASCASARJANA UNTUK S2
            [
                'kode_fakultas' => 'PASC',
                'nama_fakultas' => 'Pascasarjana',
                'prodi' => [
                    ['kode_prodi' => 'S2DIKDAS', 'nama_prodi' => 'S2 Pendidikan Dasar'],
                    ['kode_prodi' => 'S2PBIO', 'nama_prodi' => 'S2 Pendidikan Biologi'],
                ]
            ],
        ];

        // Looping untuk memasukkan data
        foreach ($data as $fakultasData) {
            $fakultas = Fakultas::create([
                'kode_fakultas' => $fakultasData['kode_fakultas'],
                'nama_fakultas' => $fakultasData['nama_fakultas'],
            ]);

            foreach ($fakultasData['prodi'] as $prodiData) {
                Prodi::create([
                    'fakultas_id' => $fakultas->id,
                    'kode_prodi' => $prodiData['kode_prodi'],
                    'nama_prodi' => $prodiData['nama_prodi'],
                ]);
            }
        }
    }
}

