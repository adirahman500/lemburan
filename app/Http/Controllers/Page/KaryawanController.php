<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Page\Jabatan;
use Illuminate\Support\Facades\Log;
use DataTables;
use Exception;

class KaryawanController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = User::getKaryawan($request);
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id.'" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		$jabatan = Jabatan::all();
		return view('page.karyawan.index',compact('jabatan'));
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'email' => 'required|unique:users,email',
			'password' => 'required',
			'id_jabatan' => 'required',
			'unit' => 'required',
			'kab_lokasi_kerja' => 'required',
			'tanggal_masuk' => 'required',
			'umk' => 'required',
			'masa_kerja' => 'required',
			'tmk' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Nama harus diisi.',
			'email.required' => 'Email harus diisi.',
			'email.unique' => 'Email yang digunakan sudah terdaftar.',
			'password.required' => 'Password harus diisi.',
			'id_jabatan.required' => 'Jabatan harus dipilih.',
			'unit.required' => 'Unit harus diisi.',
			'kab_lokasi_kerja.required' => 'Kab Lokasi Kerja harus diisi.',
			'tanggal_masuk.required' => 'Tanggal Masuk harus dipilih.',
			'umk.required' => 'UMK harus diisi.',
			'masa_kerja.required' => 'Masa Kerja harus diisi.',
			'tmk.required' => 'TMK harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$text = 'Karyawan berhasil ditambahkan !!';
			$string = "Suka*()bumi #$^%& Kode ($%^2&^)*(0&*^19.";
			$umk = preg_replace("/[^aZ0-9]/", "", $request->umk);
			$tmk = preg_replace("/[^aZ0-9]/", "", $request->tmk);

			$data = New User();
			$data -> name = $request->name;
			$data -> email = $request->email;
			$data -> password = hash::make($request->password);
			$data -> level = 'Karyawan';
			$data -> status = 'A';
			$data -> save();
			DB::table('biodata')->insert([
				'id_user' => $data->id,
				'id_jabatan' => $request->id_jabatan,
				'unit' => $request->unit,
				'kab_lokasi_kerja' => $request->kab_lokasi_kerja,
				'tanggal_masuk' => $request->tanggal_masuk,
				'umk' => $umk,
				'masa_kerja' => $request->masa_kerja,
				'tmk' => $tmk
			]);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>$text]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'email' => 'required',
			'id_jabatan' => 'required',
			'unit' => 'required',
			'kab_lokasi_kerja' => 'required',
			'tanggal_masuk' => 'required',
			'umk' => 'required',
			'masa_kerja' => 'required',
			'tmk' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Nama harus diisi.',
			'email.required' => 'Email harus diisi.',
			'id_jabatan.required' => 'Jabatan harus dipilih.',
			'unit.required' => 'Unit harus diisi.',
			'kab_lokasi_kerja.required' => 'Kab Lokasi Kerja harus diisi.',
			'tanggal_masuk.required' => 'Tanggal Masuk harus dipilih.',
			'umk.required' => 'UMK harus diisi.',
			'masa_kerja.required' => 'Masa Kerja harus diisi.',
			'tmk.required' => 'TMK harus diisi.'
		];
		$user = User::join('biodata','biodata.id_user','=','users.id')
		->where('users.id',$request->id_karyawan)
		->first();
		if ($user->email != $request->email) {
			$request->validate([
				'email' => 'unique:users,email'
			],[
				'email.unique' => 'Email yang anda masukkan sudah terdaftar.',
			]);
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$text = 'Karyawan berhasil diubah !!';
			$string = "Suka*()bumi #$^%& Kode ($%^2&^)*(0&*^19.";
			$umk = preg_replace("/[^aZ0-9]/", "", $request->umk);
			$tmk = preg_replace("/[^aZ0-9]/", "", $request->tmk);

			$data = User::where('id',$request->id_karyawan)->first();
			$data -> name = $request->name;
			$data -> email = $request->email;
			if ($request->password = '') {
				$data -> password = hash::make($request->password);
			}
			$data -> save();
			DB::table('biodata')->where('id_user',$request->id_karyawan)->update([
				'id_jabatan' => $request->id_jabatan,
				'unit' => $request->unit,
				'kab_lokasi_kerja' => $request->kab_lokasi_kerja,
				'tanggal_masuk' => $request->tanggal_masuk,
				'umk' => $umk,
				'masa_kerja' => $request->masa_kerja,
				'tmk' => $tmk
			]);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>$text]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_karyawan)
	{
		$data = User::getEditKaryawan($id_karyawan);
		return response()->json($data);
	}
	public function delete($id_karyawan)
	{
		try {
			DB::beginTransaction();
			$data = User::where('id',$id_karyawan)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Data Karyawan berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}
