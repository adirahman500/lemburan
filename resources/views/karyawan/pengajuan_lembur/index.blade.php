  @extends('page/layout/app')

  @section('title','Data Pengajuan')

  @section('content')
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Pengajuan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Pengajuan Lembur</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          Pengajuan Lembur Saya
          @if($cek == 0)
          <button type="button" style="float: right;" class="btn btn-sm btn-outline-primary block new" >
            <i class="bx bx-plus"></i> Buat Pengajuan Lembur
          </button>
          @endif
        </div>
        <?php
        function tanggal_indonesia($tgl, $tampil_hari=true){
          $nama_hari=array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu");
          $nama_bulan = array (
            1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
            "September", "Oktober", "November", "Desember");
          $tahun=substr($tgl,0,4);
          $bulan=$nama_bulan[(int)substr($tgl,5,2)];
          $tanggal=substr($tgl,8,2);
          $text="";
          if ($tampil_hari) {
            $urutan_hari=date('w', mktime(0,0,0, substr($tgl,5,2), $tanggal, $tahun));
            $hari=$nama_hari[$urutan_hari];
            $text .= $hari.", ";
          }
          $text .=$tanggal ." ". $bulan ." ". $tahun;
          return $text;
        }
        ?>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped dt-responseive nowrap table_pengajuan" id="table_pengajuan" style="width: 100%;">
              <thead>
                <tr>
                  <th rowspan="2" data-priority="1">No. </th>
                  <th rowspan="2">Tanggal / Hari</th>
                  <th rowspan="2">Hari Kerja / Libur</th>
                  <th colspan="2">Waktu Lembur</th>
                  <th rowspan="2">Total Jam Lembur Realisasi</th>
                  <th rowspan="2">Total Lembur yang ditagihkan</th>
                  <th rowspan="2">Keterangan Lembur</th>
                  <th rowspan="2">Status</th>
                  <th rowspan="2" data-priority="2">Action</th>
                </tr>
                <tr>
                  <th>Masuk</th>
                  <th>Keluar</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach($data as $dt)
                <?php  
                $tanggal_lembur = Carbon\Carbon::parse($dt->tanggal_lembur);
                $hari_ini = $dt->tanggal_lembur;
                $tahun_ini = $tanggal_lembur->format('Y');
                $hari = $tanggal_lembur->format('l');
                $hari_libur = App\Models\Page\PengajuanLembur::getHolidays($tahun_ini);
                if ($hari == 'Sunday' || $hari == 'Saturday') {
            // if ($hari == 'Sunday' || in_array($hari_ini, $hari_libur)) {
                  $keterangan_hari = 'Hari Libur';
                }else{
                  $keterangan_hari = 'Hari Kerja';
                }
                $waktu_mulai = Carbon\Carbon::parse($dt->waktu_mulai);
                $waktu_selesai = Carbon\Carbon::parse($dt->waktu_selesai);
                $selisihJam = $waktu_mulai->diffInHours($waktu_selesai);
                $selisihMenit = $waktu_mulai->diffInMinutes($waktu_selesai) % 60;
                $upah_perjam = $dt->umk / 173;
                // $lembur_ditagihkan = $selisihJam;
                $totalJam = $selisihJam . '.' . sprintf('%02d', $selisihMenit);
                // dd($totalJam);
                // kerja
                if ($keterangan_hari == 'Hari Kerja') {
                  if ($totalJam >= 4) {
                    $lembur_ditagihkan = 4;
                  }else{
                    $lembur_ditagihkan = $selisihJam . '.' . sprintf('%02d', $selisihMenit);
                  }
                }else{
                  $lembur_ditagihkan = $selisihJam . '.' . sprintf('%02d', $selisihMenit);
                }
                $total_upah_harikerja = 0;
                $result_hari_kerja_15 = 0;
                $result_hari_kerja_20 = 0;
                if ($keterangan_hari == 'Hari Kerja') {
                  if ($totalJam >= 1) {
                    $result_hari_kerja_15 = 1.5;
                  } else {
                    $result_hari_kerja_15 = ($selisihMenit / 60) * 1.5;
                  }
                  if ($totalJam >= 4) {
                    $result_hari_kerja_20 = 6;
                  } else {
                    $adjustedJam = max(0, $selisihJam - 1);
                    $adjustedMenit = $selisihMenit;
                    $result_hari_kerja_20 = ($adjustedJam * 2.0) + (($adjustedMenit / 60) * 2.0);
                  }
                  $upah_lembur_kerja = ceil(($result_hari_kerja_15 * $upah_perjam)+($result_hari_kerja_20 * $upah_perjam));
                  $total_upah_harikerja = number_format($upah_lembur_kerja,0,",",".");
                }
                // libur
                $total_upah_harilibur = 0;
                $result_hari_libur_2 = 0;
                $result_hari_libur_3 = 0;
                $result_hari_libur_4 = 0;
                if ($keterangan_hari == 'Hari Libur') {
                  $hour = date("H", strtotime($lembur_ditagihkan));
                  $minute = date("i", strtotime($lembur_ditagihkan));
                  if ($hour >= 8) {
                    $result_hari_libur_2 = 8 * 2;
                  } else {
                    $result_hari_libur_2 = ($hour * 2) + (($minute / 60) * 2);
                  }
                  $hour = date("H", strtotime($lembur_ditagihkan));
                  $minute = date("i", strtotime($lembur_ditagihkan));
                  $second = date("s", strtotime($lembur_ditagihkan));
                  if ($hour == 8) {
                    $time_diff = strtotime("$hour:$minute:$second") - strtotime("08:00:00");
                    $result_hari_libur_3 = (date("H", $time_diff) * 3) + (date("i", $time_diff) / 60) * 3;
                  } else {
                    $result_hari_libur_3 = 0.0;
                  }
                  $hour = date("H", strtotime($lembur_ditagihkan));
                  $minute = date("i", strtotime($lembur_ditagihkan));
                  $second = date("s", strtotime($lembur_ditagihkan));
                  if ($hour >= 12) {
                    $result_hari_libur_4 = 16;
                  } elseif ($hour < 12 && $hour >= 9) {
                    $time_diff = strtotime("$hour:$minute:$second") - strtotime("09:00:00");
                    $result_hari_libur_4 = (date("H", $time_diff) * 4) + (date("i", $time_diff) / 60) * 4;
                  } else {
                    $result_hari_libur_4 = 0;
                  }
                  $total_upah_harilibur = number_format(($result_hari_libur_2 * $upah_perjam)+($result_hari_libur_3 * $upah_perjam)+($result_hari_libur_4 * $upah_perjam),0,",",".");
                }
                ?>
                <tr>
                  <td>{{$loop->index+1}}</td>
                  <td>{{tanggal_indonesia($dt->tanggal_lembur)}}</td>
                  <td>{{$keterangan_hari}}</td>
                  <td>{{$dt->waktu_mulai}}</td>
                  <td>{{$dt->waktu_selesai}}</td>
                  <td>{{$lembur_ditagihkan}}</td>
                  <td>{{$lembur_ditagihkan}}</td>
                  <td>{{$dt->keterangan_lembur}}</td>
                  <td>
                    @if($dt->status_lembur == 'terima')
                    <span class="badge bg-success text-white">Pengajuan Diterima</span>
                    @elseif($dt->status_lembur == 'tolak')
                    <span class="badge bg-danger text-white">Pengajuan Ditolak</span>
                    @else
                    <span class="badge bg-warning text-white">Proses</span>
                    @endif
                  </td>
                  <td>
                    @if($dt->status_lembur == 'pending')
                    <a href="javascript:void(0)" more_id="{{$dt->id_pengajuan}}" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a>
                    @elseif($dt->status_lembur == 'terima')
                    <a href="javascript:void(0)" data-bs-target="#modal_form_detail{{$dt->id_pengajuan}}" data-bs-toggle="modal" class="btn btn-info text-white rounded-pill btn-sm view"><i class="bx bx-briefcase"></i></a>
                    @else
                    -
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
  @foreach($data as $dt)
  @include('karyawan.pengajuan_lembur.detail')
  @endforeach
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_lembur" tabindex="-1" role="dialog"
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
       <form method="post" id="lemburForm" enctype="multipart/form-data">
        @csrf
        <input type="" hidden="" id="id_pengajuan" name="id_pengajuan">
        <div class="row mt-2">
          <label class="col-sm-3">Waktu Masuk/Mulai <span class="text-danger">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="waktu_mulai" name="waktu_mulai">
            <span class="invalid-feedback d-block" role="alert" id="waktu_mulaiError">
              <strong></strong>
            </span>
          </div>
        </div>
        <div class="row mt-2">
          <label class="col-sm-3">Waktu Keluar/Selesai <span class="text-danger">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="waktu_selesai" name="waktu_selesai">
            <span class="invalid-feedback d-block" role="alert" id="waktu_selesaiError">
              <strong></strong>
            </span>
          </div>
        </div>
        <div class="row mt-2">
          <label class="col-sm-3">Keterangan Lembur <span class="text-danger">*</span></label>
          <div class="col-sm-8">
            <textarea class="form-control" rows="4" id="keterangan_lembur" name="keterangan_lembur"></textarea>
            <span class="invalid-feedback d-block" role="alert" id="keterangan_lemburError">
              <strong></strong>
            </span>
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
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/clockpicker/dist/bootstrap-clockpicker.min.css">
<style type="text/css">
  .table_detail tr th {
    text-align: center;
  }
</style>
@endsection
@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/clockpicker/dist/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
  $('#waktu_mulai').clockpicker({
    autoclose: true,
    placement: 'bottom',
    align: 'left',
    default: 'now',
    donetext: 'Done'
  });
  $('#waktu_selesai').clockpicker({
    autoclose: true,
    placement: 'bottom',
    align: 'left',
    default: 'now',
    donetext: 'Done'
  });
  $(function () {
    $('#table_pengajuan').DataTable({
      processing: true,
      pageLength: 100,
      responsive: true
    });
    $('.table_detail').DataTable({
      processing: true,
      pageLength: 100
    });
  });
  var ajaxUrl;
  $(document).ready(function() {
    $(".new").click(function() {
      $("#modal_form_lembur").modal('show');
      $("#lemburForm")[0].reset();
      $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Pengajuan Lemburan');
      $(".invalid-feedback").children("strong").text("");
      $("#lemburForm input").removeClass("is-invalid");
      $("#lemburForm select").removeClass("is-invalid");
      $("#lemburForm textarea").removeClass("is-invalid");
      ajaxUrl = " {{route('save.pengajuan_lembur')}} ";
    });
  });

  $(function () {
    $('#lemburForm').submit(function (e) {
      e.preventDefault();
      show_loading();
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $(".invalid-feedback").children("strong").text("");
      $("#lemburForm input").removeClass("is-invalid");
      $("#lemburForm select").removeClass("is-invalid");
      $("#lemburForm textarea").removeClass("is-invalid");
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
            $("#lemburForm")[0].reset();
            $('#modal_form_lembur').modal('hide');
            showToast('bg-primary','Pengajuan Lemburan Success',response.message);
            setTimeout(function() {
              document.location.href = '';
            }, 600);
          } else {
            showToast('bg-danger','Pengajuan Lemburan Error',response.message);
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
            showToast('bg-danger','Pengajuan Lemburan Error',response.message);
          }
        }
      });
    });
  });
  function get_edit(pengajuanID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/pengajuan_lembur/get_edit')}}"+"/"+pengajuanID,
      success: function(response) {
        hide_loading();
        $.each(response, function(key, value) {
          $("#id_pengajuan").val(value.id_pengajuan);
          $("#waktu_mulai").val(value.waktu_mulai);
          $("#waktu_selesai").val(value.waktu_selesai);
          $("#keterangan_lembur").val(value.keterangan_lembur);
        });
      },
      error: function(response) {
        get_edit(pengajuanID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    show_loading();
    var pengajuanID = $(this).attr('more_id');
    $("#lemburForm")[0].reset();
    ajaxUrl = " {{route('update.pengajuan_lembur')}} ";
    $(".invalid-feedback").children("strong").text("");
    $("#lemburForm input").removeClass("is-invalid");
    $("#lemburForm textarea").removeClass("is-invalid");
    $("#lemburForm select").removeClass("is-invalid");
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Pengajuan Lemburan');
    $("#modal_form_lembur").modal('show');
    if (pengajuanID) {
      get_edit(pengajuanID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    pengajuanID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Pengajua Lemburan akan dihapus secara Permanent!',
      icon: 'warning',
      type: 'warning',
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          method: "GET",
          url: "{{url('page/pengajuan_lembur/destroy')}}"+"/"+pengajuanID,
          success:function(response)
          {
            if (response.status == 'true') {
              showToast('bg-primary','Pengajua Lemburan Dihapus',response.message);
              setTimeout(function() {
                document.location.href = '';
              }, 600);
            }else{
              showToast('bg-danger','Pengajua Lemburan Error',response.message);
            }
          },
          error: function(response) {
            showToast('bg-danger','Pengajua Lemburan Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection