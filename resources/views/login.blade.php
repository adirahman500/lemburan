<!DOCTYPE html>
<html
lang="en"
class="light-style layout-wide customizer-hide"
dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>LOGIN - PT PLN (PERSERO) UID KALSELTENG</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{asset('logo.png')}}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
  rel="stylesheet" />

  <link rel="stylesheet" href="{{asset('panel/assets/vendor/fonts/boxicons.css')}}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/css/demo.css')}}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.css">
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/pages/page-auth.css')}}" />

  <!-- Helpers -->
  <script src="{{asset('panel/assets/vendor/js/helpers.js')}}"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js')}} in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('panel/assets/js/config.js')}}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
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
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="javascript:void(0)" class="app-brand-link gap-2">
                <span class="app-brand-text demo text-body fw-bold" style="text-transform: uppercase;">
                  <img src="{{asset('logo.png')}}" width="90">
                </span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2 text-center">PT PLN (PERSERO) UID KALSELTENG</h4>
            <p class="mb-4 text-center">Silakan masuk dan selamat beraktivitas !👋</p>

            <form id="loginForm" class="mb-3">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email/Username</label>
                <input type="text" class="form-control" id="email" required name="email" placeholder="Masukkan email/username" autofocus />
                <small class="text-danger" id="validasi_email"></small>
              </div>
              <div class="mb-3 form-password-toggle">
               <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" required name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary w-100 submit_login" type="submit">LOGIN</button>
            </div>
          </form>

        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>

<!-- / Content -->


<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js')}} -->

<script src="{{asset('panel/assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('panel/assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('panel/assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('panel/assets/vendor/js/menu.js')}}"></script>

<!-- endbuild -->

<!-- Vendors JS -->

<!-- Main JS -->
<script src="{{asset('panel/assets/js/main.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Page JS -->

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
<script type="text/javascript">
  function showToast(selectedType, titleText, messageText) {
    const toastPlacementExample = document.querySelector('.toast-placement-ex');
    $("#titleText").html(titleText);
    $("#messageText").html(messageText);
  // toastPlacementBtn = document.querySelector('#showToastPlacement');
  let selectedPlacement, toastPlacement;
  // Dispose toast when open another
  selectedPlacement = document.querySelector('#selectPlacement').value.split(' ');

  toastPlacementExample.classList.add(selectedType);
  DOMTokenList.prototype.add.apply(toastPlacementExample.classList, selectedPlacement);
  toastPlacement = new bootstrap.Toast(toastPlacementExample);
  toastPlacement.show();
}
$(function () {
  $('#loginForm').submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    $(".submit_login").html('LOGIN <div class="spinner-border spinner-border-sm" role="status"></div>');
    $(".submit_login").attr('disabled',true);
    $.ajax({
     method: "POST",
     headers: {
      Accept: "application/json"
    },
    contentType: false,
    processData: false,
    url: " {{route('ceklogin')}} ",
    data: formData,
    success: function(response) {
      $(".submit_login").attr('disabled',false);
      $(".submit_login").html('LOGIN');
      if(response.status == 'true') {
        showToast('bg-primary', response.title, response.message);
        document.location.href = response.url;
      }
      if (response.status == 'false') {
        showToast('bg-danger', response.title, response.message);
      }
    },
    error: function(response) {
      $(".submit_login").attr('disabled',false);
      $(".submit_login").html('LOGIN');
      Swal.fire({
        icon: "error",
        type: "error",
        title: 'Error',
        text: 'Terjadi Kesalahan [Permintaan data tidak dikirim]'
      });
    }
  });     
  }); 
}); 
// var email = document.getElementById('email');
// $(document).on('keyup','#email',function() {
//   var mail=$('#email').val();
//   var atps=mail.indexOf("@");
//   var dots=mail.lastIndexOf(".");
//   if (mail !== '') {
//     if (atps<1 || dots<atps+2 || dots+2>=mail.length || !document.getElementById("email").checkValidity())
//     {
//       $(".submit_login").attr('disabled',true);
//       document.getElementById('validasi_email').innerHTML = "Alamat email tidak valid";
//     }
//     else
//     {
//       $(".submit_login").attr('disabled',false);
//       document.getElementById('validasi_email').innerHTML = "";
//     }
//   }else{
//     $(".submit_login").attr('disabled',false);
//     document.getElementById('validasi_email').innerHTML = "";
//   }
// });
</script>
</html>
