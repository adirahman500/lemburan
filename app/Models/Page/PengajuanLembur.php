<?php

namespace App\Models\Page;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanLembur extends Model
{
    // use HasFactory;
	protected $table="pengajuan_lembur";
	protected $primaryKey="id_pengajuan";

	public static function getPengajuanLembur($request)
	{
		$data = PengajuanLembur::join('users','users.id','=','pengajuan_lembur.id_karyawan')
		->join('biodata','biodata.id_user','=','users.id')
		->leftJoin('jabatan','jabatan.id_jabatan','=','biodata.id_jabatan');
		if (Auth::user()->level == 'Supervisor') {
			$data->where('users.level','Karyawan');
		}else{
			$data->where('users.id',Auth::user()->id);
		}
		if (Auth::user()->level == 'Supervisor' && !empty($request->status_pengajuan)) {
			$data->where('pengajuan_lembur.status_lembur',$request->status_pengajuan);
		}
		$data = $data->get();
		return $data;
	}
	public static function getHijriHoliday($year) {
		return [
			date('Y-m-d', strtotime('2024-04-10')), 
			date('Y-m-d', strtotime('2024-06-06')),
			date('Y-m-d', strtotime('2024-06-07')),
			date('Y-m-d', strtotime('2024-07-17')),
		];
	}
	public static function getHolidays($year) {
		$fixed_holidays = [
			"$year-01-01",
			"$year-05-01",
			"$year-08-17",
			"$year-12-25",
		];
		$calculated_holidays = array_merge($fixed_holidays, self::getHijriHoliday($year));
		$variable_holidays = [
			'2024-02-08',
			'2024-03-11', 
			'2024-04-13', 
			'2024-05-09', 
			'2024-05-23', 
			'2024-06-01', 
			'2024-09-16', 
		];
		return array_merge($calculated_holidays, $variable_holidays);
	}
	public static function getEditPengajuanLembur($id_pengajuan)
	{
		$data = PengajuanLembur::join('users','users.id','=','pengajuan_lembur.id_karyawan')
		->join('biodata','biodata.id_user','=','users.id')
		->leftJoin('jabatan','jabatan.id_jabatan','=','biodata.id_jabatan')
		->where('pengajuan_lembur.id_pengajuan',$id_pengajuan)
		->get();
		return $data;
	}
}
