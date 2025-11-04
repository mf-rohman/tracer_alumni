@extends('layouts.instansi')
@section('title', 'Dashboard Penilaian')

@section('content')
<div class="row">
    {{-- PERUBAHAN: Kartu Selamat Datang dengan Foto Profil --}}
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        {{-- Menampilkan foto profil instansi atau avatar default --}}
                        <img src="{{ $instansi->photo_path ? asset('storage/' . $instansi->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($instansi->nama) . '&background=4B49AC&color=fff&size=64' }}"
                             alt="Logo Instansi" class="avatar avatar-xl rounded-circle shadow-sm">
                    </div>
                    <div class="col">
                        <h5 class="mb-0 font-weight-bolder">Selamat Datang, {{ $instansi->nama }}</h5>
                        <p class="text-sm mb-0">Berikut adalah ringkasan penilaian kinerja alumni yang telah Anda berikan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Kartu Statistik --}}
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <h6 class="text-uppercase text-secondary text-xxs font-weight-bolder">Total Alumni Dinilai</h6>
                <h2 class="font-weight-bolder mt-2">{{ $totalAlumniDinilai }}</h2>
                <span class="text-sm">Orang</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                 <h6 class="text-uppercase text-secondary text-xxs font-weight-bolder">Total Penilaian Diberikan</h6>
                <h2 class="font-weight-bolder mt-2">{{ $totalPenilaianDiberikan }}</h2>
                <span class="text-sm">Penilaian</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                 <h6 class="text-uppercase text-secondary text-xxs font-weight-bolder">Rata-rata Keahlian</h6>
                <h2 class="font-weight-bolder mt-2">{{ number_format($rataRataKeahlian, 2) }}</h2>
                <span class="text-sm">dari 4.00</span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    {{-- Grafik --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6>Distribusi Kinerja Keseluruhan Alumni</h6>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="kinerja-chart" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
{{-- Memuat library Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("kinerja-chart").getContext("2d");

    const labels = @json($chartLabels);
    const data = @json($chartData);

    console.log("Labels:", labels);
    console.log("Data:", data);

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Jumlah Penilaian",
                data: data,
                backgroundColor: "#4B49AC",
                borderRadius: 5,
                maxBarThickness: 40,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            },
            plugins: {
                legend: { display: false },
            },
        },
    });
});
</script>
@endpush

