<h4 class="font-semibold text-gray-800">Pertanyaan Lanjutan (Wiraswasta)</h4>
<div>
    <label for="f502_wiraswasta" class="block font-medium text-sm text-gray-700">Dalam berapa bulan setelah lulus Anda memulai wiraswasta?</label>
    <input type="number" id="f502_wiraswasta" name="f502_wiraswasta" value="{{ old('f502_wiraswasta', $answer->f502_wiraswasta) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
</div>
<div class="form-group mb-4">
    <label for="f5c_wiraswasta" class="form-label">Apa posisi/jabatan Anda saat ini?</label>
    <input type="text" id="f5c_wiraswasta" name="f5c_wiraswasta" value="{{ old('f5c_wiraswasta', $answer->f5c_wiraswasta ?? '') }}" class="form-control">
</div>


