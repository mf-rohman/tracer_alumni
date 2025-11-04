@extends('layouts.admin')

@section('title', 'Data Responden')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- FORM FILTER -->
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6><i class="fa fa-filter text-dark me-2"></i> Filter Data Responden</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.responden.index') }}" method="GET">
                    <div class="row">
                        @if(auth()->user()->role !== 'admin_prodi')
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="prodi_id">Program Studi</label>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tahun_lulus">Tahun Lulus</label>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tahun_respon">Tahun Pengisian</label>
                                <select name="tahun_respon" id="tahun_respon" class="form-control">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahunResponList as $tahun)
                                        <option value="{{ $tahun->tahun_respon }}" {{ request('tahun_respon') == $tahun->tahun_respon ? 'selected' : '' }}>
                                            {{ $tahun->tahun_respon }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status_pengisian">Status Kuesioner</label>
                                <select name="status_pengisian" id="status_pengisian" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="sudah" {{ request('status_pengisian') == 'sudah' ? 'selected' : '' }}>Sudah Mengisi</option>
                                    <option value="belum" {{ request('status_pengisian') == 'belum' ? 'selected' : '' }}>Belum Mengisi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn bg-gradient-primary">Terapkan Filter</button>
                            <a href="{{ route('admin.responden.index') }}" class="btn btn-link text-secondary">Reset</a>
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
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alumni</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Prodi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Kuesioner</th>
                                <th class="text-secondary opacity-7"></th>
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
                                <td class="align-middle">
                                    <a href="{{ route('admin.alumni.show', $alumnus) }}" class="text-secondary font-weight-bold text-xs me-2">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.alumni.edit', $alumnus) }}" class="text-secondary font-weight-bold text-xs me-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.alumni.destroy', $alumnus) }}" method="POST" class="d-inline">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="text-danger font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Apakah Anda yakin ingin menghapus data alumni ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
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
