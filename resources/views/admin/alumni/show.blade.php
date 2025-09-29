@extends('layouts.admin')

@section('title', 'Detail Alumni')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Kartu Profil Alumni -->
        <div class="card mb-4" id="alumni-profile">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Profil Alumni</h6>
                    <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline-primary btn-sm mb-0">Kembali ke Daftar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-4 text-center">
                        <img src="{{ $alumnus->photo_path ? asset('storage/' . $alumnus->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($alumnus->nama_lengkap) . '&size=128&background=4B49AC&color=fff' }}"
                             alt="Foto Profil"
                             class="img-fluid rounded-circle shadow-sm mb-3"
                             style="width: 128px; height: 128px; object-fit: cover;">
                        <h5 class="font-weight-bolder mb-1">{{ $alumnus->nama_lengkap }}</h5>
                        <p class="text-muted mb-0">{{ $alumnus->npm }}</p>
                    </div>
                    <div class="col-12 col-lg-8 mt-4 mt-lg-0">
                        <h6 class="font-weight-bolder">Informasi Akademik & Pribadi</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>Program Studi:</strong> <span>{{ $alumnus->prodi->nama_prodi ?? 'N/A' }}</span></li>
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>Tahun Lulus:</strong> <span>{{ $alumnus->tahun_lulus }}</span></li>
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>Email:</strong> <span>{{ $alumnus->user->email ?? 'N/A' }}</span></li>
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>No. HP:</strong> <span>{{ $alumnus->no_hp ?? 'N/A' }}</span></li>
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>IPK:</strong> <span>{{ $alumnus->ipk ?? 'N/A' }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Jawaban Kuesioner -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Jawaban Kuesioner Tracer Study</h6>
            </div>
            <div class="card-body">
                @if($alumnus->kuesionerAnswer)
                    @php
                        $answer = $alumnus->kuesionerAnswer;
                        $statusMap = [
                            '1' => 'Bekerja (full time/ part time)',
                            '2' => 'Belum memungkinkan bekerja',
                            '3' => 'Wiraswasta',
                            '4' => 'Melanjutkan Pendidikan',
                            '5' => 'Tidak kerja tetapi sedang mencari kerja',
                        ];
                    @endphp
                    <h6 class="font-weight-bolder">Kuesioner Wajib</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between ps-0"><strong>Status Saat Ini:</strong> <span>{{ $statusMap[$answer->f8] ?? 'N/A' }}</span></li>

                        @if($answer->f8 == 1) {{-- Jika Bekerja --}}
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>Waktu Tunggu Kerja:</strong> <span>{{ $answer->f502 ?? 'N/A' }} bulan</span></li>
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>Pendapatan:</strong> <span>Rp {{ number_format($answer->f505, 0, ',', '.') }}</span></li>
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>Nama Perusahaan:</strong> <span>{{ $answer->f5b ?? 'N/A' }}</span></li>
                        @endif

                        @if($answer->f8 == 3) {{-- Jika Wiraswasta --}}
                             <li class="list-group-item d-flex justify-content-between ps-0"><strong>Waktu Memulai Wiraswasta:</strong> <span>{{ $answer->f502 ?? 'N/A' }} bulan</span></li>
                             <li class="list-group-item d-flex justify-content-between ps-0"><strong>Jabatan:</strong> <span>{{ $answer->f5c_wiraswasta ?? 'N/A' }}</span></li>
                        @endif

                        @if($answer->f8 == 4) {{-- Jika Studi Lanjut --}}
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>Perguruan Tinggi:</strong> <span>{{ $answer->f18b ?? 'N/A' }}</span></li>
                            <li class="list-group-item d-flex justify-content-between ps-0"><strong>Program Studi:</strong> <span>{{ $answer->f18c ?? 'N/A' }}</span></li>
                        @endif
                    </ul>
                @else
                    <p class="text-center text-muted">Alumni ini belum mengisi kuesioner.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
