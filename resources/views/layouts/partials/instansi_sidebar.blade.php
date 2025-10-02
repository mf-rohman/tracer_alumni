<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('instansi.dashboard') }}">
            <span class="ms-1 font-weight-bolder">Portal Penilaian</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            {{-- 1. Menu Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instansi.dashboard') ? 'active bg-gradient-primary' : '' }}" href="{{ route('instansi.dashboard') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-chart-pie text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            {{-- 2. Menu Data Alumni --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instansi.data_alumni') ? 'active bg-gradient-primary' : '' }}" href="{{ route('instansi.data_alumni') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-users text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Alumni</span>
                </a>
            </li>
            {{-- 3. Menu Pengaturan Akun --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instansi.profile.edit') ? 'active bg-gradient-primary' : '' }}" href="{{ route('instansi.profile.edit') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-cog text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pengaturan Akun</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

