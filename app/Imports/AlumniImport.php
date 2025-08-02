<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class AlumniImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Cari prodi berdasarkan kode_prodi dari file Excel.
        //    Pastikan nama kolom di Excel Anda adalah 'kode_prodi'.
        $prodi = Prodi::where('kode_prodi', $row['kode_prodi'])->first();

        if (!$prodi) {
            return null;
        }
         // 2. Buat akun User baru untuk alumni.
        //    Pastikan nama kolom di Excel Anda adalah 'nama_lengkap' dan 'email'.
        $user = User::create([
            'name'     => $row['nama_lengkap'],
            'email'    => $row['email'],
            // Password dibuat acak. Alumni bisa menggunakan fitur "Lupa Password" untuk reset.
            'password' => Hash::make(Str::random(10)),
            'role'     => 'alumni',
        ]);

        // 3. Buat data Alumni yang terhubung dengan User yang baru saja dibuat.
        //    Pastikan nama kolom di Excel Anda sesuai.
        return new Alumni([
            'user_id'       => $user->id,
            'prodi_id'      => $prodi->id,
            'npm'           => $row['npm'],
            'nama_lengkap'  => $row['nama_lengkap'],
            'tahun_lulus'   => $row['tahun_lulus'],
            'no_hp'         => $row['no_hp'],
            'alamat'        => $row['alamat'],
        ]);
    }
}
