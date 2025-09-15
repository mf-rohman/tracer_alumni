@extends('layouts.alumni')

@section('title', 'Kuesioner Tracer Study')

{{-- Menambahkan style kustom untuk combobox Bootstrap --}}
@push('styles')
<style>
    .combobox-wrapper .dropdown-menu {
        width: 100%;
        max-height: 240px;
        overflow-y: auto;
    }
    .combobox-wrapper .dropdown-item {
        cursor: pointer;
    }
    .combobox-wrapper .dropdown-item .avatar {
        width: 24px;
        height: 24px;
    }
    .combobox-wrapper .dropdown-item-check {
        width: 20px;
        height: 20px;
    }
    /* Memastikan tombol combobox terlihat benar di dalam form-control */
    .form-control.combobox-button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: left;
    }
    .form-control.combobox-button .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush


@section('content')
{{--
    Seluruh konten di bawah ini sudah menggunakan class dari Bootstrap 5
    yang akan secara otomatis menyesuaikan gayanya dengan tema "Soft UI Dashboard"
    yang dimuat di file layouts/alumni.blade.php.
--}}
<div class="row">
    <div class="col-12">
        <div class="card mb-4 animate__animated animate__fadeIn">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if (Auth::user()->alumni && Auth::user()->alumni->photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->alumni->photo_path) }}"
                                 alt="Foto Profil"
                                 class="avatar avatar-xl rounded-circle shadow-sm border border-3 border-primary">
                        @else
                            <div class="avatar avatar-xl rounded-circle shadow-sm border border-3 border-primary bg-gradient-primary d-flex align-items-center justify-content-center">
                                <h4 class="text-white mb-0">{{ substr(Auth::user()->name, 0, 1) }}</h4>
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <h5 class="font-weight-bolder mb-1">Selamat Datang, {{ $alumni->nama_lengkap }}!</h5>
                        <p class="mb-0 text-sm text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Mohon kesediaannya untuk mengisi kuesioner di bawah ini dengan lengkap dan jujur. Jawaban Anda sangat berharga untuk evaluasi dan pengembangan almamater.
                        </p>
                        @if($answer->is_complete)
                            <span class="badge badge-sm bg-gradient-success mt-2">
                                <i class="fas fa-check-circle me-1"></i> Kuesioner Telah Lengkap
                            </span>
                        @else
                            <span class="badge badge-sm bg-gradient-warning mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> Kuesioner Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header pb-0 bg-gradient-primary">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-clipboard-list me-2"></i> Formulir Kuesioner Tracer Study
                </h6>
            </div>
            <div class="card-body" x-data="initFormData()">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                        <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                        <span class="alert-text"><strong>Sukses!</strong> {{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                        <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="alert-text"><strong>Error!</strong> Terdapat kesalahan dalam pengisian formulir. Silakan periksa kembali.</span>
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('dashboard.store') }}" method="POST" class="mt-4" x-on:submit="isLoading = true">
                    @csrf
                    <!-- Input tersembunyi untuk mengirimkan nilai status ke server -->
                    <input type="hidden" name="f8" :value="status">

                    <div class="mb-5">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-sm">Progress Pengisian</span>
                            <span class="text-sm font-weight-bold" x-text="calculateProgress() + '%'"></span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-gradient-primary"
                                 role="progressbar"
                                 :style="'width: ' + calculateProgress() + '%'"
                                 :aria-valuenow="calculateProgress()"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <!-- ▼▼▼ COMBOBOX KUSTOM DENGAN GAYA BOOTSTRAP ▼▼▼ -->
                    <div class="form-group mb-4">
                        <label for="status_pekerjaan_button" class="form-label font-weight-bold ">
                            <h4>Jelaskan status Anda saat ini? </h4><span class="text-danger">*</span>
                        </label>
                        <div class="dropdown combobox-wrapper" @click.away="open = false">
                            <!-- Tombol yang menampilkan pilihan yang dipilih -->
                            <button @click="open = !open" type="button" id="status_pekerjaan_button" class="form-control form-control-lg combobox-button">
                                <span class="d-flex align-items-center">
                                    <img :src="selected.image" alt="" class="avatar rounded-circle me-2" x-show="selected.value">
                                    <span class="truncate" x-text="selected.text"></span>
                                </span>
                                <i class="fas fa-chevron-down text-secondary"></i>
                            </button>

                            <!-- Dropdown Pilihan -->
                            <ul class="dropdown-menu" :class="{ 'show': open }">
                                <template x-for="option in options" :key="option.value">
                                    <li @click="selectOption(option)">
                                        <a class="dropdown-item d-flex align-items-center justify-content-between">
                                            <span class="d-flex align-items-center">
                                                <img :src="option.image" alt="" class="avatar rounded-circle me-2">
                                                <span x-text="option.text"></span>
                                            </span>
                                            <i class="fas fa-check text-primary dropdown-item-check" x-show="option.value === selected.value"></i>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <!-- ▲▲▲ AKHIR DARI COMBOBOX KUSTOM ▲▲▲ -->


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

                    <!-- ▼▼▼ KODE UNTUK WIRAUSAHA DITEMPATKAN DI SINI ▼▼▼ -->
                    <div x-show="status === '3'" x-transition.duration.300ms class="border rounded-3 p-3 mt-4 bg-gray-100">
                        {{-- Judul Bagian --}}
                        <h5 class="font-weight-bolder mb-3">Pertanyaan Lanjutan (Wiraswasta)</h5>

                        {{-- Pertanyaan 2 --}}
                        <div class="form-group mb-4">
                            <label for="f502_wiraswasta" class="form-label">Dalam berapa bulan setelah lulus Anda memulai wiraswasta?</label>
                            <input type="number" id="f502_wiraswasta" name="f502" value="{{ old('f502', $answer->f502 ?? '') }}" class="form-control">
                        </div>

                        {{-- Pertanyaan 7 --}}
                        <div class="form-group mb-4">
                            <label for="f5c" class="form-label">Apa posisi/jabatan Anda saat ini?</label>
                            <input type="text" id="f5c" name="f5c" value="{{ old('f5c', $answer->f5c ?? '') }}" class="form-control">
                        </div>
                    </div>
                    <!-- ▲▲▲ AKHIR DARI KODE WIRAUSAHA ▲▲▲ -->


                    <div x-show="status === '1' || status === '3'" x-transition.duration.300ms class="border rounded-3 p-3 mt-4 bg-gray-100">
                        {{-- ▼▼▼ KODE PARTIAL PEKERJAAN-UMUM YANG SUDAH DIPERBARUI ▼▼▼ --}}
                        <h5 class="font-weight-bolder mb-3">Pertanyaan Lanjutan (Pekerjaan)</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="f5d" class="form-label">Apa tingkat tempat kerja Anda?</label>
                                    <select id="f5d" name="f5d" class="form-control">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="1" @if(old('f5d', $answer->f5d ?? '') == '1') selected @endif>Lokal</option>
                                        <option value="2" @if(old('f5d', $answer->f5d ?? '') == '2') selected @endif>Nasional</option>
                                        <option value="3" @if(old('f5d', $answer->f5d ?? '') == '3') selected @endif>Internasional</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- ▲▲▲ AKHIR DARI KODE PARTIAL PEKERJAAN-UMUM ▲▲▲ --}}
                    </div>

                    <div x-show="status === '4'" x-transition.duration.300ms class="border rounded-3 p-3 mt-4 bg-gray-100">
                        @include('alumni.partials.kuesioner-studi-lanjut')
                    </div>

                    <div class="mt-4">
                        @include('alumni.partials.kuesioner-kompetensi')
                        @include('alumni.partials.kuesioner-pembelajaran')
                        @include('alumni.partials.kuesioner-mencari-kerja')
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-5 pt-3 border-top">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" id="declaration" required>
                            <label class="form-check-label" for="declaration">
                                Saya menyatakan bahwa data yang diisi adalah benar
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary px-4 py-2 mt-2 mb-4 me-4" :disabled="isLoading">
                            <span x-show="!isLoading" class="d-flex align-items-center">
                                <i class="fas fa-save me-2"></i>Simpan
                            </span>
                            <!-- <span x-show="isLoading" class="d-flex align-items-center">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Menyimpan...
                            </span> -->
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{--
    Script di bawah ini berisi semua logika Alpine.js yang sudah digabungkan.
--}}
<script>
    function initFormData() {
        // --- DATA UNTUK COMBOBOX ---
        const options = [
            { value: '1', text: 'Bekerja (full time/ part time)', image: 'https://icongr.am/fontawesome/briefcase.svg?size=32&color=8B5CF6' },
            { value: '2', text: 'Belum memungkinkan bekerja', image: 'https://icongr.am/fontawesome/ban.svg?size=32&color=737373' },
            { value: '3', text: 'Wiraswasta', image: 'https://icongr.am/fontawesome/lightbulb-o.svg?size=32&color=10B981' },
            { value: '4', text: 'Melanjutkan Pendidikan', image: 'https://icongr.am/fontawesome/graduation-cap.svg?size=32&color=3B82F6' },
            { value: '5', text: 'Tidak kerja tetapi sedang mencari kerja', image: 'https://icongr.am/fontawesome/search.svg?size=32&color=F59E0B' }
        ];
        const initialStatus = "{{ old('f8', $answer->f8) }}";
        let initialSelected = {
            value: '',
            text: '-- Pilih Status --',
            image: 'https://placehold.co/32x32/EFEFEF/A9A9A9?text=?'
        };
        if (initialStatus) {
            const foundOption = options.find(opt => opt.value === initialStatus);
            if (foundOption) {
                initialSelected = foundOption;
            }
        }

        // --- MENGGABUNGKAN SEMUA DATA & FUNGSI ---
        return {
            // State dari form asli
            status: initialStatus,
            isLoading: false,
            formChanged: false,
            answeredQuestions: {
                f8: !!initialStatus,
            },
            totalQuestions: 15,

            // State untuk combobox
            open: false,
            selected: initialSelected,
            options: options,

            // State untuk form 'bekerja'
            f1101: "{{ old('f1101', $answer->f1101 ?? '') }}",

            // Fungsi untuk memilih opsi dari combobox
            selectOption(option) {
                this.selected = option; // Update tampilan combobox
                this.status = option.value; // Update state 'status' untuk logika x-show
                this.open = false; // Tutup dropdown
                this.updateProgress('f8', option.value); // Update progress
            },

            // Fungsi dari form asli
            calculateProgress() {
                const answeredCount = Object.values(this.answeredQuestions).filter(Boolean).length;
                if (this.totalQuestions === 0) return 0;
                return Math.round((answeredCount / this.totalQuestions) * 100);
            },
            updateProgress(fieldName, value) {
                this.answeredQuestions[fieldName] = !!value;
                this.formChanged = true;
            },
            validateForm() {
                // ... (logika validasi Anda)
                return true;
            }
        }
    }
</script>
@endpush
