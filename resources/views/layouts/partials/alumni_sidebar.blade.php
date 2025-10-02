<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('dashboard', ['tahun' => Auth::user()->alumni->tahun_lulus]) }}">
            {{-- Ganti dengan path logo Anda jika ada, contoh: <img src="{{ asset('assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo"> --}}
            <span class="ms-1 font-weight-bold">Tracer Study</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard', ['tahun' => Auth::user()->alumni->tahun_lulus]) }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-desktop text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-zinc-500' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user text-lg {{ request()->routeIs('profile.show') ? 'text-white' : 'text-zinc-500' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profil Saya</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Kuesioner Tahunan</h6>
            </li>
            @if(isset($listKuesioner))
                @foreach($listKuesioner as $kuesioner)
                <li class="nav-item">
                    <a class="nav-link text-white {{ $kuesioner['is_active'] ? 'active bg-gradient-primary' : '' }}" 
                       @if($kuesioner['status'] === 'terkunci')
                           href="#" 
                           onclick="event.preventDefault(); showLockWarning('{{ $kuesioner['lock_message'] }}')"
                       @else
                           href="{{ route('dashboard', ['tahun' => $kuesioner['tahun']]) }}"
                       @endif
                    >
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            @if($kuesioner['status'] == 'terisi')
                                <i class="material-icons opacity-10">check_circle</i>
                            @elseif($kuesioner['status'] == 'tersedia')
                                <i class="material-icons opacity-10">edit</i>
                            @else
                                <i class="material-icons opacity-10">lock</i>
                            @endif
                        </div>
                        <span class="nav-link-text ms-1">Kuesioner {{ $kuesioner['tahun'] }}</span>
                    </a>
                </li>
                @endforeach
            @endif

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Akun</h6>
            </li>
            <li class="nav-item">
                 <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                 </form>
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                       <i class="fas fa-sign-out-alt text-lg text-danger"></i>
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
