<div class="border rounded-3 p-2 p-sm-3 mt-4 bg-light">
    <h5 class="font-weight-bolder mb-2" style="font-size: 1rem;">
        <i class="fas fa-star-half-alt me-2 text-primary"></i>
        Penilaian Kompetensi
    </h5>
    <p class="text-sm text-muted mb-1" style="font-size: 0.75rem;">Silakan nilai tingkat kompetensi di bawah ini berdasarkan skala berikut:</p>
    <p class="text-xs text-muted mb-2" style="font-size: 0.7rem;">
        <span class="fw-bold">1:</span> Sangat Rendah &nbsp;
        <span class="fw-bold">2:</span> Rendah &nbsp;
        <span class="fw-bold">3:</span> Cukup &nbsp;
        <span class="fw-bold">4:</span> Tinggi &nbsp;
        <span class="fw-bold">5:</span> Sangat Tinggi
    </p>
    <p class="text-sm text-muted mb-3" style="font-size: 0.75rem;">
        <span class="fw-bold">Kolom (A):</span> Tingkat penguasaan Anda saat lulus. <br>
        <span class="fw-bold">Kolom (B):</span> Tingkat kebutuhan kompetensi tersebut di dunia kerja.
    </p>

    @php
        $kompetensi = [
            ['label' => 'Etika', 'a' => 'f1761', 'b' => 'f1762'],
            ['label' => 'Keahlian berdasarkan bidang ilmu', 'a' => 'f1763', 'b' => 'f1764'],
            ['label' => 'Bahasa Inggris', 'a' => 'f1765', 'b' => 'f1766'],
            ['label' => 'Penggunaan Teknologi Informasi', 'a' => 'f1767', 'b' => 'f1768'],
            ['label' => 'Komunikasi', 'a' => 'f1769', 'b' => 'f1770'],
            ['label' => 'Kerja sama tim', 'a' => 'f1771', 'b' => 'f1772'],
            ['label' => 'Pengembangan diri', 'a' => 'f1773', 'b' => 'f1774'],
        ];
    @endphp

    {{-- TAMPILAN DESKTOP (Hanya dirender jika !isMobile) --}}
    <template x-if="!isMobile">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-sm">Kompetensi</th>
                        <th scope="col" class="text-center text-sm">Saat Lulus (A)</th>
                        <th scope="col" class="text-center text-sm">Dibutuhkan (B)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kompetensi as $item)
                    <tr>
                        <td class="text-sm font-weight-bold text-dark">{{ $item['label'] }}</td>

                        {{-- Kolom "Saat Lulus (A)" --}}
                        <td class="text-center">
                            <div class="rating-scale-group d-flex justify-content-center gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               id="{{ $item['a'] }}_{{ $i }}" 
                                               name="{{ $item['a'] }}" 
                                               value="{{ $i }}" 
                                               {{ old($item['a'], $answer->{$item['a']} ?? '') == $i ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="{{ $item['a'] }}_{{ $i }}">{{ $i }}</label>
                                    </div>
                                @endfor
                            </div>
                        </td>

                        {{-- Kolom "Dibutuhkan (B)" --}}
                        <td class="text-center">
                            <div class="rating-scale-group d-flex justify-content-center gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               id="{{ $item['b'] }}_{{ $i }}" 
                                               name="{{ $item['b'] }}" 
                                               value="{{ $i }}" 
                                               {{ old($item['b'], $answer->{$item['b']} ?? '') == $i ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="{{ $item['b'] }}_{{ $i }}">{{ $i }}</label>
                                    </div>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </template>

    {{-- TAMPILAN MOBILE (Hanya dirender jika isMobile) --}}
    <template x-if="isMobile">
        <div>
            @foreach ($kompetensi as $item)
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-2">
                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.85rem;">{{ $item['label'] }}</h6>
                    
                    {{-- Saat Lulus (A) --}}
                    <div class="mb-2">
                        <label class="form-label font-weight-bold mb-1" style="font-size: 0.7rem;">Saat Lulus (A)</label>
                        <div class="d-flex justify-content-between" style="gap: 2px;">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="form-check flex-fill text-center" style="min-width: 0; padding: 0;">
                                    <input class="form-check-input d-block mx-auto" 
                                           type="radio" 
                                           id="{{ $item['a'] }}_{{ $i }}_mobile" 
                                           name="{{ $item['a'] }}" 
                                           value="{{ $i }}" 
                                           {{ old($item['a'], $answer->{$item['a']} ?? '') == $i ? 'checked' : '' }}
                                           required 
                                           style="margin: 0;">
                                    <label class="form-check-label d-block mt-1" for="{{ $item['a'] }}_{{ $i }}_mobile" style="font-size: 0.7rem; cursor: pointer;">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Dibutuhkan (B) --}}
                    <div>
                        <label class="form-label font-weight-bold mb-1" style="font-size: 0.7rem;">Dibutuhkan (B)</label>
                        <div class="d-flex justify-content-between" style="gap: 2px;">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="form-check flex-fill text-center" style="min-width: 0; padding: 0;">
                                    <input class="form-check-input d-block mx-auto" 
                                           type="radio" 
                                           id="{{ $item['b'] }}_{{ $i }}_mobile" 
                                           name="{{ $item['b'] }}" 
                                           value="{{ $i }}" 
                                           {{ old($item['b'], $answer->{$item['b']} ?? '') == $i ? 'checked' : '' }}
                                           required 
                                           style="margin: 0;">
                                    <label class="form-check-label d-block mt-1" for="{{ $item['b'] }}_{{ $i }}_mobile" style="font-size: 0.7rem; cursor: pointer;">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </template>
</div>