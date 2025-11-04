{{-- ▼▼▼ KODE PARTIAL PEMBELAJARAN YANG SUDAH DIPERBARUI ▼▼▼ --}}
                        <div class="border rounded-3 p-3 mt-4 bg-light">
                            <h5 class="font-weight-bolder mb-2">
                                <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>
                                 Metode Pembelajaran
                            </h5>
                            <p class="text-sm text-muted mb-1">Seberapa besar penekanan pada metode pembelajaran berikut dilaksanakan di program studi Anda?</p>
                            <p class="text-xs text-muted mb-4">
                                <span class="fw-bold">1:</span> Sangat Rendah &nbsp;
                                <span class="fw-bold">2:</span> Rendah &nbsp;
                                <span class="fw-bold">3:</span> Cukup &nbsp;
                                <span class="fw-bold">4:</span> Tinggi &nbsp;
                                <span class="fw-bold">5:</span> Sangat Tinggi
                            </p>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <tbody>
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
        
                                        @foreach ($pembelajaran as $item)
                                       
                                            <tr>

                                            
                                            <!-- <div class="d-flex justify-content-between align-items-center py-2 border-bottom"> -->
                                                <td class="text-sm font-weight-bold text-dark">{{ $item['label'] }}</td>
                                                <td class="rating-scale-group d-flex justify-content-end gap-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="{{ $item['name'] }}_{{ $i }}" name="{{ $item['name'] }}" value="{{ $i }}" @if(old($item['name'], $answer->{$item['name']} ?? null) == $i) checked @endif required>
                                                        <label class="form-check-label" for="{{ $item['name'] }}_{{ $i }}">{{ $i }}</label>
                                                    </div>
                                                    @endfor
                                                </td>
                                            <!-- </div> -->
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
