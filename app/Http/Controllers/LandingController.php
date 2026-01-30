<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\KuesionerAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil data statistik untuk ditampilkan
        $totalAlumni = Alumni::count();
        $totalResponden = KuesionerAnswer::count();
        
        // Menghitung jumlah alumni yang sudah bekerja dari kuesioner yang terisi
        $totalBekerja = KuesionerAnswer::whereIn('f8', [1, 3]) // Ambil Bekerja (1) & Wiraswasta (3)
            ->distinct('alumni_id') // [PENTING] Hitung orang unik, bukan total formulir
            ->count('alumni_id');

        
        $testimonials = KuesionerAnswer::with('alumni') // Eager load relasi alumni
            ->whereNotNull('testimoni_alumni')          // Hanya yang tidak NULL
            ->where('testimoni_alumni', '!=', '')       // Hanya yang tidak kosong
            ->where('testimoni_alumni', '!=', '-')      // Filter isian strip "-"
            ->latest('tahun_kuesioner')                 // Ambil tahun terbaru
            ->inRandomOrder()                           // Acak urutannya agar tidak bosan
            ->limit(6)                                  // Batasi 6 testimoni saja
            ->get();

        // Kirim data statistik ke view
        return view('welcome', compact('totalAlumni', 'totalResponden', 'totalBekerja', 'testimonials'));
    }

    public function getPageData()
    {
        // Menggunakan cache untuk performa. Data akan di-refresh setiap 60 menit.
        // Anda bisa sesuaikan durasinya.
        $data = Cache::remember('landing_page_data', 60 * 60, function () {
            
            // --- Query Statistik ---
            $totalAlumni = Alumni::count();
            $filledQuestionnaire = Alumni::where('status_kuesioner', 'selesai')->count(); // Asumsi kolom & value

            // Query untuk distribusi industri. 
            // Mengelompokkan berdasarkan kolom 'industri' dan menghitung jumlahnya.
            $industryDistribution = Alumni::select('industri', DB::raw('count(*) as total'))
                ->whereNotNull('industri')
                ->where('industri', '!=', '')
                ->groupBy('industri')
                ->orderBy('total', 'desc')
                ->limit(5) // Ambil 5 industri teratas
                ->pluck('total', 'industri'); // Hasilnya: ['IT' => 150, 'Pendidikan' => 120, ...]

            // --- Query Testimoni ---
            // Ambil 3 testimoni yang ditandai sebagai 'featured' (terpilih).
            // Ini lebih baik daripada acak, karena Anda bisa mengontrol testimoni mana yang tampil.
            // Anda perlu menambahkan kolom boolean 'is_featured' di tabel alumni Anda.
            $testimonials = Alumni::whereNotNull('testimoni')
                ->where('is_featured', true)
                ->limit(3)
                ->get(['nama_lengkap', 'jabatan', 'photo_path', 'testimoni']);

            return [
                'statistics' => [
                    'total_alumni' => $totalAlumni,
                    'filled_questionnaire' => $filledQuestionnaire,
                    'industry_distribution' => $industryDistribution,
                ],
                'testimonials' => $testimonials,
            ];
        });

        return response()->json($data);
    }

    /**
     * Memeriksa NPM dan mengarahkan ke halaman verifikasi NIK.
     */
    public function cekNpm(Request $request)
    {
        $request->validate(['npm' => 'required|string']);

        $alumni = Alumni::where('npm', $request->npm)->first();

        if ($alumni) {
            // Jika NPM ditemukan, arahkan ke halaman verifikasi NIK
            return redirect()->route('alumni.login.show_verify', ['npm' => $request->npm]);
        }

        return back()->with('error', 'NPM tidak ditemukan atau tidak terdaftar.');
    }

    /**
     * Menampilkan form verifikasi NIK.
     */
    public function showVerifyForm($npm)
    {
        // Pastikan alumni dengan NPM ini ada sebelum menampilkan form
        if (!Alumni::where('npm', $npm)->exists()) {
            return redirect()->route('landing')->with('error', 'NPM tidak valid.');
        }
        return view('auth.verify-nik', ['npm' => $npm]);
    }

    /**
     * Memverifikasi NPM dan NIK, lalu melakukan login.
     */
    public function verifyLogin(Request $request)
    {
        $request->validate([
            'npm' => 'required|string|exists:alumni,npm',
            'nik' => 'required|string',
        ]);

        $alumni = Alumni::where('npm', $request->npm)
                        ->where('nik', $request->nik)
                        ->first();
        
        if ($alumni) {
            Auth::login($alumni->user);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard', ['tahun' => $alumni->tahun_lulus]));
        }

        // Jika tidak cocok, kembalikan ke halaman verifikasi dengan pesan error
        return back()->with('error', 'NIK tidak sesuai. Silakan coba lagi.');
    }
}

