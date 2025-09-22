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
    public function index()
    {
        $instansi = Auth::user()->instansi;

        if (!$instansi) {
            Auth::logout();
            return redirect()->route('instansi.login.show')->with('error', 'Akun Anda tidak tertaut dengan instansi manapun.');
        }

        $alumniList = Alumni::whereHas('kuesionerAnswer', function ($query) use ($instansi) {
            $query->where('f5b', $instansi->nama);
        })
        ->with('penilaianInstansi.penilai')
        ->latest()->paginate(10);

        return view('instansi.dashboard', compact('instansi', 'alumniList'));
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

    /**
     * Menyimpan data penilaian yang baru.
     */
    public function storePenilaian(Request $request, Alumni $alumnus)
    {
        // Validasi data dari semua section
        $validatedData = $request->validate([
            // Section 1
            'nama_penilai' => 'required|string|max:255',
            'no_hp_penilai' => 'required|string|max:20',
            'email_penilai' => 'nullable|email|max:255',
            'jabatan_penilai' => 'required|string|max:255',
            'website_instansi' => 'nullable|url|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'bidang_usaha_lainnya' => 'nullable|required_if:bidang_usaha,Other|string|max:255',

            // Section 2
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

            // Section 3
            'bidang_pekerjaan_ditekuni' => 'nullable|string',
            'posisi_dicapai' => 'nullable|string',
            'kesesuaian_ilmu' => 'required|string|in:Sudah,Belum',
            'ilmu_tambahan_sesuai' => 'nullable|required_if:kesesuaian_ilmu,Sudah|string',
            'ilmu_diperlukan_belum_sesuai' => 'nullable|required_if:kesesuaian_ilmu,Belum|string',
        ]);

        // Selalu buat data penilaian baru
        PenilaianInstansi::create(array_merge(
            $validatedData,
            [
                'alumni_id' => $alumnus->id,
                'instansi_id' => Auth::user()->instansi_id,
                'penilai_user_id' => Auth::id(),
            ]
        ));

        return redirect()->route('instansi.dashboard')->with('success', 'Penilaian baru untuk ' . $alumnus->nama_lengkap . ' berhasil disimpan.');
    }
}

