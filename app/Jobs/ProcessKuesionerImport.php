<?php

namespace App\Jobs;

use App\Models\Alumni;
use App\Models\KuesionerAnswer;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProcessKuesionerImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $row;
    /**
     * Create a new job instance.
     */
    public function __construct( array $row)
    {
        $this->row = $row;
    }


    private function toIntOrNull($value)
    {
        return ($value === "" || $value === null) ? null : (int) $value;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Cek jika batch dibatalkan oleh user
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }
        
        try {
            $row = $this->row;

            // ==========================================================
            // ATURAN 1: HANYA NPM DAN TAHUN YANG WAJIB ADA
            // ==========================================================
            if (empty($row['npm']) || empty($row['tahun_kuesioner'])) {
                // Hanya beri peringatan di log, jangan bikin job 'Failed'
                Log::warning("Baris dilewati: NPM atau Tahun Kuesioner Kosong.", ['row' => $row]);
                return; 
            }

            // Cari Alumni (Wajib ada di database master alumni)
            $alumni = Alumni::where('npm', $row['npm'])->first();

            if (!$alumni) {
                Log::warning("Baris dilewati: Alumni dengan NPM " . $row['npm'] . " tidak ditemukan di database.", ['row' => $row]);
                return;
            }

            // ==========================================================
            // ATURAN 2: KOLOM LAIN BOLEH KOSONG (DIUBAH JADI NULL)
            // ==========================================================
            
            // Pisahkan data NPM (karena tidak masuk tabel kuesioner)
            $rawData = Arr::except($row, ['npm']);
            $cleanData = [];

            foreach ($rawData as $key => $val) {
                
                // Amankan nilai sementara
                $processedValue = $val;

                // Bersihkan spasi jika tipe datanya string
                if (is_string($processedValue)) {
                    $processedValue = trim($processedValue);
                }

                // Cek apakah data dianggap "Kosong"
                if (
                    $processedValue === "" ||       // String kosong
                    $processedValue === null ||     // Null
                    $processedValue === "-" ||      // Strip
                    $processedValue === " "         // Spasi saja
                ) {
                    // JIKA KOSONG: Set jadi NULL. 
                    // Database akan menerimanya (asalkan kolom di database 'nullable')
                    $cleanData[$key] = null;
                } else {
                    // JIKA ADA ISI: Masukkan apa adanya
                    $cleanData[$key] = $processedValue;
                }
            }

            // ==========================================================
            // 3. SIMPAN KE DATABASE
            // ==========================================================
            KuesionerAnswer::updateOrCreate(
                [
                    'alumni_id' => $alumni->id,
                    'tahun_kuesioner' => $cleanData['tahun_kuesioner'] // Tahun diambil dari data bersih
                ],
                $cleanData
            );

        } catch(\Exception $e) {
            // Jika terjadi error teknis (misal koneksi putus), catat log
            Log::error("Gagal Import Kuesioner NPM " . ($this->row['npm'] ?? '?') . ": " . $e->getMessage());
            
            // Tandai job sebagai GAGAL agar muncul di notifikasi error
            $this->fail($e);
        }
    }


}
