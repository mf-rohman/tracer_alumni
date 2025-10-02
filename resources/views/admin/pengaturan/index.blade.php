@extends('layouts.admin')
@section('title', 'Pengaturan Kuesioner')
@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Pengaturan Tahun Aktif Kuesioner</h6>
    </div>
    <div class="card-body">
        <p>Pilih tahun kuesioner yang ingin Anda buka untuk diisi oleh alumni. Alumni hanya dapat mengisi kuesioner pada tahun yang aktif.</p>
        <form action="{{ route('admin.pengaturan.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_kuesioner_aktif">Tahun Kuesioner Aktif</label>
                        <select name="tahun_kuesioner_aktif" id="tahun_kuesioner_aktif" class="form-control" required>
                            @for ($year = date('Y') + 1; $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ $tahunAktif == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-gradient-primary">Simpan Pengaturan</button>
        </form>
    </div>
</div>
@endsection