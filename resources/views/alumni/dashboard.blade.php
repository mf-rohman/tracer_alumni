@extends('layouts.alumni')
@section('title', 'Kuesioner Tracer Study')

{{-- Style kustom untuk combobox tetap di sini --}}
@push('styles')
<style>
    .combobox-wrapper .dropdown-menu { width: 100%; max-height: 240px; overflow-y: auto; }
    .combobox-wrapper .dropdown-item { cursor: pointer; }
    .tab-content-wrapper > div { display: none; }
    .tab-content-wrapper > div.active { display: block; }


   
    /* .modal-header .btn-close {
        opacity: 0.7;
        color: black;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e");
    }
    .modal-header .btn-close:hover {
        opacity: 1;
    } */

    .btn-close:hover i {
        color: #e63946 !important; /* merah saat hover */
        transform: scale(1.1);     /* sedikit membesar */
    }

    /* #container-header-tab {
        margin-top: 32px;
    } */


</style>
@endpush

@section('content')

@php
    // Default tab aktif
    $initialTab = 'utama'; 
    $errorSectionName = '';

    if ($errors->any()) {
        // Daftar field untuk setiap tab
        $fieldsUtama = ['f8', 'nik', 'npwp', 'no_hp', 'email'];
        $fieldsWajib = ['f502', 'f505', 'f5a1', 'f5a2', 'f5b', 'f5c', 'f18a', 'f18b', 'f18c'];
        
        // Cek tab mana yang punya error
        if ($errors->hasAny($fieldsUtama)) {
            $initialTab = 'utama';
            $errorSectionName = 'Bagian 1: Status Utama';
        } elseif ($errors->hasAny($fieldsWajib)) {
            $initialTab = 'wajib';
            $errorSectionName = 'Bagian 2: Kuesioner Wajib';
        } else {
            $initialTab = 'opsional';
            $errorSectionName = 'Bagian 3: Kuesioner Opsional';
        }
    }
@endphp
{{-- Kontainer utama Alpine.js untuk mengelola semua state halaman --}}
<div  x-data="kuesionerForm('{{ $initialTab }}')" x-init="
    const el = document.querySelector('#card-body');
    if (el) el.addEventListener('scroll', () => handleScroll());">

    {{-- Kartu Selamat Datang --}}
    <div class="row" style="position: sticky; top: 0px; z-index: 90;">
        <div class="col-12">
            <div class="card mb-4 welcome-card-sticky">
                <div class="card-body p-3">
                     <div class="row align-items-center">
                        <div class="col-auto">
                            @if (Auth::user()->alumni && Auth::user()->alumni->photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->alumni->photo_path) }}" alt="Foto Profil" class="avatar avatar-xl rounded-circle shadow-sm">
                            @else
                                <div class="avatar avatar-xl rounded-circle shadow-sm bg-gradient-primary d-flex align-items-center justify-content-center">
                                    <h4 class="text-white mb-0">{{ substr(Auth::user()->name, 0, 1) }}</h4>
                                </div>
                            @endif
                        </div>
                        <div class="col">
                            <h5 class="font-weight-bolder mb-1">Selamat Datang, {{ $alumni->nama_lengkap }}!</h5>
                            <p class="mb-0 text-sm text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Silakan pilih tahun kuesioner yang tersedia untuk diisi.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- {{-- Progress Bar Anda --}}
                <div class="bg-white px-3 pt-0 pb-3 mb-2 progress-container">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Progress Pengisian</small>
                        <small class="fw-bold" x-text="progress + '%'"></small>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar bg-primary"
                             role="progressbar"
                             :style="{ width: progress + '%' }"></div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

    <div class="card mt-1" id="container-header-tab">
        <div class="card-header p-0" style="position: sticky; top: 100px; z-index: 99; background-color: white; border-bottom: 1px solid #dee2e6;">
                <div class="bg-gradient-primary pt-3 pb-2 ps-3">
                    <h6 class="mb-0 text-white"><i class="fas fa-clipboard-list me-2"></i> Formulir Kuesioner Tahun <span x-text="tahunTerpilih"></span></h6>
                </div>
                <ul class="nav nav-tabs mt-2 px-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{'active': activeTab === 'utama'}" @click="activeTab = 'utama'" type="button" :disabled="!formEnabled">1. Status Utama</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{'active': activeTab === 'wajib'}" @click="statusTerpilih && formEnabled ? activeTab = 'wajib' : null" :disabled="!statusTerpilih || !formEnabled" type="button">2. Kuesioner Wajib</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{'active': activeTab === 'opsional'}" @click="activeTab = 'opsional'" type="button" :disabled="!formEnabled">3. Kuesioner Opsional</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{'active': activeTab === 'testimoni'}" @click="activeTab = 'testimoni'" type="button" :disabled="!formEnabled">4. Testimoni</button>
                    </li>
                </ul>
            </div>
        <div class="card-body">
            <div x-show="showNotification" x-transition class="alert text-white" :class="isError ? 'alert-danger' : 'alert-success'" x-text="notificationMessage"></div>

            @if($isFormDisabled && $pesanError) 
                <div class="alert alert-warning text-white">{{ $pesanError }}</div> 
            @endif


            <form action="{{ route('dashboard.store', ['tahun' => $tahunKuesioner]) }}" method="POST">
                @csrf
                <input type="hidden" name="tahun_kuesioner" :value="tahunTerpilih">

                <fieldset :disabled="!formEnabled">
                    <div class="mt-3">
                        <div x-show="activeTab === 'utama'">
                            <div>
                                @include('alumni.partials.kuesioner-utama')
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn bg-gradient-primary" @click="activeTab = 'wajib'" :disabled="!statusTerpilih">
                                        Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div x-show="activeTab === 'wajib'">
                            <div>
                                <div x-show="statusTerpilih === '1'">@include('alumni.partials.kuesioner-bekerja')</div>
                                <div x-show="statusTerpilih === '3'">@include('alumni.partials.kuesioner-wiraswasta')</div>
                                <div x-show="statusTerpilih === '1' || statusTerpilih === '3'">@include('alumni.partials.kuesioner-pekerjaan-umum')</div>
                                <div x-show="statusTerpilih === '4'">@include('alumni.partials.kuesioner-studi-lanjut')</div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-primary" @click="activeTab = 'utama'">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </button>
                                    <button type="button" class="btn bg-gradient-primary" @click="activeTab = 'opsional'">
                                        Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div x-show="activeTab === 'opsional'">
                            <div>
                                @include('alumni.partials.kuesioner-pendanaan')
                                @include('alumni.partials.kuesioner-kompetensi')
                                @include('alumni.partials.kuesioner-pembelajaran')
                                @include('alumni.partials.kuesioner-mencari-kerja')

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-primary" @click="activeTab = 'wajib'">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </button>
                                    <button type="button" class="btn bg-gradient-primary" @click="activeTab = 'testimoni'">
                                        Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                                
                            </div>
                        </div>
                        <div x-show="activeTab === 'testimoni'">
                            <div>
                                @include('alumni.partials.kuesioner-testimoni')

                                <div class="d-flex justify-content-between align-items-center mt-7 pt-3 mx-2 border-top">
                                    <button type="button" class="btn btn-outline-primary" @click="activeTab = 'opsional'">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </button>
                                    <<button type="submit" class="btn bg-gradient-primary px-4 py-2" @click="formEnabled = true">
                                        <i class="fas fa-save me-2"></i>Simpan Kuesioner
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <div class="modal fade" id="tahunKuesionerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Tahun Kuesioner</h5>
                    <button type="button" 
                        class="btn btn-close d-flex align-items-center justify-content-center p-0 border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close"
                        style="width: 40px; height: 40px; transition: all 0.2s ease;">
                        <i class="fas fa-times-circle text-dark" style="font-size: 1.5rem; transition: color 0.2s ease; :hover {color:#e63946; transform:scale(1.1)}" ></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Silakan pilih tahun kuesioner yang ingin Anda isi atau lihat.</p>
                    <div class="list-group">
                        @foreach($listKuesioner as $kuesioner)
                            <a href="{{ route('dashboard', ['tahun' => $kuesioner['tahun']]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $kuesioner['status'] === 'terkunci' ? 'disabled' : '' }}"
                               @if($kuesioner['status'] === 'terkunci')
                                   onclick="event.preventDefault(); alert('{{ $kuesioner['lock_message'] }}')"
                               @endif
                            >
                                Kuesioner Tahun {{ $kuesioner['tahun'] }}
                                @if($kuesioner['status'] == 'terisi') <span class="badge bg-gradient-success">Terisi</span>
                                @elseif($kuesioner['status'] == 'tersedia') <span class="badge  bg-gradient-primary">Tersedia</span>
                                @else <span class="badge bg-gradient-secondary">Terkunci</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- PERUBAHAN: MODAL BARU UNTUK KONFIRMASI SALIN JAWABAN --}}
    <!-- <div class="modal fade" id="copyAnswerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"     data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Salin Jawaban dari Tahun Sebelumnya?</h5>
                </div>
                <div class="modal-body">
                    <p>Kami mendeteksi Anda telah mengisi kuesioner pada tahun {{ $previousYear }}. Apakah data pekerjaan   Anda untuk tahun {{ $tahunKuesioner }} masih sama dengan tahun sebelumnya?</p>
                    <p class="text-sm text-muted">Anda tetap dapat mengedit jawaban setelahnya jika ada perubahan kecil.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" @click="declineCopy">
                        Tidak, Isi Manual
                    </button>
                    <button type="button" class="btn bg-gradient-primary" @click="confirmCopy" :disabled="isSaving">
                        <span x-show="!isSaving">Ya, Salin Jawaban</span>
                        
                    </button>
                </div>
            </div>
        </div>
    </div> -->
    <div 
    x-show="success"
    x-transition
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold mb-3 text-green-600">Berhasil!</h2>

            <p x-text="successMessage"></p>

            <div class="text-end mt-5">
                <button 
                    class="btn bg-gradient-primary px-4 py-2"
                    @click="success = false"
                >
                    OK
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap!',
                html: `
                    <p>Mohon periksa kembali isian Anda di <strong>{{ $errorSectionName }}</strong>.</p>
                    <p class="text-sm text-muted">Beberapa kolom wajib belum diisi atau formatnya salah.</p>
                `,
                confirmButtonText: 'Periksa Sekarang',
                confirmButtonColor: '#4B49AC',
            });
        });
    @endif

    function kuesionerForm() {
        // Data untuk combobox
        const options = [
            { value: '1', text: 'Bekerja (full time/ part time)', image: 'https://icongr.am/fontawesome/briefcase.svg?size=32&color=8B5CF6' },
            { value: '2', text: 'Belum memungkinkan bekerja', image: 'https://icongr.am/fontawesome/ban.svg?size=32&color=737373' },
            { value: '3', text: 'Wiraswasta', image: 'https://icongr.am/fontawesome/lightbulb-o.svg?size=32&color=10B981' },
            { value: '4', text: 'Melanjutkan Pendidikan', image: 'https://icongr.am/fontawesome/graduation-cap.svg?size=32&color=3B82F6' },
            { value: '5', text: 'Tidak kerja tetapi sedang mencari kerja', image: 'https://icongr.am/fontawesome/search.svg?size=32&color=F59E0B' }
        ];

        const initialStatus = '{{ old('f8', $answer->f8 ?? '') }}';
        let initialSelected = options.find(opt => opt.value === initialStatus) || {
            value: '', text: '-- Pilih Status --', image: 'https://placehold.co/32x32/EFEFEF/A9A9A9?text=?'
        };

        return {
            activeTab: 'utama',
            tahunTerpilih: '{{ $tahunKuesioner }}',
            statusTerpilih: initialStatus,
            formEnabled: {{ !$isFormDisabled ? 'true' : 'false' }},

            // Combobox
            open: false,
            selected: initialSelected,
            options: options,

            // Progress
            progress: 0,
            totalField: 0,
            terisi: 0,

            // 🔥 Tambahkan state untuk salin jawaban
            isSaving: false,
            showNotification: false,
            notificationMessage: '',
            isError: false,

            regencies: [],
            selectedProvince: '{{ old('f5a1', $answer->f5a1 ?? '') }}',
            selectedRegency: '{{ old('f5a2', $answer->f5a2 ?? '') }}',
            loadingRegencies: false,

            isScrolled: false,

            instansiTidakDitemukan: false,

            success: false,
            successMessage: '',

            handleScroll() {
                const el = document.querySelector('#card-body');
                this.isScrolled = el && el.scrollTop > 50;
            },

            selectOption(option) {
                this.selected = option;
                this.statusTerpilih = option.value;
                this.open = false;
                this.hitungProgress();
            },


            fetchRegencies() {
                // Jika tidak ada provinsi yang dipilih, reset dropdown kota
                if (!this.selectedProvince) {
                    this.regencies = [];
                    this.selectedRegency = '';
                    return;
                }
                this.loadingRegencies = true;
                this.selectedRegency = ''; 

                // Panggil API yang sudah kita buat
                fetch(`/api/regencies?province_code=${this.selectedProvince}`)
                    .then(response => response.json())
                    .then(data => {
                        this.regencies = data;
                        this.loadingRegencies = false;
                        
                        // Penting: Beri sedikit waktu agar DOM terupdate sebelum mencoba set nilai lama
                        this.$nextTick(() => {
                            const oldRegency = '{{ old('f5a2', $answer->f5a2 ?? '') }}';
                            // Cek apakah nilai lama ada di dalam daftar baru, lalu set
                            if (this.regencies.find(r => r.code === oldRegency)) {
                               this.selectedRegency = oldRegency;
                            }
                        });
                    })
                    .catch(() => {
                        this.loadingRegencies = false;
                        alert('Gagal memuat data kabupaten/kota.');
                    });
            },

        
            hitungProgress() {
                const inputs = document.querySelectorAll('form input, form select, form textarea');
                this.totalField = inputs.length;
                let filled = 0;
                inputs.forEach(el => {
                    if ((el.type === 'checkbox' || el.type === 'radio')) {
                        if (el.checked) filled++;
                    } else if (el.value && el.value.trim() !== '') {
                        filled++;
                    }
                });
                this.terisi = filled;
                this.progressPersen = this.totalField > 0 ? Math.round((filled / this.totalField) * 100) : 0;
            },
        
            init() {
                if ({{ $showCopyModal ? 'true' : 'false' }}) {
                    var copyModal = new bootstrap.Modal(document.getElementById('copyAnswerModal'));
                    copyModal.show();
                }
                const hasTahunParam = {{ request()->route('tahun') ? 'true' : 'false' }};
                const hasAnswer = {{ $answer->exists ? 'true' : 'false' }};

                if (!hasTahunParam && !hasAnswer) {
                    new bootstrap.Modal(document.getElementById('tahunKuesionerModal')).show();
                }
                this.hitungProgress();
                document.querySelectorAll('form input, form select, form textarea').forEach(el => {
                    el.addEventListener('input', () => this.hitungProgress());
                    el.addEventListener('change', () => this.hitungProgress());
                });

                if (this.selectedProvince) {
                    this.fetchRegencies();
                }

                tomSelectInstance = new TomSelect('#f5b', {
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    create: false, // Tidak izinkan alumni membuat opsi baru langsung dari sini
                    load: function(query, callback) {
                        fetch(`/api/instansi/search?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(json => {
                                callback(json.results);
                            }).catch(()=>{
                                callback();
                            });
                    },
                    // Mengisi nilai awal jika ada (penting untuk edit)
                    items: ['{{ old('f5b', $answer->f5b ?? '') }}'],
                });
                
                // Menonaktifkan/mengaktifkan select saat checkbox dicentang
                this.$watch('instansiTidakDitemukan', (value) => {
                    if (value) {
                        tomSelectInstance.disable();
                        tomSelectInstance.clear(); // Hapus pilihan yang ada
                    } else {
                        tomSelectInstance.enable();
                    }
                });
            },
        
            async confirmCopy() {
                this.isSaving = true;
                const sourceYear = {{ $previousYear }};
                const targetYear = {{ $tahunKuesioner }};
            
                try {
                    const response = await fetch('{{ route('dashboard.copy') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            source_year: sourceYear,
                            target_year: targetYear
                        })
                    });
                
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Terjadi kesalahan.');
                
                    window.location.reload();
                
                } catch (error) {
                    this.notificationMessage = 'Gagal menyalin jawaban: ' + error.message;
                    this.isError = true;
                    this.showNotification = true;
                } finally {
                    this.isSaving = false; // 🔥 pastikan spinner berhenti
                }
            },
        
            declineCopy() {
                var copyModal = bootstrap.Modal.getInstance(document.getElementById('copyAnswerModal'));
                copyModal.hide();
            }
        }

    }

//     document.addEventListener('DOMContentLoaded', function() {
        
//         const elementOld = document.querySelector('#container-header-tab');
//         // const scrollPosition = window.scrollY;

//         if (!elementOld) {
//             console.log('not elementOld');
//             return;
//         }

//         const targetScroll = document.scrollingElement || document.documentElement;

//         targetScroll.addEventListener('scroll', () => {
//             const scrollY = window.scrollY || targetScroll.scrollTop;

//             if (scrollY > -5) {
//                 console.log('Scrolled')
//                 elementOld.classList.add('mt-8')
//             }
//         })
//     })
// </script>
@endpush
