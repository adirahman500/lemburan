<!DOCTYPE html>
<html
lang="en"
class="light-style layout-menu-fixed layout-compact"
dir="ltr"
data-theme="theme-default">
<head>
  <meta charset="utf-8" />
  <meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') | PT PLN (PERSERO) UID KALSELTENG</title>
  <meta name="description" content="" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{asset('logo.png')}}" />
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
  rel="stylesheet" />
  <!-- datatable -->
  <link href="cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="{{asset('panel/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  <!--  -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/fonts/boxicons.css')}}" />
  <!-- Core CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/custom.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/css/demo.css')}}" />
  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/libs/apex-charts/apex-charts.css')}}" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.css">
  <!-- Helpers -->
  <script src="{{asset('panel/assets/vendor/js/helpers.js')}}"></script>
  <script src="{{asset('panel/assets/js/config.js')}}"></script>
</head>
<style type="text/css">
  .modal-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 9999;
    visibility: hidden;
  }
  .modal-body {
    position: relative;
  }
  .modal.show .modal-loading {
    visibility: visible;
  }
  .swal2-container {
    z-index: 99999;
  }
  .select2-hidden-accessible + .select2-container .select2-selection {
    height: 36px;
    padding-top: 2px;
  }
  .select2-hidden-accessible + .select2-container .select2-selection__arrow, .select2-hidden-accessible + .select2-container .select2-selection_clear{
    height: 40px;
  }
  select[readonly].select2-hidden-accessible + .select2-container {
    pointer-events: none;
    touch-action: none;
  }
  select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
    background: #e8ebed;
    box-shadow: none;
  }

  select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection_clear {
    display: none;
  }
  .is-invalid:valid + .select2 .select2-selection{
    border-color: #dc3545!important;
  }
  *:focus{
    outline:0px;
  }
</style>
@yield('css')
<body>
  <div
  class="bs-toast toast toast-placement-ex m-2"
  role="alert"
  aria-live="assertive"
  aria-atomic="true"
  data-bs-delay="2000">
  <div class="toast-header">
    <i class="bx bx-bell me-2"></i>
    <div class="me-auto fw-medium" id="titleText"></div>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  <div class="toast-body" id="messageText"></div>
</div>
<select class="form-select placement-dropdown" hidden="" id="selectPlacement">
  <option value="top-0 end-0">Top right</option>
</select>
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
      @include('page/layout/sidebar')
    </aside>
    <div class="layout-page">
      <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
        @include('page.layout.header')
      </nav>
      <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
          <div class="row">
            <div class="col-lg-12 mb-4 order-0">
              @yield('content')
              <!-- myprofil -->
              <!-- myprofil -->
              <div class="modal fade text-left" data-bs-backdrop="static" id="modal_my_profil" tabindex="-1" role="dialog"
              aria-labelledby="myModalLabel1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-info" style="color: white;">
                    <h5 class="modal-title text-white" style="margin-bottom:12px;" id="myModalLabel1"></h5>
                    <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body">
                   <form method="post" id="myProfilForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label class="col-form-label">Nama <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" value="{{Auth::user()->name}}" id="name" name="name">
                          <span class="invalid-feedback" role="alert" id="nameError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label class="col-form-label">Email/Username <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" value="{{Auth::user()->email}}" id="email" name="email">
                          <span class="invalid-feedback" role="alert" id="emailError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="mb-3 form-password-toggle">
                         <div class="d-flex justify-content-between">
                          <label class="col-form-label" for="password">Password</label>
                        </div>
                        <div class="input-group input-group-merge">
                          <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                          <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-loading" id="modal-loading" style="display: none;">
                  <span class="fa fa-spinner fa-pulse fa-3x"></span>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn" data-bs-dismiss="modal">
                    <span>Tutup</span>
                  </button>
                  <button class="btn btn-primary ml-1 submit">
                    <i class="bx bx-save"></i> <span>Simpan Profil</span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- end -->
        <!-- end -->
      </div>
    </div>
  </div>
  <footer class="content-footer footer bg-footer-theme">
    @include('page.layout.footer')
  </footer>
  <div class="content-backdrop fade"></div>
</div>
</div>
</div>
<div class="layout-overlay layout-menu-toggle"></div>
</div>
<script src="{{asset('panel/assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('panel/assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('panel/assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('panel/assets/vendor/js/menu.js')}}"></script>
<!-- Vendors JS -->
<script src="{{asset('panel/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('panel/assets/js/ui-popover.js')}}"></script>
<!-- Main JS -->
<script src="{{asset('panel/assets/js/extended-ui-perfect-scrollbar.js')}}"></script>
<script src="{{asset('panel/assets/js/main.js')}}"></script>
<script src="{{asset('panel/assets/js/custom.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('panel/assets/js/pages-account-settings-account.js')}}"></script>
<!-- Page JS -->
<script src="{{asset('panel/assets/js/dashboards-analytics.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js')}}"></script>
<!-- datatable -->
<script src="{{asset('panel/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('panel/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('panel/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
</body>
<script type="text/javascript">
  $(".btn_my_profil").click(function() {
    $(".modal-title").html('<i class="bx bx-user"></i> Ubah Profil');
    $("#modal_my_profil").modal('show');
  });
  $(function () {
    $('#myProfilForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      show_loading();
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $(".invalid-feedback").children("strong").text("");
      $("#myProfilForm input").removeClass("is-invalid");
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        contentType: false,
        processData: false,
        url : "{{route('update_profil')}}",
        data: formData,
        success: function (response) {
          hide_loading();
          $('#myProfilForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            showToast('bg-primary','Profil Success',response.message);
            document.location.href='';
          } else {
            showToast('bg-danger','Profil Error',response.message);
          }
        },
        error: function (response) {
          hide_loading();
          $('#myProfilForm').data('submitted', false);
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              $("#" + key).addClass("is-invalid");
              $("#" + key + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            showToast('bg-danger','Profil Error',response.message);
          }
        }
      });
    });
  });
</script>
@yield('scripts')
</html>