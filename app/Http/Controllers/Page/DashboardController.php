<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Page\Jabatan;
use App\Models\Page\PengajuanLembur;
use Illuminate\Support\Facades\Log;
use Exception;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$karyawan = User::getKaryawan($request);
		$jabatan = Jabatan::count();
		$result = PengajuanLembur::join('users','users.id','=','pengajuan_lembur.id_karyawan')
		->join('biodata','biodata.id_user','=','users.id')
		->leftJoin('jabatan','jabatan.id_jabatan','=','biodata.id_jabatan')
		->where('users.level','Karyawan');
		$pending = clone $result;
		$pending = $pending->where('pengajuan_lembur.status_lembur','pending')->get();
		$terima = clone $result;
		$terima = $terima->where('pengajuan_lembur.status_lembur','terima')->get();
		$tolak = clone $result;
		$tolak = $tolak->where('pengajuan_lembur.status_lembur','tolak')->get();
		return view('page.dashboard.index',compact('karyawan','jabatan','pending','terima','tolak'));
	}
	public function update_profil(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'email' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Nama harus diisi.',
			'email.required' => 'Email/Username harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$data = User::where('id',Auth::user()->id)->first();
			$data -> name = $request->name;
			$data -> email = $request->email;
			if ($request->password != '') {
				$data -> password = hash::make($request->password);
			}
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Profil berhasil diperbarui !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}
