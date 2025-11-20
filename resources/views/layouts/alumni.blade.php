<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Alumni')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="icon" href="{{ asset('assets/img/logos/unirow.png') }}" type="image/png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">

    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />

    <style>
        :root {
            --bs-primary: #4B49AC; /* Menyesuaikan variabel Bootstrap */
            --bs-primary-rgb: 75, 73, 172; /* Menyesuaikan variabel Bootstrap */
            --skydash-primary: #4B49AC;
            --skydash-secondary: #7DA0FA;
            --skydash-text-dark: #343a40;
            --skydash-bg: #f4f5f7;
        }
        body, p, span, a, button, input, select, textarea, label {
            font-family: 'Poppins', sans-serif !important;
        }
        h1, h2, h3, h4, h5, h6, .font-weight-bolder, .nav-link-text, .breadcrumb-item {
            font-family: 'Poppins', sans-serif !important;
            /* font-weight: 600 !important; */
            color: var(--skydash-text-dark);
        }
        .main-content .position-absolute {
            display: none;
        }
        .sidenav {
            background: #ffffff !important;
            box-shadow: 0 0 2rem 0 rgba(136,152,170,.15) !important;
            border-right: 1px solid #dee2e6;
        }
        .sidenav .nav-link.active {
            background-color: var(--skydash-primary);
        }
        .card {
            box-shadow: 0 0.5rem 1.25rem rgba(31,45,61,.08) !important;
            border: none !important;
        }
        .bg-gradient-primary {
            background-image: linear-gradient(195deg, var(--skydash-secondary) 0%, var(--skydash-primary) 100%);
        }
        /* Styling untuk Grid Kuesioner Kompetensi (Versi Tailwind-Compatible) */

        .radio-group {
            display: flex;
            flex-direction: row; /* INI KUNCI UTAMANYA: Memaksa tata letak menjadi horizontal */
            flex-wrap: nowrap;   /* Mencegah angka turun ke baris baru jika tidak cukup tempat */
            justify-content: center; /* Membuat grup angka berada di tengah sel */
            align-items: center; /* Menjaga angka tetap sejajar secara vertikal */
            gap: 1rem;           /* Jarak antar angka, setara 'space-x-4' di Tailwind */
        }

        /* Sembunyikan radio button asli secara visual tapi tetap fungsional */
        .radio-group input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Styling untuk label (yang kita jadikan tombol angka) */
        .radio-group label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 9999px; /* class 'rounded-full' di Tailwind */
            cursor: pointer;
            border: 1px solid #D1D5DB; /* class 'border-gray-300' */
            color: #374151; /* class 'text-gray-700' */
            font-size: 14px;
            /* font-weight: 600; */
            transition: all 0.2s ease-in-out;
        }

        /* Efek saat kursor mouse diarahkan ke angka */
        .radio-group label:hover {
            background-color: #F3F4F6; /* class 'bg-gray-100' */
            transform: translateY(-2px);
        }

        /* Style untuk angka yang sedang DIPILIH/DICEKLIS */
        .radio-group input[type="radio"]:checked + label {
            /* Anda bisa ganti warnanya agar sesuai dengan tema Anda */
            background-color: #4B49AC; /* Warna primary dari tema Anda sebelumnya */
            border-color: #4B49AC;
            color: #ffffff;
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Fokus untuk aksesibilitas keyboard */
        .radio-group input[type="radio"]:focus-visible + label {
            outline: 2px solid #4B49AC;
            outline-offset: 2px;
        }
        /* --- CSS Kustom untuk Form yang Rapi --- */

        /* Menghilangkan style default dan menyembunyikan input asli */
        .custom-radio,
        .custom-checkbox {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            flex-shrink: 0; /* Mencegah input menyusut */
            background-color: #fff;
            border: 1px solid #D1D5DB; /* border-gray-300 */
            display: inline-block;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        /* Style untuk Radio Button (Bulat) */
        .custom-radio {
            width: 1.15em;
            height: 1.15em;
            border-radius: 9999px; /* rounded-full */
        }

        /* Style untuk Checkbox (Kotak) */
        .custom-checkbox {
            width: 1.15em;
            height: 1.15em;
            border-radius: 0.25rem; /* rounded */
        }

        /* Efek saat mouse hover */
        .custom-radio:hover,
        .custom-checkbox:hover {
            border-color: #9CA3AF; /* border-gray-400 */
        }

        /* --- Style saat DIPILIH --- */

        /* Radio Button Terpilih */
        .custom-radio:checked {
            border-color: #4B49AC;
            background-color: #4B49AC;
        }
        .custom-radio:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0.5em;
            height: 0.5em;
            border-radius: 50%;
            background-color: white;
            transform: translate(-50%, -50%);
        }

        /* Checkbox Terpilih */
        .custom-checkbox:checked {
            border-color: #4B49AC;
            background-color: #4B49AC;
        }
        .custom-checkbox:checked::after {
            content: "✓";
            position: absolute;
            top: 50%;
            left: 50%;
            color: white;
            font-size: 0.9em;
            font-weight: bold;
            transform: translate(-50%, -50%);
            line-height: 1;
        }

        /* Fokus untuk aksesibilitas keyboard */
        .custom-radio:focus,
        .custom-checkbox:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px #C7D2FE; /* ring-2 ring-indigo-200 */
        }
        label, .form-label {
            font-size: 0.75rem;
            font-weight: 400;
            margin-bottom: 0.5rem;
            color: #27272a;
            margin-left: 0.25rem;
        }
        .impersonate-banner {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            color: #fff;
            padding: 0.6rem 1rem;
            font-size: 0.875rem;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1050;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }
        
        .impersonate-banner a {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 0.375rem;
            padding: 0.35rem 0.75rem;
            text-decoration: none;
            transition: background 0.2s ease;
        }
        
        .impersonate-banner a:hover {
            background-color: rgba(255, 255, 255, 0.3);
}

    </style>
</head>

<body class="g-sidenav-show bg-gray-100 {{ session('admin_impersonator_id') ? 'impersonating' : '' }}">
    @if(session('admin_impersonator_id'))
        <div class="impersonate-banner">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-user-secret me-2"></i>
                    <span>Anda sedang login sebagai <strong>{{ Auth::user()->name }}</strong>.</span>
                </div>
                <a href="{{ route('users.logout_as') }}" class="btn btn-sm btn-light fw-semibold text-danger">
                    <i class="fas fa-sign-out-alt me-1"></i> Kembali ke Akun Admin Anda
                </a>
            </div>
        </div>
    @endif
    @include('layouts.partials.alumni_sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg"    
        class="main-content position-relative max-height-vh-100 h-100 border-radius-lg"
        x-data="{ scrollY: 0 }"
        x-init="$el.addEventListener('scroll', () => scrollY = $el.scrollTop)"
    >
        @include('layouts.partials.alumni_header')

        <div class="container-fluid py-4">
            @yield('content')

            <footer class="footer pt-3">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="text-center text-sm text-muted text-lg-start">
                                © <script>document.write(new Date().getFullYear())</script>,
                                Sistem Informasi Tracer Study.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <div class="modal fade" id="kuesionerLockModal" tabindex="-1" aria-labelledby="kuesionerLockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bolder" id="kuesionerLockModalLabel"><i class="fas fa-lock me-2"></i>Kuesioner Terkunci</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalLockMessage">Pesan notifikasi akan muncul di sini.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')

    <script>
        // Pastikan kode ini berjalan setelah semua library lain dimuat
        document.addEventListener('DOMContentLoaded', function () {
            const kuesionerLockModalElement = document.getElementById('kuesionerLockModal');
            if (kuesionerLockModalElement) {
                // Membuat instance Modal Bootstrap baru agar bisa kita kontrol
                const kuesionerLockModal = new bootstrap.Modal(kuesionerLockModalElement);
        
                // Fungsi global untuk menampilkan modal dengan pesan kustom
                window.showLockWarning = function(message) {
                    document.getElementById('modalLockMessage').innerText = message;
                    kuesionerLockModal.show();
                }
            }
        });
    </script>
    <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    
</body>
</html>
