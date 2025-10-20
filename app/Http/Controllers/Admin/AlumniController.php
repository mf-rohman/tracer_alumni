<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Alumni;
    use App\Models\Prodi;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Http\JsonResponse;
    use App\Imports\AlumniImport;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
    use Illuminate\Validation\Rule;
    use Maatwebsite\Excel\Facades\Excel;
    use Maatwebsite\Excel\Validators\ValidationException;
use Throwable;

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
            $prodi = Prodi::orderBy('nama_prodi')->get();
            return view('admin.alumni.create', compact('prodi'));
        }

        /**
         * Menyimpan alumni baru ke database.
         */
        public function store(Request $request)
        {
            $request->validate([
                'nama_lengkap' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'npm' => ['required', 'string', 'max:255', 'unique:alumni,npm'],
                'prodi_id' => ['required', 'string', 'exists:prodi,kode_prodi'],
                'tahun_lulus' => ['required', 'integer', 'digits:4'],
                'nik' => ['required', 'string', 'digits:16', 'unique:alumni,nik'],
                'no_hp' => ['nullable', 'string', 'max:20'],
                'tahun_masuk' => ['nullable', 'integer', 'digits:4'],
                'ipk' => ['nullable', 'numeric', 'between:0,4.00'],
            ]);

            // // 1. Buat User baru
            // $user = User::create([
            //     'name' => $request->nama_lengkap,
            //     'email' => $request->email,
            //     'password' => Hash::make(Str::random(10)), // Password acak
            //     'role' => 'alumni',
            // ]);

            DB::transaction(function () use ($request) {
                // 2. Buat akun user baru untuk alumni
                $user = User::create([
                    'name' => $request->nama_lengkap,
                    'email' => $request->email,
                    'password' => Hash::make($request->npm), // Password default adalah NPM
                    'role' => 'alumni',
                ]);

                // 3. Buat data alumni dan hubungkan dengan user yang baru dibuat
                Alumni::create([
                    'user_id' => $user->id,
                    'prodi_id' => $request->prodi_id, // Menyimpan kode prodi
                    'npm' => $request->npm,
                    'nama_lengkap' => $request->nama_lengkap,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'tahun_lulus' => $request->tahun_lulus,
                    'nik' => $request->nik,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'tahun_masuk' => $request->tahun_masuk,
                    'ipk' => $request->ipk,
                ]);
            });

            return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil ditambahkan.');
        }

        /**
         * Menampilkan form untuk mengedit data alumni.
         */
        public function edit(Alumni $alumnus)
        {
            $prodiList = Prodi::orderBy('nama_prodi')->get();
            $alumnus->load('user');

            return view('admin.alumni.edit', compact('alumnus', 'prodiList'));
        }

        public function show(Alumni $alumnus)
        {

            $alumnus->load('user', 'prodi');


            $latestAnswer = $alumnus->kuesionerAnswers()->orderBy('tahun_kuesioner', 'desc')->first();


            return view('admin.alumni.show', compact('alumnus', 'latestAnswer'));
        }

        /**
         * Memperbarui data alumni di database.
         */
        public function update(Request $request, Alumni $alumnus)
        {

            $request->validate([
                'nama_lengkap' => ['required', 'string', 'max:255'],
                // Pastikan email unik, kecuali untuk user ini sendiri
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($alumnus->user_id)],
                // Pastikan NPM unik, kecuali untuk alumni ini sendiri
                'npm' => ['required', 'string', 'max:255', Rule::unique('alumni')->ignore($alumnus->id)],
                'prodi_id' => ['required', 'string', 'exists:prodi,kode_prodi'],
                'tahun_lulus' => ['required', 'integer', 'digits:4'],
                'nik' => ['required', 'string', 'digits:16', Rule::unique('alumni')->ignore($alumnus->id)],
            ]);
    
            // Menggunakan transaksi database untuk memastikan kedua tabel berhasil diupdate
            DB::transaction(function () use ($request, $alumnus) {
                // 2. Update data di tabel 'users'
                $alumnus->user->update([
                    'name' => $request->nama_lengkap,
                    'email' => $request->email,
                ]);
    
                // 3. Update data di tabel 'alumni'
                $alumnus->update([
                    'prodi_id' => $request->prodi_id,
                    'npm' => $request->npm,
                    'nama_lengkap' => $request->nama_lengkap,
                    'tahun_lulus' => $request->tahun_lulus,
                    'nik' => $request->nik,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'tahun_masuk' => $request->tahun_masuk,
                    'ipk' => $request->ipk,
                ]);
            });
            
            return redirect()->route('admin.alumni.kategori')->with('success', 'Data alumni berhasil diperbarui.');
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
            // 1️⃣ Validasi awal
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv'
            ]);
        
            try {
                // 2️⃣ Ambil semua data dari Excel (sheet pertama)
                $collection = \Maatwebsite\Excel\Facades\Excel::toCollection(
                    new \App\Imports\AlumniImport,
                    $request->file('file')
                )[0];
                
                // 3️⃣ Buat kumpulan job
                $jobs = [];
                foreach ($collection as $row) {
                    $jobs[] = new \App\Jobs\ProcessAlumniImport($row->toArray());
                }
            
                // 4️⃣ Buat batch job untuk tracking progress
                $batch = Bus::batch($jobs)
                    ->name('alumni_import_' . now()->format('Ymd_His'))
                    ->then(function (Batch $batch) {
                        Log::info('✅ Batch selesai!', ['batch_id' => $batch->id]);
                    })
                    ->catch(function (Batch $batch, Throwable $e) {
                        Log::error('❌ Batch error!', [
                            'batch_id' => $batch->id,
                            'error' => $e->getMessage()
                        ]);
                    })
                    ->finally(function (Batch $batch) {
                        Log::info('🏁 Batch selesai diproses', ['batch_id' => $batch->id]);
                    })
                    ->dispatch();
                
                // 5️⃣ Simpan batch_id di session (buat progress bar nanti)
                session(['import_batch_id' => $batch->id]);
                
                // 6️⃣ Redirect ke halaman index alumni
                return redirect()
                    ->route('admin.alumni.index')
                    ->with('success', 'Proses impor dimulai. Batch ID: ' . $batch->id);
                
            } catch (\Exception $e) {
                Log::error('🚨 Gagal memulai import', ['error' => $e->getMessage()]);
                return back()->with('error', 'Terjadi kesalahan saat memulai proses impor: ' . $e->getMessage());
            }
        }

        public function downloadTemplate () {
            $filePath = public_path('template/template_import_alumni.xlsx');

            if (!file_exists($filePath)) {
                abort(404, 'File Template Not Found');
            }

            return response()->download($filePath);
        }
    }
