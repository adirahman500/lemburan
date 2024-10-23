<?php
$currentRoute = request()->route()->getName();
$isDataLaporan = false;
$isDataMaster = false;

if (in_array($currentRoute, ['laporan.pinjaman','laporan.simpanan'])) {
  $isDataLaporan = true;
}
if (in_array($currentRoute, ['index.jabatan','index.karyawan'])) {
  $isDataMaster = true;
}
?>
<div class="app-brand demo">
  <a href="javascript:void(0)" class="app-brand-link text-center" style="margin: auto;">
    <span class="app-brand-logo demo">
    </span>
    <span class="app-brand-text demo menu-text fw-bold">{{implode(" ", array_slice(explode(" ",Auth::user()->name),0,2))}}</span>
  </a>

  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
    <i class="bx bx-chevron-left bx-sm align-middle"></i>
  </a>
</div>

<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
  @if(Auth::user()->level == 'Supervisor')
  <li class="menu-header small text-uppercase">
    <span class="menu-header-text">Dashboard</span>
  </li>
  <li class="menu-item {{ (route('index.dashboard') == url()->current()) ? ' active' : '' }}">
    <a
    href=" {{route('index.dashboard')}} "
    class="menu-link">
    <i class="menu-icon tf-icons bx bx-home"></i>
    <div data-i18n="">Dashboard</div>
  </a>
</li>
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Master Jabatan & Karyawan</span>
</li>
<li class="menu-item{{ $isDataMaster ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-collection"></i>
    <div data-i18n="Dashboards">Master</div>
    <div class="badge bg-label-primary rounded-pill ms-auto">2</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{ (route('index.jabatan') == url()->current()) ? ' active' : '' }}">
      <a href=" {{ route('index.jabatan') }} " class="menu-link">
        <div data-i18n="">Jabatan</div>
      </a>
    </li>
    <li class="menu-item {{ (route('index.karyawan') == url()->current()) ? ' active' : '' }}">
      <a href=" {{ route('index.karyawan') }} " class="menu-link">
        <div data-i18n="">Karyawan</div>
      </a>
    </li>
  </ul>
</li>
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Kelola Pengajuan Lembur</span>
</li>
<li class="menu-item {{ request()->routeIs('index.pengajuan_lembur*') ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-folder"></i>
    <div data-i18n="Account Settings">Pengajuan Lembur</div>
    <div class="badge bg-label-primary rounded-pill ms-auto">3</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item{{ request()->fullUrlIs(route('index.pengajuan_lembur', ['status_pengajuan' => 'pending'])) ? ' active' : '' }}">
      <a href="{{ route('index.pengajuan_lembur', ['status_pengajuan'=>'pending']) }}" class="menu-link">
        <div data-i18n="Account">Pending</div>
      </a>
    </li>
    <li class="menu-item{{ request()->fullUrlIs(route('index.pengajuan_lembur', ['status_pengajuan'=>'terima'])) ? ' active' : '' }}">
      <a href="{{ route('index.pengajuan_lembur', ['status_pengajuan'=>'terima']) }}" class="menu-link">
        <div data-i18n="Notifications">Diterima</div>
      </a>
    </li>
    <li class="menu-item{{ request()->fullUrlIs(route('index.pengajuan_lembur', ['status_pengajuan'=>'tolak'])) ? ' active' : '' }}">
      <a href="{{ route('index.pengajuan_lembur', ['status_pengajuan'=>'tolak']) }}" class="menu-link">
        <div data-i18n="Notifications">Ditolak</div>
      </a>
    </li>
  </ul>
</li>
@else
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Pengajuan Lembur Saya</span>
</li>
<li class="menu-item {{ (route('karyawan.pengajuan_lembur') == url()->current()) ? ' active' : '' }}">
  <a
  href=" {{route('karyawan.pengajuan_lembur')}} "
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-wallet"></i>
  <div data-i18n="">Pengajuan Lembur</div>
</a>
</li>
@endif
</ul>