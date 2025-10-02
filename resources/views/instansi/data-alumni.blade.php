@extends('layouts.instansi')
@section('title', 'Data Alumni')

@section('content')
<div class="card">
    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Alumni untuk Dinilai</h6>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Alumni</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Program Studi</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status Penilaian</th>
                        <th class="text-secondary opacity-7"></th>
                    </tr>
                </thead>
                <tbody>
                     @forelse($alumniList as $alumnus)
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
                        <td class="align-middle text-sm">
                            @if($alumnus->penilaianInstansi->isNotEmpty())
                                <ul class="list-unstyled mb-0">
                                    @foreach($alumnus->penilaianInstansi as $penilaian)
                                        <li class="mb-1">
                                            <span class="badge badge-sm bg-gradient-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Dinilai oleh: {{ $penilaian->nama_penilai }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="badge badge-sm bg-gradient-secondary">Belum Dinilai</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('instansi.penilaian.show', $alumnus) }}" class="btn btn-sm bg-gradient-primary mb-0">
                                + Tambah Penilaian
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <p class="text-secondary mb-0">Belum ada data alumni yang tercatat bekerja di instansi Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="d-flex justify-content-center mt-4">
            {{ $alumniList->links() }}
        </div>
    </div>
</div>
@endsection
