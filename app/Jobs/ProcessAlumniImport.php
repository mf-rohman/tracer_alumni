<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Log;


class ProcessAlumniImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $row;

    /**
     * Create a new job instance.
     */
    public function __construct(array $row)
    {
        $this->row = $row;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $row = $this->row;
        Log::info('Import Row Data', ['data' => $row]);



        // 1. Buat atau update User
        $user = User::updateOrCreate(
            ['email' => $row['npm'] . '@unirow.ac.id'],
            [
                'name' => $row['nama_lengkap'],
                'password' => Hash::make((string)$row['npm']),
                'prodi_id' => $row['kode_prodi'],
                'role' => 'alumni',
            ]
        );

        $tanggalLahir = null;
        if (!empty($row['tanggal_lahir'])) {
            try {
                // Cek jika nilainya adalah angka (format tanggal default Excel)
                if (is_numeric($row['tanggal_lahir'])) {
                    // Gunakan konverter khusus Excel untuk mengubah angka menjadi objek tanggal
                    $tanggalLahir = Carbon::instance(ExcelDate::excelToDateTimeObject($row['tanggal_lahir']))->format('Y-m-d');
                } else {
                    // Jika bukan angka, coba parse sebagai string tanggal biasa (misal: "1998-11-11")
                    $tanggalLahir = Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Jika masih gagal karena format lain yang tidak terduga, biarkan null agar tidak error
                $tanggalLahir = null;
            }
        }

        // 3. Buat atau update Alumni
        Alumni::updateOrCreate(
            ['npm' => $row['npm']],
            [
                'user_id' => $user->id,
                'prodi_id' => $row['kode_prodi'],
                'nama_lengkap' => $row['nama_lengkap'],
                'nik' => $row['nik'],
                'tempat_lahir' => $row['tempat_lahir'] ?? null, 
                'tanggal_lahir' => $tanggalLahir,
                'no_hp' => $row['no_hp'],
                'alamat' => $row['alamat'],
                'tahun_masuk' => $row['tahun_masuk'],
                'tahun_lulus' => $row['tahun_lulus'],
                'ipk' => $row['ipk'],
                'kode_pt' => $row['kode_pt'] ?? null
            ]
        );
    }
}
