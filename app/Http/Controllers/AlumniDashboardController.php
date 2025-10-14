<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KuesionerAnswer;
use App\Models\Alumni;
use App\Models\Pengaturan; 
use App\Models\Province;
use Illuminate\Support\Arr;  

class AlumniDashboardController extends Controller
{

    public function index($tahun = null)
    {
        $alumni = Auth::user()->alumni;
        if (!$alumni) {
            Auth::logout();
            return redirect()->route('/')->with('error', 'Data alumni tidak ditemukan.');
        }

        $listKuesioner = $this->generateKuesionerList($alumni);
        
        $latestAnswer = $alumni->kuesionerAnswers()->orderBy('tahun_kuesioner', 'desc')->first();
        $tahunKuesioner = $tahun ?? ($latestAnswer->tahun_kuesioner ?? $alumni->tahun_lulus);
        $answer = $alumni->kuesionerAnswers()->where('tahun_kuesioner', $tahunKuesioner)->firstOrNew(['tahun_kuesioner' => $tahunKuesioner]);

        // Logika untuk menampilkan dialog konfirmasi salin jawaban
        $showCopyModal = false;
        $previousYear = $tahunKuesioner - 1;
        if (!$answer->exists && $alumni->kuesionerAnswers()->where('tahun_kuesioner', $previousYear)->exists()) {
            $showCopyModal = true;
        }

        // Logika untuk menonaktifkan form
        $kuesionerInfo = collect($listKuesioner)->firstWhere('tahun', $tahunKuesioner);
        $isFormDisabled = $kuesionerInfo['status'] === 'terkunci';
        $pesanError = null;
        if($isFormDisabled && !$answer->exists) {
            $pesanError = $kuesionerInfo['lock_message'] ?? "Formulir ini tidak dapat diisi saat ini.";
        }

        $provinces = Province::orderBy('name')->get();

        return view('alumni.dashboard', compact('alumni', 'answer', 'listKuesioner', 'tahunKuesioner', 'isFormDisabled', 'pesanError', 'showCopyModal', 'previousYear', 'provinces'));
    }

    /**
     * PERUBAHAN: Metode store sekarang menerima parameter tahun.
     */
    public function store(Request $request, $tahun)
    {
        $alumni = Auth::user()->alumni;
        $data = $request->except('_token');

        // Logika checkbox f4 Anda yang sudah benar
        $f4_choices = $request->input('f4', []);
        for ($i = 1; $i <= 15; $i++) {
            $data['f4' . str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
        }
        foreach ($f4_choices as $choice) {
            if (array_key_exists($choice, $data)) {
                $data[$choice] = 1;
            }
        }
        unset($data['f4']);

        // Logika checkbox f16 Anda yang sudah benar
        $f16_choices = $request->input('f16', []);
        for ($i = 1; $i <= 13; $i++) {
            $data['f16' . str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
        }
        foreach ($f16_choices as $choice) {
            if (array_key_exists($choice, $data)) {
                $data[$choice] = 1;
            }
        }
        unset($data['f16']);

        // PERUBAHAN: updateOrCreate sekarang menggunakan 'tahun_kuesioner'
        KuesionerAnswer::updateOrCreate(
            ['alumni_id' => $alumni->id, 'tahun_kuesioner' => $tahun],
            $data
        );

        if ($alumni->status_kuesioner !== 'selesai') {
            $alumni->status_kuesioner = 'selesai';
            $alumni->save();
        }

        // PERUBAHAN: Redirect kembali ke halaman kuesioner tahun yang sama
        return redirect()->route('dashboard', ['tahun' => $tahun])->with('success', 'Terima kasih, kuesioner Anda berhasil disimpan!');
    }

    /**
     * TAMBAHAN BARU: Metode ini menangani permintaan AJAX untuk menyalin jawaban.
     */
    public function copyAnswers(Request $request)
    {
        $request->validate([
            'source_year' => 'required|integer',
            'target_year' => 'required|integer',
        ]);

        $alumni = Auth::user()->alumni;
        $sourceYear = $request->source_year;
        $targetYear = $request->target_year;

        $sourceAnswer = $alumni->kuesionerAnswers()->where('tahun_kuesioner', $sourceYear)->first();
        if (!$sourceAnswer) {
            return response()->json(['success' => false, 'message' => 'Data sumber tidak ditemukan.'], 404);
        }

        $newData = Arr::except(
            $sourceAnswer->getAttributes(),
            ['id', 'created_at', 'updated_at', 'status_verifikasi']
        );
        $newData['tahun_kuesioner'] = $targetYear;

        $newAnswer = KuesionerAnswer::updateOrCreate(
            ['alumni_id' => $alumni->id, 'tahun_kuesioner' => $targetYear],
            $newData
        );

        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil disalin!',
            'target_year' => $targetYear
        ]); 
    }

    /**
     * TAMBAHAN BARU: Helper function untuk menghasilkan daftar tahun kuesioner.
     */
    private function generateKuesionerList($alumni)
    {
        $tahunLulus = (int) $alumni->tahun_lulus;
        $tahunSekarang = (int) date('Y');
        $tahunAktifAdmin = (int) (Pengaturan::where('key', 'tahun_kuesioner_aktif')->first()->value ?? $tahunSekarang);
        $list = [];
        $isTahunSebelumnyaTerisi = true;

        for ($i = 0; $i < 5; $i++) {
            $tahunIterasi = $tahunLulus + $i;
            // if ($tahunIterasi > $tahunSekarang + 1) break;

            $jawaban = $alumni->kuesionerAnswers()->where('tahun_kuesioner', $tahunIterasi)->first();
            $status = 'terkunci';
            $lockMessage = '';

            if ($isTahunSebelumnyaTerisi) {
                if ($jawaban) {
                    $status = 'terisi';
                } elseif ($tahunIterasi <= $tahunAktifAdmin) {
                    $status = 'tersedia';
                } else {
                    $lockMessage = "Kuesioner untuk tahun {$tahunIterasi} belum dibuka oleh admin.";
                }
            } else {
                 $tahunSebelumnya = $tahunIterasi - 1;
                 $lockMessage = "Anda harus mengisi kuesioner tahun {$tahunSebelumnya} terlebih dahulu.";
            }
            
            $list[] = [
                'tahun' => $tahunIterasi, 
                'status' => $status,
                'lock_message' => $lockMessage
            ];

            if (!$jawaban) $isTahunSebelumnyaTerisi = false;
        }
        return $list;
    }
}

