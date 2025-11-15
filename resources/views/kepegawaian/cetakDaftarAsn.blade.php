<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title>Daftar ASN</title>
    <style>
        table {
            width: 100%;
            font-size: .8rem;
            border-collapse: collapse;
        }
        th {
            padding: 10px;
        }

        td {
            text-align: center;
        }

        h5,
        h4 {
            margin: 0;
            padding: 0;
            text-align: center;
        }

        #kop {
            display: flex;
        }
    </style>
</head>

<body>
    <table border="0">
        <tr>
            <td>
                <td><img src="{{ public_path('img/logo.png') }}" width="50px"></td>
            </td>
            <td width="93%" style="text-align: left">
               <h3>DAFTAR ASN</h3>
               <h3>{{ config('global.nama_pd') }}</h3>                
            </td>
        </tr>
    </table>
    <hr>

    <section>
        <table border="1">
            <thead>
               <tr style="padding-top:10px; background-color:bisque">
                  <th>No</th>
                  <th>Nama</th>
                  <th>NIP</th>
                  <th>Pangkat</th>
                  <th>Jabatan</th>
                  <th>Unit Organisasi <br>Tpt Tugas Lain</th>
                  <th>TMT Absen</th>
                  <th>TPP <br>(Rp)</th>                  
               </tr>

            </thead>
            <tbody>
               @foreach ($daftarPegawai as $dts)
               <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td style="text-align: left; padding:5px 5px;">
                     {{ $dts->nama }}
                  </td>                    
                  <td class="text-center">
                     {{ $dts->nip }}
                  </td>
                  <td class="text-center">
                      {{ $dts->pangkat }}
                  </td>
                  <td class="text-center">
                      {{ $dts->jabatan }}
                  </td>
                  <td class="text-center">
                      {{ $dts->unit_organisasi }}
                  </td>
                  <td class="text-center">
                     {{ $dts->tmt_absen }}
                  </td>
                  <td class="text-center">
                     {{ $dts->tpp }}
                  </td>                  
               </tr>
               @endforeach

            </tbody>            
        </table>
        <table width="100%">
            <tr>
                <td colspan="6" align="center" style="padding-top: 25px">
                    Mengetahui
                </td>
                <td colspan="6" align="center" style="padding-top: 25px">
                    Kasubbag Umum & Kepegawaian,
                </td>
            </tr>
            <tr>
                <td colspan="6">Kepala {{ config('global.nama_lain') }},</td>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="6" align="center" style="padding-top: 25px">
                    <u>{{ $admin->kepala }}</u>
                </td>
                <td colspan="6" align="center" style="padding-top: 25px">
                    <u>{{ $admin->admin_absen }}</u>
                </td>
            </tr>
            <tr>
                <td colspan="6">NIP.{{ $admin->nip_kepala }}</td>
                <td colspan="6">NIP.{{ $admin->nip_admin }}</td>
            </tr>

        </table>
    </section>
</body>

</html>