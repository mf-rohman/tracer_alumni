<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumni;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;

class AlumniCategoryController extends Controller
{
    /**
     * Menampilkan halaman kategori alumni dengan filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Mulai query builder untuk alumni
        $alumniQuery = Alumni::with('prodi');

        // Ambil data prodi HANYA jika bukan admin prodi
        $prodi = collect(); // Defaultnya koleksi kosong
        if ($user->role !== 'admin_prodi') {
            $prodi = Prodi::orderBy('nama_prodi')->get();
            // Terapkan filter prodi dari form jika ada
            if ($request->filled('prodi_id')) {
                $alumniQuery->where('prodi_id', $request->prodi_id);
            }
        } else {
            // Jika admin prodi, langsung filter berdasarkan prodinya
            $alumniQuery->where('prodi_id', $user->prodi_id);
        }

        // Terapkan filter tahun lulus jika ada input dari form
        if ($request->filled('tahun_lulus')) {
            $alumniQuery->where('tahun_lulus', $request->tahun_lulus);
        }

        // Ambil tahun lulus yang unik SETELAH prodi difilter
        $tahunLulusQuery = clone $alumniQuery;
        $tahunLulus = $tahunLulusQuery->select('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->pluck('tahun_lulus');

        // Ambil hasil query alumni
        $alumni = $alumniQuery->latest()->paginate(10);

        // Kirim semua data yang dibutuhkan ke view
        return view('admin.alumni.kategori', [
            'alumni' => $alumni,
            'prodi' => $prodi, // Akan kosong jika admin prodi
            'tahunLulus' => $tahunLulus,
            'selectedProdi' => $request->prodi_id,
            'selectedTahun' => $request->tahun_lulus,
        ]);
    }
}
