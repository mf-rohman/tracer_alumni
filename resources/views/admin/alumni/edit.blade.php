@extends('layouts.admin')

@section('title', 'Edit Alumni')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Form Edit Data Alumni</h6>
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
        <form action="{{ route('admin.alumni.update', $alumnus->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $alumnus->nama_lengkap) }}" required>
                    </div>
                </div>
                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="npm">NPM</label>
                        <input type="text" class="form-control" id="npm" name="npm" value="{{ old('npm', $alumnus->npm) }}" required>
                    </div>
                </div>
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $alumnus->user->email) }}" required>
                    </div>
                </div>
                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="prodi_id">Program Studi</label>
                        <select class="form-control" id="prodi_id" name="prodi_id" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodi as $p)
                                <option value="{{ $p->id }}" {{ old('prodi_id', $alumnus->prodi_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_masuk">Tahun Masuk</label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', $alumnus->tahun_masuk) }}">
                    </div>
                </div>
                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_lulus">Tahun Lulus</label>
                        <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', $alumnus->tahun_lulus) }}" required>
                    </div>
                </div>
                 <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ipk">IPK</label>
                        <input type="text" class="form-control" id="ipk" name="ipk" value="{{ old('ipk', $alumnus->ipk) }}">
                    </div>
                </div>
                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="no_hp">No. HP (Opsional)</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $alumnus->no_hp) }}">
                    </div>
                </div>
                <!-- Kolom Alamat (Satu Baris Penuh) -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="alamat">Alamat (Opsional)</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $alumnus->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn bg-gradient-primary">Update</button>
            <a href="{{ route('admin.alumni.kategori') }}" class="btn btn-link">Batal</a>
        </form>
    </div>
</div>
@endsection
