<?php

namespace App\Http\Controllers;


use App\Models\Absen;
use App\Models\Dft_pegawai;
use App\Models\Tandatangan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DashboardPulangController extends Controller
{
    protected $tahun, $bulan, $tanggal, $hari, $pegawai, $db, $notifMutasi;
    public function __construct()
    {
        $this->tahun = now()->translatedFormat('Y');
        $this->bulan = now()->translatedFormat('m');
        $this->tanggal = now()->translatedFormat('d');
        $this->hari = now()->translatedFormat('l');
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


        $tglskrg = now()->translatedFormat('d');
        $cekLibur = $this->cekLibur($tglskrg, $this->bulan, $this->tahun);
        $Absensi = Absen::select('keterangan_p', 'konfirmasi_p', 'pangkat')->where([
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => 1
        ])->get();


        $jam = now()->translatedFormat('H');

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
        return view('dashboard.absenPulang', [
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


        return view('dashboard.absenPulang', [
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

    public function hari($tgl)
    {
        $tanggalAbsen = Str::of($tgl)->explode('-');
        $nmhari = $tanggalAbsen[2] . '/' . $tanggalAbsen[1] . '/' . $tanggalAbsen[0];
        $hari = Carbon::parse($nmhari)->translatedFormat('l');
        return $hari;
    }

    public function cekLibur($tanggal, $bulan, $tahun)
    {
        $db = DB::connection('eabsensi_opd');
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

    public function absenPulangReject(Request $request)
    {
        $id = $request->idtol;
        $data = Absen::where('id', $id)->first();
        $keterangan_p = $data->keterangan_p;
        $pangkat = $data->pangkat;
        if($pangkat == 'Non-PNS'){
            $pengurangan = 1;
        }else{
            $pengurangan = 1.5;
        }
        if ($keterangan_p == "hadir" or $keterangan_p == "wfh") {
            Absen::where('id', $id)->update([
                'konfirmasi_p' => 'rejected',
                'pengurangan_p' => $pengurangan,
                'skor_p' => -100
            ]);
        }
        return $pengurangan;
    }

    public function absenPulangKonfirmasi(Request $request)
    {
        $id = $request->idkonf;
        $data = Absen::where('id', $id)->first();
        $keterangan = $data->keterangan;
        $keterangan_p = $data->keterangan_p;
        $selisih_p = $data->selisih_p;
        $cekValidasi = $data->validasi_p;
        if ($cekValidasi == 'rejected') {
            $pengurangan_p = -1;
            return $pengurangan_p;
            exit();
            die();
        }

        if ($keterangan_p == "hadir" or $keterangan_p == "wfh") {
            if ($selisih_p <= -1 && $selisih_p > -31) {
                $skor_p = 75;
                $pengurangan_p = 0.5;
            } elseif ($selisih_p <= -31 && $selisih_p > -61) {
                $pengurangan_p = 1;
                $skor_p = 50;
            } elseif ($selisih_p <= -61 && $selisih_p > -91) {
                $pengurangan_p = 1.25;
                $skor_p = 38;
            } elseif ($selisih_p <= -91) {
                $pengurangan_p = 1.5;
                $skor_p = 25;
            } elseif ($selisih_p >= 0) {
                $pengurangan_p = 0;
                $skor_p = 100;
            }


            Absen::where('id', $id)->update([
                'konfirmasi_p' => 'confirmed',
                'pengurangan_p' => $pengurangan_p,
                'skor_p' => $skor_p
            ]);
        }

        return $pengurangan_p;
    }

    public function absenPulangKonfirmasiAll(Request $request)
    {

        $update = Absen::where([
            'tanggal' => $request->tanggal,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'konfirmasi_p' => 'un_confirmed',
            'tampil' => '1'
        ])->where('pangkat', '!=', 'Non-PNS')->where(function ($query) {
            $query->where('keterangan_p', 'hadir');
            $query->orWhere('keterangan_p', 'wfh');
        })->update([
            'konfirmasi_p' => 'confirmed'
        ]);
        return true;
    }

    public function absenPulangKonfirmasiAllNon(Request $request)
    {

        $update = Absen::where([
            'tanggal' => $request->tanggal,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'konfirmasi_p' => 'un_confirmed',
            'tampil' => '1'
        ])->where('pangkat', 'Non-PNS')->where(function ($query) {
            $query->where('keterangan_p', 'hadir');
            $query->orWhere('keterangan_p', 'wfh');
        })->update([
            'konfirmasi_p' => 'confirmed'
        ]);
        return true;
    }

    public function absenPulangHapus(Request $request)
    {
        $tanggal = $request->tanggal;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $data = Absen::where('id', $request->idhap)->first();
        $keterangan_p = $data->keterangan_p;
        $foto_p = $data->foto_p;
        $foto_pb = $data->foto_pb;
        Storage::delete($foto_p, $foto_pb);
        if ($keterangan_p == "hadir" or $keterangan_p == "wfh" or $keterangan_p == "tidak absen" or $keterangan_p == "") {
            Absen::where('id', $request->idhap)->update([
                'pulang' => NULL,
                'keterangan_p' => 'belum absen',
                'selisih_p' => '',
                'pengurangan_p' => '',
                'foto_p' => '',
                'foto_pb' => '',
                'konfirmasi_p' => 'un_confirmed',
                'skor_p' => null
            ]);
        }
        return true;
    }

    public function yajraAbsenPulang(Request $request)
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
            ->addColumn('foto_p', function ($model) {
                if ($model->foto_p) {

                    if(substr($model->foto_p, -3) == 'pdf'){
                       $html = '<a href="'.$model->foto_p.'" target="_blank"><img src="/img/logo-pdf.jpg" width="75px"></a>';
                       
                    }else{
                         $html = '<img class="foto_p img-fluid" src="/storage/' . $model->foto_p . '" width="75px" onclick="gbabsen(`' . $model->nama . '|' . $model->foto_p . '`)">';
                    }
                   
                    return $html;

                   
    
                } else {
                    return '';
                }
            })
            ->addColumn('foto_pb', function ($model) {
                if ($model->foto_pb) {
                     if(substr($model->foto_pb, -3) == 'pdf'){
                       $html = '<a href="'.$model->foto_pb.'" target="_blank"><img src="/img/logo-pdf.jpg" width="75px"></a>';
                       
                    }else{
                         $html = '<img class="foto_p img-fluid" src="/storage/' . $model->foto_pb . '" width="75px" onclick="gbabsen(`' . $model->nama . '|' . $model->foto_pb . '`)">';
                    }

                    return $html;
                } else {
                    return '';
                }
            })
            ->addColumn('keterangan_p', function ($model) {

                if ($model->selisih_p < 0 && $model->keterangan_p == 'hadir') {
                    return "<span class='keterangan_p'>" . "<span class='keterangan_p'>" . $model->selisih_p . ' menit PSW' . "</span>";
                } elseif ($model->selisih_p >= 0 && $model->keterangan_p == 'hadir') {
                    return "<span class='keterangan_p'>" . $model->keterangan_p . '<br>' . 'tepat waktu' . "</span>";
                } else {
                    return "<span class='keterangan_p'>" . $model->keterangan_p . "</span>";
                }
            })
            ->addColumn('ubahstatus', function ($model) {
                $html = '
                <span class="d-flex justify-content-around">
                    <button class="btn btn-sm btn-info btn_' . $model->id . '" onclick="konfirmasi(' . $model->id . ')" title="confirm"><i class="fas fa-check-circle"></i></button>
                    <button class="btn btn-sm btn-danger btn_' . $model->id . '" onclick="reject(' . $model->id . ')" title="reject"><i class="fas fa-times-circle"></i></button>
                    <button class="btn btn-sm btn-danger btn_' . $model->id . '" onclick="hapus(' . $model->id . ')" title="delete"
                    tanggal="' . $model->tanggal . '"
                    bulan="' . $model->bulan . '"
                    tahun="' . $model->tahun . '"
                    nip="' . $model->nip . '"
                    ><i class="far fa-trash-alt"></i></button>
                </span>
                ';
                if ($model->keterangan_p == 'hadir') {
                    return $html;
                }
            })
            ->addColumn('konfirmasi_p', function ($model) {
                $html = '<i><span class="status">' . $model->konfirmasi_p . '</span></i></br>
                <i><span class="validasi_p">' . $model->validasi_p . '</span></i>';
                return $html;
            })
            ->addColumn('pengurangan_p', function ($model) {
                $html = '<span class="minus">' . $model->pengurangan_p . '</span>';
                return $html;
            })
            ->addColumn('pulang', function ($model) {
                if ($model->pulang !== '00:00:00') {
                    return "<span class='pulang'>" . $model->pulang . "</span>";
                }
            })

            ->rawColumns(['tanggal', 'pulang', 'keterangan_p', 'pengurangan_p', 'foto_p', 'foto_pb', 'konfirmasi_p', 'ubahstatus'])
            ->addIndexColumn()->make(true);
    }

    public function ajaxBelumAbsen(Request $request)
    {
        $sekarang = now()->translatedFormat('d');
        return Absen::where([
            'keterangan_p' => 'belum absen',
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'tampil' => '1'
        ])->where('tanggal', '!=', $sekarang)->update([
            'keterangan_p' => 'tidak absen',
            'selisih_p' => -500,
            'pengurangan_p' => 1.5,
            'konfirmasi_p' => 'confirmed',
            'validasi_p' => 'validated',
            'skor_p' => 0
        ]);
    }
}
