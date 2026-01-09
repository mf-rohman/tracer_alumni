<div class="border rounded-3 p-2 p-sm-3 mt-4 bg-light">
    <h5 class="font-weight-bolder mb-2" style="font-size: 1rem;">
        <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
        Metode Pembelajaran
    </h5>
    <p class="text-sm text-muted mb-1" style="font-size: 0.75rem;">Seberapa besar penekanan pada metode pembelajaran berikut dilaksanakan di program studi Anda?</p>
    <p class="text-xs text-muted mb-4" style="font-size: 0.7rem;">
        <span class="fw-bold">1:</span> Sangat Rendah &nbsp;
        <span class="fw-bold">2:</span> Rendah &nbsp;
        <span class="fw-bold">3:</span> Cukup &nbsp;
        <span class="fw-bold">4:</span> Tinggi &nbsp;
        <span class="fw-bold">5:</span> Sangat Tinggi
    </p>

    @php
        $pembelajaran = [
            ['label' => 'Perkuliahan', 'name' => 'f21'],
            ['label' => 'Demonstrasi', 'name' => 'f22'],
            ['label' => 'Partisipasi dalam proyek riset', 'name' => 'f23'],
            ['label' => 'Magang', 'name' => 'f24'],
            ['label' => 'Praktikum', 'name' => 'f25'],
            ['label' => 'Kerja Lapangan', 'name' => 'f26'],
            ['label' => 'Diskusi', 'name' => 'f27'],
        ];
    @endphp

    {{-- TAMPILAN DESKTOP (Hanya dirender jika !isMobile) --}}
    <template x-if="!isMobile">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <tbody>
                    @foreach ($pembelajaran as $item)
                    <tr>
                        <td class="text-sm font-weight-bold text-dark">{{ $item['label'] }}</td>
                        <td class="text-center">
                            <div class="rating-scale-group d-flex justify-content-end gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               id="{{ $item['name'] }}_{{ $i }}" 
                                               name="{{ $item['name'] }}" 
                                               value="{{ $i }}" 
                                               {{ old($item['name'], $answer->{$item['name']} ?? '') == $i ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="{{ $item['name'] }}_{{ $i }}">{{ $i }}</label>
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
            @foreach ($pembelajaran as $item)
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-2">
                    {{-- Pertanyaan --}}
                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.85rem;">{{ $item['label'] }}</h6>
                    
                    {{-- Pilihan 1-5 Horizontal Fit --}}
                    <div class="d-flex justify-content-between" style="gap: 2px;">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="form-check flex-fill text-center" style="min-width: 0; padding: 0;">
                                <input class="form-check-input d-block mx-auto" 
                                       type="radio" 
                                       id="{{ $item['name'] }}_{{ $i }}_mobile" 
                                       name="{{ $item['name'] }}" 
                                       value="{{ $i }}" 
                                       {{ old($item['name'], $answer->{$item['name']} ?? '') == $i ? 'checked' : '' }}
                                       required 
                                       style="margin: 0;">
                                <label class="form-check-label d-block mt-1" for="{{ $item['name'] }}_{{ $i }}_mobile" style="font-size: 0.7rem; cursor: pointer;">{{ $i }}</label>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </template>
</div>