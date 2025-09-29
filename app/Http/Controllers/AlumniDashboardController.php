<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KuesionerAnswer;
use App\Models\Alumni;

class AlumniDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'alumni') {
            return redirect()->route('admin.dashboard');
        }

        $alumni = $user->alumni;

        if (!$alumni) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data alumni tidak ditemukan untuk akun Anda.');
        }

        // LOGIKA YANG DIPERBAIKI:
        // Gunakan firstOrNew. Ini akan mencari jawaban yang ada.
        // Jika tidak ada, ia akan membuat instance model baru yang kosong, bukan null.
        $answer = KuesionerAnswer::firstOrNew(['alumni_id' => $alumni->id]);

        return view('alumni.dashboard', compact('alumni', 'answer'));
    }

    public function store(Request $request)
    {
        $alumni = Auth::user()->alumni; // Kita ambil objek alumni di awal
        $data = $request->except('_token');

        $f4_choices = $request->input('f4', []);

        for ($i = 1; $i <= 15; $i++) {
            $data['f4' . str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
        }

        foreach ($f4_choices as $choices) {
            if (array_key_exists($choices, $data)) {
                $data[$choices] = 1;
            }
        }

        unset($data['f4']);

        $f16_choices = $request->input('f16', []);

        for ($i = 1; $i <= 13; $i++) {
            $data['f16' . str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
        }

        foreach ($f16_choices as $choices) {
            if (array_key_exists($choices, $data)) {
                $data[$choices] = 1;
            }
        }

        unset($data['f16']);

        KuesionerAnswer::updateOrCreate(
            ['alumni_id' => $alumni->id],
            $data
        );

        // ===================================================================
        // BARU: UPDATE STATUS ALUMNI SETELAH KUESIONER DISIMPAN
        // ===================================================================
        // Cek dulu untuk efisiensi, agar tidak update database jika tidak perlu
        if ($alumni->status_kuesioner !== 'selesai') {
            $alumni->status_kuesioner = 'selesai';
            $alumni->save();
        }
        // ===================================================================

        return redirect()->route('dashboard')->with('success', 'Terima kasih, kuesioner Anda berhasil disimpan!');
    }
}
