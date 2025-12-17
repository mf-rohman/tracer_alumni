<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        $request->authenticate();

        $request->session()->regenerate();

        // ==========================================================
        // == AWAL DARI PERUBAHAN LOGIKA REDIRECT BERDASARKAN ROLE ==
        // ==========================================================

        $user = auth()->user();

        // Jika role user adalah salah satu dari admin, arahkan ke admin dashboard
        if (in_array($user->role, ['superadmin', 'bak', 'admin_prodi', 'dekan'])) {
            return redirect()->route('admin.dashboard');
        }

        // Jika tidak, arahkan ke dashboard default (untuk alumni)
        return redirect()->intended(RouteServiceProvider::HOME);

        // ==========================================================
        // == AKHIR DARI PERUBAHAN LOGIKA ==
        // ==========================================================
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
