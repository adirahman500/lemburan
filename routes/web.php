<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Page\DashboardController;
use App\Http\Controllers\Page\JabatanController;
use App\Http\Controllers\Page\KaryawanController;
use App\Http\Controllers\Page\PengajuanLemburController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function() {
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	dd("Sudah Bersih nih!, Silahkan Kembali ke Halaman Utama");
});

Route::get('/', function () {
	return view('login');
})->name('login');
Route::post('auth/request_login',[AuthController::class,'ceklogin'])->name('ceklogin');
Route::get('auth/logout',[AuthController::class,'logout'])->name('logout');


Route::post('myprofil/update',[DashboardController::class,'update_profil'])->name('update_profil');
Route::middleware(['auth'])->prefix('page')->group(function() {
	Route::get('dashboard',[DashboardController::class,'index'])->name('index.dashboard');
});
Route::middleware(['auth'])->prefix('page/master')->group(function() {
	Route::get('jabatan',[JabatanController::class,'index'])->name('index.jabatan');
	Route::post('jabatan/save',[JabatanController::class,'save'])->name('save.jabatan');
	Route::get('jabatan/get_edit/{id_jabatan}',[JabatanController::class,'get_edit']);
	Route::get('jabatan/destroy/{id_jabatan}',[JabatanController::class,'delete']);

	Route::get('karyawan',[KaryawanController::class,'index'])->name('index.karyawan');
	Route::post('karyawan/save',[KaryawanController::class,'save'])->name('save.karyawan');
	Route::get('karyawan/get_edit/{id_karyawan}',[KaryawanController::class,'get_edit']);
	Route::post('karyawan/update',[KaryawanController::class,'update'])->name('update.karyawan');
	Route::get('karyawan/destroy/{id_karyawan}',[KaryawanController::class,'delete']);
});
Route::middleware(['auth'])->prefix('page')->group(function() {
	Route::get('pengajuan_lembur',[PengajuanLemburController::class,'index'])->name('index.pengajuan_lembur');
	Route::post('pengajuan_lembur/save',[PengajuanLemburController::class,'save'])->name('save.pengajuan_lembur');
	Route::get('pengajuan_lembur/get_edit/{id_pengajuan}',[PengajuanLemburController::class,'get_edit']);
	Route::post('pengajuan_lembur/update',[PengajuanLemburController::class,'update'])->name('update.pengajuan_lembur');
	Route::get('pengajuan_lembur/destroy/{id_pengajuan}',[PengajuanLemburController::class,'delete']);
});
Route::middleware(['auth'])->prefix('page/karyawan')->group(function() {
	Route::get('pengajuan_lembur',[PengajuanLemburController::class,'karyawan_pengajuan_lembur'])->name('karyawan.pengajuan_lembur');
});