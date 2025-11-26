<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Instansi;
use App\Models\User;
use App\Models\Prodi; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'alumni')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $prodi = Prodi::all(); 
        return view('admin.users.create', compact('prodi')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:superadmin,bak,admin_prodi'],
            'prodi_id' => ['nullable', 'required_if:role,admin_prodi', 'exists:prodi,kode_prodi'], // <-- VALIDASI BARU
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'prodi_id' => $request->role == 'admin_prodi' ? $request->prodi_id : null, // <-- SIMPAN PRODI ID
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $prodi = Prodi::all(); 
        return view('admin.users.edit', compact('user', 'prodi')); // <-- KIRIM DATA PRODI
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:superadmin,bak,admin_prodi'],
            'prodi_id' => ['nullable', 'required_if:role,admin_prodi', 'exists:prodi,id'], // <-- VALIDASI BARU
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'prodi_id' => $request->role == 'admin_prodi' ? $request->prodi_id : null, // <-- UPDATE PRODI ID
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

    // public function loginAsInstansi (Instansi $instansi) {
    //     $admin = Auth::user();

    //     if ($admin->role === 'admin_prodi' && )

    // }
}
