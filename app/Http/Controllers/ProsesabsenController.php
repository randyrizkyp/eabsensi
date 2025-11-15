<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Pejabat;
use App\Models\Dinas_luar;
use App\Models\Dft_pegawai;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DeteksiController;

class ProsesabsenController extends Controller
{

    protected $tahun, $bulan, $tanggal, $hari, $nip, $db, $namaBulan;

    public function __construct()
    {
        $this->tahun = now()->translatedFormat('Y');
        $this->bulan = now()->translatedFormat('m');
        $this->tanggal = now()->translatedFormat('d');
        $this->hari = now()->translatedFormat('l');
        $this->nip = session()->get(config('global.nama_lain'));
        $this->db = DB::connection('eabsensi_opd');
        $this->namaBulan = [['nama' => 'Januari', 'angka' => '01'], ['nama' => 'Februari', 'angka' => '02'], ['nama' => 'Maret', 'angka' => '03'], ['nama' => 'April', 'angka' => '04'], ['nama' => 'Mei', 'angka' => '05'], ['nama' => 'Juni', 'angka' => '06'], ['nama' => 'Juli', 'angka' => '07'], ['nama' => 'Agustus', 'angka' => '08'], ['nama' => 'September', 'angka' => '09'], ['nama' => 'Oktober', 'angka' => '10'], ['nama' => 'November', 'angka' => '11'], ['nama' => 'Desember', 'angka' => '12']];
    }

    public function pilihKeterangan(Request $request)
    {
        $deteksi = new DeteksiController;
        $mobile = $deteksi->isMobile();
        // $macAddr = exec('getmac');
        if (!session()->has('mobile')) {
            session()->put('mobile', $mobile);
        }
        if (session()->has('mobile') && $mobile == false) {
            session()->put('mobile', $mobile);
        }

        return view('prosesAbsen.pilihKeterangan');
    }

    public function skorIndividu(Request $request)
    {
       $tahun = $this->tahun;
       $bulan = $this->bulan;
       if($request->bulan){
        $bulan = $request->bulan;
       }
       if($request->tahun){
        $tahun = $request->tahun;
       }
       $namaBulan = $this->namaBulan;
       $nip = $this->nip;
       $data = Absen::where([
        'tahun' => $tahun,
        'bulan' => $bulan,
        'nip' => $nip,
        'tampil' => '1'
       ])->get();
       
       if(!$data || count($data)==0){
        return back()->with('fail', 'data tidak ditemukan!');
       }
       $nama = $data->pluck('nama')->first();
       if(count($data)>0){
        $totalSkor = ($data->sum('skor')+$data->sum('skor_p'))/count($data);
        $totalSkor = round($totalSkor/2,2);
       }else{
        $totalSkor = 0;
       }
      
      
       if ($totalSkor <= 30) {
        $predikat = "SANGAT RENDAH";
        } elseif ($totalSkor > 30 && $totalSkor <= 55) {
            $predikat = "RENDAH";
        } elseif ($totalSkor > 55 && $totalSkor <= 75) {
            $predikat = "CUKUP";
        } elseif ($totalSkor > 75 && $totalSkor <= 90) {
            $predikat = "TINGGI";
        } elseif ($totalSkor > 90 && $totalSkor <= 100) {
            $predikat = "SANGAT TINGGI";
        } else {
            $predikat = '';
        }
       return view('rekapitulasi.skorIndividu', [
            'totalSkor' => $totalSkor,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'namaBulan' => $namaBulan,
            'nama' => $nama,
            'nip' => $nip,
            'predikat' => $predikat,
            'data' => $data
        ]);
    }

    public function metodeSkoring(Request $request)
    {
        return view('rekapitulasi.metodeSkoring');
    }


    
    public function cekAbsen(Request $request)
    {
         if (!session()->has('sekali_apel')) {
            
            return redirect('/logout');
        }

        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });

        $cekApel = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->whereNotNull('apel_pagi')->first();

        $cekAbsen = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->first();

        if ($request->wfomasuk) {
            if ($cekAbsen) {
                return back()->with('fail', 'Anda sudah melakukan absen masuk!');
            } else {
                // if ($dapeg->pangkat == 'Non-PNS') {
                //     return redirect('/absensi/wfoMasukNon');
                // }
                return redirect('/absensi/wfoMasuk');
            }
        }

        if ($request->apel) {
            if ($cekApel) {
                return back()->with('fail', 'Anda sudah melakukan absen apel!');
            } else {
                // if ($dapeg->pangkat == 'Non-PNS') {
                //     return redirect('/absensi/wfoMasukNon');
                // }
                return redirect('/absensi/wfoApel');
            }
        }

        if ($request->wfopulang) {
            if ($cekAbsen) {
                if ($cekAbsen->keterangan_p == 'belum absen') {
                    // if ($dapeg->pangkat == 'Non-PNS') {
                    //     return redirect('/absensi/wfoPulangNon');
                    //     exit();
                    //     die();
                    // }
                    return redirect('/absensi/wfoPulang');
                } else {
                    return back()->with('fail', 'sudah ada absen pulang hari ini!');
                }
            } else {
                return back()->with('gagal', 'Silahkan absen masuk terlebih dahulu');
            }
        }

        if ($request->dinasluar) {
            return redirect('/absensi/uraianPerjadin');
        }

        if ($request->sakit) {
            return redirect('/absensi/sakit');
        }

        if ($request->izin) {
            return redirect('/absensi/izin');
        }
        if ($request->cuti) {
            return redirect('/absensi/cuti');
        }
    }


    public function wfoMasuk(Request $request)
    {

        // Deteksi device mobile
        $mobile = session()->get('mobile');
        if (!$mobile) {
            return back()->with('gagal', 'fitur ini hanya bisa diakses melalui device mobile!');
        }

        // Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        //do something with this information
        $perangkat = 'android';

        if ($iPod || $iPhone || $iPad) {
            $perangkat = 'iphone';
            //browser reported as an iPhone/iPod touch -- do something here
        }
        $db = $this->db;
        $exclude = cache::remember('exclude', now()->addMonths(1), function () use ($db) {
            return $db->table('exclude')->get();
        });
        if ($exclude->where('nip', $this->nip)->first()) {
            $perangkat = 'iphone';
        }

        // deteksi tanggal libur
        $db = $this->db;
        $liburTahun = cache::remember('libur' . $this->tahun, now()->addMonths(3), function () use ($db) {
            return $db->table('libur')->where('tahun', $this->tahun)->get();
        });
        $libur = $liburTahun->where('bulan', $this->bulan)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();
        if (in_array($this->tanggal, $libur)) {
            return back()->with('fail', 'hari ini libur!');
        }

        // deteksi jam input masuk
        $sekarang = now()->translatedFormat('H');
         if ($sekarang < 7 || $sekarang > 12) {
             return back()->with('gagal', 'anda mengakses diluar jam masuk!');
         }


        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $unor = $dapeg->unit_organisasi;
        $kode_unit = $dapeg->kode_unit;
        $tptLain = $dapeg->tpt_lain;
        $kode_tpt_lain = $dapeg->kode_tpt_lain;
        $kode_opd_lain = $dapeg->kode_opd_lain;
        $db = DB::connection('eabsensi_opd');

        $datorg = cache::remember($kode_unit, now()->addMonths(3), function () use ($db, $kode_unit) {

            return $db->table('tb_pd')->where('kode_unit', $kode_unit)->get();
        });


        $dato = $datorg->where('tpt_lain', '')->first();

        $lat = $dato->lat;
        $lot = $dato->lot;
        $latlain = $lat;
        $lotlain = $lot;
        if ($tptLain != '') {
            $datlain = $datorg->where('kode_tpt_lain', $kode_tpt_lain)->first();
            $latlain = $datlain->lat;
            $lotlain = $datlain->lot;
        }
        if ($kode_opd_lain !== '') {
            $opdlain = $db->table('tb_pd')->where('kode_pd', $kode_opd_lain)->first();
            $latopd_lain = $opdlain->lat;
            $lotopd_lain = $opdlain->lot;
        } else {
            $opdlain = false;
            $latopd_lain = false;
            $lotopd_lain = false;
        }

        $cekPejabat = $db->table('pejabats')->where('nip', $nip)->first();
        $jarak = $db->table('jartos')->where('kode_unit', $kode_unit)->pluck('lokasi')->first();

        if ($cekPejabat) {
            if ($cekPejabat->level == 1) {
                return view('prosesAbsen.wfoMasukKhusus', [
                    'nip' => $nip,
                    'unor' => $unor,
                    'tpt_lain' => $tptLain,
                    'lat' => $lat,
                    'lot' => $lot,
                    'latlain' => $latlain,
                    'lotlain' => $lotlain,
                    'opdlain' => $opdlain,
                    'latopd_lain' => $latopd_lain,
                    'lotopd_lain' => $lotopd_lain,
                    'perangkat' => $perangkat,

                ]);
            } elseif ($cekPejabat->level == 2) {
                return view('prosesAbsen.wfoMasukKhusus2', [
                    'unor' => $unor,
                    'tpt_lain' => $tptLain,
                    'lat' => $lat,
                    'lot' => $lot,
                    'latlain' => $latlain,
                    'lotlain' => $lotlain,
                    'opdlain' => $opdlain,
                    'latopd_lain' => $latopd_lain,
                    'lotopd_lain' => $lotopd_lain,
                    'perangkat' => $perangkat

                ]);
            }
        }

        // return $dapeg->tpt_lain;
        return view('prosesAbsen.wfoMasuk', [
            'unor' => $unor,
            'tpt_lain' => $tptLain,
            'lat' => $lat,
            'lot' => $lot,
            'latlain' => $latlain,
            'lotlain' => $lotlain,
            'opdlain' => $opdlain,
            'latopd_lain' => $latopd_lain,
            'lotopd_lain' => $lotopd_lain,
            'perangkat' => $perangkat,
            'jarak' => $jarak,
            'apel_pagi' => false


        ]);
    }

    public function wfoApel(Request $request)
    {
        // Deteksi device mobile
        $mobile = session()->get('mobile');
        if (!$mobile) {
            return back()->with('gagal', 'fitur ini hanya bisa diakses melalui device mobile!');
        }

        // Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        //do something with this information
        $perangkat = 'android';

        if ($iPod || $iPhone || $iPad) {
            $perangkat = 'iphone';
            //browser reported as an iPhone/iPod touch -- do something here
        }
        $db = $this->db;
        $exclude = cache::remember('exclude', now()->addMonths(1), function () use ($db) {
            return $db->table('exclude')->get();
        });
        if ($exclude->where('nip', $this->nip)->first()) {
            $perangkat = 'iphone';
        }

        // deteksi tanggal libur
        $db = $this->db;
        $liburTahun = cache::remember('libur' . $this->tahun, now()->addMonths(3), function () use ($db) {
            return $db->table('libur')->where('tahun', $this->tahun)->get();
        });
        $libur = $liburTahun->where('bulan', $this->bulan)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();
        if (in_array($this->tanggal, $libur)) {
            return back()->with('fail', 'hari ini libur!');
        }
        

        //deteksi jam input masuk
        $sekarang = now()->translatedFormat('H:i:s');
        $hms = explode(":", $sekarang);
        $jam_apel = ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
        if($jam_apel < 7.25 || $jam_apel > 7.75){
          return back()->with('gagal', 'anda mengakses diluar jam apel pagi!');
        }
        


        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $unor = $dapeg->unit_organisasi;
        $kode_unit = $dapeg->kode_unit;
        $tptLain = $dapeg->tpt_lain;
        $kode_tpt_lain = $dapeg->kode_tpt_lain;
        $kode_opd_lain = $dapeg->kode_opd_lain;
        $db = DB::connection('eabsensi_opd');

        $datorg = cache::remember($kode_unit, now()->addMonths(3), function () use ($db, $kode_unit) {

            return $db->table('tb_pd')->where('kode_unit', $kode_unit)->get();
        });


        $dato = $datorg->where('tpt_lain', '')->first();

        $lat = $dato->lat;
        $lot = $dato->lot;
        $latlain = $lat;
        $lotlain = $lot;
        if ($tptLain != '') {
            $datlain = $datorg->where('kode_tpt_lain', $kode_tpt_lain)->first();
            $latlain = $datlain->lat;
            $lotlain = $datlain->lot;
        }
        if ($kode_opd_lain !== '') {
            $opdlain = $db->table('tb_pd')->where('kode_pd', $kode_opd_lain)->first();
            $latopd_lain = $opdlain->lat;
            $lotopd_lain = $opdlain->lot;
        } else {
            $opdlain = false;
            $latopd_lain = false;
            $lotopd_lain = false;
        }

        $cekPejabat = $db->table('pejabats')->where('nip', $nip)->first();
        $jarak = $db->table('jartos')->where('kode_unit', $kode_unit)->pluck('lokasi')->first();

        // if ($cekPejabat) {
        //     if ($cekPejabat->level == 1) {
        //         return view('prosesAbsen.wfoMasukKhusus', [
        //             'unor' => $unor,
        //             'tpt_lain' => $tptLain,
        //             'lat' => $lat,
        //             'lot' => $lot,
        //             'latlain' => $latlain,
        //             'lotlain' => $lotlain,
        //             'opdlain' => $opdlain,
        //             'latopd_lain' => $latopd_lain,
        //             'lotopd_lain' => $lotopd_lain,
        //             'perangkat' => $perangkat,

        //         ]);
        //     } elseif ($cekPejabat->level == 2) {
        //         return view('prosesAbsen.wfoMasukKhusus2', [
        //             'unor' => $unor,
        //             'tpt_lain' => $tptLain,
        //             'lat' => $lat,
        //             'lot' => $lot,
        //             'latlain' => $latlain,
        //             'lotlain' => $lotlain,
        //             'opdlain' => $opdlain,
        //             'latopd_lain' => $latopd_lain,
        //             'lotopd_lain' => $lotopd_lain,
        //             'perangkat' => $perangkat

        //         ]);
        //     }
        // }

        // return $dapeg->tpt_lain;
        return view('prosesAbsen.wfoMasuk', [
            'unor' => $unor,
            'tpt_lain' => $tptLain,
            'lat' => $lat,
            'lot' => $lot,
            'latlain' => $latlain,
            'lotlain' => $lotlain,
            'opdlain' => $opdlain,
            'latopd_lain' => $latopd_lain,
            'lotopd_lain' => $lotopd_lain,
            'perangkat' => $perangkat,
            'jarak' => $jarak,
            'apel_pagi' => true

        ]);
    }


    public function wfoMasukNon(Request $request)
    {

        // Deteksi device mobile
        $mobile = session()->get('mobile');
        if (!$mobile) {
            return back()->with('gagal', 'fitur ini hanya bisa diakses melalui device mobile!');
        }

        // Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        //do something with this information
        $perangkat = 'android';

        if ($iPod || $iPhone || $iPad) {
            $perangkat = 'iphone';
            //browser reported as an iPhone/iPod touch -- do something here
        }
        $db = $this->db;
        $exclude = cache::remember('exclude', now()->addMonths(3), function () use ($db) {
            return $db->table('exclude')->get();
        });
        if ($exclude->where('nip', $this->nip)->first()) {
            $perangkat = 'iphone';
        }

        // deteksi tanggal libur
        $liburTahun = cache::remember('libur' . $this->tahun, now()->addMonths(3), function () use ($db) {
            return $db->table('libur')->where('tahun', $this->tahun)->get();
        });
        $libur = $liburTahun->where('bulan', $this->bulan)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();
        if (in_array($this->tanggal, $libur)) {
            return back()->with('fail', 'hari ini libur!');
        }

        // deteksi jam input masuk
        $sekarang = now()->translatedFormat('H');
        if ($sekarang < 7 || $sekarang > 12) {
            return back()->with('gagal', 'anda mengakses diluar jam masuk!');
        }

        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $unor = $dapeg->unit_organisasi;
        $kode_unit = $dapeg->kode_unit;
        $tptLain = $dapeg->tpt_lain;
        $kode_tpt_lain = $dapeg->kode_tpt_lain;
        $kode_opd_lain = $dapeg->kode_opd_lain;
        $db = DB::connection('eabsensi_opd');

        $datorg = cache::remember($kode_unit, now()->addMonths(3), function () use ($db, $kode_unit) {

            return $db->table('tb_pd')->where('kode_unit', $kode_unit)->get();
        });

        $jarak = $db->table('jartos')->where('kode_unit', $kode_unit)->pluck('lokasi')->first();

        $dato = $datorg->where('tpt_lain', '')->first();

        $lat = $dato->lat;
        $lot = $dato->lot;
        $latlain = $lat;
        $lotlain = $lot;
        if ($tptLain != '') {
            $datlain = $datorg->where('kode_tpt_lain', $kode_tpt_lain)->first();
            $latlain = $datlain->lat;
            $lotlain = $datlain->lot;
        }
        if ($kode_opd_lain !== '') {
            $opdlain = $db->table('tb_pd')->where('kode_pd', $kode_opd_lain)->first();
            $latopd_lain = $opdlain->lat;
            $lotopd_lain = $opdlain->lot;
        } else {
            $opdlain = false;
            $latopd_lain = false;
            $lotopd_lain = false;
        }


        // return $dapeg->tpt_lain;
        return view('prosesAbsen.wfoMasukNon', [
            'unor' => $unor,
            'tpt_lain' => $tptLain,
            'lat' => $lat,
            'lot' => $lot,
            'latlain' => $latlain,
            'lotlain' => $lotlain,
            'opdlain' => $opdlain,
            'latopd_lain' => $latopd_lain,
            'lotopd_lain' => $lotopd_lain,
            'perangkat' => $perangkat,
            'jarak' => $jarak

        ]);
    }


    public function uploadWfoMasuk(Request $request)
    {
       
       if($request->apel_pagi){
        return $this->uploadWfoApel($request);
       }
        $cekAbsen = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->first();


        if ($cekAbsen) {
          return 2;
        }

        $nip = $this->nip;
        $foto = $request->fotoMasuk;
        $foto_b = $request->fotoMasuk_b;

        define('UPLOAD_WFO', "uploads/$this->tahun/$this->bulan/");
        $foto = str_replace('data:image/jpeg;base64,', '', $foto);
        $foto = str_replace(' ', '+', $foto);
        $data = base64_decode($foto);
        $file = UPLOAD_WFO . $nip . '-M-' . uniqid() . '.jpeg';
        $success = Storage::put($file, $data);

        $foto_b = str_replace('data:image/jpeg;base64,', '', $foto_b);
        $foto_b = str_replace(' ', '+', $foto_b);
        $data_b = base64_decode($foto_b);
        $file_b = UPLOAD_WFO . $nip . '-M-' . uniqid() . '.jpeg';
        $success_b = Storage::put($file_b, $data_b);


        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $db = DB::connection('eabsensi_opd');
        $jamKerja = Cache::remember('jam_kerja_opd', now()->addMonths(1), function () use ($db) {
            return  $db->table('jam_kerja_opds')->get();
        });
        $jamMasuk = $jamKerja->where('hari', $this->hari)->pluck('masuk')->first();

        $waktu = now()->translatedFormat('H:i:s');
        if ($request->waktu) {
            $waktu = $request->waktu;
        }
        $tglskrg = Carbon::now()->translatedFormat('d-m-Y,' . $waktu);
        $tglbanding = now()->translatedFormat('d-m-Y') . ',' . $jamMasuk;
        $tglhasil = Carbon::create($tglbanding)->translatedFormat('d-m-Y, H:i:s');
        $selwak = strtotime($tglskrg) - strtotime($tglhasil);
        $selisih = floor($selwak / 60);
        if ($selisih >= 1 && $selisih < 31) {
            $pengurangan = 0.5;
            $skor = 75;
        } elseif ($selisih >= 31 && $selisih < 61) {
            $pengurangan = 1;
            $skor = 50;
        } elseif ($selisih >= 61 && $selisih < 91) {
            $pengurangan = 1.25;
            $skor = 38;
        } elseif ($selisih >= 91) {
            $pengurangan = 1.5;
            $skor = 25;
        } elseif ($selisih <= 0) {
            $pengurangan = 0;
            $skor = 100;
        }

        if($dapeg->pangkat=='Non-PNS'){
            $pengurangan = 0;
        }

        
        $hms = explode(":", $waktu);
        $jam_sekarang = ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
        if($jam_sekarang > 7.75){
            $apel = 'tanpa keterangan';
        }else{
            $apel = NULL;
        }


        $data = [
            'nama' => $dapeg->nama,
            'nip' => $nip,
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'waktu' => $waktu,
            'apel_pagi' => $apel,
            'keterangan' => 'hadir',
            'keterangan_p' => 'belum absen',
            'selisih' => $selisih,
            'pengurangan' => $pengurangan,
            'foto' => $file,
            'foto_b' => $file_b,
            'konfirmasi' => 'un_confirmed',
            'pangkat' => $dapeg->pangkat,
            'jabatan' => $dapeg->jabatan,
            'jenis_jbt' => $dapeg->jenis_jbt,
            'tpp' => $dapeg->tpp,
            'tmt_absen' => $dapeg->tmt_absen,
            'tampil' => '1',
            'person' => $nip,
            'norut' => $dapeg->norut,
            'skor' => $skor

        ];

        $insert = Absen::create($data);
        
        if ($insert) {
            echo 1;
        } else {
            echo 0;
        }
    }

     public function uploadWfoApel($request)
    {
        
      $cekAbsen = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->first();
        if($cekAbsen){
            $cekApel = $cekAbsen->apel_pagi;
        }else{
            $cekApel = false;
        }

        if($cekApel){
            return 2;
        }

        $nip = $this->nip;
        $foto = $request->fotoMasuk;
        $foto_b = $request->fotoMasuk_b;

        define('UPLOAD_WFO', "uploads/$this->tahun/$this->bulan/");
        $foto = str_replace('data:image/jpeg;base64,', '', $foto);
        $foto = str_replace(' ', '+', $foto);
        $data = base64_decode($foto);
        $file = UPLOAD_WFO . $nip . '-M-' . uniqid() . '.jpeg';
        $success = Storage::put($file, $data);

        $foto_b = str_replace('data:image/jpeg;base64,', '', $foto_b);
        $foto_b = str_replace(' ', '+', $foto_b);
        $data_b = base64_decode($foto_b);
        $file_b = UPLOAD_WFO . $nip . '-M-' . uniqid() . '.jpeg';
        $success_b = Storage::put($file_b, $data_b);


        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $db = DB::connection('eabsensi_opd');
        $jamKerja = Cache::remember('jam_kerja_opd', now()->addMonths(1), function () use ($db) {
            return  $db->table('jam_kerja_opds')->get();
        });
        $jamMasuk = $jamKerja->where('hari', $this->hari)->pluck('masuk')->first();

        $waktu = now()->translatedFormat('H:i:s');
        if ($request->waktu) {
            $waktu = $request->waktu;
        }
        //deteksi jam input masuk
        $hms = explode(":", $waktu);
        $jam_sekarang = ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
        if($jam_sekarang > 7.5){
            $waktu = '07:30:00';
        }
       
        $tglskrg = Carbon::now()->translatedFormat('d-m-Y,' . $waktu);
        $tglbanding = now()->translatedFormat('d-m-Y') . ',' . $jamMasuk;
        $tglhasil = Carbon::create($tglbanding)->translatedFormat('d-m-Y, H:i:s');
        $selwak = strtotime($tglskrg) - strtotime($tglhasil);
        $selisih = floor($selwak / 60);
        if ($selisih >= 1 && $selisih < 31) {
            $pengurangan = 0.5;
            $skor = 75;
        } elseif ($selisih >= 31 && $selisih < 61) {
            $pengurangan = 1;
            $skor = 50;
        } elseif ($selisih >= 61 && $selisih < 91) {
            $pengurangan = 1.25;
            $skor = 38;
        } elseif ($selisih >= 91) {
            $pengurangan = 1.5;
            $skor = 25;
        } elseif ($selisih <= 0) {
            $pengurangan = 0;
            $skor = 100;
        }

        if($dapeg->pangkat=='Non-PNS'){
            $pengurangan = 0;
        }


        $data = [
            'nama' => $dapeg->nama,
            'nip' => $nip,
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'waktu' => $waktu,
            'apel_pagi' => 'hadir',
            'keterangan' => 'hadir',
            'keterangan_p' => 'belum absen',
            'selisih' => $selisih,
            'pengurangan' => $pengurangan,
            'foto' => $file,
            'foto_b' => $file_b,
            'konfirmasi' => 'un_confirmed',
            'pangkat' => $dapeg->pangkat,
            'jabatan' => $dapeg->jabatan,
            'jenis_jbt' => $dapeg->jenis_jbt,
            'tpp' => $dapeg->tpp,
            'tmt_absen' => $dapeg->tmt_absen,
            'tampil' => '1',
            'person' => $nip,
            'norut' => $dapeg->norut,
            'skor' => $skor

        ];

        if($cekAbsen){
           $insert = Absen::where('id', $cekAbsen->id)->update([
                'apel_pagi' => 'hadir',
                'waktu' => $waktu,
                'selisih' => $selisih,
                'pengurangan' => $pengurangan,
                'foto' => $file,
                'foto_b' => $file_b,
            ]);
        }else{

             $insert = Absen::create($data);
        }

            if ($insert) {
                echo 1;
            } else {
                echo 0;
            }
    }

    public function uploadWfoMasukKhusus(Request $request)
    {

        $cekAbsen = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->first();
        if ($cekAbsen) {
            return redirect('/rekapPerorangan')->with('fail', 'sudah absen masuk di tanggal ini!');
        }

        $nip = $this->nip;
        if ($request->file('fotoGallery')) {
            $ext = $request->fotoGallery->extension();
            $folder = "uploads/$this->tahun/$this->bulan/";
            $file = $request->file('fotoGallery')->storeAs($folder, $nip . '-M-' . uniqid() . "." . $ext);
        };
        if ($request->file('fotoGallery_b')) {
            $ext = $request->fotoGallery_b->extension();
            $folder = "uploads/$this->tahun/$this->bulan/";
            $file_b = $request->file('fotoGallery_b')->storeAs($folder, $nip . '-M-' . uniqid() . "." . $ext);
        };


        $foto = $file;
        $foto_b = $file_b;


        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $db = DB::connection('eabsensi_opd');
        $jamKerja = Cache::remember('jam_kerja_opd', now()->addMonths(1), function () use ($db) {
            return  $db->table('jam_kerja_opds')->get();
        });
        $jamMasuk = $jamKerja->where('hari', $this->hari)->pluck('masuk')->first();

        $waktu = now()->translatedFormat('H:i:s');
        if ($request->waktu) {
            $waktu = $request->waktu;
        }

        $tglskrg = Carbon::now()->translatedFormat('d-m-Y,' . $waktu);
        $tglbanding = now()->translatedFormat('d-m-Y') . ',' . $jamMasuk;
        $tglhasil = Carbon::create($tglbanding)->translatedFormat('d-m-Y, H:i:s');
        $selwak = strtotime($tglskrg) - strtotime($tglhasil);
        $selisih = floor($selwak / 60);
        if ($selisih >= 1 && $selisih < 31) {
            $pengurangan = 0.5;
            $skor = 75;
        } elseif ($selisih >= 31 && $selisih < 61) {
            $pengurangan = 1;
            $skor = 50;
        } elseif ($selisih >= 61 && $selisih < 91) {
            $pengurangan = 1.25;
            $skor = 38;
        } elseif ($selisih >= 91) {
            $pengurangan = 1.5;
            $skor = 25;
        } elseif ($selisih <= 0) {
            $pengurangan = 0;
            $skor = 100;
        }



        $data = [
            'nama' => $dapeg->nama,
            'nip' => $nip,
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'waktu' => $waktu,
            'keterangan' => 'hadir',
            'keterangan_p' => 'belum absen',
            'selisih' => $selisih,
            'pengurangan' => $pengurangan,
            'foto' => $foto,
            'foto_b' => $foto_b,
            'konfirmasi' => 'un_confirmed',
            'pangkat' => $dapeg->pangkat,
            'jabatan' => $dapeg->jabatan,
            'jenis_jbt' => $dapeg->jenis_jbt,
            'tpp' => $dapeg->tpp,
            'tmt_absen' => $dapeg->tmt_absen,
            'tampil' => '1',
            'person' => $nip,
            'norut' => $dapeg->norut,
            'skor' => $skor

        ];
        $insert = Absen::create($data);
        if ($insert) {
            return redirect('/rekapPerorangan')->with('success', 'berhasil absen masuk');
        } else {
            return redirect('/rekapPerorangan')->with('fail', 'gagal absen masuk!');
        }
    }

    public function uploadWfoPulangKhusus(Request $request)
    {
        $cekAbsen = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->first();
        if (!$cekAbsen) {
            return redirect('/absensi/pilihKeterangan')->with('gagal', 'absen masuk terlebih dahulu!');
        }
        if ($cekAbsen && $cekAbsen->keterangan_p !== 'belum absen') {
            return redirect('/rekapPerorangan')->with('fail', 'sudah ada absen pulang!');
        }

        $nip = $this->nip;
        if ($request->file('fotoGallery')) {
            $ext = $request->fotoGallery->extension();
            $folder = "uploads/$this->tahun/$this->bulan/";
            $file = $request->file('fotoGallery')->storeAs($folder, $nip . '-P-' . uniqid() . "." . $ext);
        };
        if ($request->file('fotoGallery_b')) {
            $ext = $request->fotoGallery_b->extension();
            $folder = "uploads/$this->tahun/$this->bulan/";
            $file_b = $request->file('fotoGallery_b')->storeAs($folder, $nip . '-P-' . uniqid() . "." . $ext);
        };


        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $db = DB::connection('eabsensi_opd');
        $jamkerja = cache::remember('jam_kerja_opd', now()->addMonths(1), function () use ($db) {
            return  $db->table('jam_kerja_opds')->get();
        });
        $jamPulang = $jamkerja->where('hari', $this->hari)->pluck('pulang')->first();
        $waktu = now()->translatedFormat('H:i:s');
        if ($request->pulang) {
            $waktu = $request->pulang;
        }
        $tglskrg = Carbon::now()->translatedFormat('d-m-Y,' . $waktu);
        $tglbanding = now()->translatedFormat('d-m-Y') . ',' . $jamPulang;
        $tglhasil = Carbon::create($tglbanding)->translatedFormat('d-m-Y, H:i:s');
        $selwak = strtotime($tglskrg) - strtotime($tglhasil);
        $selisih_P = floor($selwak / 60);
        if ($selisih_P <= -1 && $selisih_P > -31) {
            $skor_p = 75;
            $pengurangan_P = 0.5;
        } elseif ($selisih_P <= -31 && $selisih_P > -61) {
            $pengurangan_P = 1;
            $skor_p = 50;
        } elseif ($selisih_P <= -61 && $selisih_P > -91) {
            $pengurangan_P = 1.25;
            $skor_p = 38;
        } elseif ($selisih_P <= -91) {
            $pengurangan_P = 1.5;
            $skor_p = 25;
        } elseif ($selisih_P >= 0) {
            $pengurangan_P = 0;
            $skor_p = 100;
        }


        $data = [
            'pulang' => $waktu,
            'keterangan_p' => 'hadir',
            'selisih_p' => $selisih_P,
            'pengurangan_p' => $pengurangan_P,
            'foto_p' => $file,
            'foto_pb' => $file_b,
            'konfirmasi_p' => 'un_confirmed',
            'skor_p' => $skor_p
        ];
        $update = Absen::where('id', $cekAbsen->id)->update($data);
        if ($update) {
            return redirect('/rekapPerorangan')->with('success', 'berhasil absen pulang');
        } else {
            return redirect('/rekapPerorangan')->with('fail', 'gagal absen pulang!');
        }
    }


    public function uploadWfoMasukNon(Request $request)
    {
        $cekAbsen = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->first();
        if ($cekAbsen) {
            return back()->with('fail', 'sudah ada absen sebelumnya!');
        }
        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $db = DB::connection('eabsensi_opd');
        $jamkerja = cache::remember('jam_kerja_opd', now()->addMonths(1), function () use ($db) {
            return  $db->table('jam_kerja_opds')->get();
        });
        $jamMasuk = $jamkerja->where('hari', $this->hari)->pluck('masuk')->first();
        $waktu = now()->translatedFormat('H:i:s');
        $tglskrg = Carbon::now()->translatedFormat('d-m-Y, H:i:s');
        $tglbanding = now()->translatedFormat('d-m-Y') . ',' . $jamMasuk;
        $tglhasil = Carbon::create($tglbanding)->translatedFormat('d-m-Y, H:i:s');
        $selwak = strtotime($tglskrg) - strtotime($tglhasil);
        $selisih = floor($selwak / 60);
        if ($selisih >= 1 && $selisih < 31) {
            $pengurangan = 0.5;
        } elseif ($selisih >= 31 && $selisih < 61) {
            $pengurangan = 1;
        } elseif ($selisih >= 61 && $selisih < 91) {
            $pengurangan = 1.25;
        } elseif ($selisih >= 91) {
            $pengurangan = 1.5;
        } elseif ($selisih <= 0) {
            $pengurangan = 0;
        }

        $data = [
            'nama' => $dapeg->nama,
            'nip' => $nip,
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'waktu' => $waktu,
            'keterangan' => 'hadir',
            'keterangan_p' => 'belum absen',
            'selisih' => $selisih,
            'pengurangan' => $pengurangan,
            'konfirmasi' => 'un_confirmed',
            'pangkat' => $dapeg->pangkat,
            'jabatan' => $dapeg->jabatan,
            'jenis_jbt' => $dapeg->jenis_jbt,
            'tpp' => $dapeg->tpp,
            'tmt_absen' => $dapeg->tmt_absen,
            'tampil' => '1',
            'person' => $nip,
            'norut' => $dapeg->norut,

        ];
        $insert = Absen::create($data);
        if ($insert) {
            return back()->with('success', 'berhasil absen masuk');
        } else {
            return back()->with('fail', 'gagal absen masuk');
        }
    }


    public function wfoPulang(Request $request)
    {

        // Deteksi device mobile
        $mobile = session()->get('mobile');
        if (!$mobile) {
            return back()->with('gagal', 'fitur ini hanya bisa diakses melalui device mobile!');
        }

        // Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        //do something with this information
        $perangkat = 'android';

        if ($iPod || $iPhone || $iPad) {
            $perangkat = 'iphone';
            //browser reported as an iPhone/iPod touch -- do something here
        }
        $db = $this->db;
        $exclude = cache::remember('exclude', now()->addMonths(3), function () use ($db) {
            return $db->table('exclude')->get();
        });
        if ($exclude->where('nip', $this->nip)->first()) {
            $perangkat = 'iphone';
        }

        // deteksi tanggal libur
        $liburTahun = cache::remember('libur' . $this->tahun, now()->addMonths(3), function () use ($db) {
            return $db->table('libur')->where('tahun', $this->tahun)->get();
        });
        $libur = $liburTahun->where('bulan', $this->bulan)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();
        if (in_array($this->tanggal, $libur)) {
            return back()->with('fail', 'hari ini libur!');
        }

        // deteksi jam input masuk
        $sekarang = now()->translatedFormat('H');
        if ($sekarang < 13 || $sekarang > 21) {
            return back()->with('gagal', 'anda mengakses diluar jam pulang!');
        }


        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $unor = $dapeg->unit_organisasi;
        $kode_unit = $dapeg->kode_unit;
        $tptLain = $dapeg->tpt_lain;
        $kode_tpt_lain = $dapeg->kode_tpt_lain;
        $kode_opd_lain = $dapeg->kode_opd_lain;
        $db = DB::connection('eabsensi_opd');

        $datorg = cache::remember($kode_unit, now()->addMonths(3), function () use ($db, $kode_unit) {

            return $db->table('tb_pd')->where('kode_unit', $kode_unit)->get();
        });


        $dato = $datorg->where('tpt_lain', '')->first();

        $lat = $dato->lat;
        $lot = $dato->lot;
        $latlain = $lat;
        $lotlain = $lot;
        if ($tptLain != '') {
            $datlain = $datorg->where('kode_tpt_lain', $kode_tpt_lain)->first();
            $latlain = $datlain->lat;
            $lotlain = $datlain->lot;
        }
        if ($kode_opd_lain !== '') {
            $opdlain = $db->table('tb_pd')->where('kode_pd', $kode_opd_lain)->first();
            $latopd_lain = $opdlain->lat;
            $lotopd_lain = $opdlain->lot;
        } else {
            $opdlain = false;
            $latopd_lain = false;
            $lotopd_lain = false;
        }

        $cekPejabat = $db->table('pejabats')->where('nip', $nip)->first();
        $jarak_p = $db->table('jartos')->where('kode_unit', $kode_unit)->pluck('lokasi_p')->first();

        if ($cekPejabat) {
            if ($cekPejabat->level == 1) {
                return view('prosesAbsen.wfoPulangKhusus', [
                    'unor' => $unor,
                    'tpt_lain' => $tptLain,
                    'lat' => $lat,
                    'lot' => $lot,
                    'latlain' => $latlain,
                    'lotlain' => $lotlain,
                    'opdlain' => $opdlain,
                    'latopd_lain' => $latopd_lain,
                    'lotopd_lain' => $lotopd_lain,
                    'perangkat' => $perangkat

                ]);
            } elseif ($cekPejabat->level == 2) {
                return view('prosesAbsen.wfoPulangKhusus2', [
                    'unor' => $unor,
                    'tpt_lain' => $tptLain,
                    'lat' => $lat,
                    'lot' => $lot,
                    'latlain' => $latlain,
                    'lotlain' => $lotlain,
                    'opdlain' => $opdlain,
                    'latopd_lain' => $latopd_lain,
                    'lotopd_lain' => $lotopd_lain,
                    'perangkat' => $perangkat

                ]);
            }
        }

        // return $dapeg->tpt_lain;
        return view('prosesAbsen.wfoPulang', [
            'unor' => $unor,
            'tpt_lain' => $tptLain,
            'lat' => $lat,
            'lot' => $lot,
            'latlain' => $latlain,
            'lotlain' => $lotlain,
            'opdlain' => $opdlain,
            'latopd_lain' => $latopd_lain,
            'lotopd_lain' => $lotopd_lain,
            'perangkat' => $perangkat,
            'jarak_p' => $jarak_p


        ]);
    }



    public function uploadWfoPulang(Request $request)
    {
        $cekAbsen = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->first();
        if (!$cekAbsen) {
            return redirect('/absensi/pilihKeterangan')->with('gagal', 'absen masuk terlebih dahulu!');
        }
        if ($cekAbsen && $cekAbsen->keterangan_p !== 'belum absen') {
            return 2;
        }

        $nip = $this->nip;
        $foto = $request->fotoPulang;
        $foto_b = $request->fotoPulang_b;

        define('UPLOAD_WFO_P', "uploads/$this->tahun/$this->bulan/");
        $foto = str_replace('data:image/jpeg;base64,', '', $foto);
        $foto = str_replace(' ', '+', $foto);
        $data = base64_decode($foto);
        $file = UPLOAD_WFO_P . $nip . '-P-' . substr(md5(microtime()), 0, 10) . '.jpeg';
        $success = Storage::put($file, $data);

        $foto_b = str_replace('data:image/jpeg;base64,', '', $foto_b);
        $foto_b = str_replace(' ', '+', $foto_b);
        $data_b = base64_decode($foto_b);
        $file_b = UPLOAD_WFO_P . $nip . '-Pb-' . substr(md5(microtime()), 0, 10) . '.jpeg';
        $success_b = Storage::put($file_b, $data_b);

        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $db = DB::connection('eabsensi_opd');
        $jamkerja = cache::remember('jam_kerja_opd', now()->addMonths(1), function () use ($db) {
            return  $db->table('jam_kerja_opds')->get();
        });
        $jamPulang = $jamkerja->where('hari', $this->hari)->pluck('pulang')->first();
        $waktu = now()->translatedFormat('H:i:s');
        if ($request->pulang) {
            $waktu = $request->pulang;
        }
        $tglskrg = Carbon::now()->translatedFormat('d-m-Y,' . $waktu);
        $tglbanding = now()->translatedFormat('d-m-Y') . ',' . $jamPulang;
        $tglhasil = Carbon::create($tglbanding)->translatedFormat('d-m-Y, H:i:s');
        $selwak = strtotime($tglskrg) - strtotime($tglhasil);
        $selisih_P = floor($selwak / 60);
        if ($selisih_P <= -1 && $selisih_P > -31) {
            $skor_p = 75;
            $pengurangan_P = 0.5;
        } elseif ($selisih_P <= -31 && $selisih_P > -61) {
            $pengurangan_P = 1;
            $skor_p = 50;
        } elseif ($selisih_P <= -61 && $selisih_P > -91) {
            $pengurangan_P = 1.25;
            $skor_p = 38;
        } elseif ($selisih_P <= -91) {
            $pengurangan_P = 1.5;
            $skor_p = 25;
        } elseif ($selisih_P >= 0) {
            $pengurangan_P = 0;
            $skor_p = 100;
        }

        if($dapeg->pangkat == 'Non-PNS'){
            $pengurangan_P = 0;
        }

        $data = [
            'pulang' => $waktu,
            'keterangan_p' => 'hadir',
            'selisih_p' => $selisih_P,
            'pengurangan_p' => $pengurangan_P,
            'foto_p' => $file,
            'foto_pb' => $file_b,
            'konfirmasi_p' => 'un_confirmed',
            'skor_p' => $skor_p
        ];
        $update = Absen::where('id', $cekAbsen->id)->update($data);
        if ($update) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function wfoPulangNon(Request $request)
    {

        // Deteksi device mobile
        $mobile = session()->get('mobile');
        if (!$mobile) {
            return back()->with('gagal', 'fitur ini hanya bisa diakses melalui device mobile!');
        }

        // Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        //do something with this information
        $perangkat = 'android';

        if ($iPod || $iPhone || $iPad) {
            $perangkat = 'iphone';
            //browser reported as an iPhone/iPod touch -- do something here
        }
        $db = $this->db;
        $exclude = cache::remember('exclude', now()->addMonths(3), function () use ($db) {
            return $db->table('exclude')->get();
        });
        if ($exclude->where('nip', $this->nip)->first()) {
            $perangkat = 'iphone';
        }

        // deteksi tanggal libur
        $liburTahun = cache::remember('libur' . $this->tahun, now()->addMonths(3), function () use ($db) {
            return $db->table('libur')->where('tahun', $this->tahun)->get();
        });
        $libur = $liburTahun->where('bulan', $this->bulan)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();
        if (in_array($this->tanggal, $libur)) {
            return back()->with('fail', 'hari ini libur!');
        }

        // deteksi jam input masuk
        $sekarang = now()->translatedFormat('H');
        if ($sekarang < 13 && $sekarang > 19) {
            return back()->with('gagal', 'anda mengakses diluar jam pulang!');
        }


        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $unor = $dapeg->unit_organisasi;
        $kode_unit = $dapeg->kode_unit;
        $tptLain = $dapeg->tpt_lain;
        $kode_tpt_lain = $dapeg->kode_tpt_lain;
        $kode_opd_lain = $dapeg->kode_opd_lain;
        $db = DB::connection('eabsensi_opd');

        $datorg = cache::remember($kode_unit, now()->addMonths(3), function () use ($db, $kode_unit) {

            return $db->table('tb_pd')->where('kode_unit', $kode_unit)->get();
        });


        $dato = $datorg->where('tpt_lain', '')->first();
        $jarak_p = $db->table('jartos')->where('kode_unit', $kode_unit)->pluck('lokasi_p')->first();

        $lat = $dato->lat;
        $lot = $dato->lot;
        $latlain = $lat;
        $lotlain = $lot;
        if ($tptLain != '') {
            $datlain = $datorg->where('kode_tpt_lain', $kode_tpt_lain)->first();
            $latlain = $datlain->lat;
            $lotlain = $datlain->lot;
        }
        if ($kode_opd_lain !== '') {
            $opdlain = $db->table('tb_pd')->where('kode_pd', $kode_opd_lain)->first();
            $latopd_lain = $opdlain->lat;
            $lotopd_lain = $opdlain->lot;
        } else {
            $opdlain = false;
            $latopd_lain = false;
            $lotopd_lain = false;
        }

        // return $dapeg->tpt_lain;
        return view('prosesAbsen.wfoPulangNon', [
            'unor' => $unor,
            'tpt_lain' => $tptLain,
            'lat' => $lat,
            'lot' => $lot,
            'latlain' => $latlain,
            'lotlain' => $lotlain,
            'opdlain' => $opdlain,
            'latopd_lain' => $latopd_lain,
            'lotopd_lain' => $lotopd_lain,
            'perangkat' => $perangkat,
            'jarak_p' => $jarak_p

        ]);
    }

    public function uploadWfoPulangNon(Request $request)
    {
        $cekAbsen = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->first();
        if ($cekAbsen && $cekAbsen->keterangan_p !== 'belum absen') {
            return back()->with('fail', 'sudah ada absen sebelumnya!');
        }
        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $db = DB::connection('eabsensi_opd');
        $jamkerja = cache::remember('jam_kerja_opd', now()->addMonths(1), function () use ($db) {
            return  $db->table('jam_kerja_opds')->get();
        });
        $jamPulang = $jamkerja->where('hari', $this->hari)->pluck('pulang')->first();
        $waktu = now()->translatedFormat('H:i:s');
        $tglskrg = Carbon::now()->translatedFormat('d-m-Y, H:i:s');
        $tglbanding = now()->translatedFormat('d-m-Y') . ',' . $jamPulang;
        $tglhasil = Carbon::create($tglbanding)->translatedFormat('d-m-Y, H:i:s');
        $selwak = strtotime($tglskrg) - strtotime($tglhasil);
        $selisih_P = floor($selwak / 60);
        if ($selisih_P <= -1 && $selisih_P > -31) {
            $pengurangan_P = 0.5;
        } elseif ($selisih_P <= -31 && $selisih_P > -61) {
            $pengurangan_P = 1;
        } elseif ($selisih_P <= -61 && $selisih_P > -91) {
            $pengurangan_P = 1.25;
        } elseif ($selisih_P <= -91) {
            $pengurangan_P = 1.5;
        } elseif ($selisih_P >= 0) {
            $pengurangan_P = 0;
        }

        $data = [
            'pulang' => $waktu,
            'keterangan_p' => 'hadir',
            'selisih_p' => $selisih_P,
            'pengurangan_p' => $pengurangan_P,
            'konfirmasi_p' => 'un_confirmed',
        ];
        $update = Absen::where('id', $cekAbsen->id)->update($data);
        if ($update) {
            return back()->with('success', 'Berhasil absen pulang');
        } else {
            return back()->with('fail', 'Gagal absen pulang');
        }
    }

    public function uraianPerjadin(Request $request)
    {
        $daftarPegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        return  view('prosesAbsen.uraian_perjadin', [
            'daftarPegawai' => $daftarPegawai,
            'nip' => $this->nip
        ]);
    }

    public function dinasLuar(Request $request)
    {

        $jenisdl = $request->jenisdl;
        $tujuan = $request->tujuan;
        $maksud = $request->maksud;
        $pengikut = $request->inputpengikut;
        $pengikut = substr($pengikut, 0, -1);

        return view('prosesAbsen.dinas_luar', [
            'jenisdl' => $jenisdl,
            'tujuan' => $tujuan,
            'maksud' => $maksud,
            'pengikut' => $pengikut
        ]);
    }



    public function cekLibur($tanggal, $bulan, $tahun)
    {
        $db = $this->db;
        $liburTahun = cache::remember('libur' . $tahun, now()->addMonths(3), function () use ($db, $tahun) {
            return $db->table('libur')->where('tahun', $tahun)->get();
        });

        $libur = $liburTahun->where('tahun', $tahun)->where('bulan', $bulan)->pluck('tanggal')->first();

        $libur = Str::of($libur)->explode(',')->toArray();

        if (in_array($tanggal, $libur)) {
            return true;
        } else {
            return false;
        }
    }

    public function uploadDinasLuar(Request $request)
    {

        $jenisdl = cleaner($request->jenisdl);
        $tujuan = cleaner($request->tujuan);
        $maksud = cleaner($request->maksud);

        $daftarPegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $pelaksana = [$this->nip];
        if ($request->pengikut) {
            $pelaksana = Str::of($request->pengikut . '|' . $this->nip)->explode('|')->toArray();
        }


        $berangkat = Carbon::parse($request->berangkat)->translatedFormat('d-m-Y');
        $kembali = Carbon::create(cleaner($request->kembali));
        $jmlHari = $kembali->diffInDays($berangkat) + 1;
        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });

        $data = [
            // 'nama' => $dapeg->nama,
            // 'nip' => $nip,
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'waktu' => '07:30:00',
            'pulang' => '16:30:00',
            'apel_pagi' => 'dinas luar',
            'keterangan' => 'dinas luar',
            'keterangan_p' => 'dinas luar',
            'selisih' => 0,
            'selisih_p' => 0,
            'pengurangan' => 0,
            'pengurangan_p' => 0,
            'konfirmasi' => 'un_confirmed',
            'konfirmasi_p' => 'un_confirmed',
            // 'pangkat' => $dapeg->pangkat,
            // 'jabatan' => $dapeg->jabatan,
            // 'jenis_jbt' => $dapeg->jenis_jbt,
            // 'tpp' => $dapeg->tpp,
            // 'tmt_absen' => $dapeg->tmt_absen,
            'tampil' => '1',
            'person' => $jenisdl . '|' . $tujuan . '|' . $maksud . '|' . $dapeg->nama,
            // 'norut' => $dapeg->norut,
            'foto' => '',
            'foto_b' => '',
            'foto_p' => '',
            'foto_pb' => '',
            'skor' => 90,
            'skor_p' => 90
        ];


        if ($request->data_uri) {

            define('UPLOAD_DL', "uploads/$this->tahun/$this->bulan/");
            $img = $request->data_uri;
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $dataImg = base64_decode($img);
            $file = UPLOAD_DL . $nip . '-DL-' . uniqid() . '.jpeg';
            $success = Storage::put($file, $dataImg);

            $data['foto'] = $file;
            $data['foto_b'] = $file;
            $data['foto_p'] = $file;
            $data['foto_pb'] = $file;
        }

        if ($request->file('imeg')) {
            $ext = $request->imeg->extension();
            $folder = "uploads/$this->tahun/$this->bulan/";
            $file = $request->file('imeg')->storeAs($folder, $nip . '-DL-' . uniqid() . '.' . $ext);
            $data['foto'] = $file;
            $data['foto_b'] = $file;
            $data['foto_p'] = $file;
            $data['foto_pb'] = $file;
        }

        $skip = [];

        foreach ($pelaksana as $pel) {
            for ($i = 0; $i < $jmlHari; $i++) {

                $dataPegawai = $daftarPegawai->where('nip', $pel)->first();
                $data['tanggal'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('d');
                $data['bulan'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('m');
                $data['tahun'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('Y');
                $data['nama'] = $dataPegawai->nama;
                $data['nip'] = $dataPegawai->nip;
                $data['pangkat'] = $dataPegawai->pangkat;
                $data['jabatan'] = $dataPegawai->jabatan;
                $data['jenis_jbt'] = $dataPegawai->jenis_jbt;
                $data['jenis_jbt'] = $dataPegawai->jenis_jbt;
                $data['tpp'] = $dataPegawai->tpp;
                $data['tmt_absen'] = $dataPegawai->tmt_absen;
                $data['norut'] = $dataPegawai->norut;

                // cek libur
                $cekLibur = $this->cekLibur($data['tanggal'], $data['bulan'], $data['tahun']);

                if (!$cekLibur) {
                    $cek = Absen::where([
                        'nip' => $data['nip'],
                        'tanggal' => $data['tanggal'],
                        'bulan' => $data['bulan'],
                        'tahun' => $data['tahun'],
                        'tampil' => '1'
                    ])->first();


                    if ($cek) {
                        array_push($skip, $data['tanggal']);
                    } else {
                        Absen::create($data);
                    }
                }

                $cekDl = Dinas_luar::where([
                    'nip' => $data['nip'],
                    'tanggal' => $data['tanggal'],
                    'bulan' => $data['bulan'],
                    'tahun' => $data['tahun'],
                    'tampil' => '1'
                ])->first();
                if ($cekDl) {
                    // Dinas_luar::where([
                    //     'nip' => $data['nip'],
                    //     'tanggal' => $data['tanggal'],
                    //     'bulan' => $data['bulan'],
                    //     'tahun' => $data['tahun'],
                    //     'tampil' => '1'
                    // ])->update([
                    //     'nip' => $data['nip'],
                    //     'tanggal' => $data['tanggal'],
                    //     'bulan' => $data['bulan'],
                    //     'tahun' => $data['tahun'],
                    //     'tampil' => '1',
                    //     'jenis' => $jenisdl,
                    //     'tujuan' => $tujuan,
                    //     'maksud' => $maksud

                    // ]);
                } else {
                    Dinas_luar::create([
                        'nip' => $data['nip'],
                        'tanggal' => $data['tanggal'],
                        'bulan' => $data['bulan'],
                        'tahun' => $data['tahun'],
                        'tampil' => '1',
                        'jenis' => $jenisdl,
                        'tujuan' => $tujuan,
                        'maksud' => $maksud
                    ]);
                }
            }
        }
        if (count($skip) > 0) {
            $tglSkip = "";
            foreach ($skip as $sk) {
                $tglSkip .= $sk . ",";
            }
            return redirect('/rekapPerorangan')->with('skipDL', "berhasil absen dinas luar (skip pada tgl $tglSkip");
        } else {
            return redirect('/rekapPerorangan')->with('success', 'berhasil absen dinas luar');
        }
    }

    public function sakit(Request $request)
    {
        return view('prosesAbsen.sakit');
    }

    public function uploadSakit(Request $request)
    {
        $berangkat = Carbon::parse($request->berangkat)->translatedFormat('d-m-Y');
        $kembali = Carbon::create(cleaner($request->kembali));
        $jmlHari = $kembali->diffInDays($berangkat) + 1;
        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $data = [
            'nama' => $dapeg->nama,
            'nip' => $nip,
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'waktu' => '07:30:00',
            'pulang' => '16:30:00',
            'apel_pagi' => 'sakit',
            'keterangan' => 'sakit',
            'keterangan_p' => 'sakit',
            'selisih' => 0,
            'selisih_p' => 0,
            'pengurangan' => 0,
            'pengurangan_p' => 0,
            'konfirmasi' => 'un_confirmed',
            'konfirmasi_p' => 'un_confirmed',
            'pangkat' => $dapeg->pangkat,
            'jabatan' => $dapeg->jabatan,
            'jenis_jbt' => $dapeg->jenis_jbt,
            'tpp' => $dapeg->tpp,
            'tmt_absen' => $dapeg->tmt_absen,
            'tampil' => '1',
            'person' => $nip,
            'norut' => $dapeg->norut,
            'foto' => '',
            'foto_b' => '',
            'foto_p' => '',
            'foto_pb' => '',
            'skor' => 25,
            'skor_p' => 25
        ];

        if ($request->data_uri) {

            define('UPLOAD_SAKIT', "uploads/$this->tahun/$this->bulan/");
            $img = $request->data_uri;
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $dataImg = base64_decode($img);
            $file = UPLOAD_SAKIT . $nip . '-sakit-' . uniqid() . '.jpeg';
            $success = Storage::put($file, $dataImg);

            $data['foto'] = $file;
            $data['foto_b'] = $file;
            $data['foto_p'] = $file;
            $data['foto_pb'] = $file;
        }

        $skip = [];
        for ($i = 0; $i < $jmlHari; $i++) {


            $data['tanggal'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('d');
            $data['bulan'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('m');
            $data['tahun'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('Y');

            // cek libur
            $cekLibur = $this->cekLibur($data['tanggal'], $data['bulan'], $data['tahun']);

            if (!$cekLibur) {
                $cek = Absen::where([
                    'nip' => $data['nip'],
                    'tanggal' => $data['tanggal'],
                    'bulan' => $data['bulan'],
                    'tahun' => $data['tahun'],
                    'tampil' => '1'
                ])->first();


                if ($cek) {
                    array_push($skip, $data['tanggal']);
                } else {
                    Absen::create($data);
                }
            }
        }

        if (count($skip) > 0) {
            $tglSkip = "";
            foreach ($skip as $sk) {
                $tglSkip .= $sk . ",";
            }
            return redirect('/rekapPerorangan')->with('skipDL', "berhasil absen sakit (skip pada tgl $tglSkip");
        } else {
            return redirect('/rekapPerorangan')->with('success', 'berhasil absen sakit');
        }
    }

    public function izin(Request $request)
    {
        return view('prosesAbsen.izin');
    }

    public function uploadIzin(Request $request)
    {
        $berangkat = Carbon::parse($request->berangkat)->translatedFormat('d-m-Y');
        $kembali = Carbon::create(cleaner($request->kembali));
        $jmlHari = $kembali->diffInDays($berangkat) + 1;
        $nip = $this->nip;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $data = [
            'nama' => $dapeg->nama,
            'nip' => $nip,
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'waktu' => '07:30:00',
            'pulang' => '16:30:00',
            'apel_pagi' => 'izin',
            'keterangan' => 'izin',
            'keterangan_p' => 'izin',
            'selisih' => 0,
            'selisih_p' => 0,
            'pengurangan' => 0,
            'pengurangan_p' => 0,
            'konfirmasi' => 'un_confirmed',
            'konfirmasi_p' => 'un_confirmed',
            'pangkat' => $dapeg->pangkat,
            'jabatan' => $dapeg->jabatan,
            'jenis_jbt' => $dapeg->jenis_jbt,
            'tpp' => $dapeg->tpp,
            'tmt_absen' => $dapeg->tmt_absen,
            'tampil' => '1',
            'person' => $nip,
            'norut' => $dapeg->norut,
            'foto' => '',
            'foto_b' => '',
            'foto_p' => '',
            'foto_pb' => '',
            'skor' => 25,
            'skor_p' => 25
        ];

        if ($request->data_uri) {

            define('UPLOAD_IZIN', "uploads/$this->tahun/$this->bulan/");
            $img = $request->data_uri;
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $dataImg = base64_decode($img);
            $file = UPLOAD_IZIN . $nip . '-izin-' . uniqid() . '.jpeg';
            $success = Storage::put($file, $dataImg);

            $data['foto'] = $file;
            $data['foto_b'] = $file;
            $data['foto_p'] = $file;
            $data['foto_pb'] = $file;
        }

        $skip = [];
        for ($i = 0; $i < $jmlHari; $i++) {


            $data['tanggal'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('d');
            $data['bulan'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('m');
            $data['tahun'] = Carbon::make($berangkat)->addDays($i)->translatedFormat('Y');

            // cek libur
            $cekLibur = $this->cekLibur($data['tanggal'], $data['bulan'], $data['tahun']);

            if (!$cekLibur) {
                $cek = Absen::where([
                    'nip' => $data['nip'],
                    'tanggal' => $data['tanggal'],
                    'bulan' => $data['bulan'],
                    'tahun' => $data['tahun'],
                    'tampil' => '1'
                ])->first();


                if ($cek) {
                    array_push($skip, $data['tanggal']);
                } else {
                    Absen::create($data);
                }
            }
        }

        if (count($skip) > 0) {
            $tglSkip = "";
            foreach ($skip as $sk) {
                $tglSkip .= $sk . ",";
            }
            return redirect('/rekapPerorangan')->with('skipDL', "berhasil absen izin (skip pada tgl $tglSkip");
        } else {
            return redirect('/rekapPerorangan')->with('success', 'berhasil absen izin');
        }
    }

    public function cuti(Request $request)
    {
        return view('prosesAbsen.pilihCuti');
    }

    public function prosesCuti(Request $request)
    {
        if ($request->pilcut) {

            $jeniscuti = $request->pilcut;
            $max = "";

            if ($jeniscuti == "cuti1") {

                $ketcut = "Cuti Tahunan";
                $makscuti = 18;
                $hari = "Hari Kerja";
                $pengurangan = 0;
            } elseif ($jeniscuti == "cuti2") {
                $ketcut = "Cuti Besar";
                $makscuti = 90;
                $hari = "Hari Kalender";
                $pengurangan = 3;
                $max = "maksimal 100% untuk 1 bln";
            } elseif ($jeniscuti == "cuti3") {
                $ketcut = "Cuti Sakit";
                $makscuti = 365;
                $pengurangan = 0;
                $hari = "Hari Kalender";
            } elseif ($jeniscuti == "cuti4") {
                $ketcut = "Cuti Melahirkan (ke-1 sd ke-3)";
                $makscuti = 90;
                $pengurangan = 0;
                $hari = "Hari Kalender";
            } elseif ($jeniscuti == "cuti5") {
                $ketcut = "Cuti Melahirkan (ke-4 dst)";
                $makscuti = 90;
                $pengurangan = 3;
                $hari = "Hari Kalender";
            } elseif ($jeniscuti == "cuti6") {
                $ketcut = "Cuti Alasan Penting";
                $makscuti = 45;
                $pengurangan = 0;
                $hari = "Hari Kalender";
            } elseif ($jeniscuti == "cuti7") {
                $ketcut = "Cuti Diluar Tanggungan Negara";
                $makscuti = 30;
                $hari = "Hari Kalender";
                $pengurangan = 3;
                $max = "maksimal 100% untuk 1 bln";
            }
        }

        return view('prosesAbsen.prosesCuti', [
            'ketcut' => $ketcut,
            'makscuti' => $makscuti,
            'hari' => $hari,
            'jeniscuti' => $jeniscuti,
            'pengurangan' => $pengurangan,
            'max' => $max,
        ]);
    }


    public function uploadCuti(Request $request)
    {

        $jeniscuti = $request->jeniscuti;
        $berangkat = $request->berangkat;
        $kembali = $request->kembali;
        $pengurangan = $request->pengurangan;
        if ($pengurangan > 0) {
            $pengurangan = $pengurangan / 2;
        }
        $makscuti = $request->makscuti;
        $lamanya = $request->lamanya;
        $hari = $request->hari;
        $nip = $this->nip;
        if ($request->fgb) {
            define('UPLOAD_CUTI', "uploads/$this->tahun/$this->bulan/");
            $img = $request->fgb;
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $dataImg = base64_decode($img);
            $file = UPLOAD_CUTI . $nip . '-CUTI-' . uniqid() . '.jpeg';
            $success = Storage::put($file, $dataImg);
            $fotoCuti = $file;
        }
        if ($request->file('imeg')) {
            $ext = $request->imeg->extension();
            $folder = "uploads/$this->tahun/$this->bulan/";
            $file = $request->file('imeg')->storeAs($folder, $nip . '-CUTI-' . uniqid() . '.' . $ext);
            $fotoCuti = $file;
        }

        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $data = [
            'nama' => $dapeg->nama,
            'nip' => $nip,
            'waktu' => '07:30:00',
            'pulang' => '16:30:00',
            'apel_pagi' => 'cuti',
            'keterangan' => 'cuti',
            'keterangan_p' => 'cuti',
            'selisih' => 0,
            'selisih_p' => 0,
            'pengurangan' => $pengurangan,
            'pengurangan_p' => $pengurangan,
            'konfirmasi' => 'un_confirmed',
            'konfirmasi_p' => 'un_confirmed',
            'pangkat' => $dapeg->pangkat,
            'jabatan' => $dapeg->jabatan,
            'jenis_jbt' => $dapeg->jenis_jbt,
            'tpp' => $dapeg->tpp,
            'tmt_absen' => $dapeg->tmt_absen,
            'tampil' => '1',
            'person' => $jeniscuti . '|' . $berangkat . '|' . $kembali . '|' . $lamanya . ' ' . $hari,
            'norut' => $dapeg->norut,
            'foto' => $fotoCuti,
            'foto_b' => $fotoCuti,
            'foto_p' => $fotoCuti,
            'foto_pb' => $fotoCuti,
            'skor' => 50,
            'skor_p' => 50
        ];

        $hariKerja = 0;
        for ($i = 0; $i < $lamanya; $i++) {
            $data['tanggal'] = Carbon::parse($berangkat)->addDays($i)->translatedFormat('d');
            $data['bulan'] = Carbon::parse($berangkat)->addDays($i)->translatedFormat('m');
            $data['tahun'] = Carbon::parse($berangkat)->addDays($i)->translatedFormat('Y');
            $cekLibur = $this->cekLibur($data['tanggal'], $data['bulan'], $data['tahun']);
            if (!$cekLibur) {
                if ($jeniscuti == 'cuti1') {
                    $hariKerja = $hariKerja + 1;
                    if ($hariKerja > $makscuti) {
                        return redirect('/rekapPerorangan')->with('warning', 'Berhasil, jumlah hari cuti tidak bisa melebihi batas maksimal!');
                    }
                }

                $cekAbsen = Absen::where([
                    'nip' => $nip,
                    'tanggal' => $data['tanggal'],
                    'bulan' => $data['bulan'],
                    'tahun' => $data['tahun'],
                    'tampil' => '1'
                ])->first();
                if ($cekAbsen) {
                    Absen::where([
                        'nip' => $nip,
                        'tanggal' => $data['tanggal'],
                        'bulan' => $data['bulan'],
                        'tahun' => $data['tahun'],
                        'tampil' => '1'
                    ])->update($data);
                } else {
                    Absen::create($data);
                }
            } else {
            }
        }

        return redirect('/rekapPerorangan')->with('success', 'berhasil absen cuti');
    }
}
