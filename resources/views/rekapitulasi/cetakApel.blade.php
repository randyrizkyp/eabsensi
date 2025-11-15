<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title>Rekap Apel</title>
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
                <h3>{{ config('global.nama_pd') }}</h3>
                <h3>Rekap Apel, Bulan {{ $bulan }} Tahun {{ $tahun }}</h3>
                <h5>Tanggal Cetak : {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</h5>
            </td>
        </tr>
    </table>
    <hr>

    <section>
        <table border="1">
            <thead>
                <tr style="padding-top:10px; background-color:bisque">
                    <th style="padding:10px;">No</th>
                    <th>Nama / NIP</th>
                    <th>Apel</th>
                    <th>Hadir</th>
                    <th>DL</th>
                    <th>Sakit</th>
                    <th>Cuti</th>
                    <th>Izin</th>
                    <th>TK</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($data as $dts)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: left; padding:5px 5px;">
                        {{ $dts->first()->nama }} <br> {{ $dts->first()->nip }} <br> {{ $dts->first()->pangkat }}
                        <br> {{ $dts->first()->jabatan }}
                    </td>
                    <?php $dts = collect($dts); $dts = $dts->sortBy(['tanggal', 'desc']); ?>
                    <td class="text-center">
                        {{ $dts->where('apel_pagi', 'hadir')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dts->where('keterangan', 'hadir')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dts->where('keterangan', 'dinas luar')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dts->where('keterangan', 'sakit')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dts->where('keterangan', 'cuti')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dts->where('keterangan', 'izin')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dts->where('keterangan', 'tanpa keterangan')->count() }}
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
                    Sekretaris {{ config('global.nama_lain') }}
                </td>
                <td colspan="6" align="center" style="padding-top: 25px">
                    Kasubbag Umum & Kepegawaian
                </td>
            </tr>
            <tr>
                <td colspan="6">Plt.Kepala {{ config('global.nama_lain') }}</td><br><br><br>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="6" align="center" style="padding-top: 25px">
                    <u>{{ $admin->kepala }}</u>
                </td>
                <td colspan="6" align="center" style="padding-top: 25px">
                    <u>{{ $admin->sekretaris }}</u>
                </td>
                <td colspan="6" align="center" style="padding-top: 25px">
                    <u>{{ $admin->admin_absen }}</u>
                </td>
            </tr>
            <tr>
                <td colspan="6">NIP.{{ $admin->nip_kepala }}</td>
                <td colspan="6">NIP.{{ $admin->nip_sekretaris }}</td>
                <td colspan="6">NIP.{{ $admin->nip_admin }}</td>
            </tr>

        </table>
    </section>
</body>

</html>