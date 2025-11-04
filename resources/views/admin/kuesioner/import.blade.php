@extends('layouts.admin')

@section('title', 'Import Data Kuesioner')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Import Kuesioner dari Excel</h6>
    </div>
    <div class="card-body">
        <p>Unggah file Excel untuk menambahkan data kuesioner secara massal. Pastikan format file Anda sesuai dengan template.</p>
        <p class="text-danger">
            <strong>Penting:</strong> Fitur ini akan **menimpa** data kuesioner yang sudah ada jika ditemukan `NPM` dan `Tahun Kuesioner` yang sama.
        </p>
        
        <a href="{{ route('admin.kuesioner.template.download') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel me-2"></i> Unduh Template Kuesioner
        </a>

        <hr class="horizontal dark">

        @if(session('import_errors'))
            <div class="alert alert-danger text-white">
                <h6 class="alert-heading text-white mb-1">Impor Gagal dengan Kesalahan Validasi!</h6>
                <ul class="mb-0 ps-3">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.kuesioner.import.handle') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="file">Pilih File Excel</label>
                <input type="file" name="file" id="file" class="form-control" required accept=".xlsx, .xls, .csv">
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.responden.index') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn bg-gradient-primary">
                    <i class="fas fa-upload me-2"></i> Mulai Proses Impor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
