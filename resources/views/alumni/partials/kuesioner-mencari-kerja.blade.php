 {{-- ▼▼▼ KODE PARTIAL MENCARI-KERJA YANG SUDAH DIPERBARUI ▼▼▼ --}}
                        <div class="border rounded-3 p-3 mt-4 bg-light">
                            <h5 class="font-weight-bolder mb-4">
                                <i class="fas fa-search me-2 text-primary"></i>
                                Proses Pencarian Kerja
                            </h5>

                            {{-- Pertanyaan 15 --}}
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

                            {{-- Pertanyaan 16 --}}
                            <div class="form-group mb-4">
                                <label class="form-label"><b>Bagaimana anda mencari pekerjaan tersebut?</b> <small class="text-muted">(Jawaban bisa lebih dari satu)</small></label>
                                <div class="row mt-2">
                                    @php
                                        $f4_options = [
                                            'f401' => 'Melalui iklan di koran/majalah, brosur', 'f402' => 'Melamar ke perusahaan tanpa mengetahui lowongan yang ada',
                                            'f403' => 'Pergi ke bursa/pameran kerja', 'f404' => 'Mencari lewat internet/iklan online/milis',
                                            'f405' => 'Dihubungi oleh perusahaan', 'f406' => 'Menghubungi Kemenakertrans',
                                            'f407' => 'Menghubungi agen tenaga kerja komersial/swasta', 'f408' => 'Memeroleh informasi dari pusat/kantor pengembangan karir fakultas/universitas',
                                            'f409' => 'Menghubungi kantor kemahasiswaan/hubungan alumni', 'f410' => 'Membangun jejaring (network) sejak masih kuliah',
                                            'f411' => 'Melalui relasi (misalnya dosen, orang tua, saudara, teman, dll.)', 'f412' => 'Membangun bisnis sendiri',
                                            'f413' => 'Melalui penempatan kerja atau magang', 'f414' => 'Bekerja di tempat yang sama dengan tempat kerja semasa kuliah',
                                            'f415' => 'Lainnya',
                                        ];
                                        $old_f4 = old('f4', []);
                                    @endphp
                                    @foreach ($f4_options as $key => $label)
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="f4[]" value="{{ $key }}" id="{{ $key }}"
                                                    @if(is_array($old_f4) && in_array($key, $old_f4)) checked @elseif(empty($old_f4) && optional($answer)->{$key} == 1) checked @endif>
                                                <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Pertanyaan 17, 18, 19 dalam satu baris --}}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group mb-4 mt-3">
                                        <label for="f6" class="form-label"><b>Berapa perusahaan yang anda lamar?</b></label>
                                        <input type="number" id="f6" name="f6" value="{{ old('f6', $answer->f6 ?? '') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-4 mt-3">
                                        <label for="f7" class="form-label"><b>Berapa banyak yang merespons?</b></label>
                                        <input type="number" id="f7" name="f7" value="{{ old('f7', $answer->f7 ?? '') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-4">
                                        <label for="f7a" class="form-label"><b>Berapa banyak yang mengundang wawancara?</b></label>
                                        <input type="number" id="f7a" name="f7a" value="{{ old('f7a', $answer->f7a ?? '') }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            {{-- Pertanyaan 20 --}}
                            <div class="form-group mb-4" x-data="{ f1001: '{{ old('f1001', $answer->f1001 ?? '') }}' }">
                                <label for="f1001" class="form-label"><b>Apakah anda aktif mencari pekerjaan dalam 4 minggu terakhir?</b></label>
                                <select id="f1001" name="f1001" x-model="f1001" class="form-select">
                                    <option value="">Pilih Jawaban</option>
                                    <option value="1">Tidak</option>
                                    <option value="2">Tidak, tapi saya sedang menunggu hasil lamaran kerja</option>
                                    <option value="3">Ya, saya akan mulai bekerja dalam 2 minggu ke depan</option>
                                    <option value="4">Ya, tapi saya belum pasti akan bekerja dalam 2 minggu ke depan</option>
                                    <option value="5">Lainnya</option>
                                </select>
                                <div x-show="f1001 === '5'" x-transition class="mt-2">
                                    <label for="f1002" class="form-label">Sebutkan lainnya:</label>
                                    <input type="text" id="f1002" name="f1002" value="{{ old('f1002', $answer->f1002 ?? '') }}" class="form-control">
                                </div>
                            </div>

                            {{-- Pertanyaan 21 --}}
                            <div class="form-group mb-4">
                                <label class="form-label"><b>Jika pekerjaan tidak sesuai pendidikan, mengapa anda mengambilnya?</b> <small class="text-muted">(Jawaban bisa lebih dari satu)</small></label>
                                <div class="row mt-2">
                                    @php
                                        $f16_options = [
                                            'f1601' => 'Pertanyaan tidak sesuai; pekerjaan saya sekarang sudah sesuai dengan pendidikan saya.', 'f1602' => 'Saya belum mendapatkan pekerjaan yang lebih sesuai.',
                                            'f1603' => 'Di pekerjaan ini saya memeroleh prospek karir yang baik.', 'f1604' => 'Saya lebih suka bekerja di area pekerjaan yang tidak ada hubungannya dengan pendidikan saya.',
                                            'f1605' => 'Saya dipromosikan ke posisi yang kurang berhubungan dengan pendidikan saya dibanding posisi sebelumnya.', 'f1606' => 'Saya dapat memeroleh pendapatan yang lebih tinggi di pekerjaan ini.',
                                            'f1607' => 'Pekerjaan saya saat ini lebih aman/terjamin/secure.', 'f1608' => 'Pekerjaan saya saat ini lebih menarik.',
                                            'f1609' => 'Pekerjaan saya saat ini lebih memungkinkan saya mengambil pekerjaan tambahan/jadwal yang fleksibel, dll.', 'f1610' => 'Pekerjaan saya saat ini lokasinya lebih dekat dari rumah saya.',
                                            'f1611' => 'Pekerjaan saya saat ini dapat lebih menjamin kebutuhan keluarga saya.', 'f1612' => 'Pada awal meniti karir ini, saya harus menerima pekerjaan yang tidak berhubungan dengan pendidikan saya.',
                                            'f1613' => 'Lainnya',
                                        ];
                                        $old_f16 = old('f16', []);
                                    @endphp
                                    @foreach ($f16_options as $key => $label)
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="f16[]" value="{{ $key }}" id="{{ $key }}"
                                                    @if(is_array($old_f16) && in_array($key, $old_f16)) checked
                                                    @elseif(empty($old_f16) && optional($answer)->{$key} == 1) checked
                                                    @endif>
                                                <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- ▲▲▲ AKHIR DARI KODE PARTIAL MENCARI-KERJA ▲▲▲ --}}
                    </div>
