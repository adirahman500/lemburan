<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
  <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
    <i class="bx bx-menu bx-sm"></i>
  </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
  <!-- Search -->
  <div class="navbar-nav align-items-center flex-row">
    <div class="nav-item d-flex align-items-center" style="font-family: Times New Roman;text-align: center;font-weight: bold;">
      <img src="{{asset('logo.png')}}" width="40" height="40">
      <marquee>
        Pengajuan dan Pengelolaan Lembur Karyawan | Efisien, Cepat, dan Terintegrasi | Kelola Pengajuan Lembur dengan Mudah | Proses Persetujuan Lembur Cepat dan Transparan | {{ date('Y-m-d') }} <span id="clock"></span>
      </marquee>
    </div>
  </div>
  <!-- /Search -->

  <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- Place this tag where you want the button to render. -->
    <!-- path foto -->
    
    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="{{asset('thumbnail.png')}}" alt class="w-px-40 h-40 rounded-circle" />
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="#">
            <div class="d-flex">
              <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
                 <img src="{{asset('thumbnail.png')}}" alt class="w-px-40 h-40 rounded-circle" />
               </div>
             </div>
             <div class="flex-grow-1">
              <span class="fw-medium d-block">{{Auth::user()->name}}</span>
              <small class="text-muted">{{Auth::user()->level}}</small>
            </div>
          </div>
        </a>
      </li>
      <li>
        <div class="dropdown-divider"></div>
      </li>
      <li>
        <a class="dropdown-item btn_my_profil" href="javascript:void(0)">
          <i class="bx bx-user me-2"></i>
          <span class="align-middle">Profil Saya</span>
        </a>
      </li>
      <li>
        <div class="dropdown-divider"></div>
      </li>
      <li>
        <a class="dropdown-item" href="{{route('logout')}}">
          <i class="bx bx-power-off me-2"></i>
          <span class="align-middle">Log Out</span>
        </a>
      </li>
    </ul>
  </li>
  <!--/ User -->
</ul>
</div>