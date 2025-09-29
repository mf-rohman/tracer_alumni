{{-- Pertanyaan 10: Sumber Dana --}}
<div class="form-group mb-4">
    <label for="f1201" class="form-label"><b>Sebutkan sumber dana dalam pembiayaan kuliah?</b> <small class="text-muted">(bukan ketika Studi Lanjut)</small></label>
    <select id="f1201" name="f1201" x-model="f1201" class="form-control">
        <option value="">-- Pilih Sumber Dana --</option>
        <option value="1">Orang Tua</option>
        <option value="2">Biaya Sendiri</option>
        <option value="3">Beasiswa ADIK</option>
        <option value="4">Beasiswa BIDIKMISI</option>
        <option value="5">Beasiswa PPA</option>
        <option value="6">Beasiswa Perusahaan/Swasta</option>
        <option value="7">Lainnya</option>
    </select>
    {{-- Input Teks "Lainnya" yang dinamis untuk Sumber Dana --}}
    <div x-show="f1201 === '7'" x-transition class="mt-2">
        <label for="f1202" class="form-label">Sebutkan sumber dana lainnya:</label>
        <input type="text" id="f1202" name="f1202" value="{{ old('f1202', $answer->f1202 ?? '') }}" class="form-control">
    </div>
</div>

