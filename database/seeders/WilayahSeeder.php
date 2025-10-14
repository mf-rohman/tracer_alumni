<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $provincesPath = database_path('seeders/data/provinces.csv');
        $regenciesPath = database_path('seeders/data/regencies.csv');

        if (!File::exists($provincesPath) || !File::exists($regenciesPath)) {
            $this->command->error("File CSV tidak ditemukan. Pastikan 'provinces.csv' dan 'regencies.csv' ada di dalam folder 'database/seeders/data'.");
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('regencies')->truncate();
        DB::table('provinces')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // === 1. Import Provinsi ===
        $provincesData = array_map('str_getcsv', file($provincesPath));
        array_shift($provincesData); // Hapus header

        $provinceCodeMap = [];
        $provincesToInsert = [];

        foreach ($provincesData as $province) {
            if (isset($province[0]) && isset($province[1])) {
                $provinceCode = (int) $province[0]; // contoh: 80000
                $provinceCodeMap[$provinceCode] = $provinceCode;

                $provincesToInsert[] = [
                    'code' => $provinceCode,
                    'name' => ucwords(strtolower($province[1])),
                ];
            }
        }

        DB::table('provinces')->insert($provincesToInsert);
        $this->command->info("Berhasil impor " . count($provincesToInsert) . " provinsi.");

        // === 2. Import Kabupaten/Kota ===
        $regenciesData = array_map('str_getcsv', file($regenciesPath));
        array_shift($regenciesData); // Hapus header

        $regenciesToInsert = [];
        $skippedRegencies = [];

        foreach ($regenciesData as $regency) {
            if (isset($regency[0]) && isset($regency[1])) {
                $regencyCode = (int) $regency[0];   // contoh: 86300
                $regencyName = $regency[1];

                // Hitung provinsi dari regency code
                $provinceCode = floor($regencyCode / 10000) * 10000;

                if (isset($provinceCodeMap[$provinceCode])) {
                    $regenciesToInsert[] = [
                        'code' => $regencyCode,
                        'province_code' => $provinceCode,
                        'name' => ucwords(strtolower($regencyName)),
                    ];
                } else {
                    $skippedRegencies[] = "{$regencyName} (Kode: {$regencyCode}) - Provinsi {$provinceCode} tidak ada di file provinces.";
                }
            }
        }

        DB::table('regencies')->insert($regenciesToInsert);
        $this->command->info("Berhasil impor " . count($regenciesToInsert) . " kabupaten/kota.");

        // === 3. Warning jika ada data yang dilewati ===
        if (!empty($skippedRegencies)) {
            $this->command->warn(count($skippedRegencies) . " kabupaten/kota dilewati karena provinsinya tidak ditemukan:");
            foreach ($skippedRegencies as $skipped) {
                $this->command->line("- " . $skipped);
            }
        } else {
            $this->command->info("Semua data kabupaten/kota berhasil dimapping ke provinsi.");
        }
    }
}
