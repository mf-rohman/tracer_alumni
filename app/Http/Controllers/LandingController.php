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
     * Memeriksa NPM dan mengarahkan ke halaman verifikasi.
     */
    public function cekNpm(Request $request)
    {
        $request->validate(['npm' => 'required|string']);

        $alumni = Alumni::where('npm', $request->npm)->first();

        if ($alumni) {
            // Jika NPM ditemukan, arahkan ke halaman verifikasi tanggal lahir
            return redirect()->route('alumni.login.show_verify', ['npm' => $request->npm]);
        }

        return back()->with('error', 'NPM tidak ditemukan atau tidak terdaftar.');
    }

    /**
     * Menampilkan form verifikasi tanggal lahir.
     */
    public function showVerifyForm($npm)
    {
        // Pastikan alumni dengan NPM ini ada sebelum menampilkan form
        if (!Alumni::where('npm', $npm)->exists()) {
            return redirect()->route('landing')->with('error', 'NPM tidak valid.');
        }
        return view('auth.verify-dob', ['npm' => $npm]);
    }

    /**
     * Memverifikasi NPM dan Tanggal Lahir, lalu melakukan login.
     */
    public function verifyLogin(Request $request)
    {
        $request->validate([
            'npm' => 'required|string|exists:alumni,npm',
            'tanggal_lahir' => 'required|date',
        ]);

        $alumni = Alumni::where('npm', $request->npm)
                        ->where('tanggal_lahir', $request->tanggal_lahir)
                        ->first();
        
        if ($alumni) {
            Auth::login($alumni->user);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Jika tidak cocok, kembalikan ke halaman verifikasi dengan pesan error
        return back()->with('error', 'Tanggal lahir tidak sesuai. Silakan coba lagi.');
    }
}

