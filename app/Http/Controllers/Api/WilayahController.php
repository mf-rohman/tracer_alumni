<?php

namespace App\Http\Controllers\Api; // <-- Berada di dalam namespace 'Api'

use App\Http\Controllers\Controller;
use App\Models\Regency; // <-- Mengimpor Model Regency yang sudah kita buat
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Mengambil daftar kabupaten/kota berdasarkan kode provinsi.
     */
    public function getRegencies(Request $request)
    {
        // 1. Ambil kode provinsi dari parameter URL (?province_code=...)
        $provinceCode = $request->query('province_code');

        // 2. Cari semua data kabupaten/kota di database yang cocok
        $regencies = Regency::where('province_code', $provinceCode)
                              ->orderBy('name') // Urutkan berdasarkan nama agar rapi
                              ->get();

        // 3. Kembalikan hasilnya sebagai respons JSON
        return response()->json($regencies);
    }
}
