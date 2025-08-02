@extends('layouts.admin')

@section('title', 'Tambah Alumni')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Form Tambah Alumni Baru</h6>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger text-white">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.alumni.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="npm">NPM</label>
                        <input type="text" class="form-control" id="npm" name="npm" value="{{ old('npm') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="prodi_id">Program Studi</label>
                        <select class="form-control" id="prodi_id" name="prodi_id" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodi as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_lulus">Tahun Lulus</label>
                        <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="no_hp">No. HP (Opsional)</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}">
                    </div>
                </div>
            </div>
             <div class="form-group">
                <label for="alamat">Alamat (Opsional)</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
            </div>
            <button type="submit" class="btn bg-gradient-primary">Simpan</button>
            <a href="{{ route('admin.alumni.index') }}" class="btn btn-link">Batal</a>
        </form>
    </div>
</div>
@endsection
