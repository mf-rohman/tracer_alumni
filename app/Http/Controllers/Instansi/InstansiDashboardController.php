<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\PenilaianInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstansiDashboardController extends Controller
{
    /**
     * Menampilkan dasbor utama untuk instansi.
     */
    public function dashboard()
    {
        $instansi = Auth::user()->instansi;
        if (!$instansi) {
            Auth::logout();
            return redirect()->route('instansi.login.show')->with('error', 'Akun Anda tidak tertaut dengan instansi manapun.');
        }

        // Ambil semua penilaian yang diberikan oleh instansi ini
        $penilaianList = PenilaianInstansi::where('instansi_id', $instansi->id)
            ->with('alumni.prodi')
            ->get();

        // Hitung statistik untuk kartu
        $totalAlumniDinilai = $penilaianList->unique('alumni_id')->count();
        $totalPenilaianDiberikan = $penilaianList->count();
        $rataRataKeahlian = $penilaianList->map(function($item){
            $aspek = [
                'integritas', 'bahasa_inggris', 'tik', 'leadership', 'komunikasi',
                'kerjasama_tim', 'pengembangan_diri', 'kedisiplinan', 'kejujuran',
                'motivasi_kerja', 'etos_kerja', 'inovasi', 'problem_solving',
                'wawasan_antar_bidang'
            ];

            
            return collect($aspek)
                ->map(fn($col)=> $item->$col)
                ->filter()
                ->avg()?? 0;
        })->avg() ?? 0;

        // Siapkan data untuk Chart (contoh: distribusi Kinerja Keseluruhan)
        $kinerjaCounts = $penilaianList->groupBy('kinerja_keseluruhan')->map->count();
        $chartLabels = $kinerjaCounts->keys()->values()->toArray();
        $chartData = $kinerjaCounts->values()->toArray();

        // dd( $chartLabels,$chartData);
        // dd($penilaianList->pluck('kinerja_keseluruhan'));
        return view('instansi.dashboard', compact(
            'instansi', 'totalAlumniDinilai', 'totalPenilaianDiberikan',
            'rataRataKeahlian', 'chartLabels', 'chartData'
        ));
    }

    /**
     * BARU: Metode ini khusus untuk menampilkan halaman "Data Alumni".
     */
    public function dataAlumni()
    {
        $instansi = Auth::user()->instansi;
        
        // Cari alumni yang tercatat bekerja di instansi ini
        $alumniList = Alumni::whereHas('kuesionerAnswers', function ($query) use ($instansi) {
            $query->where('f5b', $instansi->nama);
        })
        ->with('penilaianInstansi.penilai')
        ->latest()->paginate(10);

        return view('instansi.data-alumni', compact('instansi', 'alumniList'));
    }

    /**
     * Menampilkan form untuk penilaian baru.
     */
    public function showPenilaianForm(Alumni $alumnus)
    {
        // PERBAIKAN: Selalu definisikan dan kirim variabel $penilaian,
        // meskipun nilainya null untuk form baru.
        $penilaian = null;
        return view('instansi.penilaian', compact('alumnus', 'penilaian'));
    }

    public function editPenilaianForm(PenilaianInstansi $penilaian)
    {
        // Keamanan: Pastikan instansi ini hanya bisa mengedit penilaian mereka sendiri
        if ($penilaian->instansi_id !== Auth::user()->instansi_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $penilaian->load('alumni');
        $alumnus = $penilaian->alumni;
        
        return view('instansi.penilaian', compact('alumnus', 'penilaian'));
    }

    /**
     * Menyimpan data penilaian yang baru.
     */
    public function storePenilaian(Request $request, Alumni $alumnus)
    {

        $validatedData = $request->validate([
            'nama_penilai' => 'required|string|max:255',
            'no_hp_penilai' => 'required|string|max:20',
            'email_penilai' => 'nullable|email|max:255',
            'jabatan_penilai' => 'required|string|max:255',
            'website_instansi' => 'nullable|url|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'bidang_usaha_lainnya' => 'nullable|required_if:bidang_usaha,Other|string|max:255',

            'integritas' => 'required|integer|between:1,4',
            'bahasa_inggris' => 'required|integer|between:1,4',
            'tik' => 'required|integer|between:1,4',
            'leadership' => 'required|integer|between:1,4',
            'komunikasi' => 'required|integer|between:1,4',
            'kerjasama_tim' => 'required|integer|between:1,4',
            'pengembangan_diri' => 'required|integer|between:1,4',
            'kedisiplinan' => 'required|integer|between:1,4',
            'kejujuran' => 'required|integer|between:1,4',
            'motivasi_kerja' => 'required|integer|between:1,4',
            'etos_kerja' => 'required|integer|between:1,4',
            'inovasi' => 'required|integer|between:1,4',
            'problem_solving' => 'required|integer|between:1,4',
            'wawasan_antar_bidang' => 'required|integer|between:1,4',
            'kinerja_keseluruhan' => 'required|string|in:Sangat baik,Baik,Cukup baik,Buruk',

            'bidang_pekerjaan_ditekuni' => 'nullable|string',
            'posisi_dicapai' => 'nullable|string',
            'kesesuaian_ilmu' => 'required|string|in:Sudah,Belum',
            'ilmu_tambahan_sesuai' => 'nullable|required_if:kesesuaian_ilmu,Sudah|string',
            'ilmu_diperlukan_belum_sesuai' => 'nullable|required_if:kesesuaian_ilmu,Belum|string',
        ]);

        $penilaian = PenilaianInstansi::create(array_merge(
            $validatedData,
            [
                'alumni_id' => $alumnus->id,
                'instansi_id' => Auth::user()->instansi_id,
                'penilai_user_id' => Auth::id(),
            ]
        ));

        $aspek = [
            'integritas', 'bahasa_inggris', 'tik', 'leadership', 'komunikasi',
            'kerjasama_tim', 'pengembangan_diri', 'kedisiplinan', 'kejujuran',
            'motivasi_kerja', 'etos_kerja', 'inovasi', 'problem_solving',
            'wawasan_antar_bidang'
        ];

        $avg_score = collect($aspek)
            ->map(fn($col) => $penilaian->$col)
            ->filter()
            ->avg();

        $penilaian->alumni->update([
            'skor_penilaian' => round($avg_score, 2),
        ]);

        return redirect()->route('instansi.dashboard')->with('success', 'Penilaian baru untuk ' . $alumnus->nama_lengkap . ' berhasil disimpan.');
    }

    public function updatePenilaian(Request $request, PenilaianInstansi $penilaian)
    {
        
        if ($penilaian->instansi_id !== Auth::user()->instansi_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validatedData = $request->validate([
            'nama_penilai' => 'required|string|max:255',
            'no_hp_penilai' => 'required|string|max:20',
            'email_penilai' => 'nullable|email|max:255',
            'jabatan_penilai' => 'required|string|max:255',
            'website_instansi' => 'nullable|url|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'bidang_usaha_lainnya' => 'nullable|required_if:bidang_usaha,Other|string|max:255',
            'integritas' => 'required|integer|between:1,4',
            'bahasa_inggris' => 'required|integer|between:1,4',
            'tik' => 'required|integer|between:1,4',
            'leadership' => 'required|integer|between:1,4',
            'komunikasi' => 'required|integer|between:1,4',
            'kerjasama_tim' => 'required|integer|between:1,4',
            'pengembangan_diri' => 'required|integer|between:1,4',
            'kedisiplinan' => 'required|integer|between:1,4',
            'kejujuran' => 'required|integer|between:1,4',
            'motivasi_kerja' => 'required|integer|between:1,4',
            'etos_kerja' => 'required|integer|between:1,4',
            'inovasi' => 'required|integer|between:1,4',
            'problem_solving' => 'required|integer|between:1,4',
            'wawasan_antar_bidang' => 'required|integer|between:1,4',
            'kinerja_keseluruhan' => 'required|string|in:Sangat baik,Baik,Cukup baik,Buruk',
            'bidang_pekerjaan_ditekuni' => 'nullable|string',
            'posisi_dicapai' => 'nullable|string',
            'kesesuaian_ilmu' => 'required|string|in:Sudah,Belum',
            'ilmu_tambahan_sesuai' => 'nullable|required_if:kesesuaian_ilmu,Sudah|string',
            'ilmu_diperlukan_belum_sesuai' => 'nullable|required_if:kesesuaian_ilmu,Belum|string',
        ]);

        $penilaian->update($validatedData,);

        $aspek = [
            'integritas', 'bahasa_inggris', 'tik', 'leadership', 'komunikasi',
            'kerjasama_tim', 'pengembangan_diri', 'kedisiplinan', 'kejujuran',
            'motivasi_kerja', 'etos_kerja', 'inovasi', 'problem_solving',
            'wawasan_antar_bidang'
        ];

        $avg_score = collect($aspek)
            ->map(fn($col) => $penilaian->$col)
            ->filter()
            ->avg();

        $penilaian->alumni->update([
            'skor_penilaian' => round($avg_score, 2),
        ]);

        return redirect()->route('instansi.data_alumni')->with('success', 'Penilaian untuk ' . $penilaian->alumni->nama_lengkap . ' berhasil diperbarui.');
    }
}

