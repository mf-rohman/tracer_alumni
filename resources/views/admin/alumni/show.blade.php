@extends('layouts.admin')

@section('title', 'Detail Alumni')

@section('content')
<div class="row">
    {{-- Kartu Informasi Utama --}}
    <div class="col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <img src="{{ $alumnus->photo_path ? asset('storage/' . $alumnus->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($alumnus->nama_lengkap) . '&background=4B49AC&color=fff&size=128' }}"
                     alt="Foto Profil" class="avatar avatar-xxl rounded-circle shadow-sm border mb-3 mx-auto">
                <h5 class="font-weight-bolder">{{ $alumnus->nama_lengkap }}</h5>
                <p class="text-muted mb-0">{{ $alumnus->npm }}</p>
                <p class="text-sm">{{ $alumnus->prodi->nama_prodi ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Kartu Detail Informasi --}}
    <div class="col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Informasi Detail</h6>
            </div>
            <div class="card-body">
                <h6 class="font-weight-bolder">Informasi Akademik</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>Tahun Masuk:</strong> <span>{{ $alumnus->tahun_masuk ?? 'N/A' }}</span></li>
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>Tahun Lulus:</strong> <span>{{ $alumnus->tahun_lulus }}</span></li>
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>IPK:</strong> <span>{{ $alumnus->ipk ?? 'N/A' }}</span></li>
                </ul>
                <hr class="horizontal dark">
                <h6 class="font-weight-bolder mt-3">Informasi Pribadi</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>Email:</strong> <span>{{ $alumnus->user->email ?? 'N/A' }}</span></li>
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>No. HP:</strong> <span>{{ $alumnus->no_hp ?? 'N/A' }}</span></li>
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>NIK:</strong> <span>{{ $alumnus->nik ?? 'N/A' }}</span></li>
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>Alamat:</strong> <span>{{ $alumnus->alamat ?? 'N/A' }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- KARTU BARU UNTUK JAWABAN KUESIONER --}}
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Jawaban Kuesioner (Terbaru)</h6>
        <a href="{{ route('admin.alumni.edit', $alumnus->uuid) }}" class="btn btn-sm btn-outline-primary mb-0">Edit Alumni</a>
    </div>
    <div class="card-body">
        {{-- Menggunakan variabel $latestAnswer yang dikirim dari controller --}}
        @if($latestAnswer)
            @php
                $answer = $latestAnswer; // Ubah nama variabel agar mudah dibaca
                $statusMap = [
                    '1' => 'Bekerja',
                    '2' => 'Belum Memungkinkan Bekerja',
                    '3' => 'Wiraswasta',
                    '4' => 'Melanjutkan Pendidikan',
                    '5' => 'Mencari Kerja',
                ];
            @endphp
            <h6 class="font-weight-bolder">Kuesioner Tahun {{ $answer->tahun_kuesioner }}</h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between ps-0"><strong>Status Saat Ini:</strong> <span>{{ $statusMap[$answer->f8] ?? 'N/A' }}</span></li>

                @if($answer->f8 == 1) {{-- Jika Bekerja --}}
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>Waktu Tunggu Kerja:</strong> <span>{{ $answer->f502 ?? 'N/A' }} bulan</span></li>
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>Pendapatan per Bulan:</strong> <span>Rp {{ number_format($answer->f505 ?? 0, 0, ',', '.') }}</span></li>
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>Nama Perusahaan:</strong> <span>{{ $answer->f5b ?? 'N/A' }}</span></li>
                @endif

                @if($answer->f8 == 3) {{-- Jika Wiraswasta --}}
                     <li class="list-group-item d-flex justify-content-between ps-0"><strong>Waktu Mulai Wiraswasta:</strong> <span>{{ $answer->f502_wiraswasta ?? 'N/A' }} bulan setelah lulus</span></li>
                @endif

                @if($answer->f8 == 4) {{-- Jika Studi Lanjut --}}
                    <li class="list-group-item d-flex justify-content-between ps-0"><strong>Nama Universitas:</strong> <span>{{ $answer->f1201 ?? 'N/A' }}</span></li>
                     <li class="list-group-item d-flex justify-content-between ps-0"><strong>Program Studi:</strong> <span>{{ $answer->f1202 ?? 'N/A' }}</span></li>
                @endif
                
                {{-- Anda bisa menambahkan detail lain dari kuesioner di sini --}}
            </ul>
        @else
            <p class="text-center text-muted">Alumni ini belum pernah mengisi kuesioner.</p>
        @endif
    </div>
</div>
@endsection

