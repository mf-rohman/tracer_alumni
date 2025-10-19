<?php

namespace App\Imports;

use App\Jobs\ProcessAlumniImport; 
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading; 

class AlumniImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * Metode ini akan dipanggil untuk setiap "potongan" (chunk) baris dari file Excel.
     * Tugasnya hanya mendelegasikan pekerjaan, bukan memprosesnya.
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // 3. Logika Kunci: Mengirim setiap baris sebagai pekerjaan baru ke antrian
            ProcessAlumniImport::dispatch($row->toArray());
        }
    }

    /**
     * 4. Menentukan ukuran setiap "potongan" saat membaca file.
     * Ini penting untuk efisiensi memori saat mengimpor file besar.
     */
    public function chunkSize(): int
    {
        return 100; // Proses 100 baris per "potongan"
    }
}

