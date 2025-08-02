<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAlumni = Alumni::count();
        $totalUsers = User::where('role', '!=', 'alumni')->count();
        // Di sini Anda bisa menambahkan logika untuk mengambil data
        // seperti jumlah alumni, jumlah user, dll.

        // Data contoh untuk grafik (misalnya, pendaftar 7 hari terakhir)
        $chartLabels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $chartData = [12, 19, 3, 5, 2, 3, 9]; // Nanti bisa diganti dengan data asli dari database


        return view('admin.dashboard', compact('totalAlumni', 'totalUsers', 'chartLabels', 'chartData')); // <-- PASTIKAN MENGARAH KE SINI
    }
}
