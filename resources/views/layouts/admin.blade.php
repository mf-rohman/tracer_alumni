<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'Admin Dashboard')</title>

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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Soft UI CSS -->
  <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />

  <!-- Custom Style -->
  <style>
    :root {
        --skydash-primary: #4B49AC;
        --skydash-secondary: #7DA0FA;
        --skydash-text-dark: #343a40;
        --skydash-bg: #f4f5f7;
        --dark: #333;
    }

    body, p, span, a, button {
      font-family: 'Poppins', sans-serif !important;
    }

    h1, h2, h3, h4, h5, h6, .font-weight-bolder, .nav-link-text, .breadcrumb-item {
      font-family: 'Poppins', sans-serif !important;
      font-weight: 600 !important;
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

<body class="g-sidenav-show bg-gray-100">
  @include('layouts.partials.admin_sidebar')

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('layouts.partials.admin_header')

    <div class="container-fluid py-4">
      @yield('content')

      <footer class="footer pt-3">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="text-center text-sm text-muted text-lg-start">
                © <script>document.write(new Date().getFullYear())</script>,
                Made by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Unirow IT Team </a>.
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <!-- Core JS -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('scripts')
  <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>
</body>
</html>
