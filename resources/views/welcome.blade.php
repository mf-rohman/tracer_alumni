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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/taos@1.0.5/dist/taos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .navbar-sticky {
            transition: all 0.3s ease-in-out;
            font-size: larger;
            
        }

        .navbar-sticky.scrolled {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            padding-bottom: 20px;
            padding-top: 20px;
            font-size: medium;
        }

        .navbar-sticky.scrolled img{
            height: 50px;
        }

        .navbar-sticky.scrolled span {
            font-size: 1.25rem /* 20px */;
            line-height: 1.75rem /* 28px */;
        }

        .gradient-text {
            background: linear-gradient(90deg, #4F46E5, #8B5CF6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .slider-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            animation: fadeSlider 24s infinite; 
            transition: opacity 1s ease-in-out;
        }
        .slider-image:nth-child(1) { animation-delay: 0s; }
        .slider-image:nth-child(2) { animation-delay: 6s; }
        .slider-image:nth-child(3) { animation-delay: 12s; }
        .slider-image:nth-child(4) { animation-delay: 18s; }


        .flip-card { perspective: 1000px; cursor: pointer; }
        .flip-card-inner {
            position: relative; width: 100%; height: 100%;
            transition: transform 0.7s; transform-style: preserve-3d;
        }
        .flip-card.flipped .flip-card-inner { transform: rotateY(180deg); }
        .flip-card-front, .flip-card-back {
            position: absolute; width: 100%; height: 100%;
            -webkit-backface-visibility: hidden; backface-visibility: hidden;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            padding: 2rem; display: flex; flex-direction: column; justify-content: center;
        }
        .flip-card-back { transform: rotateY(180deg); }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        #bg-step {
            background-color: white;
        }

        #bg-step:hover {
            background-color: #4F46E5;
            /* transition: transform 0.7s; */
        }

        @keyframes fadeSlider {
            0% { opacity: 0; }
            8.33% { opacity: 1; } 
            25% { opacity: 0; } 
            100% { opacity: 0; }
        }
    </style>


</head>
<body class="bg-slate-50">

    <!-- <div class="background-animation"></div> -->

    <nav id="navbar" class="navbar-sticky fixed top-0 left-0 right-0 z-50  py-14 ">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <a href="#" class="flex items-center space-x-2">
                    <img src="{{ asset('assets/img/logos/unirow1.png') }}" class="h-20 " alt="Unirow Logo">
                    <span class="text-2xl font-bold text-gray-800">Tracer Study</span>
                </a>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#hero" class="text-gray-600 hover:text-indigo-600 transition">Beranda</a>
                    <a href="#manfaat" class="text-gray-600 hover:text-indigo-600 transition">Manfaat</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-indigo-600 transition">Testimoni</a>
                    <a href="#alur" class="text-gray-600 hover:text-indigo-600 transition">Alur</a>
                    <a href="#faq" class="text-gray-600 hover:text-indigo-600 transition">FAQ</a>
                </div>

                <!-- <div class="hidden md:block">
                    <div class="relative inline-block text-left">
                        <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true" onclick="toggleDropdown()">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Portal Login
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="dropdown-menu" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <a href="{{ route('login') }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">Admin / BAAK</a>
                                <a href="{{ route('instansi.login.show') }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">Perusahaan / Instansi</a>
                            </div>
                        </div>
                    </div>
                </div> -->
                
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-800 focus:outline-none">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-white shadow-md">
            <a href="#hero" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Beranda</a>
            <a href="#manfaat" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Manfaat</a>
            <a href="#testimonials" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Testimoni</a>
            <a href="#alur" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Alur</a>
            <a href="#faq" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">FAQ</a>
            <hr class="my-2">
            <!-- <a href="{{ route('login') }}" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Login Admin / BAAK</a>
            <a href="{{ route('instansi.login.show') }}" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Login Instansi</a> -->
        </div>
    </nav>

    <section id="hero" class="sticky bg-gray-900 text-white pt-32 pb-20 mt-24">
        <div class="absolute inset-0 bg-indigo-900">
            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070" alt="Graduation" class="slider-image">
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070" alt="Students studying" class="slider-image">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071" alt="Students collaborating" class="slider-image">
            <img src="https://images.unsplash.com/photo-1498243691581-b145c3f54a5a?q=80&w=2070" alt="University library" class="slider-image">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 id="typing-animation" class="text-4xl md:text-6xl font-extrabold leading-tight mb-5 h-32 md:h-24"></h1>
                <!-- <p class="text-lg md:text-xl text-gray-300 mb-8">
                    Partisipasi Anda dalam Tracer Study membentuk almamater yang lebih baik dan relevan dengan dunia kerja.
                </p> -->
            </div>
            
            <div class="max-w-2xl mx-auto">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl p-6 md:p-8">
                    <h3 class="text-2xl font-bold text-gray-800 text-center mb-2">Cek Status Alumni</h3>
                    <p class="text-gray-600 text-center mb-6">Masukkan NPM untuk memulai.</p>
                     @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('alumni.login.cek') }}" method="POST">
                        @csrf
                        <div class="flex items-center border-2 border-gray-200 rounded-lg focus-within:border-indigo-500 p-2">
                            
                            <i class="fas fa-id-card text-gray-400 mx-3 "></i>
                            <input type="text" name="npm" class="w-full bg-transparent border-none focus:outline-none focus:ring-0  text-gray-800 placeholder-gray-500" placeholder="Masukkan NPM Anda..." required>
                            <button type="submit" class="ml-2 bg-indigo-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-indigo-700 transition">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @if($totalAlumni > 0 || $totalResponden > 0 || $totalBekerja > 0)
    <section class="py-12 bg-slate-50">
        <div class="container mx-auto px-4 delay-[300ms] duration-[600ms] taos:translate-x-[-100%] taos:opacity-0" data-taos-offset="400">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-4xl font-extrabold text-indigo-600" data-target="{{ $totalAlumni }}">0</h2>
                    <p class="text-gray-500 font-medium">Total Alumni Terdata</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-4xl font-extrabold text-indigo-600" data-target="{{ $totalResponden }}">0</h2>
                    <p class="text-gray-500 font-medium">Alumni Berpartisipasi</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                     <h2 class="text-4xl font-extrabold text-indigo-600" data-target="{{ $totalBekerja }}">0</h2>
                    <p class="text-gray-500 font-medium">Alumni Telah Bekerja</p>
                </div>
            </div>
        </div>
    </section>
    @endif


    <section id="manfaat" class="py-20 bg-white">
        <div class="container mx-auto px-4" data-aos="slide-up">
            <div class="text-center mb-12"  data-aos="slide-up">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Mengapa Tracer Study Penting?</h2>
                <p class="mt-4 text-lg text-gray-600">Kontribusi Anda membawa dampak besar bagi pengembangan universitas.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" data-aos="fade-up">
                <div class="text-center p-6 bg-slate-50 rounded-xl transition hover:shadow-xl hover:-translate-y-2">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                        <i class="fas fa-book-reader fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Relevansi Kurikulum</h3>
                    <p class="text-gray-600">Mengevaluasi dan menyesuaikan kurikulum dengan kebutuhan dunia kerja.</p>
                </div>
                <div class="text-center p-6 bg-slate-50 rounded-xl transition hover:shadow-xl hover:-translate-y-2">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Jaringan Alumni</h3>
                    <p class="text-gray-600">Memperkuat tali silaturahmi antar alumni dan almamater.</p>
                </div>
                <div class="text-center p-6 bg-slate-50 rounded-xl transition hover:shadow-xl hover:-translate-y-2">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                        <i class="fas fa-bullseye fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Input Calon Mahasiswa</h3>
                    <p class="text-gray-600">Memberikan gambaran prospek karir bagi calon mahasiswa baru.</p>
                </div>
                <div class="text-center p-6 bg-slate-50 rounded-xl transition hover:shadow-xl hover:-translate-y-2">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                        <i class="fas fa-award fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Peringkat Akreditasi</h3>
                    <p class="text-gray-600">Data alumni adalah salah satu komponen penting dalam penilaian akreditasi.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="py-20 bg-slate-50">
      <div class="container mx-auto px-4" data-aos="fade-up" data-aos-duration="1000">
        <div class="text-center mb-16" data-aos="fade-up" data-aos-delay="200">
          <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">
            Alur Pengisian Kuesioner
          </h2>
          <p class="mt-4 text-lg text-gray-600">
            Hanya butuh beberapa langkah mudah untuk berpartisipasi.
          </p>
        </div>

        <div class="relative" data-aos="fade-up" data-aos-delay="300">
          <div class="hidden md:block absolute top-8 left-0 w-full h-0.5 bg-gray-200"></div>

          <div class="grid grid-cols-1 md:grid-cols-4 gap-12 relative">
            <div class="text-center group" data-aos="zoom-in" data-aos-delay="400">
              <div class="relative mb-4">
                <div id="bg-step"
                  class="w-16 h-16 mx-auto  border-4 border-indigo-600 rounded-full flex items-center justify-center
                         text-2xl font-bold text-indigo-600
                         transition-all duration-500 ease-out hover:bg-transparent
                         group-hover:text-white group-hover:cursor-pointer group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.5)]"
                >
                  1
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2 transition-colors duration-300 group-hover:text-indigo-600">
                Cek NPM
              </h3>
              <p class="text-gray-600 group-hover:text-gray-800">
                Masukkan NPM Anda pada form di halaman utama.
              </p>
            </div>

            <div class="text-center group" data-aos="zoom-in" data-aos-delay="600">
              <div class="relative mb-4">
                <div id="bg-step"
                  class="w-16 h-16 mx-auto  border-4 border-indigo-600 rounded-full flex items-center justify-center
                         text-2xl font-bold text-indigo-600
                         transition-all duration-500 ease-out group-hover:text-white
                         group-hover:bg-indigo-600 group-hover:cursor-pointer  group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.5)]"
                >
                  2
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2 transition-colors duration-300 group-hover:text-indigo-600">
                Verifikasi Diri
              </h3>
              <p class="text-gray-600 group-hover:text-gray-800">
                Lakukan verifikasi dengan NIK untuk keamanan data Anda.
              </p>
            </div>

            <div class="text-center group" data-aos="zoom-in" data-aos-delay="800">
              <div class="relative mb-4">
                <div id="bg-step"
                  class="w-16 h-16 mx-auto  border-4 border-indigo-600 rounded-full flex items-center justify-center
                         text-2xl font-bold text-indigo-600
                         transition-all duration-500 ease-out group-hover:text-white
                         group-hover:bg-indigo-600 group-hover:cursor-pointer group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.5)]"
                >
                  3
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2 transition-colors duration-300 group-hover:text-indigo-600">
                Isi Kuesioner
              </h3>
              <p class="text-gray-600 group-hover:text-gray-800">
                Lengkapi semua pertanyaan kuesioner dengan data yang benar.
              </p>
            </div>

            <div class="text-center group" data-aos="zoom-in" data-aos-delay="1000">
              <div class="relative mb-4">
                <div id="bg-step"
                  class="w-16 h-16 mx-auto  border-4 border-indigo-600 rounded-full flex items-center justify-center
                         text-2xl font-bold text-indigo-600
                         transition-all duration-500 ease-out group-hover:text-white
                         group-hover:bg-indigo-600 group-hover:cursor-pointer group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(79,70,229,0.5)]"
                >
                  4
                </div>
              </div>
              <h3 class="text-xl font-bold text-gray-800 mb-2 transition-colors duration-300 group-hover:text-indigo-600">
                Selesai
              </h3>
              <p class="text-gray-600 group-hover:text-gray-800">
                Terima kasih atas partisipasi Anda yang berharga!
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="testimonials" class="py-20 bg-slate-50">
        <div class="container mx-auto px-4" x-data="testimonialSlider">
            <div class="flex justify-between items-center mb-12" data-aos="fade-up">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Apa Kata Mereka?</h2>
                    <p class="mt-4 text-lg text-gray-600">Kisah sukses dan pengalaman berharga dari para alumni Unirow.</p>
                </div>
                {{-- Tombol Navigasi Slider --}}
                <div class="hidden md:flex space-x-2">
                    <button @click="prev()" class="h-12 w-12 rounded-full bg-white shadow-md text-gray-600 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button @click="next()" class="h-12 w-12 rounded-full bg-white shadow-md text-gray-600 hover:bg-indigo-600 hover:text-white transition flex items-center justify-center">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- Kontainer Slider --}}
            <div class="overflow-hidden relative" @mouseenter="pauseSlider()" @mouseleave="resumeSlider()">
                <div x-ref="slider" class="flex space-x-8 overflow-x-auto scrollbar-hide" data-aos="fade-up">
                    @php
                        $testimonials = [
                            ['name' => 'Ahmad Dahlan', 'job' => 'Software Engineer di TechCorp', 'quote' => 'Pendidikan di Unirow tidak hanya memberi saya ilmu, tetapi juga membentuk karakter dan jaringan yang sangat membantu saya di dunia kerja.', 'color' => 'bg-indigo-600'],
                            ['name' => 'Siti Aisyah', 'job' => 'Founder & CEO, CreativeHub', 'quote' => 'Saya belajar banyak tentang kepemimpinan dan kerja tim selama di organisasi kemahasiswaan. Itu menjadi bekal utama saya saat merintis usaha.', 'color' => 'bg-purple-600'],
                            ['name' => 'Budi Santoso', 'job' => 'Analis Kebijakan di Kementerian', 'quote' => 'Dosen-dosen sangat mendukung dan ilmunya relevan. Saya merasa siap bersaing di tingkat nasional berkat fondasi yang kuat dari almamater.', 'color' => 'bg-indigo-800'],
                            ['name' => 'Dewi Lestari', 'job' => 'Manajer Pemasaran', 'quote' => 'Kurikulum yang up-to-date membuat saya mudah beradaptasi dengan tren industri. Terima kasih, Unirow!', 'color' => 'bg-purple-600'],
                            ['name' => 'Eko Prasetyo', 'job' => 'Guru & Penggerak Pendidikan', 'quote' => 'Ilmu kependidikan yang saya dapatkan sangat aplikatif. Saya bangga menjadi bagian dari alumni yang berkontribusi di dunia pendidikan.', 'color' => 'bg-indigo-600'],
                            ['name' => 'Rina Amelia', 'job' => 'Wirausahawan Kuliner', 'quote' => 'Selain ilmu formal, soft-skills yang saya asah di kampus menjadi kunci sukses saya dalam membangun bisnis dari nol.', 'color' => 'bg-purple-600'],
                        ];
                    @endphp
                    @foreach($testimonials as $testi)
                    <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0">
                        <div class="h-80" x-data="{ flipped: false }" @click="flipped = !flipped">
                            <div class="flip-card h-full" :class="{ 'flipped': flipped }">
                                <div class="flip-card-inner">
                                    {{-- Sisi Depan --}}
                                    <div class="flip-card-front bg-white items-center text-center">
                                        <img class="h-24 w-24 rounded-full object-cover mb-4" src="https://ui-avatars.com/api/?name={{ urlencode($testi['name']) }}&background=4F46E5&color=fff&size=128" alt="Foto {{ $testi['name'] }}">
                                        <h3 class="font-bold text-xl text-gray-800">{{ $testi['name'] }}</h3>
                                        <p class="text-sm text-gray-500">{{ $testi['job'] }}</p>
                                        <span class="mt-4 inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">Klik untuk melihat</span>
                                    </div>
                                    {{-- Sisi Belakang --}}
                                    <div class="flip-card-back {{ $testi['color'] }} text-white">
                                        <i class="fas fa-quote-left text-white/50 text-3xl mb-4"></i>
                                        <blockquote class="italic">{{ $testi['quote'] }}</blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-zinc-50 overflow-hidden">
        <div class="flex justify-start mx-auto my-1 ">
            <div class="relative group w-1/2 mx-10" data-aos="zoom-in-right" >
                <a href="#_"> 
                    <img src="https://images.unsplash.com/photo-1530035415911-95194de4ebcc?    q=80&amp;w=2670&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;      ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="rounded-xl rotate-6 group-hover:rotate-0 transition-all duration-700 ease-out hover:-translate-y-12 object-cover transform origin-bottom" alt="#_" > 
                </a>

                <p class=" top-1/2 left-full transform -translate-y-1/2 -translate-x-[200%] group-hover:translate-x-28 group-hover:opacity-100 transition-all duration-700 ease-out text-lg font-semibold text-white max-w-md bg-blue-900 p-4 rounded-xl ">temporarily using Google images, because we are still waiting for documentation from former student</p > 
            </div>
        </div>

        <div class="flex justify-end mx-auto my-1">
            <div class="relative group w-1/2 mx-10" data-aos="zoom-in-left">
                <a href="#_"> 
                    <img src="https://images.unsplash.com/photo-1487180144351-b8472da7d491?q=80&amp;w=2672&amp;   auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="rounded-xl -rotate-6 group-hover:rotate-0 transition-all duration-700 ease-out hover:-translate-y-12 object-cover transform origin-bottom" alt="#_"> 
                </a>

                <p class=" top-1/2 left-full transform -translate-y-1/2 translate-x-[200%] group-hover:translate-x-28 group-hover:opacity-100 transition-all duration-700 ease-out text-lg font-semibold text-white max-w-md bg-blue-900 backdrop-blur-md p-4 rounded-xl ">temporarily using Google images, because we are still waiting for documentation from former student</p > 
            </div>
        </div>

        <div class="flex justify-start mx-auto my-1">
            <div class="reltive group w-1/2 mx-10" data-aos="zoom-in-right">
                <a href="#_"> 
                    <img src="https://images.unsplash.com/photo-1586996292898-71f4036c4e07?    q=80&amp;w=2670&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;     ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"  class="rounded-xl rotate-6 group-hover:rotate-0 transition-all duration-700 ease-out hover:-translate-y-12     object-cover transform origin-bottom" alt="#_"> 
                </a>

                <p class=" top-1/2 left-full transform -translate-y-1/2 -translate-x-[200%] group-hover:translate-x-28 group-hover:opacity-100 transition-all duration-700 ease-out text-lg font-semibold text-white max-w-md bg-blue-900 backdrop-blur-md p-4 rounded-xl ">temporarily using Google images, because we are still waiting for documentation from former student</p > 
            </div>
        </div>

        <div class="flex justify-end mx-auto my-1">
            <div class="relative group w-1/2 mx-10" data-aos="zoom-in-left">
                <a href="#_"> 
                    <img src="https://images.unsplash.com/photo-1522775417749-29284fb89f43?q=80&amp;w=2574&amp;auto=format&  amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"  class="rounded-xl -rotate-6 group-hover:rotate-0 transition-all duration-700 ease-out hover:-translate-y-12     object-cover transform origin-bottom" alt="#_"> 
                </a>
                
                <p class=" top-1/2 left-full transform -translate-y-1/2 translate-x-[200%] group-hover:translate-x-28 group-hover:opacity-100 transition-all duration-700 ease-out text-lg font-semibold text-white max-w-md bg-blue-900 backdrop-blur-md p-4 rounded-xl ">temporarily using Google images, because we are still waiting for documentation from former student</p > 
            </div>
        </div>
    </section>


    <section id="faq" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Pertanyaan Umum (FAQ)</h2>
                <p class="mt-4 text-lg text-gray-600">Temukan jawaban atas pertanyaan yang sering diajukan.</p>
            </div>
            <div class="max-w-3xl mx-auto" x-data="{ open: 1 }">
                <div class="border-b">
                    <button @click="open = (open === 1 ? null : 1)" class="w-full flex justify-between items-center py-4 text-left">
                        <span class="text-lg font-semibold text-gray-800">Apakah data saya akan aman?</span>
                        <i class="fas" :class="open === 1 ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="open === 1" x-collapse>
                        <p class="pb-4 text-gray-600">Tentu saja. Semua data yang Anda berikan dijamin kerahasiaannya dan hanya akan digunakan untuk keperluan statistik dan pengembangan internal universitas.</p>
                    </div>
                </div>
                <div class="border-b">
                    <button @click="open = (open === 2 ? null : 2)" class="w-full flex justify-between items-center py-4 text-left">
                        <span class="text-lg font-semibold text-gray-800">Berapa lama waktu pengisian kuesioner?</span>
                        <i class="fas" :class="open === 2 ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="open === 2" x-collapse>
                        <p class="pb-4 text-gray-600">Pengisian kuesioner biasanya memakan waktu sekitar 5-10 menit. Anda bisa menyimpan progres dan melanjutkannya di lain waktu jika diperlukan.</p>
                    </div>
                </div>
                <div class="border-b">
                    <button @click="open = (open === 3 ? null : 3)" class="w-full flex justify-between items-center py-4 text-left">
                        <span class="text-lg font-semibold text-gray-800">Bagaimana jika saya lupa NPM?</span>
                        <i class="fas" :class="open === 3 ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="open === 3" x-collapse>
                        <p class="pb-4 text-gray-600">Jika Anda lupa NPM, silakan hubungi bagian administrasi akademik (BAAK) atau layanan alumni universitas untuk mendapatkan bantuan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="relative mt-16 bg-blue-900 text-white">
        <svg class="absolute top-0 w-full h-6 -mt-5 sm:-mt-10 sm:h-16 text-blue-900" preserveAspectRatio="none" viewBox="0 0    1440 54">
          <path fill="currentColor" d="M0 22L120 16.7C240 11 480 1.00001 720 0.700012C960 1.00001 1200 11 1320 16.7L1440    22V54H1320C1200 54 960 54 720 54C480 54 240 54 120 54H0V22Z"></path>
        </svg>
        <div class="px-4 pt-12 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8">
          <div class="grid gap-16 row-gap-10 mb-8 lg:grid-cols-6">
            <div class="md:max-w-md lg:col-span-2">
              <a href="/" aria-label="Go home" title="Company" class="inline-flex items-center">
                <img src="{{ asset('assets/img/logos/unirow1.png') }}" class="h-12 " alt="Unirow Logo">
                <span class="ml-2 text-xl font-bold tracking-wide text-gray-100 uppercase">Tracer Study</span>
              </a>
              <div class="mt-4 lg:max-w-sm bg">
                <p class="text-sm text-deep-purple-50">
                    Tracking alumni Universitas PGRI Ronggolawe untuk bahan penjaminan kualitas sistem pendidikan dan penyiapan lulusan yang lebih kompeten di bidangnya.
                </p> <br>
                <i class="mt-4 text-sm text-deep-purple-50">
                  "Education is not learning of facts, but traning of the mind to think".
                </i>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-5 row-gap-8 lg:col-span-4 md:grid-cols-3">
              <div>
                <p class="font-extrabold tracking-wide text-blue-500 text-lg">
                  Navigasi
                </p>
                <ul class="mt-2 space-y-2">
                  <li>
                    <a href="/" class="transition-colors duration-300 text-deep-purple-50 hover:text-teal-accent-400">Home</a>
                  </li>
                  <li>
                    <a href="/" class="transition-colors duration-300 text-deep-purple-50 hover:text-teal-accent-400">Manfaat</a>
                  </li>
                  <li>
                    <a href="/" class="transition-colors duration-300 text-deep-purple-50 hover:text-teal-accent-400">Alur</a>
                  </li>
                  <li>
                    <a href="/" class="transition-colors duration-300 text-deep-purple-50       hover:text-teal-accent-400">Testimoni</a>
                  </li>
                </ul>
              </div>
              <div>
                <p class="font-extrabold tracking-wide text-blue-500 text-lg">Unit Penunjang</p>
                <ul class="mt-2 space-y-2">
                  <li>
                    <a href="https://kpm.unirow.ac.id" class="transition-colors duration-300 text-deep-purple-50 hover:text-teal-accent-400">Komisi Penjamin Mutu</a>
                  </li>
                  <li>
                    <a href="https://lemlit.unirow.ac.id" class="transition-colors duration-300 text-deep-purple-50 hover:text-teal-accent-400">Lembaga Penelitian</a>
                  </li>
                  <li>
                    <a href="https://lpm.unirow.ac.id" class="transition-colors duration-300 text-deep-purple-50 hover:text-teal-accent-400">LPM</a>
                  </li>
                  <li>
                    <a href="http://lp3.unirow.ac.id" class="transition-colors duration-300 text-deep-purple-50       hover:text-teal-accent-400">LP3</a>
                  </li>
                </ul>
              </div>              
              <div>
                <p class="font-extrabold tracking-wide text-blue-500 text-lg">Hubungi Kami</p>
                <ul class="mt-2 space-y-2">
                    <li><i class="fas fa-phone-alt mr-0"></i>&ensp; +6285257712828</li>
                    <li><i class="fas fa-envelope mr-0"></i>&ensp; prospective@unirow.ac.id</li>
                    <li><i class="fas fa-map-marker-alt mr-0"></i>&ensp; Jl. Manunggal No.61, Tuban</li>
                </ul>
              </div>
            </div>


          </div>
          <div class="flex flex-col justify-between pt-5 pb-10 border-t border-deep-purple-accent-200 sm:flex-row">
            <p class="text-sm text-gray-100">
              © Copyright 2025 PGRI Ronggolawe University. All rights reserved.
            </p>
            <div class="flex items-center mt-4 space-x-4 sm:mt-0">
              <a href="mailto:prospective@unirow.ac.id" class="transition-colors duration-300 text-deep-purple-100 hover:text-teal-accent-400">
                <i class="fa-solid fa-envelope text-3xl"></i>
              </a>
              <a href="https://www.instagram.com/officialunirow" class="transition-colors duration-300 text-deep-purple-100 hover:text-teal-accent-400">
                <i class="fa-brands fa-instagram text-3xl"></i>
              </a>
              <a href="https://wa.link/4mg3f4" class="transition-colors duration-300 text-deep-purple-100 hover:text-teal-accent-400">
                <i class="fa-brands fa-whatsapp text-3xl"></i>
              </a>
              <a href="https://unirow.ac.id" class="transition-colors duration-300 text-deep-purple-100 hover:text-teal-accent-400">
                <i class="fa-solid fa-globe text-3xl"></i>
              </a>
            </div>
          </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/typeit@8.7.0/dist/index.umd.js"></script>
    <script>

        document.addEventListener('alpine:init', () => {
            Alpine.data('testimonialSlider', () => ({
                interval: null,
                currentIndex: 0,

                init() {
                    this.startSlider();
                },

                startSlider() {
                    this.interval = setInterval(() => { 
                        this.next(); 
                    }, 5000);
                },

                pauseSlider() {
                    if (this.interval) {
                        clearInterval(this.interval);
                        this.interval = null;
                    }
                },

                resumeSlider() {
                    if (!this.interval) {
                        this.startSlider();
                    }
                },

                next() {
                    const slider = this.$refs.slider;
                    if (!slider || !slider.children.length) return;

                    const cardWidth = slider.children[0].offsetWidth;
                    const gap = 32; 
                    const scrollAmount = cardWidth + gap;

                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                        this.currentIndex = 0;
                    } else {
                        slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                        this.currentIndex++;
                    }
                    console.log("ffdfgdfughfuhgruit");
                },

                prev() {
                    const slider = this.$refs.slider;
                    if (!slider || !slider.children.length) return;

                    const cardWidth = slider.children[0].offsetWidth;
                    const gap = 32;
                    const scrollAmount = cardWidth + gap;

                    if (slider.scrollLeft <= 10) {
                        slider.scrollTo({ left: slider.scrollWidth, behavior: 'smooth' });
                        this.currentIndex = slider.children.length - 1;
                    } else {
                        slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                        this.currentIndex--;
                    }
                }
            }));
        });

        AOS.init({ duration: 800, once: false, mirror:true });

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        function toggleDropdown() {
            document.getElementById('dropdown-menu').classList.toggle('hidden');
        }

        window.onclick = function(event) {
            if (!event.target.matches('#menu-button') && !event.target.closest('#menu-button')) {
                var dropdowns = document.getElementsByClassName("origin-top-right");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (!openDropdown.classList.contains('hidden')) {
                        openDropdown.classList.add('hidden');
                    }
                }
            }
        }

        // window.onbeforeunload = function () {
        //     window.scrollTo(0, 0,);
        // };

        // window.addEventListener("load", () => {
        //     document.body.classList.remove("opacity-0");
        //     window.scrollTo({ top: 0, behavior: "smooth" });
        // });
        document.addEventListener("DOMContentLoaded", function() {
            const counters = document.querySelectorAll('[data-target]');
            const speed = 1000;

            const animateCounter = counter => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText.replace(/\./g, '');
                const inc = Math.ceil(target / speed);
                if (count < target) {
                    counter.innerText = Math.min(count + inc, target).toLocaleString('id-ID');
                    setTimeout(() => animateCounter(counter), 10);
                } else {
                    counter.innerText = target.toLocaleString('id-ID');
                }
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(counter => {
                observer.observe(counter);
            });

            new TypeIt("#typing-animation", {
                speed: 75,
                startDelay: 900,
                loop: true,
                breakLines: false,
                cursor: false,   
            })
            .type("Jejaki Karir, ", { delay: 100 })
            .type('<span class="gradient-text">Bangun Masa Depan.</span>', { delay: 400 })
            .pause(3000)
            .delete(null, { delay: 200, speed: 50 })
            .go();
        });

   </script>
</body>
</html>