<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        return view('welcome');
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
            return redirect()->intended(route('dashboard'));
        }

        // Jika tidak cocok, kembalikan ke halaman verifikasi dengan pesan error
        return back()->with('error', 'NIK tidak sesuai. Silakan coba lagi.');
    }
}

