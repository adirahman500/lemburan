  @extends('page/layout/app')

  @section('title','Data Karyawan')

  @section('content')
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Karyawan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data Karyawan</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          Data Karyawan
          <button type="button" style="float: right;" class="btn btn-sm btn-outline-primary block new" >
            <i class="bx bx-plus"></i> Tambah Karyawan
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped dt-responseive nowrap" id="table_karyawan" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="2">No. </th>
                  <th data-priority="3">Nama</th>
                  <th data-priority="4">Jabatan</th>
                  <th data-priority="13">Jabatan berdasarkan BPK</th>
                  <th data-priority="6">Unit</th>
                  <th data-priority="8">Kab Lokasi Kerja</th>
                  <th data-priority="7">Tanggal Masuk</th>
                  <th data-priority="9">UMK</th>
                  <th data-priority="10">Masa Kerja (Tahun)</th>
                  <th data-priority="11">TMK</th>
                  <th data-priority="6">Email</th>
                  <th data-priority="1">Action</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_Karyawan" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel1"></h5>
        <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
       <form method="post" id="KaryawanForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Nama <span class="text-danger">*</span></label>
              <input type="" hidden="" id="id_karyawan" name="id_karyawan">
              <input type="text" class="form-control input_view" id="name" name="name">
              <span class="invalid-feedback" role="alert" id="nameError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Jabatan <span class="text-danger">*</span></label>
              <select class="form-control" style="width: 100%;" name="id_jabatan" id="id_jabatan">
                @foreach($jabatan as $jbt)
                <option value="{{$jbt->id_jabatan}}">{{$jbt->nama_jabatan}} (BPK: {{$jbt->bpk_jabatan}})</option>
                @endforeach
              </select>
              <span class="invalid-feedback" role="alert" id="id_jabatanError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Email/Username <span class="text-danger">*</span></label>
              <input type="text" class="form-control input_view" id="email" name="email">
              <span class="invalid-feedback" role="alert" id="emailError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Password <span class="text-danger" id="mandatory">444</span></label>
              <input type="text" class="form-control" id="password" name="password">
              <span class="invalid-feedback" role="alert" id="passwordError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Unit <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="unit" name="unit">
              <span class="invalid-feedback" role="alert" id="unitError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Kab Lokasi Kerja <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="kab_lokasi_kerja" name="kab_lokasi_kerja">
              <span class="invalid-feedback" role="alert" id="kab_lokasi_kerjaError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Tanggal Masuk <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk">
              <span class="invalid-feedback" role="alert" id="tanggal_masukError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">UMK <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="umk" name="umk">
              <span class="invalid-feedback" role="alert" id="umkError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Masa Kerja (Tahun) <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="masa_kerja" name="masa_kerja">
              <span class="invalid-feedback" role="alert" id="masa_kerjaError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">TMK <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="tmk" name="tmk">
              <span class="invalid-feedback" role="alert" id="tmkError">
                <strong></strong>
              </span>
            </div>
          </div>
        <!--   <div class="col-lg-6">
            <div class="form-group">
              <label class="col-form-label">Status <span class="text-danger">*</span></label>
              <select class="form-control" style="width: 100%;" name="status" id="status">
                <option value="A">Aktif</option>
                <option value="I">Non Aktif</option>
              </select>
              <span class="invalid-feedback" role="alert" id="statusError">
                <strong></strong>
              </span>
            </div>
          </div> -->
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
          <i class="bx bx-save"></i> <span>Simpan</span>
        </button>
      </div>
    </form>
    <!--  -->
    <!--  -->
  </div>
</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  function tanggal_indonesia(dateString) {
    const hariDalamSeminggu = [
    'Minggu',
    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jumat',
    'Sabtu'
    ];
    const bulan = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
    ];
    const tanggal = dateString.split('-');
    const hari = tanggal[2];
    const bulanIndex = parseInt(tanggal[1]) - 1;
    const tahun = tanggal[0];
    const dateObj = new Date(tahun, bulanIndex, hari);
    const namaHari = hariDalamSeminggu[dateObj.getDay()];
    return `${hari} ${bulan[bulanIndex]} ${tahun}`;
  }
  function formatRupiah(angka) {
    let number_string = angka.replace(/[^,\d]/g, "").toString(),
    split = number_string.split(","),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? "." : "";
      rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
    return rupiah ? "Rp " + rupiah : "";
  }
  $(document).on('keyup', '#umk', function() {
    let umk = $(this).val();
    $(this).val(formatRupiah(umk));
  });
  $(document).on('keyup', '#tmk', function() {
    let tmk = $(this).val();
    $(this).val(formatRupiah(tmk));
  });
  $(function () {
    $('#table_karyawan').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      ajax: {
        url: "{{ route('index.karyawan') }}",
        error: function (jqXHR, textStatus, errorThrown) {
          $('#table_karyawan').DataTable().ajax.reload();
        }
      },
      columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex'},
      { 
        data: 'name', 
        name: 'name', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'nama_jabatan', 
        name: 'nama_jabatan', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'bpk_jabatan', 
        name: 'bpk_jabatan', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'unit', 
        name: 'unit', 
        render: function (data, type, row) {
          return data;
        }
      },
      { 
        data: 'kab_lokasi_kerja', 
        name: 'kab_lokasi_kerja', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'tanggal_masuk', 
        name: 'tanggal_masuk', 
        render: function (data, type, row) {
          return tanggal_indonesia(data);
        }  
      },
      { 
        data: 'umk', 
        name: 'umk', 
        render: function (data, type, row) {
          return formatRupiah(data);
        }  
      },
      { 
        data: 'masa_kerja', 
        name: 'masa_kerja', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'tmk', 
        name: 'tmk', 
        render: function (data, type, row) {
          return formatRupiah(data);
        }  
      },
      { 
        data: 'email', 
        name: 'email', 
        render: function (data, type, row) {
          return data;
        }  
      },
      // { 
      //   data: 'status', 
      //   name: 'status', 
      //   render: function (data, type, row) {
      //     if (data == 'A') {
      //       return '<span class="badge bg-info text-white">Aktif</span>';
      //     }else{
      //       return '<span class="badge bg-danger text-white">Non Aktif</span>';
      //     }
      //   }  
      // },
      { data: 'action', name: 'action', orderable: false, className: 'space' }
      ]
    });
  });
  $("#id_jabatan").select2({
    placeholder: ".: PILIH JABATAN :.",
    dropdownParent: $("#modal_form_Karyawan")
  });
  $("#status").select2({
    placeholder: ".: PILIH STATUS :.",
    dropdownParent: $("#modal_form_Karyawan")
  });
  var ajaxUrl;
  $(document).ready(function() {
    $(".new").click(function() {
      $("#modal_form_Karyawan").modal('show');
      $("#KaryawanForm")[0].reset();
      $("#id_jabatan").val(null).trigger('change');
      $("#status").val(null).trigger('change');
      $("#mandatory").html('*');
      ajaxUrl = " {{route('save.karyawan')}} ";
      $(".invalid-feedback").children("strong").text("");
      $("#KaryawanForm input").removeClass("is-invalid");
      $("#KaryawanForm select").removeClass("is-invalid");
      $("#KaryawanForm textarea").removeClass("is-invalid");
      $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Karyawan');
    });
  });
  $(function () {
    $('#KaryawanForm').submit(function (e) {
      e.preventDefault();
      show_loading();
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $(".invalid-feedback").children("strong").text("");
      $("#KaryawanForm input").removeClass("is-invalid");
      $("#KaryawanForm select").removeClass("is-invalid");
      $("#KaryawanForm textarea").removeClass("is-invalid");
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        contentType: false,
        processData: false,
        url : ajaxUrl,
        data: formData,
        success: function (response) {
          hide_loading();
          if (response.status == 'true') {
            $("#KaryawanForm")[0].reset();
            $('#modal_form_Karyawan').modal('hide');
            showToast('bg-primary','Data Karyawan Success',response.message);
            $('#table_karyawan').DataTable().ajax.reload();
          } else {
            showToast('bg-danger','Data Karyawan Error',response.message);
          }
        },
        error: function (response) {
          hide_loading();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              var key_temp = key.replaceAll(".", "_");
              $("#" + key_temp).addClass("is-invalid");
              $("#" + key_temp + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            showToast('bg-danger','Karyawan Error',response.message);
          }
        }
      });
    });
  });
  function get_edit(KaryawanID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/master/karyawan/get_edit')}}"+"/"+KaryawanID,
      success: function(response) {
        hide_loading();
        $.each(response, function(key, value) {
          $("#id_karyawan").val(value.id_user);
          $("#name").val(value.name);
          $("#email").val(value.email);
          $("#unit").val(value.unit);
          $("#masa_kerja").val(value.masa_kerja);
          $("#tanggal_masuk").val(value.tanggal_masuk);
          $("#umk").val(formatRupiah(value.umk));
          $("#tmk").val(formatRupiah(value.tmk));
          $("#kab_lokasi_kerja").val(value.kab_lokasi_kerja);
          $("#id_jabatan").val(value.id_jabatan).trigger('change');
        });
      },
      error: function(response) {
        get_edit(KaryawanID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    show_loading();
    var KaryawanID = $(this).attr('more_id');
    $("#KaryawanForm")[0].reset();
    ajaxUrl = " {{route('update.karyawan')}} ";
    $("#mandatory").html('');
    $(".invalid-feedback").children("strong").text("");
    $("#KaryawanForm input").removeClass("is-invalid");
    $("#KaryawanForm textarea").removeClass("is-invalid");
    $("#KaryawanForm select").removeClass("is-invalid");
    $("#id_jabatan").val(null).trigger('change');
    $("#status").val(null).trigger('change');
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Karyawan');
    $("#modal_form_Karyawan").modal('show');
    if (KaryawanID) {
      get_edit(KaryawanID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    KaryawanID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data Karyawan akan dihapus secara Permanent!',
      icon: 'warning',
      type: 'warning',
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $("#loading").show();
        $.ajax({
          method: "GET",
          url: "{{url('page/master/karyawan/destroy')}}"+"/"+KaryawanID,
          success:function(response)
          {
            if (response.status == 'true') {
              setTimeout(function(){
                showToast('bg-primary','Data Karyawan Dihapus',response.message);
                $('#table_karyawan').DataTable().ajax.reload();         
              }, 50);
            }else{
              showToast('bg-danger','Data Karyawan Error',response.message);
            }
          },
          error: function(response) {
            showToast('bg-danger','Data Karyawan Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection