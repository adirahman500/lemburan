<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Page\Jabatan;
use Illuminate\Support\Facades\Log;
use DataTables;
use Exception;

class JabatanController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = Jabatan::all();
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id_jabatan.'" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id_jabatan.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.jabatan.index');
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_jabatan' => 'required',
			'bpk_jabatan' => 'required'
		];
		$validateMessage += [
			'nama_jabatan.required' => 'Nama Jabatan harus diisi.',
			'bpk_jabatan.required' => 'BPK Jabatan harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			if ($request->id_jabatan == '') {
				$data = New Jabatan();
				$text = 'Jabatan berhasil ditambahkan !!';
			}else{
				$data = Jabatan::where('id_jabatan',$request->id_jabatan)->first();
				$text = 'Jabatan berhasil diubah !!';
			}
			$data -> nama_jabatan = $request->nama_jabatan;
			$data -> bpk_jabatan = $request->bpk_jabatan;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>$text]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_jabatan)
	{
		$data = Jabatan::where('id_jabatan',$id_jabatan)->get();
		return response()->json($data);
	}
	public function delete($id_jabatan)
	{
		try {
			DB::beginTransaction();
			$data = Jabatan::where('id_jabatan',$id_jabatan)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Jabatan berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}
