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
        try {
            $row = $this->row;

            if (empty($row['npm']) || empty($row['tahun_kuesioner'])) {
                Log::warning("Rows Kuesioner Import Ignored: NPM or Kuesioner's Year Kosong.", $row);
                return;
            }

            $alumni = Alumni::where('npm', $row['npm'])->first();

            if (!$alumni) {
                Log::warning("Rows Kuesioner Import Ignored : Alumni with NPM" . $row['npm'] . "Not Found", $row);
                return;
            }

            $data = Arr::except($row, ['npm']); 
            $data['alumni_id'] = $alumni->id;

            $intColumns = [
                'f301','f302','f303','f502','f505','f14','f15',
                'f6','f7','f7a','f1001','f1003','f1004','f1006',
                'f1007','f1008','f1009','f1101','f1101a','f1101b',
                'f1201','f1202'
            ];
            
            foreach ($intColumns as $col) {
                if (isset($data[$col])) {
                    $data[$col] = $this->toIntOrNull($data[$col]);
                }
            }
            
            KuesionerAnswer::updateOrCreate(
                [
                    'alumni_id' => $alumni->id,
                    'tahun_kuesioner' => $row['tahun_kuesioner']
                ],
                $data
            );

            Log::info("Kuesioner with npm: {$alumni->npm} has been successfully imported", [
                'alumni_id' => $alumni->id,
                'tahun_kuesioner' => $row['tahun_kuesioner']
            ]);

        } catch(\Exception $e) {
            Log::error("Failed Import rows kuesioner: " . $e->getMessage(), ['row' => $this->row]);
        }
    }


}
