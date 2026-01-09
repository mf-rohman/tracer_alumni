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
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-gradient-primary' : '' }}" href="{{ route('admin.dashboard') }}">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-tachometer-alt {{ request()->routeIs('admin.dashboard') ? '' : 'text-info' }} "></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.responden.index') ? 'active bg-gradient-primary' : '' }}" href="{{ route('admin.responden.index') }}">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-users {{ request()->routeIs('admin.responden.index') ? 'text-white' : 'text-info' }}"></i>
          </div>
          <span class="nav-link-text ms-1">Data Responden</span>
        </a>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.alumni.kategori') ? 'active bg-gradient-primary' : '' }}" href="{{ route('admin.alumni.kategori') }}">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-filter {{ request()->routeIs('admin.alumni.kategori') ? 'text-white' : 'text-info' }}"></i>
          </div>
          <span class="nav-link-text ms-1">Data Alumni</span>
        </a>
      </li> -->

      <li class="nav-item">
        <!-- Tombol untuk buka/tutup dropdown -->
        <a class="nav-link text-white {{ request()->routeIs('admin.kuesioner.import.*') || request()->routeIs('admin.alumni.import.*') ? 'active bg-gradient-primary' :      '' }}" 
           href="#" 
           data-bs-toggle="collapse" 
           data-bs-target="#collapseImport" 
           aria-expanded="{{ request()->routeIs('admin.kuesioner.import.*') ? 'true' : 'false' }}" 
           aria-controls="collapseImport">

          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-file-upload {{ request()->routeIs('admin.kuesioner.import.*') || request()->routeIs('admin.alumni.import.*') ? 'text-white' : 'text-info' }}"></i>
          </div>
          <span class="nav-link-text ms-1">Import</span>
        </a>

        <div id="collapseImport" 
             class="collapse {{ request()->routeIs('admin.kuesioner.import.*') || request()->routeIs('admin.alumni.import.*') ? 'show' : '' }}" 
             data-bs-parent="#accordionSidebar">
        
          <ul class="list-unstyled bg-white py-2 px-3 rounded shadow-sm">
            
            <li class="mb-1">
              <a class="nav-link {{ request()->routeIs('admin.alumni.import.show') ? 'bg-gradient-primary text-white' : 'text-dark' }}" 
                 href="{{ route('admin.alumni.import.show') }}">
                 <div class="text-center me-2 d-flex align-items-center justify-content-center">
                   <i class="fas fa-user-graduate me-2"></i>
                 </div>
                <span>Alumni</span>
              </a>
            </li>
            <li class="mb-1">
              <a class="nav-link {{ request()->routeIs('admin.kuesioner.import.show') ? 'bg-gradient-primary text-white' : 'text-dark' }}" 
                 href="{{ route('admin.kuesioner.import.show') }}">
                 <div class= " text-center me-2 d-flex align-items-center justify-content-center">
                   <i class="fas fa-file-alt me-2"></i>
                 </div>
                <span>Responden</span>
              </a>
            </li>
        
        
          </ul>
        </div>
      </li>

      @if(auth()->user()->role == 'superadmin')
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Super Admin</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white {{ request()->routeIs('admin.instansi.*') ? 'active bg-gradient-primary' :  '' }}" href="{{ route('admin.instansi.index') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-building {{ request()->routeIs('admin.instansi.*') ? 'text-white' : 'text-info' }}"></i>
            </div>
            <span class="nav-link-text ms-1">Manajemen Instansi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('admin.users.index') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-user-shield {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-info'  }}"></i>
            </div>
            <span class="nav-link-text ms-1">Manajemen User</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white {{ request()->routeIs('admin.pengaturan.index') ? 'active bg-gradient-primary' : '' }}" href="{{ route('admin.pengaturan.index') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-duotone fa-solid fa-gear {{ request()->routeIs('admin.pengaturan') ? 'text-white' : 'text-info' }}"></i>
            </div>
            <span class="nav-link-text ms-1">Pengaturan Kuesioner</span>
          </a>
        </li>
      @endif

    </ul>
  </div>
</aside>
