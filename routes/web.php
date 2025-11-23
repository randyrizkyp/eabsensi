<?php

use App\Models\Dbpegawai;
use App\Models\Dft_pegawai;
// use App\Http\Controllers\BpkaController;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\MasukController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPulangController;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\RekapPeroranganController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProsesabsenController;
use App\Http\Controllers\DeteksiController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Cache;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use App\Jobs\Uploadabsenmasuk;
use Laravel\Octane\Facades\Octane;
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

Route::get('/queue', function(){
    // menjalankan Job di dalam queue
    dispatch(new Uploadabsenmasuk('data'));
    return 'absen berhasil masuk';
});

Route::get('/storage/uploads/{tahun}/{bulan}/{file}', function ($tahun, $bulan, $file) {
    $path = "uploads/$tahun/$bulan/$file";

    if (!Storage::exists($path)) {
        abort(404);
    }

    return response()->file(storage_path("app/$path"));
});

Route::get('/', function () {
    
    // var_dump(openssl_get_cert_locations());
    $deteksi = new DeteksiController;
    $mobile = $deteksi->isMobile();
    // $macAddr = exec('getmac');
    if (!session()->has('mobile')) {
        session()->put('mobile', $mobile);
    }
    if (session()->has('mobile') && $mobile == false) {
        session()->put('mobile', $mobile);
    }

    return view('login.index');
});
Route::get('/flush999', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return Redirect::away('https://eabsensi-dispora.lampungutarakab.go.id/flush999');
});



Route::post('/masuk', [MasukController::class, 'index']);
Route::get('/logout', [MasukController::class, 'logout']);
Route::get('/ubahPertama', [MasukController::class, 'ubahPertama']);
Route::post('/ubahPassLogin', [MasukController::class, 'ubahPassLogin']);
Route::get('/editApel', [MasukController::class, 'editApel']);

Route::group(['middleware' => ['AbsenCheck']], function () {
    Route::get('/absensi/ubahPertama', [ProsesabsenController::class, 'ubahPertama']);
    Route::get('/absensi/pilihKeterangan', [ProsesabsenController::class, 'pilihKeterangan']);
    Route::post('/absensi/cekAbsen', [ProsesabsenController::class, 'cekAbsen']);
    Route::get('/absensi/wfoMasuk', [ProsesabsenController::class, 'wfoMasuk']);
    Route::get('/absensi/wfoApel', [ProsesabsenController::class, 'wfoApel']);
    Route::get('/absensi/wfoPulang', [ProsesabsenController::class, 'wfoPulang']);
    Route::get('/absensi/wfoMasukNon', [ProsesabsenController::class, 'wfoMasukNon']);
    Route::get('/absensi/wfoPulangNon', [ProsesabsenController::class, 'wfoPulangNon']);

    Route::get('/cekSkor/individu', [ProsesabsenController::class, 'skorIndividu']);
    Route::post('/cekSkor/individu', [ProsesabsenController::class, 'skorIndividu']);
    Route::get('/metodeSkoring', [ProsesabsenController::class, 'metodeSkoring']);

    Route::post('/uploadFoto', [ProsesabsenController::class, 'uploadFoto']);
    Route::post('/uploadFoto_b', [ProsesabsenController::class, 'uploadFoto_b']);
    Route::post('/uploadFotoPulang', [ProsesabsenController::class, 'uploadFotoPulang']);
    Route::post('/uploadFotoPulang_b', [ProsesabsenController::class, 'uploadFotoPulang_b']);
    
    Route::post('/uploadWfoMasuk', [ProsesabsenController::class, 'uploadWfoMasuk']);
    Route::post('/uploadWfoPulang', [ProsesabsenController::class, 'uploadWfoPulang']);
    Route::post('/uploadWfoMasukNon', [ProsesabsenController::class, 'uploadWfoMasukNon']);
    Route::post('/uploadWfoPulangNon', [ProsesabsenController::class, 'uploadWfoPulangNon']);
    Route::post('/uploadFoto_DL', [ProsesabsenController::class, 'uploadFoto_DL']);

    Route::post('/uploadWfoMasukKhusus', [ProsesabsenController::class, 'uploadWfoMasukKhusus']);
    Route::post('/uploadWfoPulangKhusus', [ProsesabsenController::class, 'uploadWfoPulangKhusus']);

    Route::get('/absensi/uraianPerjadin', [ProsesabsenController::class, 'uraianPerjadin']);
    Route::post('/absensi/dinasLuar', [ProsesabsenController::class, 'dinasLuar']);
    Route::post('/absensi/uploadDinasLuar', [ProsesabsenController::class, 'uploadDinasLuar']);

    Route::get('/absensi/sakit', [ProsesabsenController::class, 'sakit']);
    Route::post('/absensi/uploadSakit', [ProsesabsenController::class, 'uploadSakit']);

    Route::get('/absensi/izin', [ProsesabsenController::class, 'izin']);
    Route::post('/absensi/uploadIzin', [ProsesabsenController::class, 'uploadIzin']);
    Route::get('/absensi/cuti', [ProsesabsenController::class, 'cuti']);
    Route::post('/absensi/prosesCuti', [ProsesabsenController::class, 'prosesCuti']);
    Route::get('/hitungHariKerja', [ProsesabsenController::class, 'hitungHariKerja']);
    Route::post('/absensi/uploadCuti', [ProsesabsenController::class, 'uploadCuti']);


    Route::get('/rekapPerorangan', [RekapPeroranganController::class, 'rekapPerorangan']);
    Route::get('/daftarHadir', [RekapPeroranganController::class, 'daftarHadir']);
    Route::get('/absenShow', [RekapPeroranganController::class, 'absenShow']);
    Route::get('/ubahProfil', [RekapPeroranganController::class, 'ubahProfil']);
    Route::post('/updateProfil', [RekapPeroranganController::class, 'updateProfil']);
});



Route::group(['middleware' => ['AdminCheck']], function () {
    //Absen Masuk
    Route::get('/dashboard/absenMasuk', [DashboardController::class, 'index']);
    Route::get('/dashboard/yajraAbsenMasuk', [DashboardController::class, 'yajraAbsenMasuk'])->name('absenMasuk');
    Route::get('/dashboard/absenMasuk/carsen', [DashboardController::class, 'carsen']);
    Route::get('/absenMasuk/reject', [DashboardController::class, 'absenMasukReject']);
    Route::get('/absenMasuk/konfirmasi', [DashboardController::class, 'absenMasukKonfirmasi']);
    Route::get('/absenMasuk/konfirmasiAll', [DashboardController::class, 'absenMasukKonfirmasiAll']);
    Route::get('/absenMasuk/konfirmasiAllNon', [DashboardController::class, 'absenMasukKonfirmasiAllNon']);
    Route::get('/absenMasuk/hapus', [DashboardController::class, 'absenMasukHapus']);

    //Absen Pulang
    Route::get('/dashboard/absenPulang', [DashboardPulangController::class, 'index']);
    Route::get('/dashboard/yajraAbsenPulang', [DashboardPulangController::class, 'yajraAbsenPulang'])->name('absenPulang');
    Route::get('/dashboard/absenPulang/carsen', [DashboardPulangController::class, 'carsen']);
    Route::get('/absenPulang/reject', [DashboardPulangController::class, 'absenPulangReject']);
    Route::get('/absenPulang/konfirmasi', [DashboardPulangController::class, 'absenPulangKonfirmasi']);
    Route::get('/absenPulang/konfirmasiAll', [DashboardPulangController::class, 'absenPulangKonfirmasiAll']);
    Route::get('/absenPulang/konfirmasiAllNon', [DashboardPulangController::class, 'absenPulangKonfirmasiAllNon']);
    Route::get('/absenPulang/hapus', [DashboardPulangController::class, 'absenPulangHapus']);
    Route::get('/absenPulang/ajaxBelumAbsen', [DashboardPulangController::class, 'ajaxBelumAbsen']);

    //Rekap Absen Harian
    Route::get('/dashboard/absenHarian', [DashboardController::class, 'absenHarian']);

    //unconfirm
    Route::get('/dashboard/unconfirm', [DashboardController::class, 'unconfirm']);

    //Rekapitulasi
    Route::get('/rekapitulasi/asn', [RekapitulasiController::class, 'rekapAsn']);
    Route::post('/rekapitulasi/asn', [RekapitulasiController::class, 'rekapAsn']);
    Route::post('/rekapitulasi/Asnpdf', [RekapitulasiController::class, 'cetakAsnpdf']);
    Route::post('/rekapAsnExcel', [RekapitulasiController::class, 'cetakAsn']);
    Route::get('/rekapitulasi/Apel', [RekapitulasiController::class, 'apel']);

    Route::get('/rekapitulasi/NonAsn', [RekapitulasiController::class, 'rekapNonAsn']);
    Route::post('/rekapitulasi/NonAsn', [RekapitulasiController::class, 'rekapNonAsn']);
    Route::post('/rekapitulasi/NonAsnpdf', [RekapitulasiController::class, 'cetakNonAsnpdf']);
    Route::post('/rekapNonAsnExcel', [RekapitulasiController::class, 'cetakNonAsn']);
    Route::get('/rekapitulasi/tpp', [RekapitulasiController::class, 'rekapTpp']);
    Route::post('/rekapitulasi/tpp', [RekapitulasiController::class, 'rekapTpp']);
    Route::post('/cetakRekapTPP', [RekapitulasiController::class, 'cetakTPP']);
    Route::post('/cetakApel', [RekapitulasiController::class, 'cetakApel']);
    Route::get('/rekapitulasi/dinasLuar', [RekapitulasiController::class, 'rekapdinasLuar']);
    Route::post('/rekapitulasi/dinasLuar', [RekapitulasiController::class, 'rekapdinasLuar']);
    Route::post('/cetakRekapDL', [RekapitulasiController::class, 'cetakDL']);
    Route::post('/rekapitulasi/ubahTPP', [RekapitulasiController::class, 'ubahTPP']);
    Route::post('/rekapitulasi/hapusPenerima', [RekapitulasiController::class, 'hapusPenerima']);

    // Kepegawaian
    Route::get('/kepegawaian/pegawaiAsn', [PegawaiController::class, 'pegawaiAsn']);
    Route::post('/kepegawaian/tambahPenandatangan', [PegawaiController::class, 'tambahPenandatangan']);
    Route::post('/kepegawaian/tambahAsn', [PegawaiController::class, 'tambahAsn']);
    Route::post('/kepegawaian/updateAsn', [PegawaiController::class, 'updateAsn']);
    Route::get('/kepegawaian/hapusAsn', [PegawaiController::class, 'hapusAsn']);
    Route::get('/kepegawaian/ajaxTpt', [PegawaiController::class, 'ajaxTpt']);
    Route::get('/dataAsn', [PegawaiController::class, 'dataAsn'])->name('dataAsn');
    Route::get('/cobaPegawai', [PegawaiController::class, 'cobaPegawai']);
    Route::get('/kepegawaian/nonAsn', [PegawaiController::class, 'nonAsn']);
    Route::get('/kepegawaian/mutasiKeluar', [PegawaiController::class, 'mutasiKeluar']);
    Route::get('/kepegawaian/mutasiMasuk', [PegawaiController::class, 'mutasiMasuk']);
    Route::get('/dataMutasiPegawai', [PegawaiController::class, 'dataMutasiPegawai']);
    Route::post('/kepegawaian/prosesMutasi', [PegawaiController::class, 'prosesMutasi']);
    Route::get('/kepegawaian/tolakMutasi', [PegawaiController::class, 'tolakMutasi']);

    Route::get('/cetakDaftarAsn', [PegawaiController::class, 'cetakDaftarAsn']);

    // Admin change password
    Route::post('/admin/changePass', [PegawaiController::class, 'adminChangePass']);    

    Route::get('/shift/pengaturan', [ShiftController::class, 'pengaturan']);

    Route::get('/shift', [ShiftController::class, 'index']);
    Route::post('/shift/update', [ShiftController::class, 'updateShift']);
});
