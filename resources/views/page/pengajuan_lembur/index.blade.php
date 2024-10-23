  @extends('page/layout/app')

  @section('title','Data Pengajuan Lembur')

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
          Data Pengajuan Lembur Karyawan  {{ 
          match(request()->get('status_pengajuan')) {
          'pending' => '(Status Pengajuan: Pending)',
          'terima' => '(Status Pengajuan: Diterima)',
          'tolak' => '(Status Pengajuan: Tolak)',
          default => '(Status Pengajuan: Tidak Diketahui)',
        }
      }}
      <button type="button" style="float: right;" class="btn btn-sm btn-outline-primary block new" >
        <i class="bx bx-plus"></i> Tambah Data Lemburan
      </button>
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
              <th data-priority="1">No. </th>
              <th>Nama</th>
              <th>Tanggal / Hari</th>
              <th>Hari Kerja / Libur</th>
              <!-- <th colspan="2">Waktu Lembur</th> -->
              <th>Waktu Masuk</th>
              <th>Waktu Keluar</th>
              <th>Total Jam Lembur Realisasi</th>
              <th>Total Lembur yang ditagihkan</th>
              <th>Status Pengajuan</th>
              <th data-priority="2">Action</th>
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
                  // if ($totalJam >= 8) {
                  //   $result_hari_libur_2 = 8 * 2;
                  // } else {
                  //   $result_hari_libur_2 = ($selisihJam * 2) + (($selisihMenit / 60) * 2);
                  // }
                  // if ($totalJam >= 9) {
                  //   $adjustedJam = max(0, $selisihJam - 8);
                  //   $adjustedMenit = $selisihMenit;
                  //   $result_hari_libur_3 = ($adjustedJam * 3) + (($adjustedMenit / 60) * 3);
                  // }elseif ($selisihJam < 9){
                  //   $result_hari_libur_3 = 0.0;
                  // }else{
                  //   $result_hari_libur_3 = 1 * 3;
                  // }
                  // if ($totalJam >= 12) {
                  //   $result_hari_libur_4 = 16;
                  // } elseif ($totalJam < 12 && $totalJam >= 9) {
                  //   $adjustedJam = max(0, $selisihJam - 9);
                  //   $adjustedMenit = $selisihMenit;
                  //   $result_hari_libur_4 = ($adjustedJam * 4) + (($adjustedMenit / 60) * 4);
                  // }
              $total_upah_harilibur = number_format(($result_hari_libur_2 * $upah_perjam)+($result_hari_libur_3 * $upah_perjam)+($result_hari_libur_4 * $upah_perjam),0,",",".");
            }
                // $totalJam = $selisihJam;
                // if ($selisihMenit > 0) {
                //   $totalJam++;
                // }
                // $total_upah_harikerja_1 = 0;
                // $total_upah_harikerja_2 = 0;
                // if ($totalJam >= 1 && $keterangan_hari == 'Hari Kerja') {
                //   $hasil1 = 1.50;
                //   $total_upah_harikerja_1 = $upah_perjam * $hasil1;
                // }else{
                //   $hasil1 = 0;
                //   $total_upah_harikerja_1 = $upah_perjam * $hasil1;
                // }
                // if ($totalJam >= 4 && $keterangan_hari == 'Hari Kerja') {
                //   $hasil2 = 6;
                //   $total_upah_harikerja_2 = $upah_perjam * $hasil2;
                // }elseif ($totalJam < 4 && $keterangan_hari == 'Hari Kerja'){
                //   $hasil2 = ($totalJam - 1) * 2.00;
                //   $total_upah_harikerja_2 = $upah_perjam * $hasil2;
                // }else{
                //   $hasil2 = 0;
                //   $total_upah_harikerja_2 = $upah_perjam * $hasil2;
                // }
                // $total_upah_harikerja = $total_upah_harikerja_1 + $total_upah_harikerja_2;
                // if ($total_upah_harikerja == 0) {
                //   $total_upah_harikerja = '-';
                // }else{
                //   $total_upah_harikerja = number_format($total_upah_harikerja_1 + $total_upah_harikerja_2,0,",",".");
                // }
                // $hasilx2 = 0;
                // $hasilx3 = 0;
                // $hasilx4 = 0;
                // $total_upah_harilibur = '-';

                // if ($keterangan_hari == "Hari Libur" && $totalJam >= 8) {
                //   $hasilx2 = 8 * 2;
                // }elseif ($keterangan_hari == 'Hari Libur' && $totalJam < 8) {
                //   $hasilx2 = $totalJam * 2;
                // }else{
                //   $hasilx2 = 0;
                // }
                // // elseif ($totalJam > 8) {
                // //   $hasilx2 = 8 * 2;
                // //   $hasilx3 = 1 * 3;

                // if ($keterangan_hari == 'Hari Libur') {
                //   $hasilx4 = ($totalJam - 9) * 4;
                // }

                // if ($totalJam >= 12) {
                //   $hasilx4 = 16;
                // }
                // // }

                // $total_upah_hariliburx2 = $upah_perjam * $hasilx2;
                // $total_upah_hariliburx3 = $upah_perjam * $hasilx3;
                // $total_upah_hariliburx4 = $upah_perjam * $hasilx4;

                // if ($hasilx2 > 0 || $hasilx3 > 0 || $hasilx4 > 0) {
                //   $total_upah_harilibur = number_format($total_upah_hariliburx2 + $total_upah_hariliburx3 + $total_upah_hariliburx4, 0, ",", ".");
                // }
            ?>
            <tr>
              <td>{{$loop->index+1}}</td>
              <td>{{$dt->name}}</td>
              <td>{{tanggal_indonesia($dt->tanggal_lembur)}}</td>
              <td>{{$keterangan_hari}}</td>
              <td>{{$dt->waktu_mulai}}</td>
              <td>{{$dt->waktu_selesai}}</td>
              <td>{{$lembur_ditagihkan}}</td>
              <td>{{$lembur_ditagihkan}}</td>
              <td>
                @if($dt->status_lembur == 'terima')
                <span class="badge bg-success text-white">Pengajuan Diterima</span>
                @elseif($dt->status_lembur == 'tolak')
                <span class="badge bg-danger text-white">Pengajuan Ditolak</span>
                @else
                <span class="badge bg-warning text-white">Belum Dikonfirmasi</span>
                @endif
              </td>
              <td>
                @if($dt->status_lembur == 'pending')
                <a href="javascript:void(0)" more_id="{{$dt->id_pengajuan}}" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a>
                @endif
                <a href="javascript:void(0)" data-bs-target="#modal_form_detail{{$dt->id_pengajuan}}" data-bs-toggle="modal" class="btn btn-info text-white rounded-pill btn-sm view"><i class="bx bx-briefcase"></i></a>
                @if($dt->status_lembur == 'pending')
                <a href="javascript:void(0)" more_id="{{$dt->id_pengajuan}}" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a>
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
@include('page.pengajuan_lembur.detail')
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
      <div class="row mt-2">
        <label class="col-sm-3">Karyawan <span class="text-danger">*</span></label>
        <div class="col-sm-8">
          <input type="" hidden="" id="id_pengajuan" name="id_pengajuan">
          <select class="form-control" style="width: 100%;" id="id_karyawan" name="id_karyawan">
            @foreach($karyawan as $kry)
            <option value="{{$kry->id}}">{{$kry->name}}</option>
            @endforeach
          </select>
          <span class="invalid-feedback d-block" role="alert" id="id_karyawanError">
            <strong></strong>
          </span>
        </div>
      </div>
      <div class="row mt-2">
        <label class="col-sm-3">Tanggal Lembur <span class="text-danger">*</span></label>
        <div class="col-sm-8">
          <input type="text" class="form-control" style="width: 100%;" id="tanggal_lembur" name="tanggal_lembur">
          <span class="invalid-feedback d-block" role="alert" id="tanggal_lemburError">
            <strong></strong>
          </span>
        </div>
      </div>
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
      <div class="row mt-2" id="row_status_lembur">
        <label class="col-sm-3">Status Pengajuan <span class="text-danger">*</span></label>
        <div class="col-sm-8">
          <select class="form-control" style="width: 100%;" id="status_lembur" name="status_lembur">
            <option value="pending">Pending</option>
            <option value="terima">Terima Pengajuan</option>
            <option value="tolak">Tolak Pengajuan</option>
          </select>
          <span class="invalid-feedback d-block" role="alert" id="status_lemburError">
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
  var currentYear = moment().year();
  $("#tanggal_lembur").daterangepicker({
    minDate: moment().set({ year: currentYear - 1 }),
    maxDate: moment().endOf('year'),
    singleDatePicker: true,
    autoUpdateInput: false,
    locale: {
      format: 'YYYY-MM-DD'
    }
  }).on('apply.daterangepicker', function(ev, picker) {
    var startDate = picker.startDate;
    $(this).val(startDate.format('YYYY-MM-DD'));
  }).on('cancel.daterangepicker', function() {
    $(this).val('');
  }).on('keydown.daterangepicker',function(e) {
    e.preventDefault();
  }).on('cut.daterangepicker',function(e) {
    e.preventDefault();
  });
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
  $("#id_karyawan").select2({
    placeholder: ".: PILIH KARYAWAN :.",
    dropdownParent: $("#modal_form_lembur")
  });
  $("#status_lembur").select2({
    placeholder: ".: PILIH STATUS :.",
    dropdownParent: $("#modal_form_lembur")
  });
  var ajaxUrl;
  $(document).ready(function() {
    $(".new").click(function() {
      $("#modal_form_lembur").modal('show');
      $("#lemburForm")[0].reset();
      $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Lemburan');
      $("#id_karyawan").val(null).trigger('change');
      $("#status_lembur").val(null).trigger('change');
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
            showToast('bg-primary','Data Lemburan Success',response.message);
            setTimeout(function() {
              document.location.href = '';
            }, 600);
          } else {
            showToast('bg-danger','Data Lemburan Error',response.message);
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
            showToast('bg-danger','Data Lemburan Error',response.message);
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
          $("#id_karyawan").val(value.id).trigger('change');
          $("#status_lembur").val(value.status_lembur).trigger('change');
          $("#tanggal_lembur").val(value.tanggal_lembur);
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
    $("#id_karyawan").val(null).trigger('change');
    $("#status_lembur").val(null).trigger('change');
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Lemburan');
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
      text: 'Data Lemburan akan dihapus secara Permanent!',
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
              showToast('bg-primary','Data Lemburan Dihapus',response.message);
              setTimeout(function() {
                document.location.href = '';
              }, 600);
            }else{
              showToast('bg-danger','Data Lemburan Error',response.message);
            }
          },
          error: function(response) {
            showToast('bg-danger','Data Lemburan Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection