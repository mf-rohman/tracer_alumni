@extends('layouts.admin') @section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Import Data Alumni dari Excel</h1>

    <div class="card shadow mb-4">
        <div class="card-header">
            <p>
                Pastikan file Excel Anda memiliki kolom header berikut:
                `nama_lengkap`, `email`, `npm`, `kode_prodi`, `tahun_lulus`,
                `no_hp`, `alamat`.
            </p>
        </div>
        <div class="card-body">
            @if(session('error'))
            <div class="alert alert-danger">{{ session("error") }}</div>
            @endif
            <form
                action="{{ route('admin.alumni.import.handle') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                <div class="form-group">
                    <label for="file">Pilih File Excel</label>
                    <input
                        type="file"
                        name="file"
                        class="form-control"
                        required
                    />
                </div>
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
    </div>
</div>
@endsection
