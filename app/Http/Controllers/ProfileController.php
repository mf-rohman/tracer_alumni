<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumniProfileUpdateRequest; // <-- Menggunakan request validasi baru kita
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan ini untuk mengelola file
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * PERUBAHAN UTAMA 1:
     * Menampilkan halaman profil alumni yang baru, bukan halaman default.
     */
    public function edit(Request $request): View
    {
        // Mengambil data user dan data alumni yang terhubung
        $user = $request->user();
        $alumni = $user->alumni;

        return view('alumni.profile.edit', compact('user', 'alumni'));
    }

    /**
     * PERUBAHAN UTAMA 2:
     * Memperbarui informasi profil alumni, termasuk foto.
     */
    public function update(AlumniProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $alumni = $user->alumni;

        // 1. Update data di tabel 'users' (nama dan email)
        $user->name = $request->input('nama_lengkap');
        $user->email = $request->input('email');
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // 2. Update data di tabel 'alumni'
        $alumni->nama_lengkap = $request->input('nama_lengkap');
        $alumni->no_hp = $request->input('no_hp');
        $alumni->alamat = $request->input('alamat');

        // 3. Handle upload foto profil
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada untuk menghemat ruang penyimpanan
            if ($alumni->photo_path) {
                Storage::disk('public')->delete($alumni->photo_path);
            }
            // Simpan foto baru di folder 'storage/app/public/profile-photos'
            $path = $request->file('photo')->store('profile-photos', 'public');
            $alumni->photo_path = $path;
        }
        
        $alumni->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     * (Fungsi ini tetap sama)
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

