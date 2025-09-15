@extends('layouts.admin')

@section('title', 'Data Alumni')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">Filter Data Alumni</h6>
                <div >
                    <a href="{{ route('admin.alumni.create') }}" class="btn bg-gradient-primary btn-sm mb-0 ">Tambah Alumni</a>
                    <a href="{{ route('admin.alumni.import.show') }}" class="btn btn-outline-primary btn-sm mb-0">Import Excel</a>
                </div>
            </div>
            
            <div class="card-body">
                <form action="{{ route('admin.alumni.kategori') }}" method="GET">
                    <div class="row">
                        {{-- Tampilkan filter prodi HANYA jika bukan admin prodi --}}
                        @if(auth()->user()->role !== 'admin_prodi')
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="prodi_id">Berdasarkan Program Studi</label>
                                <select name="prodi_id" id="prodi_id" class="form-control">
                                    <option value="">Semua Prodi</option>
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->id }}" {{ $selectedProdi == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        {{-- Sesuaikan lebar kolom jika admin prodi --}}
                        <div class="{{ auth()->user()->role !== 'admin_prodi' ? 'col-md-5' : 'col-md-10' }}">
                            <div class="form-group">
                                <label for="tahun_lulus">Berdasarkan Tahun Lulus</label>
                                <select name="tahun_lulus" id="tahun_lulus" class="form-control">
                                    <option value="">Semua Tahun</option>
                                     @foreach($tahunLulus as $tahun)
                                        <option value="{{ $tahun }}" {{ $selectedTahun == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn bg-gradient-primary w-100">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">Hasil Filter (Ditemukan: {{ $alumni->count() }} alumni)</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Lengkap & NPM</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Prodi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun Lulus</th>
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
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $alumnus->tahun_lulus }}</span>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.alumni.show', $alumnus->id) }}" class="text-secondary font-weight-bold text-xs">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.alumni.edit', $alumnus->id) }}" class="text-secondary font-weight-bold text-xs">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="text-secondary mb-0">Tidak ada data alumni yang sesuai dengan filter Anda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-4">
                        {{ $alumni->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
