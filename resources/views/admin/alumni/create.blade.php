@extends('layouts.admin')

@section('title', 'Tambah Alumni')

@section('content')
<div class="card">
    <div class="card-header pb-0">
        <h6 class="mb-0">Panduan Form Kuesioner Tracer Study</h6>
    </div>
    <div class="card-body">
        <h6 class="font-weight-bolder">Identitas</h6>
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
                        <label for="npm">NIM</label>
                        <input type="text" class="form-control" id="npm" name="npm" value="{{ old('npm') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kode_pt">Kode PT</label>
                        <input type="text" class="form-control" id="kode_pt" name="kode_pt" value="{{ old('kode_pt') }}" placeholder="Contoh: 071073">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_masuk">Tahun Masuk</label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk') }}" placeholder="Contoh: 2017">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_lulus">Tahun Lulus</label>
                        <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus') }}" required placeholder="Contoh: 2021">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="prodi_id">Program Studi</label>
                        <select class="form-control" id="prodi_id" name="prodi_id" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodi as $p)
                                <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="no_hp">Nomor Telepon/HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}">
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="npwp">NPWP</label>
                        <input type="text" class="form-control" id="npwp" name="npwp" value="{{ old('npwp') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ipk">IPK</label>
                        <input type="text" class="form-control" id="ipk" name="ipk" value="{{ old('ipk') }}" placeholder="Contoh: 3.75">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-gradient-primary mt-3">Simpan</button>
            <a href="{{ route('admin.alumni.index') }}" class="btn btn-link mt-3">Batal</a>
        </form>
    </div>
</div>
@endsection
