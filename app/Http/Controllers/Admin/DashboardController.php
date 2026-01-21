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
        $selectedProdiId = $request->input('prodi_id', []);
        $selectedTahunLulus = $request->input('tahun_lulus', []);
        $selectedTahunRespon = $request->input('tahun_respon', []);

        if (!is_array($selectedProdiId)) $selectedProdiId = $selectedProdiId ? [$selectedProdiId] : [];
        if (!is_array($selectedTahunLulus)) $selectedTahunLulus = $selectedTahunLulus ? [$selectedTahunLulus] : [];
        if (!is_array($selectedTahunRespon)) $selectedTahunRespon = $selectedTahunRespon ? [$selectedTahunRespon] : [];

        $allowedProdiIds = null;


        if ($user->role === 'admin_prodi') {
            // Admin Prodi: Kunci filter prodi hanya ke miliknya (sebagai array)
            $selectedProdiId = [$user->prodi_id];
            $allowedProdiIds = [$user->prodi_id];
        } 
        elseif ($user->role === 'dekan') {
            // Dekan: Ambil semua ID prodi di fakultasnya
            $allowedProdiIds = Prodi::where('fakultas_id', $user->fakultas_id)
                                    ->pluck('kode_prodi')
                                    ->toArray();
            
            // Validasi Filter User:
            // Jika user memilih prodi, ambil irisan antara pilihan user dan prodi fakultasnya.
            // Ini mencegah Dekan melihat prodi di luar fakultasnya lewat URL manipulation.
            if (!empty($selectedProdiId)) {
                $selectedProdiId = array_intersect($selectedProdiId, $allowedProdiIds);
            }
        }

        // --- MENGHITUNG TOTAL ALUMNI (DENOMINATOR) ---
        

        $alumniQuery = Alumni::query();

        // [PERUBAHAN] Filter Scope (Hak Akses) menggunakan whereIn
        if ($allowedProdiIds !== null) {
            $alumniQuery->whereIn('prodi_id', $allowedProdiIds);
        }
            
        // [PERUBAHAN] Filter Pilihan User (Multiple) menggunakan whereIn
        if (!empty($selectedProdiId)) {
            $alumniQuery->whereIn('prodi_id', $selectedProdiId);
        }
        
        // Filter NPM
        // if ($searchNpm) {
        //     $alumniQuery->where('npm', 'like', "%{$searchNpm}%");
        // }

        // Clone untuk grafik lulusan (sebelum filter tahun lulus diterapkan)
        $graphBaseQuery = (clone $alumniQuery);

        // [PERUBAHAN] Filter Tahun Lulus (Multiple) menggunakan whereIn
        if (!empty($selectedTahunLulus)) {
            $alumniQuery->whereIn('tahun_lulus', $selectedTahunLulus);
        }

        $totalAlumni = $alumniQuery->count();

        // --- QUERY RESPONDEN (NUMERATOR) ---
        $kuesionerQuery = KuesionerAnswer::query()
            ->whereHas('alumni', function ($q) use ($selectedProdiId, $selectedTahunLulus, $allowedProdiIds) {
                
                // [PERUBAHAN] Filter Hak Akses di relasi
                if ($allowedProdiIds !== null) {
                    $q->whereIn('prodi_id', $allowedProdiIds);
                }
                // [PERUBAHAN] Filter Prodi (Multiple)
                if (!empty($selectedProdiId)) {
                    $q->whereIn('prodi_id', $selectedProdiId);
                }
                // [PERUBAHAN] Filter Tahun Lulus (Multiple)
                if (!empty($selectedTahunLulus)) {
                    $q->whereIn('tahun_lulus', $selectedTahunLulus);
                }
                // if ($searchNpm) {
                //     $q->where('npm', 'like', "%{$searchNpm}%");
                // }
            });

        // [PERUBAHAN] Filter Tahun Pengisian (Multiple) dengan logika OR Loop
        // Karena whereIn tidak bisa langsung untuk Year(created_at)
        if (!empty($selectedTahunRespon)) {
            $kuesionerQuery->where(function($q) use ($selectedTahunRespon) {
                foreach($selectedTahunRespon as $year) {
                    $q->orWhereYear('created_at', $year);
                }
            });
        }
        
        $totalResponden = $kuesionerQuery->distinct('alumni_id')->count('alumni_id');

        // --- DATA UNTUK KARTU STATISTIK ---
        $statusMapping = [
            'Bekerja' => 1, 'Wiraswasta' => 3, 'Studi Lanjut' => 4,
            'Cari Kerja' => 5, 'Tidak Bekerja' => 2,
        ];
        $statusData = [];
        foreach ($statusMapping as $label => $value) {
            $count = (clone $kuesionerQuery)
                ->where('f8', $value)
                ->distinct('alumni_id')
                ->count('alumni_id') ?? 0;
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

        

        // Pencegahan Division by Zero
        if ($totalAlumni === 0) {
           $totalResponden = 0;
           foreach ($statusData as $key => &$value) {
               $value = 0;
           }
        }


        // --- DATA GRAFIK LULUSAN 5 TAHUN TERAKHIR ---

        
        // --- GRAFIK LULUSAN ---
        // [PERUBAHAN] Logika grafik lulusan menyesuaikan multiple filter tahun
        if (!empty($selectedTahunLulus)) {
            // Jika filter tahun aktif, tampilkan tahun-tahun yang dipilih saja
            $tahunRange = array_map('intval', $selectedTahunLulus);
            sort($tahunRange); 

            $lulusanPerTahun = $graphBaseQuery
                ->select(DB::raw('tahun_lulus as tahun'), DB::raw('count(*) as jumlah'))
                ->whereIn('tahun_lulus', $selectedTahunLulus) // [PERUBAHAN] whereIn
                ->groupBy('tahun')
                ->pluck('jumlah', 'tahun');
        } else {
            $tahunSekarang = date('Y');
            $tahunMulai = $tahunSekarang - 4;
            $tahunRange = range($tahunMulai, $tahunSekarang);

            $lulusanPerTahun = $graphBaseQuery
                ->select(DB::raw('tahun_lulus as tahun'), DB::raw('count(*) as jumlah'))
                ->whereBetween('tahun_lulus', [$tahunMulai, $tahunSekarang])
                ->groupBy('tahun')
                ->pluck('jumlah', 'tahun');
        }

        $dataLulusanChart = [];
        foreach ($tahunRange as $tahun) {
            $dataLulusanChart[] = $lulusanPerTahun->get($tahun, 0);
        }

        // $respondenPerProdi = Prodi::withCount(['alumni as responden_count' => function ($query) {
        //         $query->whereHas('kuesionerAnswers');
        //     }])->get();

        // --- [DEKAN/ADMIN] DATA GRAFIK PERBANDINGAN PRODI ---
        // PERBAIKAN: Menambahkan filter tahun ke dalam withCount
        $chartLabels = [];
        $chartDataTotalAlumni = [];
        $chartDataTotalResponden = [];

        if ($user->role !== 'admin_prodi') {
            $prodiQuery = Prodi::query();

            // [PERUBAHAN] Filter prodi untuk Dekan
            if ($user->role === 'dekan') {
                $prodiQuery->where('fakultas_id', $user->fakultas_id);
            }

            // [PERUBAHAN] Jika filter prodi dipilih (multiple), tampilkan hanya prodi tersebut
            if (!empty($selectedProdiId)) {
                $prodiQuery->whereIn('kode_prodi', $selectedProdiId);
            }

            $prodiData = $prodiQuery->withCount([
                // [PERUBAHAN] Closure withCount menyesuaikan multiple filter tahun
                'alumni as alumni_count' => function ($query) use ($selectedTahunLulus) {
                    if (!empty($selectedTahunLulus)) {
                        $query->whereIn('tahun_lulus', $selectedTahunLulus); // [PERUBAHAN] whereIn
                    }
                },
                // [PERUBAHAN] Closure withCount menyesuaikan multiple filter tahun respon
                'alumni as responden_count' => function ($query) use ($selectedTahunLulus, $selectedTahunRespon) {
                    if (!empty($selectedTahunLulus)) {
                        $query->whereIn('tahun_lulus', $selectedTahunLulus); // [PERUBAHAN] whereIn
                    }
                    if (!empty($selectedTahunRespon)) {
                        $query->whereHas('kuesionerAnswers', function ($q) use ($selectedTahunRespon) {
                            $q->where(function($subQ) use ($selectedTahunRespon) {
                                foreach($selectedTahunRespon as $year) {
                                    $subQ->orWhereYear('created_at', $year); // [PERUBAHAN] orWhereYear Loop
                                }
                            });
                        });
                    } else {
                        $query->whereHas('kuesionerAnswers');
                    }
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
        if ($user->role === 'dekan') {
            // Dekan hanya melihat prodi di fakultasnya
            $prodiList = Prodi::where('fakultas_id', $user->fakultas_id)->orderBy('nama_prodi')->get();
        } else {
            // Superadmin melihat semua
            $prodiList = Prodi::orderBy('nama_prodi')->get();
        }
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

