<h4 class="font-semibold text-gray-800">Pertanyaan Lanjutan (Pekerjaan)</h4>
<div>
    <label for="f5d" class="block font-medium text-sm text-gray-700">8. Apa tingkat tempat kerja Anda?</label>
    <select id="f5d" name="f5d" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        <option value="">Pilih Tingkat</option>
        <option value="1" @if(old('f5d', $answer->f5d) == '1') selected @endif>Lokal</option>
        <option value="2" @if(old('f5d', $answer->f5d) == '2') selected @endif>Nasional</option>
        <option value="3" @if(old('f5d', $answer->f5d) == '3') selected @endif>Internasional</option>
    </select>
</div>
