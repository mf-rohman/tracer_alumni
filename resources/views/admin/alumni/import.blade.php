@extends('layouts.admin')

@section('title', 'Import Data Alumni')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Import Alumni dari Excel</h6>
    </div>
    <div class="card-body">
        <p>Unggah file Excel (.xlsx, .xls, .csv) untuk menambahkan data alumni secara massal. Pastikan format file Anda sesuai dengan template yang disediakan.</p>

        <a href="{{ route('admin.alumni.template.download') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel me-2"></i> Unduh Template Excel
        </a>

        <hr class="horizontal dark">

        @if(session('import_errors'))
            <div class="alert alert-danger text-white">
                <h6 class="alert-heading text-white mb-1">Impor Gagal dengan Beberapa Kesalahan Validasi!</h6>
                <p class="mb-2">Silakan perbaiki baris-baris berikut di file Excel Anda dan coba unggah kembali:</p>
                <ul class="mb-0 ps-3">
                    {{-- Loop ini akan menampilkan setiap pesan error dari controller --}}
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.alumni.import.handle') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="file">Pilih File Excel</label>
                <input type="file" name="file" id="file" class="form-control" required accept=".xlsx, .xls, .csv">
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.alumni.index') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn bg-gradient-primary">
                    <i class="fas fa-upload me-2"></i> Import Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

