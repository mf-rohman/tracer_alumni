@extends('layouts.instansi') {{-- Menggunakan layout admin agar tampilan konsisten --}}

@section('title', 'Dashboard Penilaian Alumni')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Selamat Datang, {{ $instansi->nama }}</h5>
                <p class="text-sm mb-0">
                    Berikut adalah daftar alumni yang tercatat bekerja di instansi Anda. Silakan berikan penilaian kinerja untuk masing-masing alumni.
                </p>
            </div>
        </div>

        <div class="card">
             <div class="card-header pb-0">
                <h6 class="mb-0">Daftar Alumni untuk Dinilai</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Alumni</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Program Studi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Penilaian</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                             {{-- PERBAIKAN: Menggunakan @forelse untuk looping data dari $alumniList --}}
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
                                <td class="align-middle text-center text-sm">
                                    @if($alumnus->penilaianInstansi)
                                        <span class="badge badge-sm bg-gradient-success">Sudah Dinilai</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-secondary">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    {{-- Link ke halaman penilaian akan kita buat nanti --}}
                                    <a href="{{ route('instansi.penilaian.show', $alumnus) }}" class="text-secondary font-weight-bold text-xs">
                                        {{ $alumnus->penilaianInstansi ? 'Edit Penilaian' : 'Beri Penilaian' }}
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
    </div>
</div>
@endsection

