<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // PENGECUALIAN: Jika pengguna mencoba mengirim form login,
        // biarkan permintaan diteruskan agar controller bisa menanganinya.
        if ($request->routeIs('login') && $request->isMethod('post')) {
            return $next($request);
        }

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Logika pengalihan standar jika sudah login
                if (in_array($user->role, ['superadmin', 'bak', 'admin_prodi', 'instansi'])) {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
