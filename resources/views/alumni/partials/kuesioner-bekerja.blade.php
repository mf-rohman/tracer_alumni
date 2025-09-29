{{-- Bagian ini akan memuat pertanyaan-pertanyaan lain dari file partials --}}
                    <div x-show="status === '1'" x-transition.duration.300ms class="border rounded-3 p-3 mt-4 bg-gray-100">
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
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="f5a1" class="form-label">Provinsi tempat Anda bekerja?</label>
                                    <input type="text" id="f5a1" name="f5a1" value="{{ old('f5a1', $answer->f5a1 ?? '') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="f5a2" class="form-label">Kota/Kabupaten tempat Anda bekerja?</label>
                                    <input type="text" id="f5a2" name="f5a2" value="{{ old('f5a2', $answer->f5a2 ?? '') }}" class="form-control">
                                </div>
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
                                    {{-- Input teks "Lainnya" yang dinamis --}}
                                    <div x-show="f1101 === '7'" x-transition class="mt-2">
                                        <label for="f1102" class="form-label">Sebutkan jenis perusahaan lainnya:</label>
                                        <input type="text" id="f1102" name="f1102" value="{{ old('f1102', $answer->f1102 ?? '') }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="f5b" class="form-label">Apa nama perusahaan/kantor tempat Anda bekerja?</label>
                                    <input type="text" id="f5b" name="f5b" value="{{ old('f5b', $answer->f5b ?? '') }}" class="form-control">
                                </div>
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
                        {{-- ▲▲▲ AKHIR DARI KODE PARTIAL KUESIONER-BEKERJA ▲▲▲ --}}
                    </div>
