@extends('layouts.admin')

@section('title', 'Data Alumni')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Alumni</h6>
                <div>
                    <a href="{{ route('admin.alumni.create') }}" class="btn bg-gradient-primary btn-sm mb-0">Tambah Alumni</a>
                    <a href="{{ route('admin.alumni.import.show') }}" class="btn btn-outline-primary btn-sm mb-0">Import Excel</a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Lengkap & NPM</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Prodi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun Lulus</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alumni as $alumnus)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="{{ $alumnus->photo_path ? asset('storage/' . $alumnus->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($alumnus->nama_lengkap) . '&background=4B49AC&color=fff' }}" class="avatar avatar-sm me-3">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $alumnus->nama_lengkap }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $alumnus->npm }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $alumnus->prodi->nama_prodi ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $alumnus->tahun_lulus }}</span>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.alumni.show', $alumnus) }}" class="text-secondary font-weight-bold text-xs me-2">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.alumni.edit', $alumnus) }}" class="text-secondary font-weight-bold text-xs me-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.alumni.destroy', $alumnus) }}" method="POST" class="d-inline">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="text-danger font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Apakah Anda yakin ingin menghapus data alumni ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="text-secondary mb-0">Belum ada data alumni.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL UNTUK DETAIL ALUMNI -->
<!-- <div class="modal fade" id="alumniDetailModal" tabindex="-1" aria-labelledby="alumniDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="alumniDetailModalLabel">Detail Alumni</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Konten akan dimuat di sini oleh JavaScript --}}
        <div id="alumniDetailContent" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div> -->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var alumniDetailModal = document.getElementById('alumniDetailModal');
        alumniDetailModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var alumniId = button.getAttribute('data-id');
            var modalBody = document.getElementById('alumniDetailContent');

            // Tampilkan spinner saat memuat
            modalBody.innerHTML = `<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>`;

            // Ambil data dari server
            fetch(`/admin/alumni/${alumniId}`)
                .then(response => response.json())
                .then(data => {
                    let photoUrl = data.photo_path
                        ? `/storage/${data.photo_path}`
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.nama_lengkap)}&background=4B49AC&color=fff`;

                    // Tampilkan data di dalam modal
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="${photoUrl}" class="img-fluid rounded-circle shadow-sm mb-3" style="width: 120px; height: 120px; object-fit: cover;" alt="Foto Profil">
                                <h5 class="font-weight-bolder">${data.nama_lengkap || 'N/A'}</h5>
                                <p class="text-muted">${data.npm || 'N/A'}</p>
                            </div>
                            <div class="col-md-8">
                                <h6 class="font-weight-bolder">Informasi Akademik</h6>
                                <table class="table table-sm table-borderless">
                                    <tr><td style="width: 120px;"><strong>Prodi</strong></td><td>: ${data.prodi ? data.prodi.nama_prodi : 'N/A'}</td></tr>
                                    <tr><td><strong>Tahun Masuk</strong></td><td>: ${data.tahun_masuk || 'N/A'}</td></tr>
                                    <tr><td><strong>Tahun Lulus</strong></td><td>: ${data.tahun_lulus}</td></tr>
                                    <tr><td><strong>IPK</strong></td><td>: ${data.ipk || 'N/A'}</td></tr>
                                </table>
                                <hr class="my-3">
                                <h6 class="font-weight-bolder">Informasi Pribadi</h6>
                                <table class="table table-sm table-borderless">
                                    <tr><td style="width: 120px;"><strong>Email</strong></td><td>: ${data.user ? data.user.email : 'N/A'}</td></tr>
                                    <tr><td><strong>No. HP</strong></td><td>: ${data.no_hp || 'N/A'}</td></tr>
                                    <tr><td><strong>NIK</strong></td><td>: ${data.nik || 'N/A'}</td></tr>
                                    <tr><td><strong>NPWP</strong></td><td>: ${data.npwp || 'N/A'}</td></tr>
                                    <tr><td><strong>Alamat</strong></td><td>: ${data.alamat || 'N/A'}</td></tr>
                                </table>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    modalBody.innerHTML = '<p class="text-danger text-center">Gagal memuat data. Silakan coba lagi.</p>';
                    console.error('Error fetching alumni details:', error);
                });
        });
    });
</script>
@endpush
