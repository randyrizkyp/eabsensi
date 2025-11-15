<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Daftar_admin;
use App\Models\Loguser;
use App\Models\Absen;


class MasukController extends Controller
{

    public function index(Request $request)
    {

        $data = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = Daftar_admin::where('username', $request->username)->first();
        if (!$user) {
            return back()->with('fail', 'username tidak ada!');
        } else {
            $pass = $user->password;
            $sebagai = $user->sebagai;
            if ($data['password'] == $pass) {
                if ($sebagai == 'admin_absensi') {
                    
                    $log = [
                        'username' => $request->username,
                        'status' => 'Login Masuk'
                    ];
                    Loguser::create($log);

                    $request->session()->put('adminAbsensi', $user->username);
                    return redirect('/dashboard/absenMasuk');
                } elseif ($sebagai == 'pegawai' && $user->pertama == 'true') {
                    $request->session()->put(config('global.nama_lain'), $user->username);
                    return redirect('/ubahPertama');
                } else {
                    $request->session()->put(config('global.nama_lain'), $user->username);
                    $request->session()->put('sekali_apel', 'true');
                    return redirect('/absensi/pilihKeterangan');
                }
            } else {

                session()->pull('adminAbsensi');
                $request->session()->invalidate();

                $request->session()->regenerateToken();
                return back()->with('fail', 'password salah!');
            }
        }


        return redirect('/')->with('loginerror', 'Login gagal!');
    }

    public function logout(Request $request)
    {
        if (session()->has('adminAbsensi')) {
            session()->pull('adminAbsensi');

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect('/');
        }
        if (session()->has(config('global.nama_lain'))) {
            session()->pull(config('global.nama_lain'));

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect('/');
        }
        // if (session()->has('loggedAdminIrwil')) {
        //     session()->pull('loggedAdminDesa');
        //     session()->pull('loggedAdmin');
        //     session()->pull('loggedAdimIrwil');
        //     $request->session()->invalidate();

        //     $request->session()->regenerateToken();
        //     return redirect('/');
        // }
    }

    public function ubahPertama(Request $request)
    {
        $username = session()->get(config('global.nama_lain'));
        $data = Daftar_admin::where('username', $username)->first();

        if (!$username || !$data) {
            return back()->with('fail', 'anda belum terdaftar!');
        }

        return view('login.ubahPertama', [
            'nama_lain' => config('global.nama_lain'),
            'username' => $username,
            'sebagai' => $data->sebagai,
            'id' => $data->id
        ]);
    }

    public function ubahPassLogin(Request $request)
    {
        $passbaru = $request->passbaru;
        $konfirmasi = $request->konfirmasi;
        $username = $request->username;
        $id = $request->id;
        if ($passbaru !== $konfirmasi) {
            return back()->with('fail', 'kanfirmasi tidak sesuai dgn password baru!');
        }

        $update = Daftar_admin::where('id', $id)->update([
            'password' => $passbaru,
            'pertama' => 'false'
        ]);

        if (!$update) {
            return back()->with('fail', 'gagal ubah password!');
        } else {
            return redirect('/')->with('success', 'berhasil ubah password, silahkan login dengan pass baru');
        }
    }

     public function editApel(Request $request)
    {
         $cekApels = Absen::where([
            'tahun' => now()->translatedFormat('Y'),
            'bulan' => now()->translatedFormat('m'),
            'tanggal' => '28',
            'tampil' => '1',
            'keterangan' => 'hadir',
            'apel_pagi' => 'hadir'
        ])->get();
        
        foreach($cekApels as $cek){
            $jam = $cek->waktu;
            $hms = explode(":", $jam);
            $jam_apel = ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
            if($jam_apel < 7.75){
               $update = Absen::where('id', $cek->id)->update([
                    'waktu' => '07:30:00',
                    'selisih' => 0,
                    'pengurangan' => 0
                ]);
                echo $cek->nip."=>".$cek->waktu."=>".$jam_apel."<br>";
             }
            
        }
        
    }


}
