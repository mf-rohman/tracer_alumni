<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\KuesionerAnswer;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RespondenController extends Controller
{
    /**
     * Menampilkan halaman data responden dengan filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Alumni::query()->with(['user', 'prodi', 'kuesionerAnswers']);

        // Menerapkan filter berdasarkan input
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->tahun_lulus);
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
        $prodiList = ($user->role == 'admin_prodi')
            ? Prodi::where('id', $user->prodi_id)->get()
            : Prodi::orderBy('nama_prodi')->get();
            
        $tahunLulusList = Alumni::select('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->get();
        $tahunResponList = KuesionerAnswer::select(DB::raw('YEAR(created_at) as tahun_respon'))
            ->distinct()->orderBy('tahun_respon', 'desc')->get();

        return view('admin.responden.index', compact('alumni', 'prodiList', 'tahunLulusList', 'tahunResponList'));
    }
}
