<?php

namespace App\Http\Controllers;

use App\Models\Daftar_admin;
use Illuminate\Http\Request;
use App\Models\Dft_pegawai;
use App\Models\Mutasi_pegawai;
use App\Models\Tandatangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use PDF;

class PegawaiController extends Controller
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

    public function pegawaiAsn(Request $request)
    {

        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $pegawaiAsn = $pegawai->where('pangkat', '!=', 'Non-PNS')->sortBy('norut');

        $kode_pd = config('global.kode_pd');
        $tbPD = cache()->remember('tbPD', 60 * 60 * 24, function () {
            $db = $this->db;
            return $db->table('tb_pd')->get();
        });
        $dataOpd = $tbPD->where('kode_pd', $kode_pd);
        $unors = $dataOpd->where('unit_organisasi', '!=', '');
        $subunit = $dataOpd->where('tpt_lain', '!=', '');
        $opdlain = $tbPD->unique('kode_pd');
        
        $db = $this->db;
        $pokjab = $db->table('kelompok_jabs')->where('kode_pd', $kode_pd)->get();       

        return view('kepegawaian.pegawaiAsn', [
            'pegawaiAsn' => $pegawaiAsn,
            'tahun' => $this->tahun,
            'unors' => $unors,
            'subunit' => $subunit,
            'opdlain' => $opdlain,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi,
            'pokjab' => $pokjab
        ]);
    }

    public function cetakDaftarAsn()
    {
        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });

        $daftarPegawai = Dft_pegawai::where('pangkat', '!=', 'Non-PNS')->orderBy('norut', 'asc')->get();
    
        $pdf = PDF::loadView('kepegawaian.cetakDaftarAsn', [            
            'daftarPegawai' => $daftarPegawai,
            'admin' => $admin,
        ]);

        return $pdf->stream();
    }

    public function tambahAsn(Request $request)
    {
        $daftarPegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $cekPegawai = $daftarPegawai->where('nip', $request->nip)->count();
        if ($cekPegawai > 0) {
            return back()->with('fail', 'nip pegawai sudah ada!');
        }
        $unorg = Str::of($request->uorg)->explode('|');
        $kode_unit = $unorg[0];
        $unit_organisasi = $unorg[1];
        $lat = $unorg[2];
        $lot = $unorg[3];
        $tptlain = Str::of($request->tpt_lain)->explode('|');
        $kode_tpt_lain = $tptlain[0];
        $tpt_lain = $tptlain[1];
        $opdlain = Str::of($request->opd_lain)->explode('|');
        $kode_opd_lain = $opdlain[0];
        $opd_lain = $opdlain[1];
        $data = [
            'nama' => strip_tags($request->nama),
            'nip' => strip_tags($request->nip),
            'jenis_asn' => $request->jenis_asn,
            'status_jbt' => $request->status_jbt,
            'jenkel' => strip_tags($request->jenkel),
            'pangkat' => strip_tags($request->pangkat),
            'jabatan' => strip_tags($request->jabatan),
            'jenis_jbt' => strip_tags($request->jenjab),
            'unit_kerja' => strip_tags($request->uker),
            'unit_organisasi' => strip_tags($unit_organisasi),
            'tpt_lain' => strip_tags($tpt_lain),
            'opd_lain' => strip_tags($opd_lain),
            'kode_pd' => strip_tags($request->kode_pd),
            'kode_unit' => strip_tags($kode_unit),
            'kode_tpt_lain' => strip_tags($kode_tpt_lain),
            'kode_opd_lain' => strip_tags($kode_opd_lain),
            'tpp' => strip_tags($request->tpp),
            'tmt_absen' => strip_tags($request->tmt_absen),
            'norut' => strip_tags($request->norut),
            'status' => 'aktif',
        ];
        $user = [
            'username' => strip_tags($request->nip),
            'nama_asli' => strip_tags($request->nama),
            'password' => 'lampura',
            'unit_kerja' => strip_tags($request->uker),
            'unit_organisasi' => strip_tags($unit_organisasi),
            'sebagai' => 'pegawai',
            'pertama' => 'true',
        ];

        if ($request->jenkel == "Laki-laki") {
            $data['foto'] = 'no_image.png';
        } elseif ($request->jenkel == 'Perempuan') {
            $data['foto'] = 'no_image2.jpg';
        }
        if ($request->file('foto_pegawai')) {
            $request->validate([
                'foto_pegawai' => 'image|file|max:200'
            ]);

            $ext = $request->foto_pegawai->extension();
            $file = $request->file('foto_pegawai')->storeAs('foto_pegawai', $request->nip . '-' . now()->format('H-i-s') . '.' . $ext);

            $data['foto'] = $request->nip . '-' . now()->format('H-i-s') . '.' . $ext;
        }

        Dft_pegawai::create($data);
        Daftar_admin::create($user);
        cache::forget('DaftarPegawai');
        if ($request->mutasi_masuk) {
            $db = $this->db;
            $db->table('alih_mutasi')->where('nip', $request->nip)->where('tmt_mutasi', $request->tmt_mutasi)->update([
                'respon' => 'sudah'
            ]);
            return redirect('/kepegawaian/mutasiMasuk')->with('success', 'pegawai berhasil ditambahkan');
        }
        return back()->with('success', 'data berhasil ditambah');
    }

    public function updateAsn(Request $request)
    {
        $unorg = Str::of($request->uorg)->explode('|');
        $kode_unit = $unorg[0];
        $unit_organisasi = $unorg[1];
        $lat = $unorg[2];
        $lot = $unorg[3];
        $tptlain = Str::of($request->tpt_lain)->explode('|');
        $kode_tpt_lain = $tptlain[0];
        $tpt_lain = $tptlain[1];
        $opdlain = Str::of($request->opd_lain)->explode('|');
        $kode_opd_lain = $opdlain[0];
        $opd_lain = $opdlain[1];
        $data = [
            'nama' => strip_tags($request->nama),
            'nip' => strip_tags($request->nip),
            'jenis_asn' => $request->jenis_asn,
            'status_jbt' => $request->status_jbt,
            'jenkel' => strip_tags($request->jenkel),
            'pangkat' => strip_tags($request->pangkat),
            'jabatan' => strip_tags($request->jabatan),
            'jenis_jbt' => strip_tags($request->jenjab),
            'unit_kerja' => strip_tags($request->uker),
            'unit_organisasi' => strip_tags($unit_organisasi),
            'tpt_lain' => strip_tags($tpt_lain),
            'opd_lain' => strip_tags($opd_lain),
            'kode_pd' => strip_tags($request->kode_pd),
            'kode_unit' => strip_tags($kode_unit),
            'kode_tpt_lain' => strip_tags($kode_tpt_lain),
            'kode_opd_lain' => strip_tags($kode_opd_lain),
            'tpp' => strip_tags($request->tpp),
            'tmt_absen' => strip_tags($request->tmt_absen),
            'norut' => strip_tags($request->norut),
            'status' => 'aktif',
        ];

        if ($request->file('foto_pegawai')) {
            Storage::delete('foto_pegawai/' . $request->old_foto);
            $ext = $request->foto_pegawai->extension();
            $folder = "img/foto_pegawai";
            $file = $request->file('foto_pegawai')->storeAs('foto_pegawai', $request->nip . '-' . now()->format('H-i-s') . '.' . $ext);

            $data['foto'] = $request->nip . '-' . now()->format('H-i-s') . '.' . $ext;
        }

        Dft_pegawai::where('id', $request->id)->update($data);
        
        if ($request->resetpass == '1'){
            Daftar_admin::where('username', $request->nip)->update([
                'pertama' => 'true',
                'password' => 'lampura',
            ]);
        }
        cache::pull('DaftarPegawai');
        cache::pull($request->nip);
        return back()->with('success', 'data berhasil diubah');
    }

    public function hapusAsn(Request $request)
    {
        $data = Dft_pegawai::where('id', $request->idpeg)->first();
        $foto = 'foto_pegawai/' . $data->foto;
        $delete = Storage::delete($foto);
        $username = $data->nip;
        Daftar_admin::where('username', $username)->delete();

        $hapus = Dft_pegawai::destroy($request->idpeg);
        cache::forget('DaftarPegawai');
        if ($hapus) {
            return 1;
        } else {
            return 0;
        }
    }

    public function ajaxTpt(Request $request)
    {
        $tbPD = cache()->remember('tbPD', 60 * 60 * 24, function () {
            $db = $this->db;
            return $db->table('tb_pd')->get();
        });

        $subunit = $tbPD->where('kode_unit', $request->kode_uorg)->where('kode_tpt_lain', '!=', '');
        return view('kepegawaian.daftar_tpt', [
            'subunit' => $subunit
        ]);
    }

    public function dataAsn(Request $request)
    {
        $kode_pd = config('global.kode_pd');
        $tbPD = cache()->remember('tbPD', 60 * 60 * 24, function () {
            $db = $this->db;
            return $db->table('tb_pd')->get();
        });
        $dataOpd = $tbPD->where('kode_pd', $kode_pd);
        $unors = $dataOpd->where('unit_organisasi', '!=', '');
        $subunit = $dataOpd->where('tpt_lain', '!=', '');
        $opdlain = $tbPD->unique('kode_pd');
        $tahun = $this->tahun;

        return DataTables::of(Dft_pegawai::where('pangkat', '!=', 'Non-PNS')->orderBy('norut', 'asc'))
            ->addColumn(
                'aksi',
                function ($model) {
                    return '<div class="d-flex justify-content-center"><button class="btn btn-sm btn-info" onclick="updatePegawai(' . $model->id . ')" title="update">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-warning ml-2" title="mutasi" onclick="mutasiPegawai(' . $model->id . ')">
                <i class="fas fa-user-minus"></i>
                </button>
                <button class="btn btn-sm btn-danger ml-2 hapus_' . $model->id . '" onclick="hapusPegawai(' . $model->id . ')" title="hapus">
                <i class="fas fa-trash-alt"></i>
                </button></div>';
                }
            )

            ->addColumn('unit_organisasi', function ($model) {
                $html =
                    '<ul class="pl-1">
                <li>' . $model->unit_organisasi . '</li>';
                if ($model->kode_tpt_lain) {
                    $html .= '<li>' . $model->tpt_lain . '</li>';
                } elseif ($model->opd_lain) {
                    $html .= '<li>' . $model->opd_lain . '</li>';
                } else {
                    $html .= '<ul>';
                }
                return $html;
            })
            ->addColumn('foto', function ($model) {
                return '<img class="gb_absen img-fluid" src="/storage/foto_pegawai/' . $model->foto . '" width="75px" onclick="gbAbsen(`' . $model->foto . '`)">';
            })
            ->rawColumns(['aksi', 'nama', 'unit_organisasi', 'foto'])
            ->addIndexColumn()->make(true);
    }

    public function cobaPegawai(Request $request)
    {

        $daftarPegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $id = $request->id;
        $asn =  $daftarPegawai->where('id', $request->id)->first();
        $kode_pd = config('global.kode_pd');
        $tbPD = cache()->remember('tbPD', 60 * 60 * 24, function () {
            $db = $this->db;
            return $db->table('tb_pd')->get();
        });
        $dataOpd = $tbPD->where('kode_pd', $kode_pd);
        $unors = $dataOpd->where('unit_organisasi', '!=', '');
        $subunit = $dataOpd->where('tpt_lain', '!=', '');
        $opdlain = $tbPD->unique('kode_pd');
        $resetpass = Daftar_admin::where('username', $asn->nip)->pluck('pertama')->first();

        $db = $this->db;
        $pokjab = $db->table('kelompok_jabs')->where('kode_pd', $kode_pd)->get();

        return view('kepegawaian.updatePegawai', [
            'asn' => $asn,
            'tahun' => $this->tahun,
            'unors' => $unors,
            'subunit' => $subunit,
            'opdlain' => $opdlain,
            'resetpass' => $resetpass,
            'pokjab' => $pokjab
        ]);
    }

    public function dataMutasiPegawai(Request $request)
    {

        $daftarPegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $id = $request->id;
        $asn =  $daftarPegawai->where('id', $request->id)->first();
        $kode_pd = config('global.kode_pd');
        $tbPD = cache()->remember('tbPD', 60 * 60 * 24, function () {
            $db = $this->db;
            return $db->table('tb_pd')->get();
        });
        $dataOpd = $tbPD->unique('kode_pd');

        return view('kepegawaian.dataMutasiPegawai', [
            'asn' => $asn,
            'tahun' => $this->tahun,
            'dataOpd' => $dataOpd

        ]);
    }


    public function tambahPenandatangan(Request $request)
    {
        $kepala = explode(',', $request->kepala);
        $sekretaris = explode(',', $request->sekretaris);
        $admin_absen = explode(',', $request->admin_absen);

        $data = [
            'kepala' => $kepala[1],
            'nip_kepala' => htmlspecialchars($request->nip_kepala),
            'sekretaris' => $sekretaris[1],
            'nip_sekretaris' => htmlspecialchars($request->nip_sekretaris),
            'admin_absen' => $admin_absen[1],
            'nip_admin' => htmlspecialchars($request->nip_admin),
        ];
        Tandatangan::where('id', 1)->update($data);
        cache()->pull('Admin');
        return back()->with('success', 'berhasil update penandatangan');
    }

    public function nonAsn(Request $request)
    {

        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();

        $pegawaiNon = $pegawai->where('pangkat', 'Non-PNS')->sortBy('norut');

        $kode_pd = config('global.kode_pd');
        $tbPD = cache()->remember('tbPD', 60 * 60 * 24, function () {
            $db = $this->db;
            return $db->table('tb_pd')->get();
        });
        $dataOpd = $tbPD->where('kode_pd', $kode_pd);
        $unors = $dataOpd->where('unit_organisasi', '!=', '');
        $subunit = $dataOpd->where('tpt_lain', '!=', '');
        $opdlain = $tbPD->unique('kode_pd');


        return view('kepegawaian.nonAsn', [
            'pegawaiNon' => $pegawaiNon,
            'tahun' => $this->tahun,
            'unors' => $unors,
            'subunit' => $subunit,
            'opdlain' => $opdlain,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi
        ]);
    }

    public function adminChangePass(Request $request)
    {
        $username = session()->get('adminAbsensi');
        $passlama = htmlspecialchars($request->passlama);
        $passbaru = htmlspecialchars($request->passbaru);
        $data = Daftar_admin::where('username', $username)->first();
        if ($data->password === $passlama) {
            Daftar_admin::where('username', $username)->update([
                'password' => $passbaru
            ]);
            return back()->with('berhasil', 'berhasil ubah password');
        } else {
            return back()->with('gagalUbah', 'password lama tidak sesuai!');
        }
    }

    public function mutasiKeluar(Request $request)
    {
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();
        $data = Mutasi_pegawai::all();
        return view('kepegawaian.mutasiKeluar', [
            'data' => $data,
            'tahun' => $this->tahun,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'notifMutasi' => $this->notifMutasi
        ]);
    }

    public function mutasiMasuk(Request $request)
    {
        $pegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });

        $admin = cache::remember('Admin', now()->addMonths(3), function () {
            return Tandatangan::first();
        });
        $foto_admin = $pegawai->where('nip', $admin->nip_admin)->pluck('foto')->first();
        $db = $this->db;
        $data = $db->table('alih_mutasi')->where('kode_pindah', config('global.kode_pd'))->where('respon', 'belum')->get();
        $tbPD = cache()->remember('tbPD', 60 * 60 * 24, function () {
            $db = $this->db;
            return $db->table('tb_pd')->get();
        });
        $kode_pd = config('global.kode_pd');
        $dataOpd = $tbPD->where('kode_pd', $kode_pd);
        $unors = $dataOpd->where('unit_organisasi', '!=', '');
        $subunit = $dataOpd->where('tpt_lain', '!=', '');
        $opdlain = $tbPD->unique('kode_pd');
        $pegawaiAsn = $pegawai->where('pangkat', '!=', 'Non-PNS')->sortBy('norut');

        $db = $this->db;
        $pokjab = $db->table('kelompok_jabs')->where('kode_pd', $kode_pd)->get();       

        return view('kepegawaian.mutasiMasuk', [
            'data' => $data,
            'tahun' => $this->tahun,
            'admin' => $admin,
            'foto_admin' => $foto_admin,
            'unors' => $unors,
            'subunit' => $subunit,
            'opdlain' => $opdlain,
            'notifMutasi' => $this->notifMutasi,
            'pokjab' => $pokjab,
            'pegawaiAsn' => $pegawaiAsn

        ]);
    }

    public function prosesMutasi(Request $request)
    {
        $daftarPegawai = cache::remember('DaftarPegawai', now()->addMonths(3), function () {
            return Dft_pegawai::all();
        });
        $asn =  $daftarPegawai->where('nip', $request->nip)->first();
        $db = $this->db;
        if ($request->pindahke) {
            $pindahke = $request->pindahke;
            $pindahke = Str::of($pindahke)->explode('|');
            $ke = $pindahke[0];
            $kode_ke = $pindahke[1];
        } else {
            $ke = NULL;
            $kode_ke = NULL;
        }


        $data = [
            'kode_asal' => config('global.kode_pd'),
            'asal_opd' => config('global.nama_lain'),
            'nama' => $asn->nama,
            'nip' => $asn->nip,
            'jenkel' => $asn->jenkel,
            'pangkat' => $asn->pangkat,
            'jabatan' => $asn->jabatan,
            'jenis_jbt' => $asn->jenis_jbt,
            'unit_kerja' => $asn->unit_kerja,
            'unit_organisasi' => $asn->unit_organisasi,
            'foto' => $asn->foto,
            'tpp' => $asn->tpp,
            'jenis_mutasi' => strip_tags($request->jenmut),
            'tmt_mutasi' => strip_tags($request->tmt_mutasi),
            'pindah_ke' => $ke,
            'kode_pindah' => $kode_ke,
            'akhir_absen' => strip_tags($request->akhir_absen)
        ];
        Mutasi_pegawai::create($data);
        Dft_pegawai::where('nip', $request->nip)->delete();
        cache::forget('DaftarPegawai');
        if ($request->jenmut == 'Pindah OPD') {
            $data['respon'] = 'belum';
            unset($data['tpp']);
            unset($data['jenis_mutasi']);
            $data['foto'] = 'no_image.png';
            $db->table('alih_mutasi')->insert($data);
        }

        return back()->with('success', 'berhasil mutasi pegawai');
    }

    public function cekMutasi($nip, $tmt_mutasi)
    {
        $db = $this->db;
        $cek = $db->table('alih_mutasi')->where('nip', $nip)->where('tmt_mutasi', $tmt_mutasi)->pluck('respon')->first();
        return $cek;
    }

    public function tolakMutasi(Request $request)
    {
        $db = $this->db;
        $cek = $db->table('alih_mutasi')->where('id', $request->id)->update([
            'respon' => 'ditolak'
        ]);
        return $cek;
    }
}
