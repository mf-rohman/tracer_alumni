@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- KARTU STATISTIK ATAS -->
    <div class="row">
        {{-- Kartu Statistik --}}
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
            <div class="card text-center">
                <div class="card-body p-3">
                    <h6 class="text-sm mb-0 text-capitalize font-weight-bold">Responden</h6>
                    <h5 class="font-weight-bolder mb-0">{{ $totalResponden }} / {{ $totalAlumni }}</h5>
                    <span class="badge bg-light text-dark">{{ $totalAlumni > 0 ? round(($totalResponden / $totalAlumni) * 100) : 0 }}% Lulusan</span>
                </div>
            </div>
        </div>
        @foreach($statusCounts as $status => $count)
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
            <div class="card text-center">
                <div class="card-body p-3">
                    <h6 class="text-sm mb-0 text-capitalize font-weight-bold">{{ $status }}</h6>
                    <h5 class="font-weight-bolder mb-0">{{ $count }} / {{ $totalResponden }}</h5>
                    <span class="badge bg-light text-dark">{{ $totalResponden > 0 ? round(($count / $totalResponden) * 100) : 0 }}% Responden</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- FILTER PRODI (TOMBOL) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard', ['tahun_lulus' => request('tahun_lulus'), 'tahun_respon' => request('tahun_respon')]) }}" 
                       class="btn {{ empty($selectedProdiId) ? 'bg-gradient-primary' : 'btn-outline-primary' }} btn-sm mb-0">Semua</a>
                    @foreach($prodiList as $prodi)
                        <a href="{{ route('admin.dashboard', ['prodi_id' => $prodi->id, 'tahun_lulus' => request('tahun_lulus'), 'tahun_respon' => request('tahun_respon')]) }}" 
                           class="btn {{ $selectedProdiId == $prodi->id ? 'bg-gradient-primary' : 'btn-outline-primary' }} btn-sm mb-0">{{ $prodi->kode_prodi }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER TAHUN (DROPDOWN) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.dashboard') }}" method="GET">
                        {{-- Input tersembunyi untuk menyimpan prodi_id yang sedang aktif --}}
                        <input type="hidden" name="prodi_id" value="{{ $selectedProdiId }}">

                        <div class="row align-items-end">
                            <div class="col-md-5">
                                <label for="tahun_lulus">Filter Tahun Lulus</label>
                                <select name="tahun_lulus" id="tahun_lulus" class="form-control">
                                    <option value="">-- Semua Tahun Lulus --</option>
                                    @foreach($tahunLulusList as $tahun)
                                        <option value="{{ $tahun->tahun_lulus }}" {{ $selectedTahunLulus == $tahun->tahun_lulus ? 'selected' : '' }}>{{ $tahun->tahun_lulus }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="tahun_respon">Filter Tahun Respon</label>
                                <select name="tahun_respon" id="tahun_respon" class="form-control">
                                    <option value="">-- Semua Tahun Respon --</option>
                                    @foreach($tahunResponList as $tahun)
                                        <option value="{{ $tahun->tahun_respon }}" {{ $selectedTahunRespon == $tahun->tahun_respon ? 'selected' : '' }}>{{ $tahun->tahun_respon }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn bg-gradient-primary w-100 mb-0">Terapkan</button>
                                <a href="{{ route('admin.dashboard', ['prodi_id' => $selectedProdiId]) }}" class="btn btn-link text-secondary w-100 mt-1">Reset Tahun</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAFIK BARIS PERTAMA -->
    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card z-index-2">
                <div class="card-header pb-0">
                    <h6>Data Lulusan (5 Tahun Terakhir)</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="lulusan-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Waktu Tunggu Mendapat Pekerjaan</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-7 text-center d-flex flex-column justify-content-center">
                            <div class="chart mx-auto">
                                <canvas id="waktu-tunggu-chart" class="chart-canvas" height="180"></canvas>
                            </div>
                            <h4 class="font-weight-bold mt-n8">
                                <span>{{ number_format($rataRataWaktuTunggu, 1) }}</span>
                                <span class="d-block text-sm text-muted">Rata-rata Bulan</span>
                            </h4>
                        </div>
                        <div class="col-5 my-auto">
                           <ul class="list-unstyled">
                                <li class="d-flex align-items-center mb-2">
                                    <span class="p-2 me-2 rounded" style="background-color: #5e72e4;"></span>
                                    <span class="text-xs">WT < 3 Bulan</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <span class="p-2 me-2 rounded" style="background-color: #2dce89;"></span>
                                    <span class="text-xs">3 ≤ WT ≤ 6 Bulan</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <span class="p-2 me-2 rounded" style="background-color: #fb6340;"></span>
                                    <span class="text-xs">6 < WT ≤ 12 Bulan</span>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="p-2 me-2 rounded" style="background-color: #f5365c;"></span>
                                    <span class="text-xs">WT > 12 Bulan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- GRAFIK BARIS KEDUA -->
     <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Data Responden per Prodi</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="responden-prodi-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Data Lulusan
    var ctxLulusan = document.getElementById("lulusan-chart").getContext("2d");
    new Chart(ctxLulusan, {
        type: "line",
        data: {
            labels: @json($tahunRange),
            datasets: [{
                label: "Jumlah Lulusan",
                tension: 0.4,
                borderWidth: 3,
                borderColor: "#5e72e4",
                backgroundColor: 'transparent',
                data: @json($dataLulusanChart),
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                },
            },
        },
    });

    // Grafik Waktu Tunggu
    var ctxWaktuTunggu = document.getElementById("waktu-tunggu-chart").getContext("2d");
    new Chart(ctxWaktuTunggu, {
        type: "doughnut",
        data: {
            labels: ['< 3 Bulan', '3-6 Bulan', '7-12 Bulan', '> 12 Bulan'],
            datasets: [{
                data: @json($waktuTungguChartData),
                backgroundColor: ['#5e72e4', '#2dce89', '#fb6340', '#f5365c'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            cutout: '75%',
        }
    });

     // Grafik Responden per Prodi
    var ctxRespondenProdi = document.getElementById("responden-prodi-chart").getContext("2d");
    new Chart(ctxRespondenProdi, {
        type: "bar",
        data: {
            labels: @json($respondenPerProdi->pluck('kode_prodi')),
            datasets: [{
                label: "Jumlah Responden",
                backgroundColor: "#5e72e4",
                data: @json($respondenPerProdi->pluck('responden_count')),
                maxBarThickness: 30
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
             plugins: {
                legend: {
                    display: false,
                }
            },
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true
                    }
                }
            }
        }
    });
</script>
@endpush

