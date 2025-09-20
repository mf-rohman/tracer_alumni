<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\KuesionerAnswer;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedProdiId = $request->input('prodi_id');
        $selectedTahunLulus = $request->input('tahun_lulus');
        $selectedTahunRespon = $request->input('tahun_respon');

        // --- MENGHITUNG TOTAL ALUMNI (DENOMINATOR) ---
        $alumniQuery = Alumni::query();
        if ($selectedProdiId) {
            $alumniQuery->where('prodi_id', $selectedProdiId);
        }
        if ($selectedTahunLulus) {
            $alumniQuery->where('tahun_lulus', $selectedTahunLulus);
        }
        $totalAlumni = $alumniQuery->count();

        // --- MENGHITUNG RESPONDEN (NUMERATOR) ---
        $kuesionerQuery = KuesionerAnswer::query()
            ->whereHas('alumni', function ($q) use ($selectedProdiId, $selectedTahunLulus) {
                if ($selectedProdiId) {
                    $q->where('prodi_id', $selectedProdiId);
                }
                if ($selectedTahunLulus) {
                    $q->where('tahun_lulus', $selectedTahunLulus);
                }
            });

        if ($selectedTahunRespon) {
            $kuesionerQuery->whereYear('created_at', $selectedTahunRespon);
        }
        $totalResponden = $kuesionerQuery->count();

        // --- DATA UNTUK KARTU STATISTIK ---
        $statusMapping = [
            'Bekerja' => 1, 'Wiraswasta' => 3, 'Studi Lanjut' => 4,
            'Mencari Kerja' => 5, 'Tidak Bekerja' => 2,
        ];
        $statusCounts = [];
        foreach ($statusMapping as $label => $value) {
            $countQuery = clone $kuesionerQuery;
            $statusCounts[$label] = $countQuery->where('f8', $value)->count();
        }

        // Pencegahan Division by Zero
        if ($totalAlumni === 0) {
           $totalResponden = 0;
           foreach ($statusCounts as $key => &$value) {
               $value = 0;
           }
        }


        // --- DATA GRAFIK LULUSAN 5 TAHUN TERAKHIR ---
        $tahunSekarang = date('Y');
        $tahunMulai = $tahunSekarang - 4;
        $tahunRange = range($tahunMulai, $tahunSekarang);

        $lulusanPerTahun = Alumni::query()
            ->select(DB::raw('tahun_lulus as tahun'), DB::raw('count(*) as jumlah'))
            ->where('tahun_lulus', '>=', $tahunMulai)
            ->groupBy('tahun')->orderBy('tahun', 'asc')->get()->pluck('jumlah', 'tahun');

        $dataLulusanChart = [];
        foreach ($tahunRange as $tahun) {
            $dataLulusanChart[] = $lulusanPerTahun->get($tahun, 0);
        }

        $respondenPerProdi = Prodi::withCount(['alumni as responden_count' => function ($query) {
                $query->whereHas('kuesionerAnswer');
            }])->get();

        // --- DATA WAKTU TUNGGU ---
        $waktuTungguData = (clone $kuesionerQuery)->whereNotNull('f502')->pluck('f502');
        $rataRataWaktuTunggu = $waktuTungguData->avg();
        $waktuTungguChartData = [
            $waktuTungguData->filter(fn($value) => $value < 3)->count(),
            $waktuTungguData->filter(fn($value) => $value >= 3 && $value <= 6)->count(),
            $waktuTungguData->filter(fn($value) => $value >= 7 && $value <= 12)->count(),
            $waktuTungguData->filter(fn($value) => $value > 12)->count(),
        ];

        // --- DATA UNTUK FILTER DROPDOWN ---
        $prodiList = Prodi::orderBy('nama_prodi')->get();
        $tahunLulusList = Alumni::select('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->get();
        $tahunResponList = KuesionerAnswer::select(DB::raw('YEAR(created_at) as tahun_respon'))
            ->distinct()->orderBy('tahun_respon', 'desc')->get();

        return view('admin.dashboard', compact(
            'totalAlumni', 'totalResponden', 'statusCounts',
            'prodiList', 'selectedProdiId',
            'tahunLulusList', 'selectedTahunLulus',
            'tahunResponList', 'selectedTahunRespon', // <-- Memastikan variabel ini dikirim
            'tahunRange', 'dataLulusanChart',
            'respondenPerProdi',
            'rataRataWaktuTunggu', 'waktuTungguChartData'
        ));
    }
}

