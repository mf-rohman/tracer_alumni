<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\KuesionerAnswer;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedProdiId = $request->input('prodi_id');
        $selectedTahunLulus = $request->input('tahun_lulus');
        $selectedTahunRespon = $request->input('tahun_respon');

        if ($user->role === 'admin_prodi') {
            // Paksa filter untuk hanya menggunakan prodi dari admin yang login
            $selectedProdiId = $user->prodi_id;
        }

        // --- MENGHITUNG TOTAL ALUMNI (DENOMINATOR) ---
        $alumniQuery = Alumni::query();
        if ($selectedProdiId) {
            $alumniQuery->where('prodi_id', $selectedProdiId);
        }

        $graphBaseQuery = (clone $alumniQuery);

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
        $statusData = [];
        foreach ($statusMapping as $label => $value) {
            $count = (clone $kuesionerQuery)->where('f8', $value)->count() ?? 0;
            $percentage = $totalResponden > 0 ? round(($count / $totalResponden) * 100) : 0;
            $chartData = [$count, max(0, $totalResponden - $count)];
            
            $statusData[$label] = [
                'count' => (int) $count,
                'percentage' => (int) $percentage,
                'chartData' => $chartData,
            ];
        }

        if (empty($statusData)) {
            $statusData = [
                'Bekerja' => ['count' => 0, 'percentage' => 0, 'chartData' => [0,0]],
                'Wiraswasta' => ['count' => 0, 'percentage' => 0, 'chartData' => [0,0]],
                'Studi Lanjut' => ['count' => 0, 'percentage' => 0, 'chartData' => [0,0]],
                'Mencari Kerja' => ['count' => 0, 'percentage' => 0, 'chartData' => [0,0]],
                'Tidak Bekerja' => ['count' => 0, 'percentage' => 0, 'chartData' => [0,0]],
            ];
        }

        $persentaseResponden = $totalAlumni > 0 ? round(($totalResponden / $totalAlumni) * 100) : 0;
        $chartDataResponden = [$totalResponden, max(0, $totalAlumni - $totalResponden)];

        // --- PERBAIKAN: DATA UNTUK KARTU STATISTIK DONAT ---
        // $statusBekerjaCount = (clone $kuesionerQuery)->where('f8', 1)->count();
        // $statusStudiLanjutCount = (clone $kuesionerQuery)->where('f8', 4)->count();
        // $statusWiraswastaCount = (clone $kuesionerQuery)->where('f8', 3)->count();
        // // Anda bisa menambahkan count untuk status lain di sini jika perlu

        // // Data untuk Chart 1: Responden
        // $persentaseResponden = $totalAlumni > 0 ? round(($totalResponden / $totalAlumni) * 100) : 0;
        // $chartDataResponden = [$totalResponden, max(0, $totalAlumni - $totalResponden)];
        
        // // Data untuk Chart 2: Bekerja
        // $persentaseBekerja = $totalResponden > 0 ? round(($statusBekerjaCount / $totalResponden) * 100) : 0;
        // $chartDataBekerja = [$statusBekerjaCount, max(0, $totalResponden - $statusBekerjaCount)];
        
        // // Data untuk Chart 3: Studi Lanjut
        // $persentaseStudiLanjut = $totalResponden > 0 ? round(($statusStudiLanjutCount / $totalResponden) * 100) : 0;
        // $chartDataStudiLanjut = [$statusStudiLanjutCount, max(0, $totalResponden - $statusStudiLanjutCount)];
        
        // // Data untuk Chart 3: Wiraswasta
        // $persentaseWiraswasta = $totalResponden > 0 ? round(($statusWiraswastaCount / $totalResponden) * 100) : 0;
        // $chartDataWiraswasta  = [$statusWiraswastaCount, max(0, $totalResponden - $statusWiraswastaCount)];
        // // --- SELESAI ---
        

        // Pencegahan Division by Zero
        if ($totalAlumni === 0) {
           $totalResponden = 0;
           foreach ($statusData as $key => &$value) {
               $value = 0;
           }
        }


        // --- DATA GRAFIK LULUSAN 5 TAHUN TERAKHIR ---

        if ($selectedTahunLulus) {
            $tahunRange = [(int) $selectedTahunLulus];

            $lulusanPerTahun = $graphBaseQuery
                ->select(DB::raw('tahun_lulus as tahun'), DB::raw('count(*) as jumlah'))
                ->where('tahun_lulus', $selectedTahunLulus)
                ->groupBy('tahun')
                ->pluck('jumlah', 'tahun');
        } else {
            $tahunSekarang = date('Y');
            $tahunMulai = $tahunSekarang - 4;
            $tahunRange = range($tahunMulai, $tahunSekarang);

            $lulusanPerTahun = $graphBaseQuery
                ->select(DB::raw('tahun_lulus as tahun'), DB::raw('count(*) as jumlah'))
                ->whereBetween('tahun_lulus', [$tahunMulai, $tahunSekarang])
                ->groupBy('tahun')->orderBy('tahun', 'asc')->get()->pluck('jumlah', 'tahun');
        }


        $dataLulusanChart = [];
        foreach ($tahunRange as $tahun) {
            $dataLulusanChart[] = $lulusanPerTahun->get($tahun, 0);
        }

        // $respondenPerProdi = Prodi::withCount(['alumni as responden_count' => function ($query) {
        //         $query->whereHas('kuesionerAnswers');
        //     }])->get();

        $chartLabels = [];
        $chartDataTotalAlumni = [];
        $chartDataTotalResponden = [];

        if($user->role !== 'admin_prodi') {
            $prodiData = Prodi::withCount ([
                'alumni',
                'alumni as responden_count' => function ($query) {
                    $query->whereHas('kuesionerAnswers');
                }
            ])
                ->orderBy('nama_prodi')
                ->get();

            $chartLabels = $prodiData->pluck('singkatan');
            $chartDataTotalAlumni = $prodiData->pluck('alumni_count');
            $chartDataTotalResponden = $prodiData->pluck('responden_count');

        }


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
            'totalAlumni', 'totalResponden', 
            'prodiList', 'selectedProdiId',
            'tahunLulusList', 'selectedTahunLulus',
            'tahunResponList', 'selectedTahunRespon',
            'tahunRange', 'dataLulusanChart',
            'chartLabels', 'chartDataTotalAlumni', 'chartDataTotalResponden',
            'rataRataWaktuTunggu', 'waktuTungguChartData', 'persentaseResponden', 'chartDataResponden','statusData',
        ));
    }
}

