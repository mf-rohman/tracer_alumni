@extends('layouts.alumni')

@section('title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pengaturan Profil</h5>
                <p class="text-sm mb-0">Perbarui foto dan informasi pribadi Anda di sini.</p>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success text-white">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger text-white">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    {{-- Bagian Foto Profil --}}
                    <div class="row align-items-center mb-4">
                        <div class="col-md-3 text-center">
                            <img src="{{ $alumni->photo_path ? asset('storage/' . $alumni->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4B49AC&color=fff&size=128' }}"
                                 alt="Foto Profil"
                                 class="avatar avatar-xxl rounded-circle shadow-sm border">
                        </div>
                        <div class="col-md-9">
                            <label for="photo" class="form-label">Ubah Foto Profil</label>
                            <input class="form-control" type="file" id="photo" name="photo">
                            <small class="text-muted">Format yang didukung: JPG, PNG. Ukuran maksimal: 2MB.</small>
                        </div>
                    </div>

                    <hr class="horizontal dark">

                    {{-- Bagian Data Diri --}}
                    <h6 class="font-weight-bolder mt-4">Informasi Personal</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input id="nama_lengkap" name="nama_lengkap" type="text" class="form-control" value="{{ old('nama_lengkap', $alumni->nama_lengkap) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_hp">No. Handphone</label>
                                <input id="no_hp" name="no_hp" type="tel" class="form-control" value="{{ old('no_hp', $alumni->no_hp) }}">
                            </div>
                        </div>
                         <div class="col-md-12">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea id="alamat" name="alamat" class="form-control" rows="3">{{ old('alamat', $alumni->alamat) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

