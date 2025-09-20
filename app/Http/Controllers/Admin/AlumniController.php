<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Imports\AlumniImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AlumniController extends Controller
{
    /**
     * Menampilkan daftar semua alumni.
     */
    public function index()
    {
        $alumniQuery = Alumni::with('prodi');

        // LOGIKA BARU: Filter berdasarkan prodi_id di tabel users
        if (auth()->user()->role == 'admin_prodi') {
            // Ambil prodi_id dari user yang sedang login
            $prodiId = auth()->user()->prodi_id;

            // Terapkan filter jika prodi_id ada
            if ($prodiId) {
                $alumniQuery->where('prodi_id', $prodiId);
            } else {
                // Jika admin prodi tidak punya prodi_id, jangan tampilkan apa-apa
                $alumniQuery->whereRaw('1 = 0');
            }
        }

        $alumni = $alumniQuery->latest()->get();
        return view('admin.alumni.index', compact('alumni'));
    }

    /**
     * Menampilkan form untuk menambah alumni baru.
     */
    public function create()
    {
        $prodi = Prodi::all();
        return view('admin.alumni.create', compact('prodi'));
    }

    /**
     * Menyimpan alumni baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'npm' => 'required|string|max:20|unique:alumni,npm',
            'email' => 'required|string|email|max:255|unique:users,email',
            'prodi_id' => 'required|exists:prodi,id',
            'tahun_lulus' => 'required|numeric|digits:4',
        ]);

        // 1. Buat User baru
        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make(Str::random(10)), // Password acak
            'role' => 'alumni',
        ]);

        // 2. Buat data Alumni yang terhubung
        Alumni::create([
            'user_id' => $user->id,
            'prodi_id' => $request->prodi_id,
            'npm' => $request->npm,
            'nama_lengkap' => $request->nama_lengkap,
            'tahun_lulus' => $request->tahun_lulus,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'nik' => $request->nik,
        ]);

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data alumni.
     */
    public function edit(Alumni $alumnus)
    {
        $prodi = Prodi::all();
        return view('admin.alumni.edit', compact('alumnus', 'prodi'));
    }

    public function show(Alumni $alumnus)
    {
        // Eager load relasi yang dibutuhkan agar tidak ada query tambahan di view
        $alumnus->load('user', 'prodi', 'kuesionerAnswer');

        // Arahkan ke view baru dan kirim data alumni
        return view('admin.alumni.show', compact('alumnus'));
    }

    /**
     * Memperbarui data alumni di database.
     */
    public function update(Request $request, Alumni $alumnus)
    {
        // Ambil user yang terhubung dengan data alumni
        $user = $alumnus->user;

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'npm' => 'required|string|max:20|unique:alumni,npm,' . $alumnus->id,
            // Validasi email unik, abaikan email user saat ini
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'prodi_id' => 'required|exists:prodi,id',
            'tahun_masuk' => 'nullable|numeric|digits:4',
            'tahun_lulus' => 'required|numeric|digits:4',
            'nik' => 'nullable|string|digits:16',
            'npwp' => 'nullable|string|max:25',
            'ipk' => 'nullable|numeric|between:0,4.00',
        ]);

        // Update data di tabel alumni
        $alumnus->update($request->all());

        // Update juga nama dan email di tabel user jika berubah
        $user->update([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil diperbarui.');
    }

    /**
     * Menghapus data alumni.
     */
    public function destroy(Alumni $alumnus)
    {
        // Hapus user terkait terlebih dahulu untuk menghindari error
        $alumnus->user()->delete();
        // Hapus data alumni
        $alumnus->delete();

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil dihapus.');
    }

    /**
     * Menampilkan form untuk import Excel.
     */
    public function showImportForm()
    {
        return view('admin.alumni.import');
    }

    /**
     * Menangani proses upload file Excel.
     */
    public function handleImport(Request $request)
    {
        set_time_limit(300);
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        try {
            Excel::import(new AlumniImport, $request->file('file'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil diimpor.');
    }
}
