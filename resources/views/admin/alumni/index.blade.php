@extends('layouts.admin')

@section('title', 'Data Alumni')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Alumni</h6>
                <div>
                    {{-- Tombol mengarah ke rute yang benar --}}
                    <a href="{{ route('admin.alumni.create') }}" class="btn bg-gradient-primary btn-sm mb-0">Tambah Alumni</a>
                    <a href="{{ route('admin.alumni.import.show') }}" class="btn btn-outline-primary btn-sm mb-0">Import Excel</a>
                </div>
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
                            {{-- Looping data alumni dari database --}}
                            @forelse($alumni as $alumnus)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            {{-- Avatar dinamis berdasarkan nama --}}
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($alumnus->nama_lengkap) }}&background=4B49AC&color=fff" class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $alumnus->nama_lengkap }}</h6>
                                            {{-- Menampilkan NPM --}}
                                            <p class="text-xs text-secondary mb-0">{{ $alumnus->npm }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{-- Menampilkan nama prodi dari relasi --}}
                                    <p class="text-xs font-weight-bold mb-0">{{ $alumnus->prodi->nama_prodi ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $alumnus->tahun_lulus }}</span>
                                </td>
                                <td class="align-middle">
                                    {{-- Tombol Edit mengarah ke rute yang benar --}}
                                    <a href="{{ route('admin.alumni.edit', $alumnus->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="text-secondary mb-0">Belum ada data alumni.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
