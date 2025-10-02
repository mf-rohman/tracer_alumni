@extends('layouts.instansi')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pengaturan Akun</h5>
                <p class="text-sm mb-0">Perbarui profil instansi dan ubah password Anda di sini.</p>
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

                {{-- BAGIAN 1: FORMULIR PROFIL INSTANSI --}}
                <h6 class="font-weight-bolder">Profil Instansi</h6>
                <form method="post" action="{{ route('instansi.profile.update') }}" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    @method('PUT')

                    {{-- Bagian Foto Profil --}}
                    <div class="row align-items-center mb-4">
                        <div class="col-md-4 text-center">
                            <img src="{{ $instansi->photo_path ? asset('storage/' . $instansi->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($instansi->nama) . '&background=4B49AC&color=fff&size=128' }}" 
                                 alt="Foto Profil" class="avatar avatar-xxl rounded-circle shadow-sm border">
                        </div>
                        <div class="col-md-8">
                            <label for="photo" class="form-label">Ubah Foto Profil</label>
                            <input class="form-control" type="file" id="photo" name="photo">
                            <small class="text-muted">Max: 2MB (JPG, PNG)</small>
                        </div>
                    </div>
                    
                    {{-- Bagian Data Diri --}}
                    <div class="form-group">
                        <label for="nama">Nama Instansi</label>
                        <input id="nama" name="nama" type="text" class="form-control" value="{{ old('nama', $instansi->nama) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email Login</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled readonly>
                         <small class="text-muted">Email tidak dapat diubah.</small>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-primary">Simpan Profil</button>
                    </div>
                </form>

                <hr class="horizontal dark my-4">

                {{-- BAGIAN 2: FORMULIR UBAH PASSWORD --}}
                <h6 class="font-weight-bolder">Ubah Password</h6>
                <form method="post" action="{{ route('instansi.password.update') }}" class="mt-4">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input id="current_password" name="current_password" type="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input id="password" name="password" type="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-primary">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

