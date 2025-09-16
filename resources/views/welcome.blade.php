<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracer Study Alumni - Universitas PGRI Ronggolawe</title>

    <link rel="icon" href="{{ asset('assets/img/logos/unirow1.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body data-bs-spy="scroll" data-bs-target="#mainNavbar">

    <div class="background-animation"></div>

    <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <img src="{{ asset('assets/img/logos/unirow1.png') }}" alt="Unirow Logo" height="35" class="d-inline-block align-text-top me-2">
                Tracer Study Unirow
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#manfaat">Manfaat</a></li>
                    <li class="nav-item"><a class="nav-link" href="#alur">Alur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                    <!-- @if (Route::has('login'))
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                                <i class="fas fa-sign-in-alt me-1"></i> Login Admin
                            </a>
                        </li>
                    @endif -->
                </ul>
            </div>
        </div>
    </nav>

    <header id="hero" class="hero-section vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-8 text-center">
                    <div data-aos="fade-up">
                        <h1 class="display-4 fw-bolder text-white mb-3">Portal Tracer Study Alumni Unirow</h1>
                        <p class="lead text-white-50 mb-4">Jejaki Karir, Bangun Masa Depan. Partisipasi Anda membentuk almamater yang lebih baik.</p>
                    </div>

                    <div class="card form-card shadow-lg" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="card-title mb-3">Cek Status Alumni</h3>
                            <p class="text-muted mb-4">Masukkan Nomor Pokok Mahasiswa (NPM) untuk memulai pengisian kuesioner.</p>
                            
                            <form action="{{ route('alumni.login.cek') }}" method="POST" class="d-flex flex-column align-items-center">
                                @csrf
                                <div class="input-group input-group-lg mb-3 w-100">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="npm" name="npm" placeholder="Contoh: 123456789" required value="{{ old('npm') }}">
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow">
                                    <i class="fas fa-search me-2"></i> Lanjutkan
                                </button>
                            </form>
                             @if (session('success') || session('error'))
                                <div class="mt-4 w-100">
                                     @if (session('success'))
                                        <div class="alert alert-success d-flex align-items-center" role="alert">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <div>{{ session('success') }} <a href="{{ route('login') }}" class="alert-link">Lanjut Login</a>.</div>
                                        </div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>{{ session('error') }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section id="manfaat" class="py-5">
            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="fw-bold">Mengapa Tracer Study Penting?</h2>
                    <p class="lead text-muted">Kontribusi Anda membawa dampak besar bagi pengembangan universitas.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-feature text-center h-100">
                            <div class="icon-circle"><i class="fas fa-book-reader fa-2x"></i></div>
                            <h5 class="mt-3 fw-bold">Relevansi Kurikulum</h5>
                            <p>Mengevaluasi dan menyesuaikan kurikulum dengan kebutuhan dunia kerja.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-feature text-center h-100">
                           <div class="icon-circle"><i class="fas fa-users fa-2x"></i></div>
                            <h5 class="mt-3 fw-bold">Jaringan Alumni</h5>
                            <p>Menjaga dan memperkuat tali silaturahmi antar alumni dan almamater.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-feature text-center h-100">
                            <div class="icon-circle"><i class="fas fa-bullseye fa-2x"></i></div>
                            <h5 class="mt-3 fw-bold">Input Calon Mahasiswa</h5>
                            <p>Memberikan gambaran prospek karir bagi calon mahasiswa baru.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-feature text-center h-100">
                           <div class="icon-circle"><i class="fas fa-award fa-2x"></i></div>
                            <h5 class="mt-3 fw-bold">Peringkat Akreditasi</h5>
                            <p>Data alumni adalah salah satu komponen penting dalam penilaian akreditasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="alur" class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="fw-bold">Alur Pengisian Kuesioner</h2>
                    <p class="lead text-muted">Hanya butuh beberapa langkah mudah untuk berpartisipasi.</p>
                </div>
                <div class="row g-4 text-center">
                    <div class="col-md-3" data-aos="fade-right">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <h6 class="fw-bold mt-3">Cek NPM</h6>
                            <p>Masukkan NPM Anda pada form di halaman utama.</p>
                        </div>
                    </div>
                    <div class="col-md-3" data-aos="fade-right" data-aos-delay="100">
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <h6 class="fw-bold mt-3">Login/Registrasi</h6>
                            <p>Jika NPM valid, Anda akan diarahkan untuk login atau registrasi.</p>
                        </div>
                    </div>
                    <div class="col-md-3" data-aos="fade-right" data-aos-delay="200">
                         <div class="step-item">
                            <div class="step-number">3</div>
                            <h6 class="fw-bold mt-3">Isi Kuesioner</h6>
                            <p>Lengkapi semua pertanyaan kuesioner dengan data yang benar.</p>
                        </div>
                    </div>
                    <div class="col-md-3" data-aos="fade-right" data-aos-delay="300">
                        <div class="step-item">
                           <div class="step-number">4</div>
                            <h6 class="fw-bold mt-3">Selesai</h6>
                            <p>Simpan data Anda. Terima kasih atas partisipasi Anda!</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="kontak" class="footer bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <h5 class="fw-bold text-uppercase">Tracer Study Unirow</h5>
                    <p class="text-white-50">Studi pelacakan jejak alumni Universitas PGRI Ronggolawe untuk penjaminan kualitas sistem pendidikan dan penyiapan lulusan yang lebih kompeten di dunia kerja.</p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold text-uppercase">Navigasi</h5>
                    <ul class="list-unstyled">
                        <li><a href="#hero" class="footer-link">Beranda</a></li>
                        <li><a href="#manfaat" class="footer-link">Manfaat</a></li>
                        <li><a href="#alur" class="footer-link">Alur</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link">Login Admin</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold text-uppercase">Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex mb-2">
                            <i class="fas fa-map-marker-alt mt-1 me-3 text-primary"></i>
                            <span class="text-white-50">Jl. Manunggal No.61, Semanding, Tuban, Jawa Timur 62391</span>
                        </li>
                         <li class="d-flex mb-2">
                            <i class="fas fa-phone mt-1 me-3 text-primary"></i>
                            <span class="text-white-50">0852-5771-2828</span>
                        </li>
                         <li class="d-flex mb-2">
                            <i class="fas fa-envelope mt-1 me-3 text-primary"></i>
                            <span class="text-white-50">prospective@unirow.ac.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0 text-white-50">&copy; {{ date('Y') }} Universitas PGRI Ronggolawe. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS (Animate on Scroll)
        AOS.init({
            duration: 800,
            once: true,
        });

        // Efek navbar saat scroll
        const navbar = document.getElementById('mainNavbar');
        window.onscroll = function () {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };
    </script>
</body>
</html>