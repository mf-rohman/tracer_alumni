<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan form untuk mengedit profil instansi.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $instansi = $user->instansi; // Ambil data instansi yang terhubung
        return view('instansi.profile.edit', compact('user', 'instansi'));
    }

    /**
     * Memperbarui informasi profil instansi (nama & foto).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:instansi,nama,' . $instansi->id],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Update nama di tabel instansi dan users
        $instansi->nama = $request->nama;
        $user->name = $request->nama;

        // Handle upload foto profil
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($instansi->photo_path) {
                Storage::disk('public')->delete($instansi->photo_path);
            }
            // Simpan foto baru
            $path = $request->file('photo')->store('instansi-photos', 'public');
            $instansi->photo_path = $path;
        }

        $instansi->save();
        $user->save();

        return redirect()->route('instansi.profile.edit')->with('success', 'Profil instansi berhasil diperbarui.');
    }

    /**
     * Memperbarui password akun instansi.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('instansi.profile.edit')->with('success', 'Password berhasil diperbarui.');
    }
}

