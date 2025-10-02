<?php
namespace App\Http\Controllers;
use App\Models\KuesionerAnswer;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AlumniDashboardController extends Controller
{
    public function index($tahun = null)
    {
        $alumni = Auth::user()->alumni;
        if (!$alumni) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data alumni tidak ditemukan.');
        }

        $tahunLulus = (int) $alumni->tahun_lulus;
        $tahunSekarang = (int) date('Y');
        
        // Tentukan tahun kuesioner yang sedang dilihat. Defaultnya adalah tahun lulus.
        $tahunKuesioner = $tahun ? (int) $tahun : $tahunLulus;

        // Ambil tahun kuesioner yang aktif dari pengaturan admin
        $tahunAktifAdmin = Pengaturan::where('key', 'tahun_kuesioner_aktif')->first()->value ?? $tahunSekarang;
        $tahunAktifAdmin = (int) $tahunAktifAdmin;

        // Bangun daftar kuesioner untuk 5 tahun
        $listKuesioner = [];
        $isTahunSebelumnyaTerisi = true; // Anggap tahun sebelum tahun lulus sudah "terisi"
        
        // PERBAIKAN: Logika perulangan untuk 5 tahun
        for ($i = 0; $i < 5; $i++) {
            $tahunIterasi = $tahunLulus + $i;
            
            // Jangan tampilkan tahun yang belum saatnya (lebih dari 1 tahun di masa depan)
            if ($tahunIterasi > $tahunSekarang + 4) break; 

            $jawaban = $alumni->kuesionerAnswers()->where('tahun_kuesioner', $tahunIterasi)->first();
            $status = 'terkunci'; // Status default
            $lockMessage = '';

            if ($isTahunSebelumnyaTerisi) {
                if ($jawaban) {
                    $status = 'terisi'; // Sudah diisi
                } elseif ($tahunIterasi == $tahunAktifAdmin) {
                    $status = 'tersedia'; // Belum diisi dan tahunnya aktif
                } else {
                    $lockMessage = "Kuesioner untuk tahun {$tahunIterasi} belum dibuka oleh admin.";
                }
            } else {
                $tahunSebelumnya = $tahunIterasi - 1;
                $lockMessage = "Anda harus mengisi kuesioner tahun {$tahunSebelumnya} terlebih dahulu.";
            }
            
            $listKuesioner[] = [
                'tahun' => $tahunIterasi,
                'status' => $status,
                'is_active' => $tahunIterasi == $tahunKuesioner,
                'lock_message' => $lockMessage,
            ];
            
            // Logika kunci: Jika tahun ini belum terisi, maka tahun berikutnya terkunci
            if (!$jawaban) {
                $isTahunSebelumnyaTerisi = false;
            }
        }

        // Ambil data jawaban untuk tahun yang dipilih
        $answer = $alumni->kuesionerAnswers()->where('tahun_kuesioner', $tahunKuesioner)->firstOrNew(['tahun_kuesioner' => $tahunKuesioner]);
        
        // Tentukan apakah form boleh diisi atau tidak
        $isFormDisabled = collect($listKuesioner)->firstWhere('tahun', $tahunKuesioner)['status'] !== 'tersedia';
        $pesanError = null;
        if ($isFormDisabled && !$answer->exists) {
            if ($tahunKuesioner != $tahunAktifAdmin) {
                $pesanError = "Kuesioner untuk tahun $tahunKuesioner belum dibuka oleh admin.";
            } else {
                $tahunSebelumnya = $tahunKuesioner - 1;
                // Hanya tampilkan pesan ini jika bukan tahun pertama
                if ($tahunKuesioner > $tahunLulus) {
                    $pesanError = "Anda harus mengisi kuesioner tahun $tahunSebelumnya terlebih dahulu.";
                }
            }
        }
        
        return view('alumni.dashboard', compact('alumni', 'answer', 'listKuesioner', 'tahunKuesioner', 'isFormDisabled', 'pesanError'));
    }

    public function store(Request $request, $tahun)
    {
        $alumni = Auth::user()->alumni;
        
        $data = $request->except('_token');

        // Proses data checkbox 'f4'
        $f4_choices = $request->input('f4', []);
        for ($i = 1; $i <= 15; $i++) {
            $data['f4' . str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
        }
        foreach ($f4_choices as $choice) {
            // Pastikan key ada sebelum di-set
            if (str_starts_with($choice, 'f4')) {
                $data[$choice] = 1;
            }
        }
        unset($data['f4']);

        // Proses data checkbox 'f16'
        $f16_choices = $request->input('f16', []);
        for ($i = 1; $i <= 13; $i++) {
             $data['f16' . str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
        }
        foreach ($f16_choices as $choice) {
             if (str_starts_with($choice, 'f16')) {
                $data[$choice] = 1;
            }
        }
        unset($data['f16']);

        KuesionerAnswer::updateOrCreate(
            ['alumni_id' => $alumni->id, 'tahun_kuesioner' => $tahun],
            $data
        );

        if ($alumni->status_kuesioner !== 'selesai') {
            $alumni->status_kuesioner = 'selesai';
            $alumni->save();
        }

        return redirect()->route('dashboard', ['tahun' => $tahun])->with('success', "Kuesioner tahun $tahun berhasil disimpan!");
    }
}

