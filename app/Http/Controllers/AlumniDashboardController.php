<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TracerAlumni;

class AlumniDashboardController extends Controller
{
    /**
     * Display the alumni dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // PENGECEKAN PENTING: Jika yang login bukan alumni, alihkan ke dashboard admin.
        if ($user->role !== 'alumni') {
            return redirect()->route('admin.dashboard');
        }

        // Ambil data alumni yang sedang login
        $alumni = $user->alumni;

        // Jika karena suatu hal data alumni tidak ada (meskipun rolenya alumni)
        if (!$alumni) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data alumni tidak ditemukan untuk akun Anda.');
        }

        // Ambil data tracer study yang sudah ada untuk alumni ini
        $tracerData = TracerAlumni::where('alumni_id', $alumni->id)
                                 ->pluck('status_kegiatan', 'tahun');

        return view('alumni.dashboard', compact('alumni', 'tracerData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kegiatan.*.status' => 'required|string',
            'kegiatan.*.deskripsi' => 'nullable|string',
        ]);

        $alumniId = Auth::user()->alumni->id;

        foreach ($request->kegiatan as $tahun => $data) {
            TracerAlumni::updateOrCreate(
                ['alumni_id' => $alumniId, 'tahun' => $tahun],
                ['status_kegiatan' => $data['status'], 'deskripsi_kegiatan' => $data['deskripsi']]
            );
        }

        return redirect()->route('dashboard')->with('success', 'Data kegiatan berhasil disimpan!');
    }
}
