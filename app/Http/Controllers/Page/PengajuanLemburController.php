<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Page\PengajuanLembur;
use Illuminate\Support\Facades\Log;
use DataTables;
use Exception;

class PengajuanLemburController extends Controller
{
	public function index(Request $request)
	{
		$data = PengajuanLembur::getPengajuanLembur($request);
		$karyawan = User::getKaryawan($request);
		return view('page.pengajuan_lembur.index',compact('data','karyawan'));
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'waktu_mulai' => 'required',
			'waktu_selesai' => 'required',
			'keterangan_lembur' => 'required'
		];
		$validateMessage += [
			'waktu_mulai.required' => 'Waktu Masuk harus diisi.',
			'waktu_selesai.required' => 'Waktu Keluar harus diisi.',
			'keterangan_lembur.required' => 'Keterangan Lembur harus diisi.'
		];
		if (Auth::user()->level == 'Supervisor') {
			$validateRules += [
				'id_karyawan' => 'required',
				'status_lembur' => 'required',
				'tanggal_lembur' => 'required'
			];
			$validateMessage += [
				'id_karyawan.required' => 'Karyawan harus dipilih.',
				'status_lembur.required' => 'Status harus dipilih.',
				'tanggal_lembur.required' => 'Tanggal Lembur harus dipilih.'
			];
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			if (Auth::user()->level == 'Supervisor') {
				$id_karyawan = $request->id_karyawan;
				$tanggal_lembur = $request->tanggal_lembur;
				$status_lembur = $request->status_lembur;
			}else{
				$id_karyawan = Auth::user()->id;
				$tanggal_lembur = date('Y-m-d');
				$status_lembur = 'pending';
			}
			$cek = PengajuanLembur::where('id_karyawan',$id_karyawan)
			->where('tanggal_lembur',$tanggal_lembur)
			->count();
			if ($cek > 0) {
				return response()->json(['status'=>'warning', 'message'=>'Karyawan dan Tanggal Lembur sudah ditambahkan.']);
			}
			$data = New PengajuanLembur();
			$data -> id_karyawan = $id_karyawan;
			$data -> tanggal_lembur = $tanggal_lembur;
			$data -> waktu_mulai = $request->waktu_mulai;
			$data -> waktu_selesai = $request->waktu_selesai;
			$data -> keterangan_lembur = $request->keterangan_lembur;
			$data -> status_lembur = $status_lembur;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Lemburan berhasil di tambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_pengajuan)
	{
		$data = PengajuanLembur::getEditPengajuanLembur($id_pengajuan);
		return response()->json($data);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'waktu_mulai' => 'required',
			'waktu_selesai' => 'required',
			'keterangan_lembur' => 'required'
		];
		$validateMessage += [
			'waktu_mulai.required' => 'Waktu Masuk harus diisi.',
			'waktu_selesai.required' => 'Waktu Keluar harus diisi.',
			'keterangan_lembur.required' => 'Keterangan Lembur harus diisi.'
		];
		if (Auth::user()->level == 'Supervisor') {
			$validateRules += [
				'id_karyawan' => 'required',
				'status_lembur' => 'required',
				'tanggal_lembur' => 'required'
			];
			$validateMessage += [
				'id_karyawan.required' => 'Karyawan harus dipilih.',
				'status_lembur.required' => 'Status harus dipilih.',
				'tanggal_lembur.required' => 'Tanggal Lembur harus dipilih.'
			];
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			if (Auth::user()->level == 'Supervisor') {
				$id_karyawan = $request->id_karyawan;
				$tanggal_lembur = $request->tanggal_lembur;
				$status_lembur = $request->status_lembur;
			}else{
				$id_karyawan = Auth::user()->id;
				$tanggal_lembur = date('Y-m-d');
				$status_lembur = 'pending';
			}
			$data = PengajuanLembur::where('id_pengajuan',$request->id_pengajuan)->first();
			// if ($data->id_karyawan != $id_karyawan) {
			// 	$cek = PengajuanLembur::where('id_karyawan',$id_karyawan)
			// 	->where('tanggal_lembur',$tanggal_lembur)
			// 	->count();
			// 	if ($cek > 0) {
			// 		return response()->json(['status'=>'warning', 'message'=>'Karyawan dan Tanggal Lembur sudah ditambahkan.']);
			// 	}
			// }
			$data -> id_karyawan = $id_karyawan;
			$data -> tanggal_lembur = $tanggal_lembur;
			$data -> waktu_mulai = $request->waktu_mulai;
			$data -> waktu_selesai = $request->waktu_selesai;
			$data -> keterangan_lembur = $request->keterangan_lembur;
			$data -> status_lembur = $status_lembur;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Lemburan berhasil di ubah !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_pengajuan)
	{
		try {
			DB::beginTransaction();
			$data = PengajuanLembur::where('id_pengajuan',$id_pengajuan)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Lemburan berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function karyawan_pengajuan_lembur(Request $request)
	{
		$data = PengajuanLembur::getPengajuanLembur($request);
		$cek = PengajuanLembur::where('id_karyawan',Auth::user()->id)
		->where('tanggal_lembur',date('Y-m-d'))
		->count();
		return view('karyawan.pengajuan_lembur.index',compact('data','cek'));
	}
}
