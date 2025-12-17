<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
    <div class="sidenav-header">
        <a class="navbar-brand m-0" href="{{ route('dashboard', ['tahun' => Auth::user()->alumni->tahun_lulus]) }}">
            <span class="ms-1 font-weight-bolder">Tracer Study</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard', ['tahun' => Auth::user()->alumni->tahun_lulus]) }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-desktop text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                @php
                    // Cek apakah user sedang berada di halaman dashboard
                    $isDashboard = request()->routeIs('dashboard');
                    
                    // Jika di dashboard, link hanya '#' tapi memicu modal
                    // Jika BUKAN di dashboard, link mengarah ke route dashboard dengan sinyal 'open_modal=true'
                    $targetUrl = $isDashboard 
                        ? '#' 
                        : route('dashboard', ['tahun' => Auth::user()->alumni->tahun_lulus, 'open_modal' => 'true']);
                        
                    // Atribut data-bs hanya ditambahkan jika sedang di dashboard
                    $modalAttributes = $isDashboard 
                        ? 'data-bs-toggle="modal" data-bs-target="#tahunKuesionerModal"' 
                        : '';
                @endphp

                <a class="nav-link" href="{{ $targetUrl }}" {!! $modalAttributes !!}>
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-clipboard-list text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Kuesioner</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user-circle text-dark"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profil Saya</span>
                </a>
            </li>
             <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                         <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-out-alt text-dark"></i>
                        </div>
                        <span class="nav-link-text ms-1">Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</aside>

