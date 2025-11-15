<?php 

use Illuminate\Support\Carbon;

  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=RekPNS-I-II-".$bulan."-".$tahun.".xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            font-size: .7rem;
            border-collapse: collapse;
        }

        td {
            text-align: center;
            padding: 5px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <table style="font-size: .7rem" id="rekapBulan" border='1'>
        <thead>
            <tr style="padding: 5px; background-color:lightblue">
                <th>No</th>
                <th>Nama / NIP</th>
                <th style="padding-left:15px; padding-right:15px">Hari <br> Tanggal</th>
                @foreach ($tglabsen as $tgl)
                <th class="text-center">
                    {{ Carbon::parse($tgl.'-'.$bulan.'-'.$tahun)->translatedFormat('l') }}
                    <br>
                    [ {{ $tgl }} ]
                </th>
                @endforeach
            </tr>

        </thead>
        <tbody>

            @foreach($data as $dts)
            <tr>
                <td rowspan="2">{{ $loop->iteration }}</td>
                <td rowspan="2" style="vertical-align: middle; text-align:left">
                    {{ $dts->first()->nama }} <br> {{ "NIP. ".(string)$dts->first()->nip }} <br> {{
                    $dts->first()->pangkat }}
                    <br> {{ $dts->first()->jabatan }}
                </td>
                <td>
                    &emsp; masuk &emsp;
                </td>
                <?php $dts = collect($dts); $dts = $dts->sortBy(['tanggal', 'desc']); ?>
                @foreach($dts as $dt)
                <td class="text-center">
                    {!! $dts->count() < $tglabsen->count() ? $dt->tanggal.'<br>' : '' !!}
                        {{ $dt->keterangan }} <br>
                        {{ $dt->keterangan=='hadir' || $dt->keterangan=='wfh' ? $dt->waktu : '-' }} <br>
                        {{ $dt->pengurangan }} %
                </td>
                @endforeach

            </tr>
            <tr>
                <td>pulang</td>
                <?php $dts = collect($dts); $dts = $dts->sortBy(['tanggal', 'desc']); ?>
                @foreach($dts as $dt)
                <td class="text-center">
                    {!! $dts->count() < $tglabsen->count() ? $dt->tanggal.'<br>' : '' !!}
                        {{ $dt->keterangan_p }} <br>
                        {{ $dt->keterangan_p=='hadir' || $dt->keterangan_p=='wfh' ? $dt->pulang : '-' }} <br>
                        {{ $dt->pengurangan_p }} %
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>

    </table>


</body>

</html>