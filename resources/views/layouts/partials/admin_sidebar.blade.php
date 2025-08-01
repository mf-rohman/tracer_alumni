<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="{{ route('admin.dashboard') }}">
      <span class="ms-1 font-weight-bold">Tracer Study</span>
    </a>
  </div>

  <hr class="horizontal dark mt-0">

  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
          <div class="icon icon-shape bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-tachometer-alt text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}" href="{{ route('admin.alumni.index') }}">
          <div class="icon icon-shape bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-users text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Data Alumni</span>
        </a>
      </li>

      @if(auth()->user()->role == 'superadmin')
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Super Admin</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <div class="icon icon-shape bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-user-shield text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Manajemen User</span>
          </a>
        </li>
      @endif

    </ul>
  </div>
</aside>
