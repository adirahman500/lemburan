  @extends('page/layout/app')

  @section('title','Data Jabatan')

  @section('content')
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Master Data</a></li>
              <li class="breadcrumb-item active" aria-current="page">Jabatan</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          Data Jabatan
          <button type="button" style="float: right;" class="btn btn-sm btn-outline-primary block new" >
            <i class="bx bx-plus"></i> Tambah Jabatan
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="table_jabatan" style="width: 100%;">
              <thead>
                <tr>
                  <th>No. </th>
                  <th>Nama Jabatan</th>
                  <th>Jabatan berdasarkan BPK</th>
                  <th>Action</th>
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
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_jabatan" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
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
       <form method="post" id="jabatanForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="col-form-label">Nama Jabatan <span class="text-danger">*</span></label>
              <input type="" hidden="" id="id_jabatan" name="id_jabatan">
              <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan">
              <span class="invalid-feedback" role="alert" id="nama_jabatanError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label class="col-form-label">Jabatan berdasarkan BPK <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="bpk_jabatan" name="bpk_jabatan">
              <span class="invalid-feedback" role="alert" id="bpk_jabatanError">
                <strong></strong>
              </span>
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
          <i class="bx bx-save"></i> <span>Simpan</span>
        </button>
      </div>
    </form>
  </div>
</div>
</div>
@endsection
@section('css')
<style type="text/css">

</style>
@endsection
@section('scripts')
<script type="text/javascript">
  $(function () {
    $('#table_jabatan').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      responsive: true,
      ajax: {
        url: "{{ route('index.jabatan') }}",
        error: function (jqXHR, textStatus, errorThrown) {
          $('#table_jabatan').DataTable().ajax.reload();
        }
      },
      columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex'},
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
      { data: 'action', name: 'action', orderable: false, className: 'space' }
      ]
    });
  });
  var ajaxUrl = "";
  $(document).ready(function() {
    $(".new").click(function() {
      $("#jabatanForm")[0].reset();
      $(".invalid-feedback").children("strong").text("");
      $("#jabatanForm input").removeClass("is-invalid");
      $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Jabatan');
      $("#modal_form_jabatan").modal('show');
      ajaxUrl = "{{route('save.jabatan')}}";
    });
  });
  $(function () {
    $('#jabatanForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      show_loading();
      $(this).data('submitted', true);
      let formData = $(this).serializeArray();
      $(".invalid-feedback").children("strong").text("");
      $("#jabatanForm input").removeClass("is-invalid");
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        url : ajaxUrl,
        data: formData,
        success: function (response) {
          hide_loading();
          $('#jabatanForm').data('submitted', false);
          if (response.status == 'true') {
            $("#jabatanForm")[0].reset();
            $('#modal_form_jabatan').modal('hide');
            showToast('bg-primary','Jabatan Success',response.message);
            $('#table_jabatan').DataTable().ajax.reload();
          } else {
            showToast('bg-danger','Jabatan Error',response.message);
          }
        },
        error: function (response) {
          hide_loading();
          $('#jabatanForm').data('submitted', false);
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              $("#" + key).addClass("is-invalid");
              $("#" + key + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            showToast('bg-danger','Jabatan Error',response.message);
          }
        }
      });
    });
  });
  function get_edit(jabatanID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/master/jabatan/get_edit')}}"+"/"+jabatanID,
      success: function(response) {
        hide_loading();
        $.each(response, function(key, value) {
          $("#id_jabatan").val(value.id_jabatan);
          $("#nama_jabatan").val(value.nama_jabatan);
          $("#bpk_jabatan").val(value.bpk_jabatan);
        });
      },
      error: function(response) {
        get_edit(jabatanID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    var jabatanID = $(this).attr('more_id');
    show_loading();
    $("#jabatanForm")[0].reset();
    $(".invalid-feedback").children("strong").text("");
    $("#jabatanForm input").removeClass("is-invalid");
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Jabatan');
    $("#modal_form_jabatan").modal('show');
    ajaxUrl = "{{route('save.jabatan')}}";
    if (jabatanID) {
      get_edit(jabatanID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    jabatanID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Jabatan akan dihapus secara Permanent!',
      icon: 'warning',
      type: 'warning',
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          method: "GET",
          url: "{{url('page/master/jabatan/destroy')}}"+"/"+jabatanID,
          success:function(response)
          {
            if (response.status == 'true') {
              showToast('bg-success','Jabatan Dihapus',response.message);
              $('#table_jabatan').DataTable().ajax.reload();
            }else{
              showToast('bg-danger','Jabatan Error',response.message);
            }
          },
          error: function(response) {
            showToast('bg-danger','Jabatan Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection