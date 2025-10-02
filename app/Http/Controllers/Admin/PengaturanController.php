<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
class PengaturanController extends Controller
{
    public function index()
    {
        $tahunAktif = Pengaturan::where('key', 'tahun_kuesioner_aktif')->first()->value ?? date('Y');
        return view('admin.pengaturan.index', compact('tahunAktif'));
    }

    public function update(Request $request)
    {
        $request->validate(['tahun_kuesioner_aktif' => 'required|integer|digits:4']);
        Pengaturan::updateOrCreate(
            ['key' => 'tahun_kuesioner_aktif'],
            ['value' => $request->tahun_kuesioner_aktif]
        );
        return back()->with('success', 'Tahun kuesioner aktif berhasil diperbarui.');
    }
}