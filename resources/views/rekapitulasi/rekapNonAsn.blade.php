@extends('templatesLTE.main')
@section('content')
<?php 
use App\Http\Controllers\RekapitulasiController;
use Illuminate\Support\Carbon;
$rekap = new RekapitulasiController;
?>
<div class="row">
    <div class="col-md-12 p-0 mx-0">
        <div class="mt-4" style="background : linear-gradient(to left ,#6DECFF, #9DB6FC, #C6EEFF);">
            <h4 class="text-center pt-2 adopd2">REKAP ABSENSI NON-ASN PER BULAN </h4>

            <br>

            <div class="d-flex justify-content-center">
                <form class="form-inline" action="/rekapitulasi/NonAsn" method="post">
                    @csrf
                    <div class="form-group mb-2 ml-2">
                        <select class="custom-select mr-sm-2" id="carbul" name="bulan">
                            @foreach($namaBulan as $nambul)
                            <option value="{{ $nambul['angka'] }}" {{ $bulan==$nambul['angka'] ? 'selected' : '' }}>
                                {{ $nambul['nama'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2 ml-2">
                        <select class="custom-select mr-sm-2" id="carbul" name="tahun">
                            <option {{ $tahun==now()->translatedFormat('Y')-2 ? 'selected' : '' }}>
                                {{ now()->translatedFormat('Y')-2 }}</option>
                            <option {{ $tahun==now()->translatedFormat('Y')-1 ? 'selected' : '' }}>
                                {{ now()->translatedFormat('Y')-1 }}</option>
                            <option {{ $tahun==now()->translatedFormat('Y') ? 'selected' : '' }}>{{
                                now()->translatedFormat('Y') }}</option>
                        </select>
                    </div>
                    <button type="submit" name="carsenrang" class="btn btn-primary mb-2 ml-3">Cari
                        Absen</button>
                </form>
            </div>
        </div>

    </div>
</div>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="d-flex mb-3">
            <form action="/rekapNonAsnExcel" method="post">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-info"><img src="/img/excel.png"
                        width="25px">&ensp;Download</button>
            </form>
            <form action="/rekapitulasi/NonAsnpdf" method="post" class="pl-3">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-info"><img src="/img/pdf.png"
                        width="25px">&ensp;Download Pdf</button>
            </form>
        </div>

        <section style="width: 100%; height: 90vh; overflow : scroll">
            <table class="table table-bordered" style="font-size: .7rem" id="rekapBulan">
                <thead>
                    <tr class="bg-info">
                        <th>No</th>
                        <th>Nama / NIP</th>
                        <th>Hari <br> Tanggal</th>
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
                        <td rowspan="2">
                            {{ $dts->first()->nama }} <br> {{ $dts->first()->nip }} <br> {{ $dts->first()->pangkat }}
                            <br> {{ $dts->first()->jabatan }}
                        </td>
                        <td>
                            masuk
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
        </section>

    </div>
</div>

@endsection
@push('script')
<script>
    $('#rekapBulan').dataTable();
</script>

@endpush