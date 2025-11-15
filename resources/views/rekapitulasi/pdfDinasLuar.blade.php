<div class="row mt-4">
    <div class="col-md-12">
        <section>

        </section>

    </div>
</div>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title>Rekap Dinas Luar</title>
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
            padding: 5px;
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
            <td><img src="{{ public_path('img/logo.png') }}" width="50px"></td>
            <td width="93%" style="text-align: left">
                <h3>{{ config('global.nama_pd') }}</h3>
                <h3>Rekap Dinas Luar, Bulan {{ $bulan }} Tahun {{ $tahun }}</h3>
            </td>
        </tr>
    </table>
    <hr>
    <section>
        <table border="1" style="font-size: .7rem" id="rekapBulan">
            <thead>
                <tr style="background-color: aquamarine">
                    <th>No</th>
                    <th>Nama / NIP</th>
                    <th width="100px">Tanggal</th>
                    <th width="100px">Jenis DL</th>
                    <th>Tujuan</th>
                    <th>Dalam Rangka</th>
                    <th>Konfirmasi</th>
                </tr>

            </thead>
            <tbody>
                <?php $i=1; ?>
                @foreach ($dataDL as $dls)
                <?php $rowspan = $dls->count();
                      $nama = $dls->first()->nama;
                ?>
                @foreach ($dls as $dl)
                @if ($loop->first)
                <tr>
                    <td rowspan="{{ $rowspan }}">{{ $i}}</td>
                    <td rowspan="{{ $rowspan }}">
                        {{ $nama }} <br> NIP.{{ $dls->first()->nip }} <br>
                        {{ $dls->first()->pangkat }} <br> {{ $dls->first()->jabatan }}
                    </td>
                    <td>{{ $dl->tanggal.'-'.$dl->bulan.'-'.$dl->tahun }}</td>
                    <td>{{ Str::of($dl->person)->explode('|')[0] ?? '-'  }}</td>
                    <td>{{ Str::of($dl->person)->explode('|')[1] ?? '-' }}</td>
                    <td>{{ Str::of($dl->person)->explode('|')[2] ?? '-'  }}</td>
                    
                    <td>{{ $dl->konfirmasi }}</td>
                </tr>
                @else
                <tr>

                    <td>{{ $dl->tanggal.'-'.$dl->bulan.'-'.$dl->tahun }}</td>
                    <td>{{ Str::of($dl->person)->explode('|')[0] ?? '-'  }}</td>
                    <td>{{ Str::of($dl->person)->explode('|')[1] ?? '-'  }}</td>
                    <td>{{ Str::of($dl->person)->explode('|')[2] ?? '-'  }}</td>
                    
                    <td>{{ $dl->konfirmasi }}</td>
                </tr>
                @endif
                @endforeach
                <?php $i++; ?>
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
                <td colspan="6" align="center">Kepala {{ config('global.nama_lain') }},</td>
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
                <td colspan="6" align="center">NIP.{{ $admin->nip_kepala }}</td>
                <td colspan="6" align="center">NIP.{{ $admin->nip_admin }}</td>
            </tr>
        </table>
    </section>
</body>

</html>