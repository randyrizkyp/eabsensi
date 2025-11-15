<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Absensi Harian</title>
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script> --}}

    <style>
        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px;
        }
        table {
            border-collapse: collapse;
        }
        td {
            padding : 5px;
        }
        th {
            padding: 8px 2px;
        }

    </style>

</head>

<body>
    <table >
        <thead>
            <tr>
                <th><img src="{{ public_path('/img/logolampura.jpg') }}" width="50px"></th>
                <th width="80%"><span class="tatas" style="font-size: 20px">{{ $nama_pd }}</span> <br>
                    <span class="tatas"> Rekap Absensi Harian {{ $status == 'asn' ? 'ASN' : 'Non-ASN' }}, Hari : {{ $hari }}, Tanggal : {{ $tanggal.'-'.$bulan.'-'.$tahun }}</span>

                </th>
            </tr>
        </thead>
    </table>
    
    @if($kedepan)
    <table width="100%" border="1" style="font-size: 11px">
        <thead style="font-size: 12px; ">
            <tr style="vertical-align: middle;background-color:#b1dff4" >
                <th rowspan="2" width="6%" style="vertical-align: middle">
                    No
                </th>
                <th rowspan="2" width="25%" style="vertical-align: middle">
                    Nama / NIP / Jabatan
                </th>
                <th colspan="3" align="center">Absen Masuk</th>
                <th colspan="3" align="center">
                    Absen Pulang
                </th>
            </tr>
            <tr style="vertical-align: middle;background-color:#b1dff4">
                <th align="center">Keterangan</th>
                <th align="center">Waktu</th>
                <th align="center">Konf/Val</th>
                <th align="center">Keterangan</th>
                <th align="center">Waktu</th>
                <th align="center">Konf/Val</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawais as $peg )
            <tr>
                <td align="center"> {{ $loop->iteration }}</td>
                <td>
                    {{ $peg->nama }} <br />
                    NIP.{{ $peg->nip }} <br />
                    <i>{{ $peg->jabatan }}</i>
                </td>
                <td align="center">
                    <?php $dahar = $dataAbsensi->where('nip', $peg->nip)->first();
                    ?>
                    {{ $dahar->keterangan ?? '-'}}
                </td>
                <td align="center">
                    {{ $dahar->waktu ?? '-' }}
                </td>
                <td align="center">
                    {{ $dahar->konfirmasi ?? '-' }} <br />
                    {{ $dahar->validasi ?? '-' }}
                </td>
                <td align="center">
                    {{ $dahar->keterangan_p  ?? '-'}}
                </td>
                <td align="center">
                    {{ $dahar->pulang ?? '-' }}
                </td>
                <td align="center">
                    {{ $dahar->konfirmasi_p  ?? '-'}} <br />
                    {{ $dahar->validasi ?? '-'}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <table width="100%" border="1" style="font-size: 11px">
        <thead style="font-size: 12px; ">
            <tr style="vertical-align: middle;background-color:#b1dff4" >
                <th rowspan="2" width="6%" style="vertical-align: middle">
                    No
                </th>
                <th rowspan="2" width="25%" style="vertical-align: middle">
                    Nama / NIP / Jabatan
                </th>
                <th colspan="3" align="center">Absen Masuk</th>
                <th colspan="3" align="center">
                    Absen Pulang
                </th>
            </tr>
            <tr style="vertical-align: middle;background-color:#b1dff4">
                <th align="center">Keterangan</th>
                <th align="center">Waktu</th>
                <th align="center">Konf/Val</th>
                <th align="center">Keterangan</th>
                <th align="center">Waktu</th>
                <th align="center">Konf/Val</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataAbsensi as $absen )
            <tr>
                <td align="center"> {{ $loop->iteration }}</td>
                <td>
                    {{ $absen->nama }} <br />
                    NIP.{{ $absen->nip }} <br />
                    <i>{{ $absen->jabatan }}</i>
                </td>
                <td align="center">
                  
                    {{ $absen->keterangan ?? '-'}}
                </td>
                <td align="center">
                    {{ $absen->waktu ?? '-' }}
                </td>
                <td align="center">
                    {{ $absen->konfirmasi ?? '-' }} <br />
                    {{ $absen->validasi ?? '-' }}
                </td>
                <td align="center">
                    {{ $absen->keterangan_p  ?? '-'}}
                </td>
                <td align="center">
                    {{ $absen->pulang ?? '-' }}
                </td>
                <td align="center">
                    {{ $absen->konfirmasi_p  ?? '-'}} <br />
                    {{ $absen->validasi ?? '-'}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
   
    <table width="100%" border="1" style="font-size: 11px; margin-top:10px">
        <tr style="background-color: darkcyan">
            <th class="pl-4">Keterangan</th>
            <th align="center">Hadir</th>
            <th align="center">DL</th>
            <th align="center">Izin</th>
            <th align="center">Sakit</th>
            <th align="center">Cuti</th>
            <th align="center">Tanpa Ket</th>
            <th align="center">Rejected</th>
            <th align="center">Un_Confirmed</th>
            <th align="center">Total</th>
        </tr>
        <tr>
            <th class="pl-4">Jumlah</th>
            <td align="center">
                {{ $dataAbsensi->where('keterangan', 'hadir')->where('konfirmasi','confirmed')->count() }}
            </td>
            <td align="center">
                {{ $dataAbsensi->where('keterangan', 'dinas luar')->where('konfirmasi','confirmed')->count()
                            }}
            </td>
            <td align="center">
                {{ $dataAbsensi->where('keterangan', 'izin')->where('konfirmasi','confirmed')->count() }}
            </td>
            <td align="center">
                {{ $dataAbsensi->where('keterangan', 'sakit')->where('konfirmasi','confirmed')->count() }}
            </td>
            <td align="center">
                {{ $dataAbsensi->where('keterangan', 'cuti')->where('konfirmasi','confirmed')->count() }}
            </td>
            <td align="center">
                {{ $dataAbsensi->where('keterangan', 'tanpa keterangan')->count() }}
            </td>
            <td align="center">
                {{ $dataAbsensi->where('konfirmasi', 'rejected')->count() }}
            </td>
            <td align="center">
                {{ $dataAbsensi->where('konfirmasi', 'un_confirmed')->count() }}
            </td>
            <td align="center">
                {{ $dataAbsensi->count() }}
            </td>
        </tr>
    </table>
    <hr>
    <table width="100%" style="font-size: 12px">
        <tr>
            <td colspan="6" align="center" style="padding-top: 20px">
                Mengetahui
            </td>
            <td colspan="6" align="center" style="padding-top: 20px">
                Kasubbag Umum & Kepegawaian,
            </td>
        </tr>
        <tr>
            <td colspan="6" align="center">Kepala {{ config('global.nama_lain') }},</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6" align="center" style="padding-top: 20px">
                <u>{{ $admin->kepala }}</u>
            </td>
            <td colspan="6" align="center" style="padding-top: 20px">
                <u>{{ $admin->admin_absen }}</u>
            </td>
        </tr>
        <tr>
            <td colspan="6" align="center">NIP.{{ $admin->nip_kepala }}</td>
            <td colspan="6" align="center">NIP.{{ $admin->nip_admin }}</td>
        </tr>

    </table>

    <footer>
        <small>Register : {{ $register }}</small>
    </footer>
</body>

</html>