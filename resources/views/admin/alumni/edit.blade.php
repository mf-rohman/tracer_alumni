@extends('layouts.admin')

@section('title', 'Edit Data Alumni')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Formulir Edit Alumni</h6>
        <p class="text-sm mb-0">Perbarui detail alumni di bawah ini.</p>
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

        {{-- PERBAIKAN: Mengarahkan form ke route 'admin.alumni.update' dengan metode PUT --}}
        <form action="{{ route('admin.alumni.update', $alumnus->uuid) }}" method="POST">
            @csrf
            @method('PUT')
            
            <h6 class="font-weight-bolder">Informasi Akademik</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="npm">NPM</label>
                        <input type="text" class="form-control" id="npm" name="npm" value="{{ old('npm', $alumnus->npm) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="prodi_id">Program Studi</label>
                        <select name="prodi_id" id="prodi_id" class="form-control" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi->kode_prodi }}" {{ old('prodi_id', $alumnus->prodi_id) == $prodi->kode_prodi ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_masuk">Tahun Masuk</label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', $alumnus->tahun_masuk) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_lulus">Tahun Lulus</label>
                        <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', $alumnus->tahun_lulus) }}" required>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="ipk">IPK</label>
                        <input type="text" class="form-control" id="ipk" name="ipk" value="{{ old('ipk', $alumnus->ipk) }}">
                    </div>
                </div>
            </div>

            <hr class="horizontal dark">
            <h6 class="font-weight-bolder mt-3">Informasi Personal & Akun</h6>
            <div class="row">
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $alumnus->nama_lengkap) }}" required>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $alumnus->user->email) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nik">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $alumnus->nik) }}" required maxlength="16" pattern="\d{16}" title="NIK harus terdiri dari 16 digit angka.">
                         <small class="form-text text-muted">NIK akan digunakan sebagai verifikasi login kedua.</small>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="no_hp">No. Handphone</label>
                        <input type="tel" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $alumnus->no_hp) }}">
                    </div>
                </div>
                <div class="col-md-12">
                     <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat', $alumnus->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.alumni.kategori') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn bg-gradient-primary">Update Alumni</button>
            </div>
        </form>
    </div>
</div>
@endsection
