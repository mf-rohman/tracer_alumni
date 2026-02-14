<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\KuesionerAnswer;
use App\Models\PenilaianInstans;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\RespondenExport;
use Maatwebsite\Excel\Facades\Excel;

class RespondenController extends Controller
{
    /**
     * Menampilkan halaman data responden dengan filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Alumni::query()->with(['user', 'prodi', 'kuesionerAnswers', 'penilaianInstansi']);

        if ($user->role == 'dekan') {
            // [DEKAN] Hanya ambil alumni yang prodinya ada di fakultas user ini
            // Asumsi: Tabel prodi punya kolom 'fakultas_id'
            $prodiIds = Prodi::where('fakultas_id', $user->fakultas_id)->pluck('kode_prodi');
            $query->whereIn('prodi_id', $prodiIds);
        }
        elseif ($user->role == 'admin_prodi') {
            // [ADMIN PRODI] Hanya ambil alumni dari prodinya sendiri
            $query->where('prodi_id', $user->prodi_id);
        }

        // Menerapkan filter berdasarkan input
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->tahun_lulus);
        }

        if ($request->filled('npm')) {
            $searchNpm = $request->npm;
            $query->where('npm', 'like', "%{$searchNpm}%");
        }

        if ($request->filled('tahun_respon')) {
            $query->whereHas('kuesionerAnswers', function ($q) use ($request) {
                $q->whereYear('created_at', $request->tahun_respon);
            });
        }

        if ($request->filled('status_pengisian')) {
            if ($request->status_pengisian == 'sudah') {
                $query->whereHas('kuesionerAnswers');
            } elseif ($request->status_pengisian == 'belum') {
                $query->whereDoesntHave('kuesionerAnswers');
            }
        }
        
        // Filter Wajib untuk Admin Prodi
        if ($user->role == 'admin_prodi') {
            $query->where('prodi_id', $user->prodi_id);
        }

        // Ambil data dengan pagination
        $alumni = $query->latest()->paginate(15)->withQueryString();

        // Data untuk dropdown filter
        if ($user->role == 'dekan') {
            // Dekan: Dropdown hanya prodi di fakultasnya
            $prodiList = Prodi::where('fakultas_id', $user->fakultas_id)->orderBy('nama_prodi')->get();
        } 
        elseif ($user->role == 'admin_prodi') {
            // Admin Prodi: Dropdown hanya 1 prodi
            $prodiList = Prodi::where('kode_prodi', $user->prodi_id)->get();
        } 
        else {
            // Superadmin: Semua prodi
            $prodiList = Prodi::orderBy('nama_prodi')->get();
        }
        
        $tahunLulusList = Alumni::select('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->get();
        $tahunResponList = KuesionerAnswer::select(DB::raw('YEAR(created_at) as tahun_respon'))
            ->distinct()->orderBy('tahun_respon', 'desc')->get();
        $npmList = Alumni::select('npm')->distinct()->orderBy('npm', 'desc')->get();

        return view('admin.responden.index', compact('alumni', 'prodiList', 'tahunLulusList', 'tahunResponList', 'npmList'));
    }

    public function exportExcel()
    {
        // Menggunakan library Maatwebsite untuk mendownload
        return Excel::download(new RespondenExport, 'Data_Responden_Tracer_Study.xlsx');
    }
}
