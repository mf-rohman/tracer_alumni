@extends('layouts.admin')

@section('title', 'Tambah Instansi Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Form Tambah Instansi & Akun Login</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.instansi.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama">Nama Instansi / Perusahaan</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email untuk Login</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-gradient-primary">Simpan</button>
            <a href="{{ route('admin.instansi.index') }}" class="btn btn-link">Batal</a>
        </form>
    </div>
</div>
@endsection
