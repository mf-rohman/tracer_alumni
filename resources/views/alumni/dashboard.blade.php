<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Alumni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Selamat Datang, {{ $alumni->nama_lengkap }}!</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        NPM: {{ $alumni->npm }} <br>
                        Program Studi: {{ $alumni->prodi->nama_prodi }} <br>
                        Tahun Lulus: {{ $alumni->tahun_lulus }}
                    </p>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Laporan Kegiatan Anda</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Silakan isi atau perbarui status kegiatan Anda untuk 5 tahun ke depan.
                    </p>

                    @if (session('success'))
                        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('dashboard.store') }}" method="POST" class="mt-6 space-y-6">
                        @csrf
                        @for ($i = 0; $i < 5; $i++)
                            @php
                                $tahun = date('Y') + $i;
                                $statusTersimpan = $tracerData[$tahun] ?? '';
                            @endphp
                            <div class="p-4 border rounded-md">
                                <h4 class="font-semibold">{{ $tahun }}</h4>
                                <div class="mt-2">
                                    <label for="status_{{ $tahun }}" class="block text-sm font-medium text-gray-700">Status Kegiatan</label>
                                    <select id="status_{{ $tahun }}" name="kegiatan[{{ $tahun }}][status]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Bekerja" {{ $statusTersimpan == 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                                        <option value="Wirausaha" {{ $statusTersimpan == 'Wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                                        <option value="Studi Lanjut" {{ $statusTersimpan == 'Studi Lanjut' ? 'selected' : '' }}>Studi Lanjut</option>
                                        <option value="Belum Bekerja" {{ $statusTersimpan == 'Belum Bekerja' ? 'selected' : '' }}>Belum Bekerja</option>
                                    </select>
                                </div>
                                <div class="mt-2">
                                     <label for="deskripsi_{{ $tahun }}" class="block text-sm font-medium text-gray-700">Deskripsi (Nama Perusahaan/Usaha/Universitas)</label>
                                     <input type="text" id="deskripsi_{{ $tahun }}" name="kegiatan[{{ $tahun }}][deskripsi]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('kegiatan.'.$tahun.'.deskripsi') }}">
                                </div>
                            </div>
                        @endfor

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan Data') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
