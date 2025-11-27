@extends('layouts.admin')

@section('title', 'Status Impor Kuesioner')

@section('content')

@push('styles')
    <style>
        .progress {
            height: 20px !important;
            border-radius: 10px !important;
            overflow: hidden;
        }
        .progress-bar {
            padding: 0 !important;
            margin: 0 !important;
            border-radius: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        .progress .progress-bar {
            height: 100%;
        }
    </style>
@endpush

<div class="card" x-data="importStatus('{{ $batch->id }}')" x-init="startPolling()">
    <div class="card-header">
        <h6 class="mb-0">Status Proses Impor Kuesioner</h6>
        <p class="text-sm">Data sedang diproses di latar belakang. Anda bisa meninggalkan halaman ini dan kembali lagi nanti.</p>
    </div>
    <div class="card-body">
        
        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <span class="text-sm">Progress</span>
                <span class="text-sm font-weight-bold" x-text="progress + '%'"></span>
            </div>

            <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-gradient-success"
                     role="progressbar"
                     :style="'width: ' + progress + '%'">

                     <span class="label" x-text="progress > 10 ? progress + '%' : ''"></span>

                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-1">
                <span x-text="processedJobs"></span> dari <span x-text="totalJobs"></span> baris kuesioner telah diproses.
            </p>
            
            <div x-show="isFinished && failedJobs === 0">
                <div class="alert alert-success text-white">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Selesai!</strong> Semua data kuesioner berhasil diimpor.
                </div>
                <a href="{{ route('admin.responden.index') }}" class="btn btn-primary mt-2">Kembali ke Data Responden</a>
            </div>
            
            <div x-show="isFinished && failedJobs > 0">
                <div class="alert alert-warning text-white">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Selesai dengan catatan.</strong> Terdapat <span x-text="failedJobs"></span> baris yang gagal diimpor. Periksa log untuk detail.
                </div>
            </div>

             <div x-show="!isFinished">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="ms-1">Sedang memproses...</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script ini sama persis dengan yang Anda gunakan untuk impor alumni --}}
<script>
    function importStatus(batchId) {
        return {
            batchId: batchId,
            progress: 0,
            totalJobs: 0,
            processedJobs: 0,
            failedJobs: 0,
            isFinished: false,
            interval: null,
            
            startPolling() {
                this.fetchStatus();
                this.interval = setInterval(() => {
                    this.fetchStatus();
                }, 2000); // Cek status setiap 2 detik
            },
            
            fetchStatus() {
                if (this.isFinished) {
                    clearInterval(this.interval);
                    return;
                }
                
                fetch(`/api/kuesioner-import-status/${this.batchId}`)
                    .then(response => response.json())
                    .then(data => {
                        this.progress = data.progress;
                        this.totalJobs = data.totalJobs;
                        this.processedJobs = data.processedJobs;
                        this.failedJobs = data.failedJobs;
                        this.isFinished = data.finished;
                        
                        if (this.isFinished) {
                            clearInterval(this.interval);
                        }
                    })
                    .catch(() => {
                        clearInterval(this.interval);
                    });
            }
        }
    }
</script>
@endpush
