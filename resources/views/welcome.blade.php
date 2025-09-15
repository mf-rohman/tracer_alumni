<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracer Study Alumni</title>

    <link rel="icon" href="{{ asset('assets/img/logos/unirow1.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        /* */
        html, body {
            height: 100%; /* Pastikan html dan body setinggi viewport */
        }
        body {
            font-family: 'Poppins', sans-serif;
            color: #495057;
            display: flex; /* Menggunakan Flexbox untuk layout utama */
            flex-direction: column; /* Mengatur item (main, footer) secara vertikal */
        }
        main {
            flex-grow: 1; /* Membuat konten utama mengisi ruang yang tersedia */
        }
        /* */

        #page-bg-animation {
            position: fixed;
            width: 100vw;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: -1;
            background-color: #E3F2FD;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .hero-section {
            background-image: linear-gradient(135deg, rgba(75, 73, 172, 0.9) 0%, rgba(125, 160, 250, 0.9) 100%), url('https://source.unsplash.com/random/1600x900/?university,graduation');
            background-size: cover;
            background-position: center;
            color: white;
            border-radius: 1rem;
        }
        .hero-section h1 {
            font-weight: 700;
        }
        .form-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .form-card:hover {
            transform: translateY(-5px);
        }
        .aside-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }
        .list-group-item {
            border: none;
            padding: 1rem 1.25rem;
        }
        .footer {
            background-color: #343a40;
            color: #adb5bd;
            /* Tambahkan padding atas dan bawah agar lebih rapi */
            padding-top: 3rem;
            padding-bottom: 2rem;
        }
        .footer h5 {
            color: #ffffff;
            margin-bottom: 1rem;
        }
        .footer p {
            color: #adb5bd;
        }
        .footer .copyright {
            border-top: 1px solid #495057;
            padding-top: 1.5rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

    <div id="page-bg-animation"></div>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <img src="assets/img/logos/unirow1.png" alt="Tracer Study Logo" style="height: 35px; margin-right: 10px;">
                Tracer Study
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                         @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3">Login Admin</a>
                         @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container" style="margin-top: 100px; margin-bottom: 50px;">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="hero-section p-5 mb-4 text-center">
                    <h1 class="display-5">Selamat Datang di Portal Tracer Study Unirow</h1>
                    <p class="lead">Kami mengajak para alumni untuk berpartisipasi dalam studi pelacakan ini guna meningkatkan kualitas pendidikan dan relevansi kurikulum almamater.</p>
                </div>

                <div class="card form-card">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="card-title text-center mb-3">Pengecekan Status Alumni</h3>
                        <p class="text-muted text-center mb-4">Silakan masukkan Nomor Pokok Mahasiswa (NPM) Anda untuk melanjutkan ke tahap pengisian kuesioner.</p>
                        <form action="{{ route('cek.npm') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="npm" class="form-label visually-hidden">NPM</label>
                                <input type="text" class="form-control form-control-lg text-center" id="npm" name="npm" placeholder="Masukkan NPM Anda" required value="{{ old('npm') }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                                <i class="fas fa-search me-2"></i> Cek NPM
                            </button>
                        </form>
                        @if (session('success'))
                            <div class="alert alert-success mt-4">{{ session('success') }}</div>
                            <div class="mt-3 text-center">
                                <a href="{{ route('login') }}" class="btn btn-success fw-bold">Lanjut ke Halaman Login</a>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger mt-4">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card aside-card mb-4">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="fas fa-stream me-2"></i> Alur Pengisian Kuesioner
                    </div>
                    <ol class="list-group list-group-flush list-group-numbered">
                        <li class="list-group-item">Cek NPM Anda pada formulir di samping.</li>
                        <li class="list-group-item">Jika data ditemukan, lakukan registrasi/login.</li>
                        <li class="list-group-item">Isi kuesioner dengan lengkap dan benar.</li>
                        <li class="list-group-item">Simpan dan selesaikan pengisian. Terima kasih!</li>
                    </ol>
                </div>
                <div class="card aside-card">
                     <div class="card-header bg-info text-white fw-bold">
                        <i class="fas fa-award me-2"></i> Manfaat Tracer Study
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>Mengevaluasi relevansi kurikulum.</li>
                        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>Menjaga hubungan dengan alumni.</li>
                        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>Memberikan masukan bagi calon mahasiswa.</li>
                        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>Meningkatkan peringkat akreditasi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0" id="tentang">
                    <h5>Tentang Tracer Study Alumni</h5>
                    <p>Tracer Study ini merupakan studi pelacakan jejak lulusan/alumni yang dilakukan untuk mendapatkan feedback dari alumni. Feedback diperlukan dalam usaha perbaikan dan penjaminan kualitas sistem pendidikan serta untuk menyiapkan lulusan yang lebih kompeten di dunia kerja.</p>
                </div>

                <div class="col-lg-5 offset-lg-1" id="kontak">
                    <h5>Kontak yang Bisa Dihubungi</h5>
                    <p class="mb-1">
                        <i class="fas fa-map-marker-alt me-2"></i> Jl. Manunggal No.61, Wire, Gedongombo, Kec. Semanding, Kabupaten Tuban, Jawa Timur 62391
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-phone me-2"></i> Telepon: 085257712828
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-envelope me-2"></i> Email: prospective@unirow.ac.id
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center copyright">
                    <p class="mb-0">Copyright &copy; {{ date('Y') }} Universitas PGRI Ronggolawe.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        particlesJS('page-bg-animation', {
            "particles": { "number": { "value": 100, "density": { "enable": true, "value_area": 800 } }, "color": { "value": "#2196F3" }, "shape": { "type": "circle" }, "opacity": { "value": 0.6, "random": true }, "size": { "value": 4, "random": true }, "line_linked": { "enable": true, "distance": 150, "color": "#64B5F6", "opacity": 0.4, "width": 1 }, "move": { "enable": true, "speed": 2, "direction": "none", "random": false, "straight": false, "out_mode": "out", "bounce": false } }, "interactivity": { "detect_on": "window", "events": { "onhover": { "enable": true, "mode": "repulse" }, "onclick": { "enable": true, "mode": "push" }, "resize": true }, "modes": { "repulse": { "distance": 100, "duration": 0.4 }, "push": { "particles_nb": 4 } } }, "retina_detect": true });
    </script>
</body>
</html>
