@extends('layouts.admin')
@section('title', 'Tambah User Baru')
@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Form Tambah User Baru</h6>
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
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required onchange="toggleProdiSelect()">
                    <option value="">-- Pilih Role --</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="bak" {{ old('role') == 'bak' ? 'selected' : '' }}>BAK</option>
                    <option value="admin_prodi" {{ old('role') == 'admin_prodi' ? 'selected' : '' }}>Admin Prodi</option>
                </select>
            </div>
            {{-- Dropdown Prodi, awalnya disembunyikan --}}
            <div class="form-group" id="prodi-select-div" style="display:none;">
                <label for="prodi_id">Program Studi</label>
                <select class="form-control" id="prodi_id" name="prodi_id">
                    <option value="">-- Pilih Prodi --</option>
                    @foreach($prodi as $p)
                        <option value="{{ $p->kode_prodi }}">{{ $p->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn bg-gradient-primary">Simpan</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-link">Batal</a>
        </form>
    </div>
</div>

<script>
    function toggleProdiSelect() {
        var roleSelect = document.getElementById('role');
        var prodiSelectDiv = document.getElementById('prodi-select-div');
        if (roleSelect.value === 'admin_prodi') {
            prodiSelectDiv.style.display = 'block';
        } else {
            prodiSelectDiv.style.display = 'none';
        }
    }
    // Panggil fungsi saat halaman dimuat untuk memeriksa nilai awal
    document.addEventListener('DOMContentLoaded', toggleProdiSelect);
</script>
@endsection
