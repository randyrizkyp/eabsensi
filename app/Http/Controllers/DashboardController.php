<?php

namespace App\Http\Controllers;

use App\Models\Dft_pegawai;
use App\Models\Absen;
use App\Models\Tandatangan;
use App\Models\Tanpa_keterangan;
use App\Models\Dinas_luar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
// use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use PDF;

class DashboardController extends Controller
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
    public function index(Request $request)
    {

        if (session()->has('tahun')) {
            session()->pull('tahun');
            session()->pull('bulan');
            session()->pull('tanggal');
        }

        $db = $this->db;
        $liburTahun = cache::remember('libur' . $this->tahun, now()->addMonths(3), function () use ($db) {
            return $db->table('libur')->where('tahun', $this->tahun)->get();
        });

        $libur = $liburTahun->where('bulan', $this->bulan)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $cekBulanIni = $this->cekBulanIni($libur, $pegawai);
        $cekBulanLalu = $this->cekBulanLalu($pegawai, $liburTahun);

        if (!session()->has('cekKonfirmasi')) {
            $cekKonfirmasi = $this->cekKonfirmasi();
        }


        $tglskrg = now()->translatedFormat('d');
        $cekLibur = $this->cekLibur($tglskrg, $this->bulan, $this->tahun);
        $Absensi = Absen::select('nip', 'keterangan', 'konfirmasi', 'pangkat')->where([
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => 1
        ])->get();

        $jam = now()->translatedFormat('H');

        if ($jam > 14) {
            if (!in_array($tglskrg, $libur)) {
                $tanpaket = $this->tanpaKeterangan($Absensi);
                if ($tanpaket) {
                    $Absensi = Absen::select('nip', 'keterangan', 'konfirmasi', 'pangkat')->where([
                        'tahun' => $this->tahun,
                        'bulan' => $this->bulan,
                        'tanggal' => $this->tanggal,
                        'tampil' => 1
                    ])->get();
                }
            }
        }
        if ($request->pegawai) {
            if ($request->pegawai == 'asn') {
                session()->put('statusPegawai', 'asn');
                $dataAbsensi = $Absensi->where('pangkat', '!=', 'Non-PNS');
            } else {
                session()->put('statusPegawai', 'non');
                $dataAbsensi =  $Absensi->where('pangkat', 'Non-PNS');
            }
            $status = session()->get('statusPegawai');
        } else {
            session()->pull('statusPegawai');
            $status = 'asn';
            $dataAbsensi = $Absensi->where('pangkat', '!=', 'Non-PNS');
        }


        $hariini = true;
        return view('dashboard.absenMasuk', [
            'nama_lain' => config('global.nama_lain'),
            'dataAbsensi' => $dataAbsensi,
            'status' => $status,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'hari' => $this->hari,
            'hariini' => $hariini,
            'cekLibur' => $cekLibur,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi
        ]);
    }

    public function tanpaKeterangan($Absensi)
    {
        $nipabsen = $Absensi->pluck('nip')->toArray();
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $nipPegawai = $pegawai->pluck('nip');

        $nipBelumAbsen = $nipPegawai->diff($nipabsen)->toArray();
        $belumAbsen = $pegawai->filter(function ($value) use ($nipBelumAbsen) {
            return in_array($value->nip, $nipBelumAbsen);
        });


        $tglskrg = Carbon::parse(now()->translatedFormat('d-m-Y'));

        $dataTK = [];
        foreach ($belumAbsen as $tk) {
            $tgl_tmt = Carbon::create($tk->tmt_absen)->translatedFormat('d-m-Y');
            $jarwak = strtotime($tglskrg) - strtotime($tgl_tmt);
            //khusus Non-PNS
            if($tk->pangkat == 'Non-PNS'){
                $pengurangan = 1;
                $pengurangan_p = 1;
            }else{
                $pengurangan = 1.5;
                $pengurangan_p = 1.5;
            }

            if ($jarwak >= 0) {
                $data = [
                    'nip' => $tk->nip,
                    'nama' => $tk->nama,
                    'tanggal' => now()->translatedFormat('d'),
                    'bulan' => now()->translatedFormat('m'),
                    'tahun' => now()->translatedFormat('Y'),
                    'waktu' => Null,
                    'pulang' => Null,
                    'apel_pagi' => 'tanpa keterangan',
                    'keterangan' => 'tanpa keterangan',
                    'keterangan_p' => 'tanpa keterangan',
                    'pengurangan' => $pengurangan,
                    'pengurangan_p' => $pengurangan_p,
                    'konfirmasi' => 'confirmed',
                    'konfirmasi_p' => 'confirmed',
                    'validasi' => 'validated',
                    'validasi_p' => 'validated',
                    'selisih' => 500,
                    'selisih_p' => -500,
                    'pangkat' => $tk->pangkat,
                    'jabatan' => $tk->jabatan,
                    'jenis_jbt' => $tk->jenis_jbt,
                    'tpp' => $tk->tpp,
                    'tmt_absen' => $tk->tmt_absen,
                    'tampil' => '1',
                    'person' => 'admin_absensi',
                    'norut' => $tk->norut,
                    'skor' => 0,
                    'skor_p' => 0
                ];
                array_push($dataTK, $data);
            }
        }
        DB::table('absens')->insert($dataTK);
        $cekTK = Tanpa_keterangan::where([
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun
        ])->first();
        if (!$cekTK) {
            Tanpa_keterangan::create([
                'tanggal' => $this->tanggal,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
                'jam' => now()->format('H')
            ]);
        }
        return true;
    }

    public function cekBulanIni($libur, $pegawai)
    {
        $cekTanpaKeterangan = Tanpa_keterangan::where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)->where('adahapus', false)->get();
        $tanggalTanpaKeterangan = $cekTanpaKeterangan->pluck('tanggal')->toArray();
        $tanggalTanpaKeterangan = array_unique($tanggalTanpaKeterangan);

        $jumhari = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);
        $tanggalBulan = [];
        for ($i = 01; $i <= $jumhari; $i++) {
            $tanggalBulan[] .= sprintf("%02d", $i);
        }

        $tanggalAbsen = [];
        foreach ($tanggalBulan as $tgb) {
            if (!in_array($tgb, $libur)) {
                $tanggalAbsen[] .= $tgb;
            }
        }

        $tanggalAbsen =  collect($tanggalAbsen)->filter(function ($value) use ($tanggalTanpaKeterangan) {
            return $value < now()->translatedFormat('d') && (!in_array($value, $tanggalTanpaKeterangan));
        });

        if (count($tanggalAbsen) == 0) {
            return "cek absen bulan ini sudah semua";
        }


        foreach ($tanggalAbsen as $tgabsen) {
            $absenTanggalIni = Absen::where([
                'tahun' => $this->tahun,
                'bulan' => $this->bulan,
                'tanggal' => $tgabsen,
                'tampil' => '1'
            ])->get(['nip', 'tanggal']);

            $cekTAP = Absen::where([
                'tanggal' => $tgabsen,
                'tahun' => $this->tahun,
                'bulan' => $this->bulan,
                'tampil' => '1',
                'keterangan_p' => 'belum absen'
            ])->where('pangkat', '!=', 'Non-PNS')->update([
                'keterangan_p' => 'tidak absen',
                'selisih_p' => 500,
                'pengurangan_p' => 1.5,
                'konfirmasi_p' => 'confirmed',
                'validasi_p' => 'validated',
                'skor_p' => 0
            ]);

            // khusus Non-ASN
            $cekTAPNon = Absen::where([
                'tanggal' => $tgabsen,
                'tahun' => $this->tahun,
                'bulan' => $this->bulan,
                'tampil' => '1',
                'keterangan_p' => 'belum absen',
                'pangkat' => 'Non-PNS'
            ])->update([
                'keterangan_p' => 'tidak absen',
                'selisih_p' => 500,
                'pengurangan_p' => 1,
                'konfirmasi_p' => 'confirmed',
                'validasi_p' => 'validated',
                'skor_p' => 0
            ]);


            $cekTK = $this->tanpaKetBulanIni($tgabsen, $this->bulan, $this->tahun, $absenTanggalIni, $pegawai);
        }

        return "cek bulan ini = " . $cekTK;
    }

    public function tanpaKetBulanIni($tgabsen, $bulan, $tahun, $absenTanggalIni, $pegawai)
    {

        $dataAbsen = [];
        foreach ($pegawai as $peg) {
            $ceknip = $absenTanggalIni->where('nip', $peg->nip)->first();
            $tgl_absen = Carbon::create($tahun . '-' . $bulan . '-' . $tgabsen)->translatedFormat('d-m-Y');
            $tgl_tmt = Carbon::create($peg->tmt_absen)->translatedFormat('d-m-Y');
            $jarwak = strtotime($tgl_absen) - strtotime($tgl_tmt);
            // echo $peg->nip . "-" . $tgl_tmt . "=" . $jarwak . "<br>";

            //khusus Non-PNS
            if($peg->pangkat == 'Non-PNS'){
                $pengurangan = 1;
                $pengurangan_p = 1;
            }else{
                $pengurangan = 1.5;
                $pengurangan_p = 1.5;
            }

            if (!$ceknip && $jarwak > 0) {
                $dasen =  [
                    'nip' => $peg->nip,
                    'nama' => $peg->nama,
                    'tanggal' => $tgabsen,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'waktu' => Null,
                    'pulang' => Null,
                    'keterangan' => 'tanpa keterangan',
                    'keterangan_p' => 'tanpa keterangan',
                    'pengurangan' => $pengurangan,
                    'pengurangan_p' => $pengurangan_p,
                    'konfirmasi' => 'confirmed',
                    'konfirmasi_p' => 'confirmed',
                    'validasi' => 'validated',
                    'validasi_p' => 'validated',
                    'selisih' => 500,
                    'selisih_p' => -500,
                    'pangkat' => $peg->pangkat,
                    'jabatan' => $peg->jabatan,
                    'jenis_jbt' => $peg->jenis_jbt,
                    'tpp' => $peg->tpp,
                    'tmt_absen' => $peg->tmt_absen,
                    'tampil' => '1',
                    'person' => 'admin_absensi',
                    'norut' => $peg->norut,
                    'skor' => 0,
                    'skor_p' => 0
                ];
                array_push($dataAbsen, $dasen);
            }
        }

        $insert = DB::table('absens')->insert($dataAbsen);
        if ($insert) {
            Tanpa_keterangan::create([
                'tanggal' => $tgabsen,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jam' => now()->format('h:i:s'),
                'adahapus' => false
            ]);
            return "berhasil";
        }
    }

    public function cekBulanLalu($pegawai, $liburTahun)
    {
        $lalu = now()->subMonths(1);
        $bulanLalu = $lalu->translatedFormat('m');
        $tahun = $lalu->format('Y');

        if (now()->translatedFormat('m') == $bulanLalu) {
            return false;
            exit();
        }
        if (now()->translatedFormat('m') == '01') {
            $db = $this->db;
            $liburTahun = cache::remember('libur' . $tahun, now()->addMonths(3), function () use ($db, $tahun) {
                return $db->table('libur')->where('tahun', $tahun)->get();
            });
        }

        $cekTanpaKeterangan = Tanpa_keterangan::where('bulan', $bulanLalu)
            ->where('tahun', $tahun)->get();
        $tanggalTanpaKeterangan = $cekTanpaKeterangan->pluck('tanggal')->toArray();
        $tanggalTanpaKeterangan = array_unique($tanggalTanpaKeterangan);

        $libur = $liburTahun->where('bulan', $bulanLalu)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $jumhari = cal_days_in_month(CAL_GREGORIAN, $bulanLalu, $tahun);
        $tanggalBulan = [];
        for ($i = 01; $i <= $jumhari; $i++) {
            $tanggalBulan[] .= sprintf("%02d", $i);
        }

        $tanggalAbsen = [];
        foreach ($tanggalBulan as $tgb) {
            if (!in_array($tgb, $libur)) {
                $tanggalAbsen[] .= $tgb;
            }
        }

        $tanggalAbsen =  collect($tanggalAbsen)->filter(function ($value) use ($tanggalTanpaKeterangan) {
            return $value && (!in_array($value, $tanggalTanpaKeterangan));
        });
        if (count($tanggalAbsen) == 0) {
            return "cek absen bulan lalu sudah semua";
        }

        foreach ($tanggalAbsen as $tgabsen) {
            $absenTanggalIni = Absen::where([
                'tahun' => $tahun,
                'bulan' => $bulanLalu,
                'tanggal' => $tgabsen,
                'tampil' => 1
            ])->get('nip', 'tanggal');
            $cekTK = $this->tanpaKetBulanIni($tgabsen, $bulanLalu, $tahun, $absenTanggalIni, $pegawai);
        }

        return "cek absen bulan lalu = " . $cekTK;
    }


    public function carsen(Request $request)
    {
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $tanggal = $this->tanggal;
        $bulan = $this->bulan;
        $tahun = $this->tahun;
        $carhari = $this->tanggal . '-' . $this->bulan . '-' . $this->tahun;
        if (session()->has('tahun')) {
            $tanggal = session()->get('tanggal');
            $bulan = session()->get('bulan');
            $tahun = session()->get('tahun');
            $carhari = $tanggal . '-' . $bulan . '-' . $tahun;
        }

        if ($request->tglcari) {
            $tanggalAbsen = Str::of($request->tglcari)->explode('-');
            $tahun = $tanggalAbsen[2];
            $bulan = $tanggalAbsen[1];
            $tanggal = $tanggalAbsen[0];
            $carhari = $request->tglcari;
        }

        if ($request->tambah) {
            $awal = Carbon::parse($request->awal)->addDay();
            $akhir = Carbon::create($awal)->translatedFormat('d-m-Y');
            $tanggalAbsen = Str::of($akhir)->explode('-');
            $tahun = $tanggalAbsen[2];
            $bulan = $tanggalAbsen[1];
            $tanggal = $tanggalAbsen[0];
            $carhari = $akhir;
        }

        if ($request->kurang) {
            $awal = Carbon::parse($request->awal)->addDay(-1);
            $akhir = Carbon::create($awal)->translatedFormat('d-m-Y');
            $tanggalAbsen = Str::of($akhir)->explode('-');
            $tahun = $tanggalAbsen[2];
            $bulan = $tanggalAbsen[1];
            $tanggal = $tanggalAbsen[0];
            $carhari = $akhir;
        }
        session()->put('tanggal', $tanggal);
        session()->put('bulan', $bulan);
        session()->put('tahun', $tahun);

        $cekLibur = $this->cekLibur($tanggal, $bulan, $tahun);

        $tglcari = Carbon::parse($request->tglcari);
        $tglskrg = Carbon::parse(now()->format('d-m-Y'));
        $jarwak = $tglskrg->diffInDays($tglcari);
        $cekwaktu = now()->format('H');
        if ($cekwaktu > 0) {
            $tanpaket = 'Tanpa Keterang';
        } else {
            $tanpaket = 'belum';
        }
        $hariini = false;
        $Absensi = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tanggal' => $tanggal,
            'tampil' => 1
        ])->orderBy('norut', 'ASC')->get();

        if ($request->pegawai) {
            if ($request->pegawai == 'asn') {
                session()->put('statusPegawai', 'asn');
                $dataAbsensi = $Absensi->where('pangkat', '!=', 'Non-PNS');
            } else {
                session()->put('statusPegawai', 'non');
                $dataAbsensi =  $Absensi->where('pangkat', 'Non-PNS');
            }
            $status = session()->get('statusPegawai');
        } else {
            if (session()->has('statusPegawai')) {
                $status = session()->get('statusPegawai');
            } else {
                session()->pull('statusPegawai');
                $status = 'asn';
            }
            $dataAbsensi = $Absensi->where('pangkat', '!=', 'Non-PNS');
        }


        return view('dashboard.absenMasuk', [
            'nama_lain' => config('global.nama_lain'),
            'dataAbsensi' => $dataAbsensi,
            'status' => $status,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tanggal' => $tanggal,
            'hari' => $this->hari($carhari),
            'jarwak' => $jarwak,
            'hariini' => $hariini,
            'cekLibur' => $cekLibur,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi

        ]);
    }

    public function absenHarian(Request $request)
    {


        $db = $this->db;
        $liburTahun = cache::remember('libur' . $this->tahun, now()->addMonths(3), function () use ($db) {
            return $db->table('libur')->where('tahun', $this->tahun)->get();
        });

        $libur = $liburTahun->where('bulan', $this->bulan)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $hide = false;
        if ($request->hariIni) {
            session()->pull('tahun');
            session()->pull('bulan');
            session()->pull('tanggal');
            $hide = true;
        }
        $tanggal = $this->tanggal;
        $bulan = $this->bulan;
        $tahun = $this->tahun;
        $carhari = $this->tanggal . '-' . $this->bulan . '-' . $this->tahun;

        if (session()->has('tahun')) {
            $tanggal = session()->get('tanggal');
            $bulan = session()->get('bulan');
            $tahun = session()->get('tahun');
            $carhari = $tanggal . '-' . $bulan . '-' . $tahun;
        }

        if ($request->tglcari) {
            $tanggalAbsen = Str::of($request->tglcari)->explode('-');
            $tahun = $tanggalAbsen[2];
            $bulan = $tanggalAbsen[1];
            $tanggal = $tanggalAbsen[0];
            $carhari = $request->tglcari;
        }

        if ($request->tambah) {
            $awal = Carbon::parse($request->awal)->addDay();
            $akhir = Carbon::create($awal)->translatedFormat('d-m-Y');
            $tanggalAbsen = Str::of($akhir)->explode('-');
            $tahun = $tanggalAbsen[2];
            $bulan = $tanggalAbsen[1];
            $tanggal = $tanggalAbsen[0];
            $carhari = $akhir;
        }

        if ($request->kurang) {
            $awal = Carbon::parse($request->awal)->addDay(-1);
            $akhir = Carbon::create($awal)->translatedFormat('d-m-Y');
            $tanggalAbsen = Str::of($akhir)->explode('-');
            $tahun = $tanggalAbsen[2];
            $bulan = $tanggalAbsen[1];
            $tanggal = $tanggalAbsen[0];
            $carhari = $akhir;
        }
        session()->put('tanggal', $tanggal);
        session()->put('bulan', $bulan);
        session()->put('tahun', $tahun);

        $cekLibur = $this->cekLibur($tanggal, $bulan, $tahun);

        $Absensi = Absen::where([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tanggal' => $tanggal,
            'tampil' => 1
        ])->orderBy('norut')->get();



        if ($request->pegawai) {
            if ($request->pegawai == 'asn') {
                session()->put('statusPegawai', 'asn');
                $pegawai = $pegawai->where('pangkat', '!=', 'Non-PNS')->sortBy('norut');
                $dataAbsensi = $Absensi->where('pangkat', '!=', 'Non-PNS');
            } else {
                session()->put('statusPegawai', 'non');
                $pegawai = $pegawai->where('pangkat', 'Non-PNS')->sortBy('norut');
                $dataAbsensi =  $Absensi->where('pangkat', 'Non-PNS');
            }
            $status = session()->get('statusPegawai');
        } else {
            session()->pull('statusPegawai');
            $status = 'asn';
            $pegawai = $pegawai->where('pangkat', '!=', 'Non-PNS')->sortBy('norut');
            $dataAbsensi = $Absensi->where('pangkat', '!=', 'Non-PNS');
        }


        $sekarang = $tanggal . '-' . $bulan . '-' . $tahun;
        if ($sekarang == now()->translatedFormat('d-m-Y')) {
            $hariini = true;
        } else {
            $hariini = false;
        }
        $text = config('global.nama_lain') . $status . $tanggal . $bulan . $tahun;
        $register = Hash::make($text);

        $tglskrg = Carbon::parse(now()->translatedFormat('d-m-Y'));
        $tgl_cari = Carbon::create($tanggal . '-' . $bulan . '-' . $tahun)->translatedFormat('d-m-Y');
        $jarwak = strtotime($tglskrg) - strtotime($tgl_cari);
        if ($jarwak <= 0) {
            $kedepan = true;
        } else {
            $kedepan = false;
        }



        if ($request->print) {

            $pdf = PDF::loadView('dashboard.cetakAja', [
                'nama_pd' => config('global.nama_pd'),
                'nama_lain' => config('global.nama_lain'),
                'dataAbsensi' => $dataAbsensi,
                'status' => $status,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'tanggal' => $tanggal,
                'hari' => $this->hari($carhari),
                'hariini' => $hariini,
                'cekLibur' => $cekLibur,
                'pegawais' => $pegawai,
                'admin' => $admin,
                'register' => $register,
                'kedepan' => $kedepan
            ],[],[
                'orientation' => 'P'
            ]);

            return $pdf->stream('absen_' . $tanggal . '-' . $bulan . '-' . $tahun . '.pdf');
        }

        return view('dashboard.absenHarian', [
            'nama_lain' => config('global.nama_lain'),
            'dataAbsensi' => $dataAbsensi,
            'status' => $status,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tanggal' => $tanggal,
            'hari' => $this->hari($carhari),
            'hariini' => $hariini,
            'cekLibur' => $cekLibur,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi,
            'pegawais' => $pegawai,
            'kedepan' => $kedepan
        ]);
    }

    public function hari($tgl)
    {
        $tanggalAbsen = Str::of($tgl)->explode('-');
        $nmhari = $tanggalAbsen[2] . '/' . $tanggalAbsen[1] . '/' . $tanggalAbsen[0];
        $hari = Carbon::parse($nmhari)->translatedFormat('l');
        return $hari;
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

    public function absenMasukReject(Request $request)
    {
        $id = $request->idtol;
        $data = Absen::where('id', $id)->first();
        $keterangan = $data->keterangan;
        $pangkat = $data->pangkat;
        if($pangkat == 'Non-PNS'){
            $pengurangan = 1;
        }else{
            $pengurangan = 1.5;
        }
        if ($keterangan == "hadir" or $keterangan == "wfh") {
            Absen::where('id', $id)->update([
                'konfirmasi' => 'rejected',
                'pengurangan' => $pengurangan,
                'skor' => -100
            ]);
        } elseif ($keterangan == "dinas luar" or $keterangan == "sakit" or $keterangan == "cuti" or $keterangan == "izin") {
            $update = Absen::where('id', $id)->update([
                'konfirmasi' => 'rejected',
                'konfirmasi_p' => 'rejected',
                'pengurangan' => $pengurangan,
                'pengurangan_p' => $pengurangan,
                'skor' => -100,
                'skor_p' => -100
            ]);
        }
        return $pengurangan;
    }

    public function absenMasukKonfirmasi(Request $request)
    {
        $id = $request->idkonf;
        $data = Absen::where('id', $id)->first();
        $keterangan = $data->keterangan;
        $selisih = $data->selisih;
        $cekValidasi = $data->validasi;
        if ($cekValidasi == 'rejected') {
            $pengurangan = -1;
            return $pengurangan;
            exit();
            die();
        }elseif ($keterangan == 'tanpa keterangan'){
            $data = 0;
            return $data;
            exit();
            die();
        }
        if ($keterangan == "hadir" or $keterangan == "wfh") {
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

            if ($data->pangkat == 'Non-PNS'){
                $pengurangan = 0;
            }

            Absen::where('id', $id)->update([
                'konfirmasi' => 'confirmed',
                'pengurangan' => $pengurangan,
                'skor' => $skor
            ]);
        } elseif ($keterangan == "dinas luar") {
            $pengurangan = 0;
            $skor = 90;
            Absen::where('id', $id)->update([
                'konfirmasi' => 'confirmed',
                'konfirmasi_p' => 'confirmed',
                'pengurangan' => 0,
                'pengurangan_p' => 0,
                'skor' => $skor,
                'skor_p' => $skor
            ]);
        } elseif ($keterangan == "cuti") {
            $pengurangan = 0;
            $skor = 50;
            Absen::where('id', $id)->update([
                'konfirmasi' => 'confirmed',
                'konfirmasi_p' => 'confirmed',
                'pengurangan' => 0,
                'pengurangan_p' => 0,
                'skor' => $skor,
                'skor_p' => $skor
            ]);
        } elseif ($keterangan == "sakit") {
            $pengurangan = 0;
            $skor = 25;
            Absen::where('id', $id)->update([
                'konfirmasi' => 'confirmed',
                'konfirmasi_p' => 'confirmed',
                'pengurangan' => 0,
                'pengurangan_p' => 0,
                'skor' => $skor,
                'skor_p' => $skor
            ]);
        } elseif ($keterangan == "izin") {
            $pengurangan = 0;
            $skor = 25;
            Absen::where('id', $id)->update([
                'konfirmasi' => 'confirmed',
                'konfirmasi_p' => 'confirmed',
                'pengurangan' => 0,
                'pengurangan_p' => 0,
                'skor' => $skor,
                'skor_p' => $skor
            ]);
        }

        return $pengurangan;
    }

    public function absenMasukKonfirmasiAll(Request $request)
    {

        Absen::where([
            'tanggal' => $request->tanggal,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'konfirmasi' => 'un_confirmed',
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->update([
            'konfirmasi' => 'confirmed'
        ]);
        Absen::where([
            'tanggal' => $request->tanggal,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'konfirmasi' => 'un_confirmed',
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->where('keterangan', '!=', 'hadir')->update([
            'konfirmasi_p' => 'confirmed'
        ]);

        return true;
    }

    public function absenMasukKonfirmasiAllNon(Request $request)
    {

        Absen::where([
            'tanggal' => $request->tanggal,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'konfirmasi' => 'un_confirmed',
            'tampil' => '1'
        ])->where('pangkat', 'Non-PNS')->update([
            'konfirmasi' => 'confirmed'
        ]);
        Absen::where([
            'tanggal' => $request->tanggal,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'konfirmasi' => 'un_confirmed',
            'tampil' => '1'
        ])->where('pangkat', 'Non-PNS')->where('keterangan', '!=', 'hadir')->update([
            'konfirmasi' => 'confirmed'
        ]);
        return true;
    }

    public function absenMasukHapus(Request $request)
    {

        $dataAbsen = Absen::where('id', $request->idhap)->first();
        $nip = $dataAbsen->nip;
        $tanggal = $dataAbsen->tanggal;
        $bulan = $dataAbsen->bulan;
        $tahun = $dataAbsen->tahun;
        $keterangan = $dataAbsen->keterangan;

        if ($keterangan == 'dinas luar') {
            Dinas_luar::where([
                'nip' => $nip,
                'tanggal' => $tanggal,
                'bulan' => $bulan,
                'tahun' => $tahun
            ])->update([
                'tampil' => 0
            ]);
        }
        Absen::where('id', $request->idhap)->update([
            'tampil' => 0
        ]);

        Tanpa_keterangan::where([
            'tanggal' => $tanggal,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->delete();
    }

    public function yajraAbsenMasuk(Request $request)
    {
        if (session()->has('tahun')) {
            $tanggal = session()->get('tanggal');
            $bulan = session()->get('bulan');
            $tahun = session()->get('tahun');
        } else {
            $tanggal = $this->tanggal;
            $bulan = $this->bulan;
            $tahun = $this->tahun;
        }


        if (session()->has('statusPegawai')) {
            $status = session()->get('statusPegawai');
            if ($status == 'asn') {
                $pangkat = '!=';
            } elseif ($status == 'non') {
                $pangkat = '=';
            }
        } else {
            $pangkat = '!=';
        }
        return DataTables::of(Absen::where([
            'tanggal' => $tanggal,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tampil' => '1'
        ])->where('pangkat', $pangkat, 'Non-PNS')->orderBy('norut', 'asc'))
            ->addColumn('tanggal', function ($model) {
                return $model->tanggal . '-' . $model->bulan . '-' . $model->tahun;
            })
            ->addColumn('foto', function ($model) {
                if ($model->foto) {
                    if(substr($model->foto, -3) == 'pdf'){
                       $html = '<a href="'.$model->foto.'" target="_blank"><img src="/img/logo-pdf.jpg" width="75px"></a>';
                       
                    }else{
                         $html = '<img src="/storage/' . $model->foto . '" width="75px" onclick="gbabsen(`' . $model->nama . '|' . $model->foto . '`)">';
                    }
                   
                    return $html;
                } else {
                    return '';
                }
            })
            ->addColumn('foto_b', function ($model) {
                if ($model->foto_b) {
                    if(substr($model->foto_b, -3) == 'pdf'){
                       $html = '<a href="'.$model->foto_b.'" target="_blank"><img src="/img/logo-pdf.jpg" width="75px"></a>';
                       
                    }else{
                         $html = '<img src="/storage/' . $model->foto_b . '" width="75px" onclick="gbabsen(`' . $model->nama . '|' . $model->foto_b . '`)">';
                    }
                   
                    return $html;
                } else {
                    return '';
                }
            })
            ->addColumn('keterangan', function ($model) {
                if ($model->keterangan == 'hadir') {
                    if ($model->selisih <= 0) {
                        return $model->apel_pagi."<br>".$model->keterangan . "<br>" . 'tepat waktu';
                    } else {
                        return $model->apel_pagi."<br>".$model->keterangan . "<br>" . 'telat ' . $model->selisih . " menit";
                    }
                } else {
                    return $model->apel_pagi."<br>".$model->keterangan;
                }
            })
            ->addColumn('ubahstatus', function ($model) {
                $html = '
                <span class="d-flex justify-content-around">
                    <button class="btn btn-sm btn-info btn_' . $model->id . '" onclick="konfirmasi(' . $model->id . ')" title="confirm"><i class="fas fa-check-circle"></i></button>
                    <button class="btn btn-sm btn-danger btn_' . $model->id . '" onclick="reject(' . $model->id . ')" title="reject"><i class="fas fa-times-circle"></i></button>
                    <button class="btn btn-sm btn-danger btn_' . $model->id . '" onclick="hapus(' . $model->id . ')"><i class="far fa-trash-alt"></i></button>
                </span>
                ';
                return $html;
            })
            ->addColumn('konfirmasi', function ($model) {
                $html = '<i><span class="status">' . $model->konfirmasi . '</span></i></br>
                <i><span class="validasi">' . $model->validasi . '</span></i>';
                return $html;
            })
            ->addColumn('pengurangan', function ($model) {
                $html = '<span class="minus">' . $model->pengurangan . '</span>';
                return $html;
            })

            ->rawColumns(['tanggal', 'keterangan', 'pengurangan', 'foto', 'foto_b', 'konfirmasi', 'ubahstatus'])
            ->addIndexColumn()->make(true);
    }

    public function unconfirm(Request $request)
    {

        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $Absensi = Absen::where([
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tampil' => 1,
        ])->get();
        $AbsensiM = $Absensi->where('konfirmasi', 'un_confirmed');
        $AbsensiP = $Absensi->where('konfirmasi_p', 'un_confirmed');

        if ($request->pegawai) {
            if ($request->pegawai == 'asn') {
                session()->put('statusPegawai', 'asn');
                $dataAbsensiM = $AbsensiM->where('pangkat', '!=', 'Non-PNS');
                $dataAbsensiP = $AbsensiP->where('pangkat', '!=', 'Non-PNS')->where('keterangan_p', 'hadir');
            } else {
                session()->put('statusPegawai', 'non');
                $dataAbsensiM =  $AbsensiM->where('pangkat', 'Non-PNS');
                $dataAbsensiP =  $AbsensiP->where('pangkat', 'Non-PNS')->where('keterangan_p', 'hadir');
            }
            $status = session()->get('statusPegawai');
        } else {
            session()->pull('statusPegawai');
            $status = 'asn';
            $dataAbsensiM = $AbsensiM->where('pangkat', '!=', 'Non-PNS');
            $dataAbsensiP = $AbsensiP->where('pangkat', '!=', 'Non-PNS')->where('keterangan_p', 'hadir');
        }

        if ($request->kriteria) {
            $kriteria = $request->kriteria;
        } else {
            $kriteria = 'absen_masuk';
        }

        return view('dashboard.unconfirm', [
            'nama_lain' => config('global.nama_lain'),
            'dataAbsensiM' => $dataAbsensiM,
            'dataAbsensiP' => $dataAbsensiP,
            'status' => $status,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'kriteria' => $kriteria,
            'notifMutasi' => $this->notifMutasi
        ]);
    }

    public function cekKonfirmasi()
    {
        $unconfirm = Absen::where([
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'konfirmasi' => 'un_confirmed',
            'tampil' => 1
        ])->where('tanggal', '<', $this->tanggal)->update([
            'konfirmasi' => 'confirmed'
        ]);

        $unconfirmP = Absen::where([
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'konfirmasi_p' => 'un_confirmed',
            'tampil' => 1
        ])->where('tanggal', '<', $this->tanggal)->update([
            'konfirmasi_p' => 'confirmed'
        ]);

        session()->put('cekKonfirmasi', true);
    }
}
