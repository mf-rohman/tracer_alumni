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

        // 1. Buat atau update User
        $user = User::updateOrCreate(
            ['email' => $row['npm'] . '@unirow.ac.id'],
            [
                'name' => $row['nama_lengkap'],
                'password' => Hash::make((string)$row['npm']),
                'role' => 'alumni',
            ]
        );

        // 2. Konversi tanggal lahir
        $tanggalLahir = null;
        if (!empty($row['tgl_lahir'])) {
            try {
                $datePart = Str::after($row['tgl_lahir'], ',');
                $datePart = trim($datePart);
                $tanggalLahir = Carbon::parse($datePart)->format('Y-m-d');
            } catch (\Exception $e) {
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
