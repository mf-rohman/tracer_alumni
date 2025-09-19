@extends('layouts.admin')

@section('title', 'Edit Instansi')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Form Edit Instansi & Akun Login</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.instansi.update', $instansi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama">Nama Instansi / Perusahaan</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $instansi->nama) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email untuk Login</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', optional($instansi->user)->email) }}"
                        required>
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password Baru (Opsional)</label>
                        <input type="password" class="form-control" id="password" name="password">
                         <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-gradient-primary">Update</button>
            <a href="{{ route('admin.instansi.index') }}" class="btn btn-link">Batal</a>
        </form>
    </div>
</div>
@endsection
