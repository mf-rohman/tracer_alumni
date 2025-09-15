<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Auth facade

class LandingController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page).
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Memeriksa NPM, melakukan login otomatis, dan mengarahkan ke dashboard.
     */
    public function cekNpm(Request $request)
    {
        $request->validate([
            'npm' => 'required|string|max:20',
        ]);

        $alumni = Alumni::where('npm', $request->npm)->first();

        // Periksa apakah data alumni dan akun user yang terhubung ada
        if ($alumni && $alumni->user) {
            
            // Lakukan login otomatis untuk user tersebut
            Auth::login($alumni->user);

            // Buat ulang session untuk keamanan
            $request->session()->regenerate();

            // Arahkan langsung ke dashboard
            return redirect()->route('dashboard');

        } else {
            // Jika NPM tidak ditemukan atau tidak terhubung dengan akun, kembalikan dengan pesan error.
            return back()->with('error', 'NPM tidak ditemukan atau tidak terhubung dengan akun pengguna.');
        }
    }
}
