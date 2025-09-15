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
        $alumniId = Auth::user()->alumni->id;

        $data = $request->except('_token');

        KuesionerAnswer::updateOrCreate(
            ['alumni_id' => $alumniId],
            $data
        );

        return redirect()->route('dashboard')->with('success', 'Terima kasih, kuesioner Anda berhasil disimpan!');
    }
}
