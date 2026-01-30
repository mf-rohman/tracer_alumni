<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Fakultas;
use App\Models\Instansi;
use App\Models\User;
use App\Models\Prodi; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::where('role', '!=', 'alumni')->latest()->get();
        // return view('admin.users.index', compact('users'));

        $users = User::whereIn('role', ['superadmin', 'bak', 'admin_prodi', 'dekan'])->latest()->get();

        $prodiList = Prodi::all();
        $fakultasList = Fakultas::all(); 

        return view('admin.users.index', compact('users', 'prodiList', 'fakultasList'));
    }

    public function create()
    {
        $prodi = Prodi::all();
        $fakultas = Fakultas::all(); 
        return view('admin.users.create', compact('prodi','fakultas')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:superadmin,bak,admin_prodi,dekan'],
            'prodi_id' => ['nullable', 'required_if:role,admin_prodi', 'exists:prodi,kode_prodi'],
            'fakultas_id' => 'required_if:role,dekan|nullable|exists:fakultas,id', 
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'prodi_id' => $request->role == 'admin_prodi' ? $request->prodi_id : null,
            'fakultas_id' => $request->role == 'dekan' ? $request->fakultas_id : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $prodi = Prodi::all();
        $fakultas = Fakultas::all(); 
        return view('admin.users.edit', compact('user', 'prodi', 'fakultas')); 
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:superadmin,bak,admin_prodi,dekan'],
            'prodi_id' => ['nullable', 'required_if:role,admin_prodi', 'exists:prodi,id'],
            'fakultas_id' => 'required_if:role,dekan|nullable',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'prodi_id' => $request->role == 'admin_prodi' ? $request->prodi_id : null,
            'fakultas_id' => $request->role === 'dekan' ? $request->fakultas_id : null, 
        ]);
        
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->id == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function loginAs(Alumni $alumnus)
    {
        $admin = Auth::user();

        // Keamanan: Admin Prodi hanya bisa login sebagai alumni dari prodinya
        if ($admin->role === 'admin_prodi' && $admin->prodi_id !== $alumnus->prodi_id) {
            abort(403, 'Aksi tidak diizinkan. Anda hanya bisa mengakses alumni dari prodi Anda.');
        }

        session(['admin_impersonator_id' => $admin->id]);

        Auth::login($alumnus->user);

        return redirect()->route('dashboard', ['tahun' => $alumnus->tahun_lulus]);
    }

    /**
     * Mengembalikan admin ke akun aslinya.
     */
    public function logoutAsAdmin()
    {
        $adminId = session('admin_impersonator_id');

        if (!$adminId) {
            return redirect('/');
        }

        Auth::logout();

        session()->forget('admin_impersonator_id');

        Auth::loginUsingId($adminId);

        return redirect()->route('admin.responden.index');
    }

    public function loginAsInstansi($alumniId)
    {
        // 1. Simpan ID Admin saat ini sebelum switch user
        $adminId = Auth::id();

        // 2. Cari Data Alumni & Jawaban Terakhir
        $alumni = Alumni::with('kuesionerAnswers')->findOrFail($alumniId);
        $answer = $alumni->kuesionerAnswers()->latest('tahun_kuesioner')->first();

        // 3. Validasi Status Bekerja
        if (!$answer || $answer->f8 != '1') {
            return back()->with('error', 'Login Instansi hanya untuk alumni yang sudah bekerja.');
        }

        // 4. Siapkan Data Akun Instansi
        $namaPerusahaan = $answer->f5b ?? 'Instansi Alumni';
        $emailInstansi = $answer->f5a2; // Email atasan asli
        
        // Jika email kosong, buat email dummy unik
        if (empty($emailInstansi)) {
            $cleanName = Str::slug(substr($namaPerusahaan, 0, 20));
            $emailInstansi = "instansi.{$alumni->npm}.{$cleanName}@tracer.local";
        }

        $namaAkun = $answer->f5a1 ?? $namaPerusahaan; 

        // 5. Cari atau Buat User Instansi (Auto-Register)
        $userInstansi = User::firstOrCreate(
            ['email' => $emailInstansi],
            [
                'name' => $namaAkun,
                'password' => bcrypt(Str::random(16)), 
                'role' => 'instansi',
                // [PERBAIKAN] Set verified agar tidak ditendang middleware 'verified'
                'email_verified_at' => now(), 
            ]
        );

        // Validasi Role
        if ($userInstansi->role !== 'instansi') {
             return back()->with('error', 'Email ini terdaftar sebagai akun Non-Instansi.');
        }

        // 6. PROSES IMPERSONATE YANG LEBIH BERSIH (Logout -> Login)
        
        // A. Logout Admin sepenuhnya untuk mencegah konflik sesi
        Auth::guard('web')->logout();
        
        // B. Invalidate session lama (PENTING: membersihkan sisa data admin)
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // C. Login User Baru (Instansi)
        Auth::guard('web')->login($userInstansi);

        // D. Set Session Penyamaran (Di sesi baru milik Instansi)
        // Kita simpan ID admin agar nanti bisa kembali
        session(['admin_impersonator_id' => $adminId]);

        // 7. Redirect
        return redirect()->route('instansi.dashboard')
            ->with('success', "Mode Penyamaran Aktif: Anda login sebagai {$namaPerusahaan}");
    }
}
