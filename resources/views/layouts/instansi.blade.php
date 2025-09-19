<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - Portal Penilaian Alumni</title>
    <!--     Fonts and icons     -->
    <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Soft UI CSS -->
  <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />

    <!-- GAYA KUSTOM ANDA -->
    <style>
        :root {
            --skydash-primary: #4B49AC;
            --skydash-secondary: #7DA0FA;
            --skydash-text-dark: #343a40;
            --skydash-bg: #f4f5f7;
        }

        body, p, span, a, button {
            font-family: 'Poppins', sans-serif !important;
        }

        h1, h2, h3, h4, h5, h6, .font-weight-bolder, .nav-link-text, .breadcrumb-item {
            font-family: 'Poppins', sans-serif !important;
            font-weight: 600 !important;
            color: var(--skydash-text-dark);
        }

        /* Menghilangkan background blur default dari tema */
        .main-content .position-absolute {
            display: none;
        }

        .sidenav {
        background: #ffffff !important;
        box-shadow: 0 0 2rem 0 rgba(136,152,170,.15) !important;
        border-right: 1px solid #dee2e6;
    }

    .sidenav .nav-link {
        transition: all 0.2s ease-in-out;
        border-radius: 0.5rem;
    }

    .sidenav .nav-link .icon {
        width: 3rem;
        height: 3rem;
        font-size: 1.75rem;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .sidenav .nav-link:hover {
        background-color: rgba(75, 73, 172, 0.08);
    }

    .sidenav .nav-link:hover .icon {
        transform: scale(1.1) translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    .sidenav .nav-link.active {
        background-color: var(--skydash-primary);
    }

    .sidenav .nav-link.active .icon {
        background-color: #fff !important;
    }

    .sidenav .nav-link.active .icon i {
        color: var(--skydash-primary) !important;
    }

    .sidenav .nav-link.active .nav-link-text {
        color: #fff !important;
        font-weight: 600;
    }
    
    .navbar-vertical.navbar-expand-xs .navbar-collapse {
        height: auto !important; /* Menonaktifkan batasan tinggi */
        max-height: calc(100vh - 180px); /* Memberi batas tinggi maksimal yang lebih masuk akal */
    }

        .card {
            box-shadow: 0 0.5rem 1.25rem rgba(31,45,61,.08) !important;
            border: none !important;
        }

        .bg-gradient-primary {
            background-image: linear-gradient(195deg, var(--skydash-secondary) 0%, var(--skydash-primary) 100%);
        }
    </style>
</head>
<body class="g-sidenav-show" style="background-color: var(--skydash-bg);">
    
    {{-- Memuat sidebar khusus untuk instansi --}}
    @include('layouts.partials.instansi_sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <h6 class="font-weight-bolder mb-0">@yield('title')</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-link text-body font-weight-bold px-0">
                                    <i class="fa fa-sign-out-alt me-1"></i>
                                    <span class="d-sm-inline d-none">Logout</span>
                                </a>
                            </form>
                        </li>
                         <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            @if (session('success'))
                <div class="alert alert-success text-white">
                    {{ session('success') }}
                </div>
            @endif
             @if (session('error'))
                <div class="alert alert-danger text-white">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </main>
    
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/admin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/material-dashboard.min.js?v=3.1.0') }}"></script>
</body>
</html>

