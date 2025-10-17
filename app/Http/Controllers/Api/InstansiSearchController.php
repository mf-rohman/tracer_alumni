<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiSearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('q');

        $query = Instansi::query()
            ->where('nama', 'LIKE', "%{$search}%")
            ->select('id', 'nama as text'); // Format yang dibutuhkan oleh Select2/TomSelect

        // Jika tidak ada query pencarian, tampilkan beberapa data awal
        if (empty($search)) {
            $query->latest();
        }
        
        $instansi = $query->limit(20)->get();

        return response()->json([
            'results' => $instansi
        ]);
    }
}