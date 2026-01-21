@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-3">

    {{-- [PERUBAHAN] Tambahkan Library Tom Select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .ts-control { border-radius: 0.25rem; font-size: 0.75rem; padding: 2px 8px; min-height: 32px; }
        .ts-dropdown { font-size: 0.8rem; }
        .ts-wrapper.multi .ts-control > div { background: #e9ecef; color: #344767; border-radius: 3px; padding: 0 6px; }
    </style>

    <div class="row">
        {{-- Chart 1: Responden --}}
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card h-200">
                <div class="card-body px-3 py-2 d-flex align-items-center">
                    <div class="row align-items-center">
                        <div class="col-7 pe-0">
                            <div class="numbers">
                                <p class="text-xxs mb-0 text-capitalize text-muted text-truncate" style="font-size: 0.65rem;">Responden</p>
                                <h6 class="font-weight-bold mb-0 mt-1" style="font-size: 0.8rem; line-height: 1.2;">{{ $totalResponden }} / {{ $totalAlumni }}</h6>
                            </div>
                        </div>
                        <div class="col-5 text-end ps-0">
                            <div class="position-relative d-inline-block" style="height: 40px; width: 40px;">
                                <canvas id="chart-responden"></canvas>
                                <small class="position-absolute top-50 start-50 translate-middle font-weight-bold" style="font-size: 0.65rem; display:block;">{{ $persentaseResponden }}%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Looping untuk 5 status lainnya --}}
        @php
            $statusColors = [
                'Bekerja' => '#2dce89', 
                'Wiraswasta' => '#11cdef', 
                'Studi Lanjut' => '#fb6340', 
                'Mencari Kerja' => '#f5365c', 
                'Tidak Bekerja' => '#8898aa', 
            ];
        @endphp
        @foreach($statusData as $status => $data)
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card">
                <div class="card-body px-3 py-2">
                    <div class="row align-items-center">
                        <div class="col-7 pe-0">
                            <div class="numbers">
                                <p class="text-xxs mb-0 text-capitalize text-muted" style="font-size: 0.65rem;">{{ $status }}</p>
                                <h6 class="font-weight-bold mb-0 mt-1" style="font-size: 0.8rem; line-height: 1.2;">{{ $data['count'] ?? 0 }} / {{ $totalResponden ?? 0 }}</h6>
                            </div>
                        </div>
                        <div class="col-5 text-end ps-0">
                            <div class="position-relative d-inline-block" style="height: 40px; width: 40px;">
                                <canvas id="chart-{{ Str::slug($status) }}"></canvas>
                                <small class="position-absolute top-50 start-50 translate-middle font-weight-bold" style="font-size: 0.65rem; display:block;">{{ $data['percentage'] ?? 0 }}%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- [PERUBAHAN] Filter Prodi Menjadi FORM (Multi Select) --}}
    {{-- Sebelumnya ini hanya link tombol, sekarang jadi Form Filter agar bisa Multi-Select --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <form action="{{ route('admin.dashboard') }}" method="GET" id="filterForm">
                        
                        <div class="row gx-2 align-items-end">
                            
                            {{-- Filter Prodi (Hanya muncul jika bukan Admin Prodi) --}}
                            @if(auth()->user()->role !== 'admin_prodi')
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="prodi_id" class="form-label text-xs font-weight-bold mb-1 text-uppercase text-secondary">Program Studi</label>
                                {{-- [PERUBAHAN] Gunakan array name="prodi_id[]" dan class .tom-select --}}
                                <select name="prodi_id[]" id="prodi_id" class="tom-select" multiple placeholder="Pilih Prodi...">
                                    @foreach($prodiList as $prodi)
                                        <option value="{{ $prodi->kode_prodi }}" {{ in_array($prodi->kode_prodi, $selectedProdiId) ? 'selected' : '' }}>
                                            {{ $prodi->singkatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Filter Tahun Lulus --}}
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="tahun_lulus" class="form-label text-xs font-weight-bold mb-1 text-uppercase text-secondary">Tahun Lulus</label>
                                <select name="tahun_lulus[]" id="tahun_lulus" class="tom-select" multiple placeholder="Pilih Tahun...">
                                    @foreach($tahunLulusList as $tahun)
                                        <option value="{{ $tahun->tahun_lulus }}" {{ in_array($tahun->tahun_lulus, $selectedTahunLulus) ? 'selected' : '' }}>
                                            {{ $tahun->tahun_lulus }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Tahun Pengisian --}}
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="tahun_respon" class="form-label text-xs font-weight-bold mb-1 text-uppercase text-secondary">Tahun Pengisian</label>
                                <select name="tahun_respon[]" id="tahun_respon" class="tom-select" multiple placeholder="Pilih Tahun...">
                                    @foreach($tahunResponList as $tahun)
                                        <option value="{{ $tahun->tahun_respon }}" {{ in_array($tahun->tahun_respon, $selectedTahunRespon) ? 'selected' : '' }}>
                                            {{ $tahun->tahun_respon }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pencarian NPM --}}
                            <!-- <div class="col-md-3 mb-2 mb-md-0">
                                <label for="npm" class="form-label text-xs font-weight-bold mb-1 text-uppercase text-secondary">Cari NPM</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text text-xs"><i class="fas fa-search"></i></span>
                                    <input type="text" name="npm" id="npm" class="form-control form-control-sm" value="{{ request('npm') }}" placeholder="Ketik NPM...">
                                </div>
                            </div> -->

                            {{-- Tombol Aksi --}}
                            <div class="col-md-2 d-flex ">
                                <button type="submit" class="btn btn-sm bg-gradient-primary w-100 mb-0 me-1">Terapkan</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary w-100 mb-0">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK BARIS PERTAMA --}}
    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0">
                    <h6>Data Lulusan (Trend)</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="lulusan-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>Waktu Tunggu Mendapat Pekerjaan</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-7 text-center d-flex flex-column justify-content-center">
                            <div class="chart">
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
                                    <span class="text-xs">3-6 Bulan</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <span class="p-2 me-2 rounded" style="background-color: #fb6340;"></span>
                                    <span class="text-xs">7-12 Bulan</span>
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="p-2 me-2 rounded" style="background-color: #f5365c;"></span>
                                    <span class="text-xs">> 12 Bulan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK BARIS KEDUA (Perbandingan Prodi) --}}
    @if(auth()->user()->role !== 'admin_prodi')
     <div class="row mt-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>Perbandingan Alumni vs. Responden per Prodi</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="perbandingan-prodi-chart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- [PERUBAHAN] Script Tom Select --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        
        // [PERUBAHAN] Inisialisasi Tom Select
        document.querySelectorAll('.tom-select').forEach((el) => {
            new TomSelect(el, {
                plugins: ['remove_button'],
                maxItems: null,
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                create: false
            });
        });

        // Helper Donat Kecil
        function createDoughnutChart(elementId, data, color) {
            var ctx = document.getElementById(elementId);
            if (!ctx) return; 
            new Chart(ctx.getContext("2d"), {
                type: "doughnut",
                data: {
                    datasets: [{
                        data: data,
                        backgroundColor: [color, '#e9ecef'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    cutout: '80%',
                }
            });
        }

        // Init Chart Statistik
        createDoughnutChart('chart-responden', @json($chartDataResponden), '#fb6340');
        
        @foreach($statusData as $status => $data)
            @php
                $chartData = $data['chartData'] ?? [0,0];
                $color = $statusColors[$status] ?? '#5e72e4';
            @endphp
            createDoughnutChart('chart-{{ Str::slug($status) }}', @json($chartData), '{{ $color }}');
        @endforeach

        // Grafik Lulusan (Trend)
        var ctxLulusan = document.getElementById("lulusan-chart");
        if (ctxLulusan) {
            new Chart(ctxLulusan.getContext("2d"), {
                type: "line", // Ubah ke Line agar lebih cocok untuk Trend
                data: {
                    labels: @json($tahunRange),
                    datasets: [{
                        label: "Jumlah Lulusan",
                        data: @json($dataLulusanChart),
                        borderColor: "#5e72e4",
                        backgroundColor: 'rgba(94, 114, 228, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }],
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { borderDash: [2, 2], drawBorder: false }, ticks: { beginAtZero: true } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Grafik Waktu Tunggu
        var ctxWaktuTunggu = document.getElementById("waktu-tunggu-chart");
        if (ctxWaktuTunggu) {
            new Chart(ctxWaktuTunggu.getContext("2d"), {
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
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    cutout: '75%',
                }
            });
        }

        // [PERUBAHAN] Grafik Perbandingan Prodi (Grouped Bar)
        const ctxPerbandinganProdi = document.getElementById('perbandingan-prodi-chart');
        if (ctxPerbandinganProdi) {
            new Chart(ctxPerbandinganProdi.getContext("2d"), {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: "Total Alumni",
                            data: @json($chartDataTotalAlumni),
                            backgroundColor: '#5e72e4',
                            borderRadius: 4,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        },
                        {
                            label: "Total Responden",
                            data: @json($chartDataTotalResponden),
                            backgroundColor: '#2dce89',
                            borderRadius: 4,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' }
                    },
                    scales: { 
                        y: { ticks: { beginAtZero: true } },
                        x: { grid: { display: false } }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                }
            });
        }
    });
</script>
@endpush