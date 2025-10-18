<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class InstansiController extends Controller
{
    /**
     * Menampilkan daftar semua instansi.
     */
    public function index()
    {
        $instansiList = Instansi::with('user')->latest()->paginate(10);
        return view('admin.instansi.index', compact('instansiList'));
    }

    /**
     * Menampilkan form untuk membuat instansi baru.
     */
    public function create()
    {
        return view('admin.instansi.create');
    }

    /**
     * Menyimpan instansi baru beserta akun loginnya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:instansi,nama',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat data instansi
            $instansi = Instansi::create(['nama' => $request->nama]);

            // 2. Buat akun user untuk instansi tersebut
            User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'instansi',
                'instansi_id' => $instansi->id, // Tautkan user ke instansi
            ]);
        });

        return redirect()->route('admin.instansi.index')->with('success', 'Instansi dan akun login berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit instansi.
     */
    public function edit(Instansi $instansi)
    {
        // PERBAIKAN: Memuat relasi 'user' secara eksplisit sebelum menampilkan view.
        $instansi->load('user');
        return view('admin.instansi.edit', compact('instansi'));
    }

    /**
     * Memperbarui data instansi dan akun loginnya.
     */
    public function update(Request $request, Instansi $instansi)
    {
        // Pastikan user ada sebelum validasi
        // if (!$instansi->user) {
        //     return redirect()->route('admin.instansi.index')->with('error', 'Akun login untuk instansi ini tidak ditemukan.');
        // }
        $userId = $instansi->user->id ?? null;

        $request->validate([
            'nama' => 'required|string|max:255|unique:instansi,nama,' . $instansi->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . ($userId ?? 'NULL'),
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request, $instansi) {
            // 1. Update nama instansi
            $instansi->update(['nama' => $request->nama]);

            if ($instansi->user) {
                
                $instansi->user->update([
                    'name' => $request->nama,
                    'email' => $request->email,
                ]);

                if ($request->filled('password')) {
                    $instansi->user->update([
                        'password' => Hash::make($request->password)
                    ]);
                }
            } else {
                User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => $request->filled('password')
                        ? Hash::make($request->password)
                        : Hash::make('password123'),
                    'role' => 'instansi',
                    'instansi_id' =>$instansi->id,
                ]);
            }

        });

        return redirect()->route('admin.instansi.index')->with('success', 'Data instansi berhasil diperbarui.');
    }

    /**
     * Menghapus instansi (akun user akan terhapus otomatis oleh cascade).
     */
    public function destroy(Instansi $instansi)
    {
        // User yang tertaut akan terhapus secara otomatis karena onDelete('cascade')
        $instansi->delete();

        return redirect()->route('admin.instansi.index')->with('success', 'Instansi berhasil dihapus.');
    }
}

