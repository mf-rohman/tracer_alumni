<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('instansi.dashboard') }}">
            <span class="ms-1 font-weight-bold text-white">Portal Penilaian</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main" style="height: auto;">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('instansi.dashboard') ? 'active bg-gradient-primary' : '' }}" href="{{ route('instansi.dashboard') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-tachometer"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('instansi.dashboard') ? 'active bg-gradient-primary' : '' }}" href="{{ route('instansi.dashboard') }}">  {{-- Arahkan ke route edit profil instansi nanti --}}
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-cog"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pengaturan Akun</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
