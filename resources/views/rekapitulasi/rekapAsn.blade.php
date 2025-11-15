@extends('templatesLTE.main')
@section('content')
<?php 
use App\Http\Controllers\RekapitulasiController;
use Illuminate\Support\Carbon;
$rekap = new RekapitulasiController;
?>
<div class="row">
    <div class="col-md-12 p-0 mx-0">
        <div class="mt-4" style="background : linear-gradient(to left ,#8f9103, #f4fad8, #8f9103);">
            <h4 class="text-center pt-2 adopd2">REKAP ABSENSI ASN PER BULAN </h4>

            <br>

            <div class="d-flex justify-content-center">
                <form class="form-inline" action="/rekapitulasi/asn" method="post">
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
            <form action="/rekapAsnExcel" method="post">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-info"><img src="/img/excel.png"
                        width='25px'>&ensp;Download</button>
            </form>
            <form action="/rekapitulasi/Asnpdf" method="post" class="pl-3">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-info"><img src="/img/pdf.png"
                        width="25px">&ensp;Download</button>
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
                        @foreach ($tglabsen as $tgl)
                        <?php  
                             $keterangan = $dts->where('tanggal', $tgl)->pluck('keterangan')->first();
                             if($keterangan=='tanpa keterangan'){
                                $ket = 'TK';
                             }elseif($keterangan == 'dinas luar'){
                                $ket = 'DL';
                             }else{
                                $ket = $keterangan;
                             }
                        ?>
                        <td class="text-center">
                            @if($keterangan)
                            {{ $ket }} <br>
                            {{ $keterangan == 'hadir' ? $dts->where('tanggal', $tgl)->pluck('waktu')->first() : '-' }}
                            <br>
                            {{ $dts->where('tanggal', $tgl)->pluck('pengurangan')->first() . '%' }}
                            @endif

                        </td>
                        @endforeach

                    </tr>
                    <tr>
                        <td>pulang</td>
                        <?php $dts = collect($dts); $dts = $dts->sortBy(['tanggal', 'desc']); ?>
                        @foreach ($tglabsen as $tgl)
                        <?php  
                            $keterangan_p = $dts->where('tanggal', $tgl)->pluck('keterangan_p')->first();
                            if($keterangan_p =='tanpa keterangan'){
                            $ket_p = 'TK';
                            }elseif($keterangan_p == 'dinas luar'){
                            $ket_p = 'DL';
                            }else{
                            $ket_p = $keterangan_p;
                            }
                        ?>
                        <td class="text-center">
                            @if($keterangan_p)
                            {{ $ket_p }} <br>
                            {{ $keterangan_p == 'hadir' ? $dts->where('tanggal', $tgl)->pluck('pulang')->first() : '-'
                            }}
                            <br>
                            {{ $dts->where('tanggal', $tgl)->pluck('pengurangan_p')->first() . '%' }}
                            @endif
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