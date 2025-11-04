<?php

namespace App\Http\Controllers;

use App\Imports\KuesionerImport;
use App\Jobs\ProcessKuesionerImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class KuesionerImportController extends Controller
{
    public function showImportForm() {
        return view('admin.kuesioner.import');
    }

    public function handleImport(Request $request) {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try{
            $rows = Excel::toCollection(new KuesionerImport, $request->file('file'))->first();
            $jobs = [];

            foreach($rows as $row) {
                if (!empty($row['npm']) && !empty($row['tahun_kuesioner'])) {
                    $jobs[] = new ProcessKuesionerImport($row->toArray());
                }
            }

            if (empty($jobs)) {
                return back()->with('error', 'File excel invalid (NPM or Kuesioner Year Empty)');
            }

            $batch = Bus::batch($jobs)
                ->name("Import Data Kuesioner Alumni")
                ->dispatch();
            
            return redirect()-route('admin.kuesioner.import.status', ['batch_id' => $batch->id]);

        } catch(\Exception $e) {
            Log::error('Failed to start import batch kuesioner ' . $e->getMessage());
            return back()->with('error' . 'There was problem when read file: ' . $e->getMessage());
        }
    }


    public function showImportStatus($batchId)
    {
        $batch = Bus::findBatch($batchId);
        if (!$batch) {
            abort(404);
        }
        return view('admin.kuesioner.import-status', compact('batch'));
    }

    /**
     * Menyediakan data progres untuk API. (Dipanggil oleh JavaScript)
     */
    public function getImportStatus($batchId)
    {
        $batch = Bus::findBatch($batchId);
        if (!$batch) {
            return response()->json(['error' => 'Batch not found'], 404);
        }
        return response()->json([
            'totalJobs' => $batch->totalJobs,
            'processedJobs' => $batch->processedJobs(),
            'progress' => $batch->progress(),
            'failedJobs' => $batch->failedJobs,
            'finished' => $batch->finished(),
            'cancelled' => $batch->cancelled(),
        ]);
    }
    
    /**
     * Menyediakan file template kuesioner untuk diunduh.
     */
    public function downloadKuesionerTemplate()
    {
        $filePath = public_path('templates/template_import_kuesioner.xlsx');
        if (!file_exists($filePath)) {
             return redirect()->back()->with('error', 'File template kuesioner tidak ditemukan.');
        }
        return response()->download($filePath);
    }

}
