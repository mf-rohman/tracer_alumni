<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AlumniImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Pastikan nama kolom header di file Excel Anda menggunakan format snake_case dan lowercase
        // Contoh: nomor_mahasiswa, kode_pt, tahun_angkatan, tahun_lulus, kode_prodi, nama, email, nomor_telepon_hp, nik, npwp, ipk

        // 1. Cari prodi berdasarkan kode_prodi dari file Excel.
        $prodi = Prodi::where('kode_prodi', $row['kode_prodi'])->first();

        // Jika prodi tidak ditemukan atau email kosong, lewati baris ini dan catat di log
        if (!$prodi || empty($row['email'])) {
            Log::warning('Melewati baris karena prodi tidak ditemukan atau email kosong.', $row);
            return null;
        }

        // 2. Buat atau temukan User baru untuk alumni.
        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name'     => $row['nama'],
                'password' => Hash::make(Str::random(10)), // Password dibuat acak
                'role'     => 'alumni',
            ]
        );

        // 3. Buat atau perbarui data Alumni yang terhubung dengan User.
        return Alumni::updateOrCreate(
            ['npm' => $row['nomor_mahasiswa']], // Kunci unik untuk mencari alumni
            [
                'user_id'       => $user->id,
                'prodi_id'      => $prodi->id,
                'kode_pt'       => $row['kode_pt'] ?? null,
                'tahun_masuk'   => $row['tahun_angkatan'] ?? null,
                'tahun_lulus'   => $row['tahun_lulus'],
                'nama_lengkap'  => $row['nama'],
                'no_hp'         => $row['nomor_telepon_hp'] ?? null,
                'nik'           => $row['nik'] ?? null,
                'npwp'          => $row['npwp'] ?? null,
                'ipk'           => $row['ipk'] ?? null,
                'alamat'        => $row['alamat'] ?? null,
            ]
        );
    }
}
