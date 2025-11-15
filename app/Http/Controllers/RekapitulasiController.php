<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Dft_pegawai;
use App\Models\Tandatangan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;

use function GuzzleHttp\Promise\each;

class RekapitulasiController extends Controller
{
    protected $pegawai, $absensiBulanIni, $bulan, $tahun, $namaBulan, $db, $notifMutasi;
    public function __construct()
    {
        $this->tahun = now()->translatedFormat('Y');
        $this->bulan = now()->translatedFormat('m');
        $this->namaBulan = [['nama' => 'Januari', 'angka' => '01'], ['nama' => 'Februari', 'angka' => '02'], ['nama' => 'Maret', 'angka' => '03'], ['nama' => 'April', 'angka' => '04'], ['nama' => 'Mei', 'angka' => '05'], ['nama' => 'Juni', 'angka' => '06'], ['nama' => 'Juli', 'angka' => '07'], ['nama' => 'Agustus', 'angka' => '08'], ['nama' => 'September', 'angka' => '09'], ['nama' => 'Oktober', 'angka' => '10'], ['nama' => 'November', 'angka' => '11'], ['nama' => 'Desember', 'angka' => '12']];
        $this->db = DB::connection('eabsensi_opd');
        $this->notifMutasi = $this->db->table('alih_mutasi')->where('kode_pindah', config('global.kode_pd'))->where('respon', 'belum')->get();
    }
    public function rekapAsn(Request $request)
    {
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();


        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->orderBy('norut', 'asc')->get();

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        $data = collect($data);

        return view('rekapitulasi.rekapAsn', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi

        ]);
    }

    public function rekapTpp(Request $request)
    {

     $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();


        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->orderBy('norut', 'asc')->get();

        $absenUnik = $absensiBulanIni->unique('nip')->pluck('tpp');
        $tppUnik = $absenUnik->map(function ($value) {
            return Str::replace('.', '', $value);
        });
        $jumlahTPPKehadiran =  $tppUnik->sum() * .4;

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->where('tpp', '!=', '0')->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        // return dd($data);
        $data = collect($data);
        
        $jumTotalPot = 0;
        $arrayPot = [];
        foreach ($data as $dts) {
            $pengurangan = $dts->sum('pengurangan');
           
            foreach ($dts as $dt) {
                $tpp = str_replace('.', '', $dt->tpp);
                $tppK = $tpp * .4;
                   $pengurangan = $dt->pengurangan;
                   $pengurangan_p = $dt->pengurangan_p;
                   if(is_null($pengurangan)){
                    $pengurangan = 0;
                   }
                   if(is_null($pengurangan_p)){
                    $penguranga_p = 0;
                   }


                    $pengurT = $tppK * ($pengurangan / 100);
               
                
                    $pengurTP = $tppK * ($pengurangan_p / 100);
                
                $jumPengur = $pengurT + $pengurTP;
                $jumTotalPot += $jumPengur;
            }
            
        }
       
         $jumlahTPPBersih = $jumlahTPPKehadiran - $jumTotalPot;

           
        return view('rekapitulasi.rekapTpp', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi,
             'jumlahTPPBersih' => $jumlahTPPBersih,
            'jumlahTPPKehadiran' => $jumlahTPPKehadiran,
            'jumlahTotalPot' => $jumTotalPot,

        ]);
    }

    public function Apel(Request $request)
    {
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();


        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->orderBy('norut', 'asc')->get();

        $absenUnik = $absensiBulanIni->unique('nip')->pluck('tpp');
        $tppUnik = $absenUnik->map(function ($value) {
            return Str::replace('.', '', $value);
        });
        $jumlahTPPKehadiran =  $tppUnik->sum() * .4;

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->where('tpp', '!=', '0')->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        // return dd($data);
        $data = collect($data);
        
        $jumTotalPot = 0;
        $arrayPot = [];
        foreach ($data as $dts) {
            $pengurangan = $dts->sum('pengurangan');
           
            foreach ($dts as $dt) {
                $tpp = str_replace('.', '', $dt->tpp);
                $tppK = $tpp * .4;
                   $pengurangan = $dt->pengurangan;
                   $pengurangan_p = $dt->pengurangan_p;
                   if(is_null($pengurangan)){
                    $pengurangan = 0;
                   }
                   if(is_null($pengurangan_p)){
                    $penguranga_p = 0;
                   }


                    $pengurT = $tppK * ($pengurangan / 100);
               
                
                    $pengurTP = $tppK * ($pengurangan_p / 100);
                
                $jumPengur = $pengurT + $pengurTP;
                $jumTotalPot += $jumPengur;
            }
            
        }
       
         $jumlahTPPBersih = $jumlahTPPKehadiran - $jumTotalPot;

           
        return view('rekapitulasi.apel', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi,
             'jumlahTPPBersih' => $jumlahTPPBersih,
            'jumlahTPPKehadiran' => $jumlahTPPKehadiran,
            'jumlahTotalPot' => $jumTotalPot,

        ]);
    }

    public function rekapNonAsn(Request $request)
    {
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', 'Non-PNS')->orderBy('norut', 'asc')->get();

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->sortBy(['norut', 'asc'])->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        $data = collect($data);



        return view('rekapitulasi.rekapNonAsn', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi

        ]);
    }

    public function cetakAsn(Request $request)
    {

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });


        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->orderBy('norut', 'asc')->get();

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->where('pangkat', '!=', 'Non-PNS')->sortBy(['norut', 'asc'])->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        $data = collect($data);



        return view('rekapitulasi.cetakAsn', [
            'nama_lain' => config('global.nama_lain'),
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'notifMutasi' => $this->notifMutasi

        ]);
    }
    
    

    public function cetakAsnpdf(Request $request)
    {
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=','Non-PNS')->orderBy('norut', 'asc')->get();

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->sortBy(['norut', 'asc'])->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        $data = collect($data);

        $data = [
            'nama_lain' => config('global.nama_lain'),
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'notifMutasi' => $this->notifMutasi

        ];
        // return $data;
        $pdf = Pdf::loadView('rekapitulasi.cetakAsnpdf', $data);
        return $pdf->download('laporan.pdf');
    }

    public function cetakNonAsn(Request $request)
    {
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', 'Non-PNS')->orderBy('norut', 'asc')->get();

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->sortBy(['norut', 'asc'])->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        $data = collect($data);



        return view('rekapitulasi.cetakNonAsn', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'notifMutasi' => $this->notifMutasi

        ]);
    }
    public function cetakNonAsnpdf(Request $request)
    {
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', 'Non-PNS')->orderBy('norut', 'asc')->get();

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->sortBy(['norut', 'asc'])->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        $data = collect($data);

        $data = [
            'nama_lain' => config('global.nama_lain'),
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'notifMutasi' => $this->notifMutasi

        ];
        $pdf = Pdf::loadView('rekapitulasi.cetakNonAsnpdf', $data);
        return $pdf->download('laporan.pdf');
    }

    public function cetakTPP(Request $request)
    {
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->orderBy('norut', 'asc')->get();

        $absenUnik = $absensiBulanIni->unique('nip')->pluck('tpp');
        $tppUnik = $absenUnik->map(function ($value) {
            return Str::replace('.', '', $value);
        });
        $jumlahTPPKehadiran =  $tppUnik->sum() * .4;

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->where('tpp', '!=', '0')->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        // return dd($data);
        $data = collect($data);
        
        $jumTotalPot = 0;
        $arrayPot = [];
        foreach ($data as $dts) {
            $pengurangan = $dts->sum('pengurangan');
           
            foreach ($dts as $dt) {
                $tpp = str_replace('.', '', $dt->tpp);
                $tppK = $tpp * .4;
                   $pengurangan = $dt->pengurangan;
                   $pengurangan_p = $dt->pengurangan_p;
                   if(is_null($pengurangan)){
                    $pengurangan = 0;
                   }
                   if(is_null($pengurangan_p)){
                    $penguranga_p = 0;
                   }


                    $pengurT = $tppK * ($pengurangan / 100);
               
                
                    $pengurTP = $tppK * ($pengurangan_p / 100);
                
                $jumPengur = $pengurT + $pengurTP;
                $jumTotalPot += $jumPengur;
            }
            
        }
       

        $jumlahTPPBersih = $jumlahTPPKehadiran - $jumTotalPot;


        $pdf = PDF::loadView('rekapitulasi.cetakTPP', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'jumlahTPPBersih' => $jumlahTPPBersih,
            'jumlahTPPKehadiran' => $jumlahTPPKehadiran,
            'jumlahTotalPot' => $jumTotalPot,

        ]);


        return $pdf->stream();
    }

     public function cetakApel(Request $request)
    {
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->orderBy('norut', 'asc')->get();

        $absenUnik = $absensiBulanIni->unique('nip')->pluck('tpp');
        $tppUnik = $absenUnik->map(function ($value) {
            return Str::replace('.', '', $value);
        });
        $jumlahTPPKehadiran =  $tppUnik->sum() * .4;

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $coba = $absensiBulanIni->where('tpp', '!=', '0')->groupBy('nip');

        $data = [];
        foreach ($coba as $cb) {
            $test =  $cb->filter(function ($item, $key) use ($libur) {
                return !in_array($item->tanggal, $libur);
            });
            array_push($data, $test);
        }
        // return dd($data);
        $data = collect($data);
        
        $jumTotalPot = 0;
        $arrayPot = [];
        foreach ($data as $dts) {
            $pengurangan = $dts->sum('pengurangan');
           
            foreach ($dts as $dt) {
                $tpp = str_replace('.', '', $dt->tpp);
                $tppK = $tpp * .4;
                   $pengurangan = $dt->pengurangan;
                   $pengurangan_p = $dt->pengurangan_p;
                   if(is_null($pengurangan)){
                    $pengurangan = 0;
                   }
                   if(is_null($pengurangan_p)){
                    $penguranga_p = 0;
                   }


                    $pengurT = $tppK * ($pengurangan / 100);
               
                
                    $pengurTP = $tppK * ($pengurangan_p / 100);
                
                $jumPengur = $pengurT + $pengurTP;
                $jumTotalPot += $jumPengur;
            }
            
        }
       

        $jumlahTPPBersih = $jumlahTPPKehadiran - $jumTotalPot;


        $pdf = PDF::loadView('rekapitulasi.cetakApel', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'data' => $data,
            'admin' => $admin,
            'jumlahTPPBersih' => $jumlahTPPBersih,
            'jumlahTPPKehadiran' => $jumlahTPPKehadiran,
            'jumlahTotalPot' => $jumTotalPot,

        ]);


        return $pdf->stream();
    }

    public function rekapdinasLuar(Request $request)
    {
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->where('keterangan', 'dinas luar')->orderBy('norut', 'asc')->get();

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $dataDL = $absensiBulanIni->groupBy('nip');


        return view('rekapitulasi.rekapdinasLuar', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'dataDL' => $dataDL,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi
        ]);
    }
    public function cetakDLexcel(Request $request)
    {
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->where('keterangan', 'dinas luar')->orderBy('norut', 'asc')->get();

        $tglabsen = $absensiBulanIni->sortBy(['tanggal', 'asc'])->pluck('tanggal')->unique();
        $tglabsen = $tglabsen->filter(function ($item, $key) use ($libur) {
            return !in_array($item, $libur);
        });
        $dataDL = $absensiBulanIni->groupBy('nip');


        return view('rekapitulasi.cetakdinasLuar', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tglabsen' => $tglabsen,
            'dataDL' => $dataDL,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi
        ]);
    }
    public function cetakDL(Request $request)
    {
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $bulan = $this->bulan;
        $tahun = $this->tahun;
        if ($request->bulan) {
            $bulan = $request->bulan;
        }
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $db = DB::connection('eabsensi_opd');
        $libur = $db->table('libur')->where([
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $namaBulan = $this->namaBulan;
        $absensiBulanIni = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->where('keterangan', 'dinas luar')->orderBy('norut', 'asc')->get();

        $dataDL = $absensiBulanIni->where('tpp', '!=', '0')->groupBy('nip');




        $pdf = PDF::loadView('rekapitulasi.pdfDinasLuar', [
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'dataDL' => $dataDL,
            'admin' => $admin,
        ]);


        return $pdf->stream();
    }

    public function ubahTPP(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nip = $request->nip;
        $update = Absen::where([
            'nip' => $nip,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->update([
            'tpp' => $request->tpp_update
        ]);
        if ($update) {
            return back()->with('success', 'berhasil update TPP');
        } else {
            return back()->with('error', 'gagal update TPP');
        }
    }

    public function hapusPenerima(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nip = $request->nip;
        $update = Absen::where([
            'nip' => $nip,
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->update([
            'tampil' => '0'
        ]);
        if ($update) {
            return back()->with('success', 'berhasil hapus');
        } else {
            return back()->with('error', 'gagal hapus');
        }
    }
}
