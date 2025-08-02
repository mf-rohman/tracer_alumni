@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Kartu Statistik -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Alumni</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $totalAlumni ?? 0 }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-user-graduate text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Pengguna Sistem</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $totalUsers ?? 0 }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-user-shield text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris untuk Grafik -->
    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Pendaftar Alumni (7 Hari Terakhir)</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        {{-- Data untuk chart sekarang dilewatkan melalui data attributes --}}
                        <canvas id="line-chart" 
                                class="chart-canvas" 
                                height="300"
                                data-labels="{{ json_encode($chartLabels) }}"
                                data-values="{{ json_encode($chartData) }}"
                        ></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const chartCanvas = document.getElementById("line-chart");
    const chartLabels = JSON.parse(chartCanvas.dataset.labels);
    const chartValues = JSON.parse(chartCanvas.dataset.values);

    var ctx = chartCanvas.getContext("2d");

    new Chart(ctx, {
        type: "line",
        data: {
            labels: chartLabels,
            datasets: [{
                label: "Pendaftar",
                tension: 0.4,
                borderWidth: 3,
                borderColor: "#5e72e4",
                backgroundColor: "rgba(94, 114, 228, 0.1)",
                fill: true,
                data: chartValues,
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
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#6c757d'
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                    },
                    ticks: {
                        display: true,
                        color: '#6c757d',
                        padding: 20
                    }
                },
            },
        },
    });
</script>
@endpush
