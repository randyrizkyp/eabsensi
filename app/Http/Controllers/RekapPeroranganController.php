<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Daftar_admin;
use App\Models\Dft_pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RekapPeroranganController extends Controller
{
    protected $tahun, $bulan, $tanggal, $hari, $nip, $rekapOrang, $dapeg, $namaBulan;

    public function __construct()
    {
        $this->tahun = now()->translatedFormat('Y');
        $this->bulan = now()->translatedFormat('m');
        $this->tanggal = now()->translatedFormat('d');
        $this->hari = now()->translatedFormat('l');
        $this->nip = session()->get(config('global.nama_lain'));

        $this->namaBulan = [['nama' => 'Januari', 'angka' => '01'], ['nama' => 'Februari', 'angka' => '02'], ['nama' => 'Maret', 'angka' => '03'], ['nama' => 'April', 'angka' => '04'], ['nama' => 'Mei', 'angka' => '05'], ['nama' => 'Juni', 'angka' => '06'], ['nama' => 'Juli', 'angka' => '07'], ['nama' => 'Agustus', 'angka' => '08'], ['nama' => 'September', 'angka' => '09'], ['nama' => 'Oktober', 'angka' => '10'], ['nama' => 'November', 'angka' => '11'], ['nama' => 'Desember', 'angka' => '12']];
    }

    public function rekapPerorangan(Request $request)
    {

        $tahun = $this->tahun;
        $bulan = $this->bulan;
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });

        $rekapOrang = Absen::where([
            'nip' => $this->nip,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tampil' => '1'
        ])->orderBy('tanggal', 'asc')->get();


        if ($request->bulan) {
            $tahun = $request->tahun;
            $bulan = $request->bulan;
            $rekapOrang = Absen::where([
                'nip' => $this->nip,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'tampil' => '1'
            ])->orderBy('tanggal', 'asc')->get();
        }
        $jumlahHariKerja = $this->jumlahHariKerja($bulan, $tahun);
        $jumlahTK = $rekapOrang->where('keterangan', 'tanpa keterangan')->count();
        $jumlahCuti = $rekapOrang->where('keterangan', 'cuti')->pluck('person');
        if ($jumlahCuti->count() > 0) {
            $cutiBesar = $jumlahCuti->filter(function ($value, $key) {
                return preg_match('/cuti2/i', $value);
            })->count();
            $cutiML4 = $jumlahCuti->filter(function ($value, $key) {
                return preg_match('/cuti5/i', $value);
            })->count();
            $cutiDTN = $jumlahCuti->filter(function ($value, $key) {
                return preg_match('/cuti7/i', $value);
            })->count();
        } else {
            $cutiBesar = 0;
            $cutiML4 = 0;
            $cutiDTN = 0;
        }



        return view('rekapitulasi.rekapPerorang', [
            'nama_lain' => config('global.nama_lain'),
            'tahun' => $tahun,
            'bulan' => $bulan,
            'nama' => $dapeg->nama,
            'nip' => $dapeg->nip,
            'uker' => $dapeg->unit_kerja,
            'uorg' => $dapeg->unit_organisasi,
            'jabatan' => $dapeg->jabatan,
            'foto' => $dapeg->foto,
            'rekapOrang' => $rekapOrang,
            'namaBulan' => $this->namaBulan,
            'jumlahHariKerja' => $jumlahHariKerja,
            'jumlahCuti' => $jumlahCuti,
            'cutiBesar' => $cutiBesar,
            'cutiML4' => $cutiML4,
            'cutiDTN' => $cutiDTN,
        ]);
    }

    public function daftarHadir(Request $request)
    {
        $daftarHadir = Absen::where([
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->orderBy('tanggal', 'asc')->get();
        $dapeg = Dft_pegawai::where('pangkat', '!=', 'Non-PNS')->orderBy('norut', 'asc')->get();
        return view('rekapitulasi.daftarHadir', [
            'nama_lain' => config('global.nama_lain'),
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'daftarHadir' => $daftarHadir,
            'dapeg' => $dapeg
        ]);
    }

    public function absenShow(Request $request)
    {
        if ($request->jenis_jbt) {
            $jenis_jbt = $request->jenis_jbt;
        } else {
            $jenis_jbt = 'Struktural';
        }

        if ($request->kategori) {
            $kategori = $request->kategori;
        } else {
            $kategori = 'masuk';
        }

        $daftarAbsen = Absen::where([
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
            'tampil' => '1'
        ])->where([
            'jenis_jbt' => $jenis_jbt
        ])->orderBy('waktu', 'asc')->get();

        return view('rekapitulasi.absenShow', [
            'nama_lain' => config('global.nama_lain'),
            'tanggal' => $this->tanggal,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'daftarAbsen' => $daftarAbsen,
            'jenis_jbt' => $jenis_jbt,
            'kategori' => $kategori,

        ]);
    }

    public function ubahProfil(Request $request)
    {
        $dapeg = cache::remember($this->nip, now()->addDays(7), function () {
            return Dft_pegawai::where('nip', $this->nip)->first();
        });
        $foto = $dapeg->foto;
        $admin = Daftar_admin::where('username', $this->nip)->first();
        return view('rekapitulasi.ubahProfil', [
            'dapeg' => $dapeg,
            'admin' => $admin
        ]);
    }

    public function updateProfil(Request $request)
    {

        if ($request->file()) {
            $lama = $request->oldFoto;
            $ext = $request->ubahFoto->extension();
            $file = $request->file('ubahFoto')->storeAs('foto_pegawai', $request->nip . '-' . now()->format('H-i-s') . '.' . $ext);
            $foto = $request->nip . '-' . now()->format('H-i-s') . '.' . $ext;
            Dft_pegawai::where('id', $request->id)->update([
                'foto' => $foto
            ]);
            cache::forget('DaftarPegawai');
            cache::forget($this->nip);
            return back()->with('success', 'berhasil ganti foto');
        }

        if ($request->passlama) {
            $passlama = htmlspecialchars($request->passlama);
            $passbaru = htmlspecialchars($request->passbaru);
            $data = Daftar_admin::where('username', $this->nip)->first();
            if ($data->password === $passlama) {
                Daftar_admin::where('username', $this->nip)->update([
                    'password' => $passbaru
                ]);
                return back()->with('gantiPass', 'berhasil ubah password');
            } else {
                return back()->with('gagal', 'password lama tidak sesuai!');
            }
        }
    }

    public function jumlahHariKerja($bulan, $tahun)
    {
        $jumhari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $tanggalBulan = [];
        for ($i = 01; $i <= $jumhari; $i++) {
            $tanggalBulan[] .= sprintf("%02d", $i);
        }
        $db = DB::connection('eabsensi_opd');
        $liburTahun = cache::remember('libur' . $tahun, now()->addMonths(3), function () use ($db, $tahun) {
            return $db->table('libur')->where('tahun', $tahun)->get();
        });
        $libur = $liburTahun->where('bulan', $bulan)->pluck('tanggal')->first();
        $libur = Str::of($libur)->explode(',')->toArray();

        $tanggalAbsen = [];
        foreach ($tanggalBulan as $tgb) {
            if (!in_array($tgb, $libur)) {
                $tanggalAbsen[] .= $tgb;
            }
        }
        return count($tanggalAbsen);
    }
}
