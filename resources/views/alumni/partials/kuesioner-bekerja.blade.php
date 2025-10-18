{{-- Bagian ini akan memuat pertanyaan-pertanyaan lain dari file partials --}}
<!-- <div x-show="status === '1'" x-transition.duration.300ms class="border rounded-3 p-3 mt-4 bg-gray-100"> -->
    {{-- ▼▼▼ KODE PARTIAL KUESIONER-BEKERJA YANG SUDAH DIPERBARUI ▼▼▼ --}}
<h5 class="font-weight-bolder mb-4">Pertanyaan Lanjutan (Bekerja)</h5>
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="f502_bekerja" class="form-label">Dalam berapa bulan Anda mendapatkan pekerjaan pertama setelah lulus?</label>
            <input type="number" id="f502_bekerja" name="f502" value="{{ old('f502', $answer->f502 ?? '') }}" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="f505" class="form-label">Berapa rata-rata pendapatan Anda per bulan? (take home pay)</label>
            <input type="number" id="f505" name="f505" value="{{ old('f505', $answer->f505 ?? '') }}" class="form-control" placeholder="Contoh: 5000000">
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <label for="f5a1" class="form-label">Provinsi tempat Anda bekerja?</label>
        <select id="f5a1" name="f5a1" x-model="selectedProvince" @change="fetchRegencies" class="form-control">
            <option value="">-- Pilih Provinsi --</option>
            @foreach($provinces as $province)
                <option value="{{ $province->code }}">{{ $province->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-4">
        <label for="f5a2" class="form-label">Kota/Kabupaten tempat Anda bekerja?</label>
        <select id="f5a2" name="f5a2" x-model="selectedRegency" class="form-control" :disabled="!selectedProvince || loadingRegencies">
            <option value="" x-show="loadingRegencies">Memuat...</option>
            <template x-if="!loadingRegencies && regencies.length === 0 && selectedProvince">
                <option value="">-- Tidak ada data --</option>
            </template>
            <template x-if="!selectedProvince">
                <option value="">-- Pilih Provinsi Dahulu --</option>
            </template>
            <template x-for="regency in regencies" :key="regency.code">
                <option :value="regency.code" x-text="regency.name"></option>
            </template>
        </select>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="f1101" class="form-label">Apa jenis perusahaan/instansi tempat Anda bekerja?</label>
            <select id="f1101" name="f1101" x-model="f1101" class="form-control">
                <option value="">Pilih Jenis</option>
                <option value="1">Instansi pemerintah</option>
                <option value="2">Organisasi non-profit/LSM</option>
                <option value="3">Perusahaan swasta</option>
                <option value="4">Wiraswasta/perusahaan sendiri</option>
                <option value="5">BUMN/BUMD</option>
                <option value="6">Institusi/Organisasi Multilateral</option>
                <option value="7">Lainnya</option>
            </select>

            <div x-show="f1101 === '7'" x-transition class="mt-2">
                <label for="f1102" class="form-label">Sebutkan jenis perusahaan lainnya:</label>
                <input type="text" id="f1102" name="f1102" value="{{ old('f1102', $answer->f1102 ?? '') }}" class="form-control">
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <label for="f5b" class="form-label">Apa nama perusahaan/kantor tempat Anda bekerja?</label>
        
        <select id="f5b" name="f5b" placeholder="Ketik untuk mencari nama perusahaan..." :disabled="instansiTidakDitemukan"></select>
        
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="instansi-not-found" x-model="instansiTidakDitemukan">
            <label class="form-check-label" for="instansi-not-found">
                Perusahaan/instansi saya tidak ada dalam daftar.
            </label>
        </div>
    </div>

    {{-- 3. Input Teks Alternatif --}}
    <div class="col-md-12 mb-4" x-show="instansiTidakDitemukan" x-transition>
         <label for="f5b_plain" class="form-label">Silakan ketik nama perusahaan/instansi Anda:</label>
         {{-- Input ini hanya aktif jika checkbox di atas dicentang --}}
         <input type="text" id="f5b_plain" name="f5b" class="form-control" placeholder="Contoh: PT Jaya Abadi" :disabled="!instansiTidakDitemukan">
    </div>
    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="f5c" class="form-label">Apa posisi/jabatan Anda saat ini?</label>
            <input type="text" id="f5c" name="f5c" value="{{ old('f5c', $answer->f5c ?? '') }}" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="f14" class="form-label">Seberapa erat hubungan bidang studi dengan pekerjaan Anda?</label>
            <select id="f14" name="f14" class="form-control">
                <option value="">Pilih Hubungan</option>
                <option value="1" @if(old('f14', $answer->f14 ?? '') == '1') selected @endif>Sangat Erat</option>
                <option value="2" @if(old('f14', $answer->f14 ?? '') == '2') selected @endif>Erat</option>
                <option value="3" @if(old('f14', $answer->f14 ?? '') == '3') selected @endif>Cukup Erat</option>
                <option value="4" @if(old('f14', $answer->f14 ?? '') == '4') selected @endif>Kurang Erat</option>
                <option value="5" @if(old('f14', $answer->f14 ?? '') == '5') selected @endif>Tidak Sama Sekali</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="f15" class="form-label">Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat ini?</label>
            <select id="f15" name="f15" class="form-control">
                <option value="">Pilih Tingkat</option>
                <option value="1" @if(old('f15', $answer->f15 ?? '') == '1') selected @endif>Setingkat Lebih Tinggi</option>
                <option value="2" @if(old('f15', $answer->f15 ?? '') == '2') selected @endif>Tingkat yang Sama</option>
                <option value="3" @if(old('f15', $answer->f15 ?? '') == '3') selected @endif>Setingkat Lebih Rendah</option>
                <option value="4" @if(old('f15', $answer->f15 ?? '') == '4') selected @endif>Tidak Perlu Pendidikan Tinggi</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group mb-4" x-data="{ f301: '{{ old('f301', $answer->f301 ?? '') }}' }">
    <label class="form-label"><b>Kapan anda mulai mencari pekerjaan?</b></label>
    <div class="mt-2 custom-radio-group">
        <div class="form-check d-flex align-items-center mb-2">
            <input class="form-check-input" type="radio" name="f301" value="1" x-model="f301" id="f301_1">
            <label class="form-check-label ms-2" for="f301_1"></label>
            <input type="number" name="f302" value="{{ old('f302', $answer->f302 ?? '') }}" :disabled="f301 !== '1'" class="form-control form-control-sm mx-2">
            <label class="form-check-label" for="f301_1">bulan sebelum lulus</label>
        </div>
        <div class="form-check d-flex align-items-center mb-2">
            <input class="form-check-input" type="radio" name="f301" value="2" x-model="f301" id="f301_2">
            <label class="form-check-label ms-2" for="f301_2"></label>
            <input type="number" name="f303" value="{{ old('f303', $answer->f303 ?? '') }}" :disabled="f301 !== '2'" class="form-control form-control-sm mx-2">
            <label class="form-check-label" for="f301_2">bulan sesudah lulus</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="f301" value="3" x-model="f301" id="f301_3">
            <label class="form-check-label" for="f301_3">Saya tidak mencari kerja</label>
        </div>
    </div>
</div>
