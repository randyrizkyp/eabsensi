<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dft_pegawai;
use App\Models\Absen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;

use Image;

use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function dataPegawai(Request $request)
    {
        $data = Dft_pegawai::all();
        return $data;
    }

    public function updatePegawai(Request $request)
    {
        
       $perangkat = $request->perangkat;
       $url = $request->url;
      
       $pass = $perangkat . "b4Jin64n" . now()->format('d-m-y');
       if(Hash::check($pass, $request->passCode)){
        $db = DB::connection('eabsensi_opd');
        $jamKerja = Cache::remember('jam_kerja_opd', now()->addMonths(1), function () use ($db) {
            return  $db->table('jam_kerja_opds')->get();
        });
        $hari = Carbon::parse($request->tahun.'/'.$request->bulan.'/'.$request->tanggal)->translatedFormat('l');
        
        if($request->keterangan=='hadir'){
            $jamMasuk = $jamKerja->where('hari', $hari)->pluck('masuk')->first();
            $tglskrg = Carbon::now()->translatedFormat('d-m-Y,' . $request->waktu);
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

        }elseif($request->keterangan=='dinas luar'){
            $selisih = 0;
            $pengurangan = 0;
            $skor = 90;
        }elseif($request->keterangan=='sakit'){
            $selisih = 0;
            $pengurangan = 0;
            $skor = 25;
        }elseif($request->keterangan=='izin'){
            $selisih = 0;
            $pengurangan = 0;
            $skor = 25;
        }elseif($request->keterangan=='tanpa keterangan'){
            $selisih = 500;
            $pengurangan = 1.5;
            $skor = 0;
        }
        
        if($request->keterangan_p=='hadir'){
            $jamPulang = $jamKerja->where('hari', $hari)->pluck('pulang')->first();  
            $tglskrg = Carbon::now()->translatedFormat('d-m-Y,' . $request->pulang);
            $tglbanding = now()->translatedFormat('d-m-Y') . ',' . $jamPulang;
            $tglhasil = Carbon::create($tglbanding)->translatedFormat('d-m-Y, H:i:s');
            $selwak = strtotime($tglskrg) - strtotime($tglhasil);
            $selisih_p = floor($selwak / 60);
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


        }elseif($request->keterangan_p=='dinas luar'){
            $selisih_p = 0;
            $pengurangan_p = 0;
            $skor_p = 90;
        }elseif($request->keterangan_p=='sakit'){
            $selisih_p = 0;
            $pengurangan_p = 0;
            $skor_p = 25;
        }elseif($request->keterangan_p=='izin'){
            $selisih_p = 0;
            $pengurangan_p = 0;
            $skor_p = 25;
        }elseif($request->keterangan_p=='belum absen'){
            $selisih_p = -500;
            $pengurangan_p = 0;
            $skor_p = 0;
        }elseif($request->keterangan_p=='tidak absen'){
            $selisih_p = -500;
            $pengurangan_p = 1.5;
            $skor_p = 0;
        }elseif($request->keterangan_p=='tanpa keterangan'){
            $selisih_p = -500;
            $pengurangan_p = 1.5;
            $skor_p = 0;
        }
        Absen::where('id', $request->id)->update([
            'waktu' => $request->waktu,
            'pulang' => $request->pulang,
            'keterangan' => $request->keterangan,
            'keterangan_p' => $request->keterangan_p,
            'selisih' => $selisih,
            'selisih_p' => $selisih_p,
            'pengurangan' => $pengurangan,
            'pengurangan_p' => $pengurangan_p,
            'konfirmasi' => 'confirmed',
            'konfirmasi_p' => 'confirmed',
            'validasi' => $request->validasi,
            'validasi_p' => $request->validasi_p,
            'skor' => $skor,
            'skor_p' => $skor_p,
        ]);

        if ($request->file('foto')) {
           
            $ext = $request->foto->extension();
            $folder =  "storage/uploads/$request->tahun/$request->bulan/";
            $namaFile = $request->nip . '-M-' . uniqid() . '.'.$ext;
            $image = Image::make($request->file('foto'));
            $image->resize(360,480);
            // $image->text('testing text',10,10);
            $image->save($folder.$namaFile);
            $nmf = "uploads/$request->tahun/$request->bulan/".$namaFile;
            
            Absen::where('id', $request->id)->update([
                'foto' => $nmf
            ]);
        }
        if ($request->file('foto_b')) {
            $ext = $request->foto_b->extension();
            $folder =  "storage/uploads/$request->tahun/$request->bulan/";
            $namaFile = $request->nip . '-Mb-' . uniqid() . '.'.$ext;
            Image::make($request->file('foto_b'))->resize(360,480)->save($folder.$namaFile);
            $nmf = "uploads/$request->tahun/$request->bulan/".$namaFile;
            
            Absen::where('id', $request->id)->update([
                'foto_b' => $nmf
            ]);
        }
        if ($request->file('foto_p')) {
            $ext = $request->foto_p->extension();
            $folder =  "storage/uploads/$request->tahun/$request->bulan/";
            $namaFile = $request->nip . '-P-' . uniqid() . '.'.$ext;
            Image::make($request->file('foto_p'))->resize(360,480)->save($folder.$namaFile);
            $nmf = "uploads/$request->tahun/$request->bulan/".$namaFile;
            
            Absen::where('id', $request->id)->update([
                'foto_p' => $nmf
            ]);
        }
        if ($request->file('foto_pb')) {
            $ext = $request->foto_pb->extension();
            $folder =  "storage/uploads/$request->tahun/$request->bulan/";
            $namaFile = $request->nip . '-Pb-' . uniqid() . '.'.$ext;
            Image::make($request->file('foto_pb'))->resize(360,480)->save($folder.$namaFile);
            $nmf = "uploads/$request->tahun/$request->bulan/".$namaFile;
            
            Absen::where('id', $request->id)->update([
                'foto_pb' => $nmf
            ]);
        }
       
    
        return redirect($url."&update=true");
       }else{
        return redirect($url."&gagal=true");
       }
        
       
    }

    public function test_api(Request $request)
    {
        $data = Dft_pegawai::all();
        return 'okee';
    }

    
    public function flush(Request $request)
    {
        $url = $request->url;
        $kd_pd = $request->kd_pd;
        $nama_lain = $request->nama_lain;
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $db = DB::connection('eabsensi_opd');
        $db->table('flush')->where('kode_pd', config('global.kode_pd'))->update([
            'flush' => false
        ]);
        return redirect($url.'?flush='.$nama_lain);
    }

}
