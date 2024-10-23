 <div class="modal text-left" data-bs-backdrop="static" id="modal_form_detail{{$dt->id_pengajuan}}" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white" style="margin-bottom:12px;" id="myModalLabel1">Detail Lemburan</h5>
        <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"
        ></button>
      </div>
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
      <div class="modal-body">
        <div class="row">
          <h5 class="col-4">Nama</h5>
          <h5 class="col-1">:</h5>
          <h5 class="col-7">{{$dt->name}}</h5>
          <h5 class="col-4">Tanggal Lembur</h5>
          <h5 class="col-1">:</h5>
          <h5 class="col-7">{{tanggal_indonesia($dt->tanggal_lembur)}}</h5>
          <h5 class="col-4">Waktu Lembur</h5>
          <h5 class="col-1">:</h5>
          <h5 class="col-7">{{$dt->waktu_mulai}} s/d {{$dt->waktu_selesai}}</h5>
          <div class="col-12"><hr></div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped nowrap table_pengajuan table-responsive table_detail" style="width: 100%;">
            <thead class="table-info text-white">
              <tr>
                <th rowspan="3">Nama</th>
                <th rowspan="3">Tanggal / Hari</th>
                <th rowspan="3">Hari Kerja / Libur</th>
                <th colspan="2" rowspan="1">Waktu Lembur</th>
                <th rowspan="3">Total Jam Lembur Realisasi</th>
                <th rowspan="3">Total Lembur yang ditagihkan</th>
                <th colspan="9">PERHITUNGAN LEBUR</th>
                <th rowspan="3">TOTAL LEMBUR (Rp.)</th>
                <th rowspan="3">JUMLAH TK</th>
                <th rowspan="3">NAMA & BULAN</th>
                <th rowspan="3">BULAN</th>
                <th rowspan="3">TOTAL JAM LEMBUR</th>
                <th rowspan="3">Keterangan Lembur</th>
              </tr>
              <tr class="text-center">
                <th rowspan="2">Masuk</th>
                <th rowspan="2">Keluar</th>
                <th colspan="4">LEMBUR HARI KERJA</th>
                <th colspan="5">LEMBUR HARI ISTIRAHAT MINGGUAN DAN ATAU HARI LIBUR RESMI</th>
              </tr>
              <tr class="text-center">
                <th>1,50</th>
                <th>2,00</th>
                <th>TARIF PERJAM</th>
                <th>TOTAL (Rp.)</th>
                <th>x 2 (8 jam Kebawah)</th>
                <th>x 3 (Jam Ke 9)</th>
                <th>x 4 (Jam Ke 10 & Jam Ke 11)</th>
                <th>TARIF PERJAM</th>
                <th>TOTAL (Rp.)</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0 text-center">
              <tr>
                <td>{{$dt->name}}</td>
                <td>{{tanggal_indonesia($dt->tanggal_lembur)}}</td>
                <td>{{$keterangan_hari}}</td>
                <td>{{$dt->waktu_mulai}}</td>
                <td>{{$dt->waktu_selesai}}</td>
                <td>{{$lembur_ditagihkan}}</td>
                <td>{{$lembur_ditagihkan}}</td>
                <td>
                  {{ $result_hari_kerja_15 }}
                </td>
                <td>
                  {{ $result_hari_kerja_20 }}
                </td>
                <td>{{number_format($upah_perjam,0,",",".")}}</td>
                <td>{{$keterangan_hari == 'Hari Kerja' ? $total_upah_harikerja : '-'}}</td>
                <td>{{ $result_hari_libur_2 }}</td>
                <td>{{ $result_hari_libur_3 }}</td>
                <td>{{ $result_hari_libur_4 }}</td>
                <td>{{number_format($upah_perjam,0,",",".")}}</td>
                <td>{{$keterangan_hari == 'Hari Libur' ? $total_upah_harilibur : '-'}}</td>
                <td>
                  {{ $keterangan_hari == 'Hari Libur' ? $total_upah_harilibur : ($keterangan_hari == 'Hari Kerja' ? $total_upah_harikerja : '-') }}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$dt->keterangan_lembur}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">
          <span>Tutup</span>
        </button>
      </div>
    </div>
  </div>
</div>