<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InstansiLoginController extends Controller
{
    /**
     * Menampilkan form login untuk instansi.
     */
    public function showLoginForm()
    {
        // Ambil kembali semua instansi untuk ditampilkan di dropdown
        $instansiList = Instansi::orderBy('nama')->get();
        return view('auth.instansi-login', compact('instansiList'));
    }

    /**
     * Menangani proses login menggunakan Nama Instansi dan Password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'instansi_id' => 'required|exists:instansi,id',
            'password' => 'required|string',
        ]);

        // Cari user yang terhubung dengan instansi yang dipilih
        $user = User::where('instansi_id', $request->instansi_id)
                    ->where('role', 'instansi')
                    ->first();
        
        if (!$user) {
        return back()->withErrors([
                'instansi_id' => 'User tidak ditemukan untuk instansi ini.',
            ])->withInput();
        }
        
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->withInput();
        }

        // Verifikasi user dan password
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            // Arahkan ke dashboard instansi
            return redirect()->intended(route('instansi.dashboard'));
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'password' => 'Password yang Anda masukkan salah.',
        ])->withInput($request->only('instansi_id'));
    }
}

