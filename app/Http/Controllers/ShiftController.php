<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\Jadwal;
use App\Models\Dft_pegawai;
use App\Models\Tandatangan;

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

   public function pengaturan(Request $request)
   {
      // --- Ambil data pegawai & admin (cache) ---
    $pegawai = Cache::remember('DaftarPegawai', now()->addMonths(3), function () {
        return Dft_pegawai::all();
    });

    $admin = Cache::remember('Admin', now()->addMonths(3), function () {
        return Tandatangan::first();
    });

    $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

    // Pegawai yang statusnya 'shift'
    $nama = Dft_pegawai::where('status', 'shift')->get();

    // --- Ambil bulan & tahun dari request ---
    // variabel tetap: $bulan dan $tahun
    $tahun = $request->tahun ?? date('Y');
    $bulan = $request->bulan ?? date('m');

    // Hitung jumlah hari dalam bulan tsb
    $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

    // --- Ambil Jadwal Berdasarkan Bulan & Tahun ---
    // sesuai format tabel Jadwal Anda (kolom: nip, bulan, tahun, tanggal_string)
    $shiftData = Jadwal::where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->get()
        ->keyBy('nip');   // JANGAN diubah, biar view tetap sama
   $kode_pd = config('global.kode_pd');
   $db = $this->db;
   $ruangan = $db->table('tb_pd')->where('kode_pd',$kode_pd)->where('tpt_lain' , '!=', '')->get();

    return view('shift.pengaturan', [
        'nama_lain'   => config('global.nama_lain'),
        'tahun'       => $tahun,
        'bulan'       => $bulan,
        'jumlahHari'  => $jumlahHari,
        'admin'       => $admin,
        'nama'        => $nama,
        'foto_admin'  => $foto_admin,
        'shiftData'   => $shiftData,
        'ruangan'   => $ruangan,
        'notifMutasi' => $this->notifMutasi,
    ]);
   }

   public function updateShift(Request $request)
   {
      $tanggal = $request->tanggal;  // string panjang "123LL231..."
      
      // Cek apakah sudah ada record bulan ini
      $tahun = date('Y');
      $bulan = date('m');

      $cek = Jadwal::where('nip', $request->nip)
                  ->where('tahun', $tahun)
                  ->where('bulan', $bulan)
                  ->first();

      if ($cek) {
         // UPDATE
         $cek->tanggal = $tanggal;
         $cek->save();
      } else {
         // INSERT
         Jadwal::create([
               'nip' => $request->nip,
               'nama' => $request->nama,
               'tahun'      => $tahun,
               'bulan'      => $bulan,
               'tanggal'    => $tanggal
         ]);
      }

      return response()->json(['status' => 'ok']);

   }
}