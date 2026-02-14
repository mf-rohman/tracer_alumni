@extends('layouts.admin')

@section('title', 'Data Responden')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- FORM FILTER -->
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6><i class="fa fa-filter text-dark me-2"></i> Filter Data Responden</h6>
                <a href="{{ route('admin.responden.export') }}" class="btn btn-success mb-3">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
            </div>

        
            <div class="card-body">
                <form action="{{ route('admin.responden.index') }}" method="GET">
                    <div class="row">
        
                        {{-- Filter Prodi (hilang jika admin_prodi) --}}
                        @if(auth()->user()->role !== 'admin_prodi')
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="prodi_id" class="font-weight-bold">Program Studi</label>
                                    <select name="prodi_id" id="prodi_id" class="form-control">
                                        <option value="">Semua Prodi</option>
                                        @foreach($prodiList as $prodi)
                                            <option value="{{ $prodi->kode_prodi }}" {{ request('prodi_id') == $prodi->kode_prodi ? 'selected' : '' }}>
                                                {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
        
                        {{-- Tahun Lulus --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tahun_lulus" class="font-weight-bold">Tahun Lulus</label>
                                <select name="tahun_lulus" id="tahun_lulus" class="form-control">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahunLulusList as $tahun)
                                        <option value="{{ $tahun->tahun_lulus }}" {{ request('tahun_lulus') == $tahun->tahun_lulus ? 'selected' : '' }}>
                                            {{ $tahun->tahun_lulus }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
        
                        {{-- NPM --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="npm" class="font-weight-bold">Cari NPM Alumni</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        name="npm" 
                                        id="npm" 
                                        class="form-control" 
                                        value="{{ request('npm') }}" 
                                        placeholder="Masukkan NPM..."
                                    >
                                </div>
                            </div>
                        </div>
        
                        {{-- Status Kuesioner --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status_pengisian" class="font-weight-bold">Status Kuesioner</label>
                                <select name="status_pengisian" id="status_pengisian" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="sudah" {{ request('status_pengisian') == 'sudah' ? 'selected' : '' }}>Sudah Mengisi</option>
                                    <option value="belum" {{ request('status_pengisian') == 'belum' ? 'selected' : '' }}>Belum Mengisi</option>
                                </select>
                            </div>
                        </div>
        
                    </div> {{-- end .row --}}
        
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <button type="submit" class="btn bg-gradient-primary w-100">Terapkan Filter</button>
                        </div>
        
                        <div class="col-md-2">
                            <a href="{{ route('admin.responden.index') }}" class="btn btn-link text-secondary w-100">
                                Reset
                            </a>
                        </div>

                        
                    </div>
        
                </form>
            </div>
        </div>


        <!-- TABEL DATA ALUMNI -->
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Hasil Filter</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0 overflow-visible">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alumni</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Prodi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Kuesioner</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Skor</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alumni as $alumnus)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="{{ $alumnus->photo_path ? asset('storage/' . $alumnus->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($alumnus->nama_lengkap) . '&background=4B49AC&color=fff' }}" class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $alumnus->nama_lengkap }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $alumnus->npm }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $alumnus->prodi->nama_prodi ?? 'N/A' }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ $alumnus->tahun_lulus }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($alumnus->kuesionerAnswers->isNotEmpty())
                                        <span class="badge badge-sm bg-gradient-success">Sudah Mengisi</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-secondary">Belum Mengisi</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($alumnus->penilaianInstansi->isNotEmpty())
                                        <span class="badge badge-sm bg-gradient-success">{{$alumnus->skor_penilaian}}</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-secondary">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="aksiDropdown{{ $alumnus->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <!-- <i class="fas fa-ellipsis-v"></i> {{-- ikon 3 titik vertikal --}}   -->
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="aksiDropdown{{ $alumnus->id }}">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.alumni.show', $alumnus) }}">
                                                    <i class="fas fa-eye text-primary me-2"></i> Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.alumni.edit', $alumnus) }}">
                                                    <i class="fas fa-pen text-warning me-2"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.users.login_as', $alumnus->uuid) }}" target="_blank">
                                                    <i class="fas fa-sign-in-alt text-success me-2"></i> Isi Kuesioner
                                                </a>
                                            </li>
                                            <li>
                                                @php
                                                    // 1. Ambil kuesioner terakhir dari alumni ini
                                                    // Menggunakan variabel $alumnus sesuai loop Anda
                                                    $latestAnswer = $alumnus->kuesionerAnswers->sortByDesc('tahun_kuesioner')->first();

                                                    // 2. Cek Status: Apakah ada jawaban DAN statusnya '1' (Bekerja)?
                                                    $isBekerja = $latestAnswer && $latestAnswer->f8 == '1';
                                                @endphp

                                                @if($isBekerja)
                                                 
                                                    <form action="{{ route('admin.responden.login.instansi', $alumnus->id) }}" 
                                                          method="POST" 
                                                          class="d-inline-block" 
                                                          onsubmit="return confirm('PERINGATAN:\nAnda akan keluar dari akun Admin dan masuk sebagai                                             Instansi.\n\nLanjutkan?');">
                                                        @csrf

                                                        <button type="submit" class="btn btn-link text-warning px-2 mb-0" title="Isi Penilaian Pengguna                                             Lulusan">
                                                            <i class="fas fa-user-tie text-warning me-2"></i>As Instansi
                                                        </button>
                                                    </form>
                                                @endif
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.alumni.destroy', $alumnus) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data alumni ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                                        <i class="fas fa-trash-alt me-2"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-secondary mb-0">Tidak ada data yang cocok dengan filter Anda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                {{ $alumni->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
