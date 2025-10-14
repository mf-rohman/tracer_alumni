<h4 class="font-semibold text-gray-800">Pertanyaan Lanjutan (Wiraswasta)</h4>
<div>
    <label for="f502_wiraswasta" class="block font-medium text-sm text-gray-700">Dalam berapa bulan setelah lulus Anda memulai wiraswasta?</label>
    <input type="number" id="f502_wiraswasta" name="f502_wiraswasta" value="{{ old('f502_wiraswasta', $answer->f502_wiraswasta) }}" class="form-control">
</div>
<div class="form-group mb-4">
    <label for="f5c_wiraswasta" class="form-label">Apa posisi/jabatan Anda saat ini?</label>
    <input type="text" id="f5c_wiraswasta" name="f5c_wiraswasta" value="{{ old('f5c_wiraswasta', $answer->f5c_wiraswasta ?? '') }}" class="form-control">
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
