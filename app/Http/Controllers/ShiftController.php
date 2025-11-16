<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
// use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;

class ShiftController extends Controller
{
   protected $tahun, $bulan, $tanggal, $hari, $pegawai, $db, $notifMutasi;
   public function __construct()
   {
      $this->tahun = now()->translatedFormat('Y');
      $this->bulan = now()->translatedFormat('m');
      $this->tanggal = now()->translatedFormat('d');
      $this->hari = now()->translatedFormat('l');
      // $this->pegawai = Dft_pegawai::all();
      $this->db = DB::connection('eabsensi_opd');
      $this->notifMutasi = $this->db->table('alih_mutasi')->where('kode_pindah', config('global.kode_pd'))->where('respon', 'belum')->get();
   }

   public function shift(Request $request)
   {
      if (session()->has('tahun')) {
         session()->pull('tahun');
         session()->pull('bulan');
         session()->pull('tanggal');
      }

      return view('shift.shift', [
         'nama_lain' => config('global.nama_lain'),                 
         'notifMutasi' => $this->notifMutasi
      ]);
   }
}