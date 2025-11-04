<?php

namespace App\Imports;

use App\Jobs\ProcessKuesionerImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;


class KuesionerImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row) {
            ProcessKuesionerImport::dispatch($row->toArray());
        }
    }

   public function chunkSize(): int
   {
        return 100;
   }
}
