@extends('layouts.admin')

@section('title', 'Form Penilaian Alumni')

@section('content')
<div class="card" x-data="{
    bidang_usaha: '{{ old('bidang_usaha', $penilaian->bidang_usaha ?? '') }}',
    kesesuaian_ilmu: '{{ old('kesesuaian_ilmu', $penilaian->kesesuaian_ilmu ?? '') }}'
}">
    <div class="card-header">
        <h5 class="mb-0">Formulir Penilaian Kinerja Alumni</h5>
        <p class="text-sm">Anda sedang menilai: <strong>{{ $alumnus->nama_lengkap }} ({{ $alumnus->npm }})</strong></p>
    </div>
    <div class="card-body">
         <form action="{{ route('instansi.penilaian.store', $alumnus) }}" method="POST">
            @csrf

            {{-- SECTION 1: IDENTITAS PENILAI --}}
            <h6 class="font-weight-bolder text-primary">Bagian 1: Informasi Penilai</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nama Bapak/Ibu</label>
                    <input type="text" name="nama_penilai" class="form-control" value="{{ old('nama_penilai', $penilaian->nama_penilai ?? '') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>No Handphone</label>
                    <input type="tel" name="no_hp_penilai" class="form-control" value="{{ old('no_hp_penilai', $penilaian->no_hp_penilai ?? '') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Alamat Email (Opsional)</label>
                    <input type="email" name="email_penilai" class="form-control" value="{{ old('email_penilai', $penilaian->email_penilai ?? '') }}">
                </div>
                 <div class="col-md-6 mb-3">
                    <label>Jabatan</label>
                    <input type="text" name="jabatan_penilai" class="form-control" value="{{ old('jabatan_penilai', $penilaian->jabatan_penilai ?? '') }}" required>
                </div>
                 <div class="col-md-6 mb-3">
                    <label>Alamat Website/Situs (Opsional)</label>
                    <input type="url" name="website_instansi" class="form-control" value="{{ old('website_instansi', $penilaian->website_instansi ?? '') }}" placeholder="https://contoh.com">
                </div>
                 <div class="col-md-6 mb-3">
                    <label>Bidang Usaha</label>
                    <select name="bidang_usaha" x-model="bidang_usaha" class="form-control" required>
                        <option value="">-- Pilih Bidang Usaha --</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="Jasa">Jasa</option>
                        <option value="Perdagangan">Perdagangan</option>
                        <option value="Pertanian">Pertanian</option>
                        <option value="Kebudayaan dan Pariwisata">Kebudayaan dan Pariwisata</option>
                        <option value="Other">Lainnya</option>
                    </select>
                 </div>
                 <div class="col-md-12 mb-3" x-show="bidang_usaha === 'Other'" x-transition>
                     <label>Sebutkan Bidang Usaha Lainnya</label>
                     <input type="text" name="bidang_usaha_lainnya" class="form-control" value="{{ old('bidang_usaha_lainnya', $penilaian->bidang_usaha_lainnya ?? '') }}">
                 </div>
            </div>
            
            <hr class="horizontal dark my-4">

            {{-- SECTION 2: PENILAIAN KOMPETENSI --}}
            <h6 class="font-weight-bolder text-primary">Bagian 2: Penilaian Kinerja Lulusan</h6>
             <p class="text-sm">Bagaimana kemampuan lulusan sesuai dengan kompetensi berikut? (1=Kurang, 2=Cukup, 3=Baik, 4=Sangat Baik)</p>
            @php
                $kompetensi = [
                    'integritas' => 'Integritas (Etika dan Moral)', 'bahasa_inggris' => 'Kemampuan Bahasa Inggris', 'tik' => 'Penggunaan TIK',
                    'leadership' => 'Leadership', 'komunikasi' => 'Kemampuan Komunikasi', 'kerjasama_tim' => 'Kerjasama Tim',
                    'pengembangan_diri' => 'Pengembangan Diri', 'kedisiplinan' => 'Kedisiplinan', 'kejujuran' => 'Kejujuran',
                    'motivasi_kerja' => 'Motivasi Kerja', 'etos_kerja' => 'Etos Kerja', 'inovasi' => 'Inovasi dan Kreativitas',
                    'problem_solving' => 'Problem Solving', 'wawasan_antar_bidang' => 'Wawasan Antar Bidang Ilmu'
                ];
            @endphp
            @foreach($kompetensi as $key => $label)
            <div class="row align-items-center mb-3">
                <div class="col-md-4">
                    <label class="form-control-label">{{ $label }}</label>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-around">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="{{ $key }}" id="{{ $key }}{{ $i }}" value="{{ $i }}" {{ (old($key, $penilaian ? $penilaian->$key : '') == $i) ? 'checked' : '' }} required>
                            <label class="form-check-label" for="{{ $key }}{{ $i }}">{{ $i }}</label>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
            @endforeach

            <div class="form-group mt-4">
                <label class="fw-bold">Bagaimanakah kinerja lulusan tersebut secara keseluruhan?</label>
                <div>
                     @foreach(['Sangat baik', 'Baik', 'Cukup baik', 'Buruk'] as $kinerja)
                     <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kinerja_keseluruhan" id="kinerja_{{ Str::slug($kinerja) }}" value="{{ $kinerja }}" {{ (old('kinerja_keseluruhan', $penilaian->kinerja_keseluruhan ?? '') == $kinerja) ? 'checked' : '' }} required>
                        <label class="form-check-label" for="kinerja_{{ Str::slug($kinerja) }}">{{ $kinerja }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <hr class="horizontal dark my-4">

            {{-- SECTION 3: UMPAN BALIK UMUM --}}
            <h6 class="font-weight-bolder text-primary">Bagian 3: Umpan Balik untuk Universitas</h6>
            <div class="form-group">
                <label>Apa saja bidang pekerjaan yang ditekuni lulusan pada instansi Anda?</label>
                <textarea name="bidang_pekerjaan_ditekuni" rows="3" class="form-control">{{ old('bidang_pekerjaan_ditekuni', $penilaian->bidang_pekerjaan_ditekuni ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label>Apa saja posisi yang telah dicapai oleh lulusan pada instansi Anda?</label>
                <textarea name="posisi_dicapai" rows="3" class="form-control">{{ old('posisi_dicapai', $penilaian->posisi_dicapai ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label>Apakah bekal ilmu yang dimiliki lulusan sudah sesuai dengan kebutuhan?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kesesuaian_ilmu" id="ilmu_sesuai" value="Sudah" x-model="kesesuaian_ilmu" required>
                        <label class="form-check-label" for="ilmu_sesuai">Sudah</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kesesuaian_ilmu" id="ilmu_belum" value="Belum" x-model="kesesuaian_ilmu" required>
                        <label class="form-check-label" for="ilmu_belum">Belum</label>
                    </div>
                </div>
            </div>
            <div class="form-group" x-show="kesesuaian_ilmu === 'Sudah'" x-transition>
                <label>Jika ilmu telah sesuai, ilmu/keterampilan tambahan apa yang perlu dimiliki?</label>
                <textarea name="ilmu_tambahan_sesuai" rows="3" class="form-control">{{ old('ilmu_tambahan_sesuai', $penilaian->ilmu_tambahan_sesuai ?? '') }}</textarea>
            </div>
            <div class="form-group" x-show="kesesuaian_ilmu === 'Belum'" x-transition>
                <label>Jika ilmu belum sesuai, ilmu/keterampilan apa yang terutama diperlukan?</label>
                <textarea name="ilmu_diperlukan_belum_sesuai" rows="3" class="form-control">{{ old('ilmu_diperlukan_belum_sesuai', $penilaian->ilmu_diperlukan_belum_sesuai ?? '') }}</textarea>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('instansi.dashboard') }}" class="btn btn-link me-2">Batal</a>
                <button type="submit" class="btn bg-gradient-primary">Simpan Penilaian</button>
            </div>
        </form>
    </div>
</div>
@endsection

