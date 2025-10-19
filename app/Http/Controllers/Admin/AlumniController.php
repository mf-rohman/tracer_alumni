<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Alumni;
    use App\Models\Prodi;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Http\JsonResponse;
    use App\Imports\AlumniImport;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\Facades\Excel;
    use Maatwebsite\Excel\Validators\ValidationException;

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
            $prodi = Prodi::all();
            return view('admin.alumni.edit', compact('alumnus', 'prodi'));
        }

        public function show(Alumni $alumnus)
        {
            // Memuat relasi dasar
            $alumnus->load('user', 'prodi');

            // Mengambil satu jawaban kuesioner yang paling baru berdasarkan tahun
            $latestAnswer = $alumnus->kuesionerAnswers()->orderBy('tahun_kuesioner', 'desc')->first();

            // Mengirim data alumni dan jawaban terbarunya ke view
            return view('admin.alumni.show', compact('alumnus', 'latestAnswer'));
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
            // 1. Validasi awal: Pastikan file yang di-upload adalah file Excel
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv'
            ]);

            try {
                // 2. PERINTAH KUNCI: Gunakan Excel::queueImport
                // Alih-alih memprosesnya langsung, perintah ini akan membaca file
                // dan mengirimkan pekerjaan ke dalam antrian di database.
                // Proses ini sangat cepat dan tidak akan menyebabkan timeout.
                Excel::queueImport(new AlumniImport, $request->file('file'));

            } catch (\Exception $e) {
                // 3. TANGKAP ERROR UMUM: Untuk masalah lain (misal: format file rusak)
                return back()->with('error', 'Terjadi kesalahan saat memulai proses impor: ' . $e->getMessage());
            }

            // 4. Jika berhasil, kembalikan dengan pesan sukses instan
            return redirect()->route('admin.alumni.index')->with('success', 'Proses impor telah dimulai. Data alumni akan ditambahkan di latar belakang.');
        }

        public function downloadTemplate () {
            $filePath = public_path('template/template_import_alumni.xlsx');

            if (!file_exists($filePath)) {
                abort(404, 'File Template Not Found');
            }

            return response()->download($filePath);
        }
    }
