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
            <h4 class="text-center pt-2 adopd2">REKAP DINAS LUAR PER BULAN </h4>

            <br>

            <div class="d-flex justify-content-center">
                <form class="form-inline" action="/rekapitulasi/dinasLuar" method="post">
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
                        Rekap</button>
                </form>
            </div>
        </div>

    </div>
</div>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="d-flex mb-2">
            <form action="/cetakRekapDL" method="post" target="_blank">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-info"><img src="/img/pdf.png" width="25px">&ensp;Download</button>

            </form>
        </div>

        <section>
            <table class="table table-bordered" style="font-size: .7rem" id="rekapBulan">
                <thead>
                    <tr class="bg-info">
                        <th>No</th>
                        <th>Nama / NIP</th>
                        <th width="100px">Tanggal</th>
                        <th width="100px">Jenis DL</th>
                        <th>Tujuan</th>
                        <th>Dalam Rangka</th>
                        <th>SPT/SPPD</th>
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
                        <td>
                            <img src="/storage/{{ $dl->foto }}" width="50px" class="gbAbsen">
                        </td>
                        <td>{{ $dl->konfirmasi }}</td>
                    </tr>
                    @else
                    <tr>

                        <td>{{ $dl->tanggal.'-'.$dl->bulan.'-'.$dl->tahun }}</td>
                        <td>{{ Str::of($dl->person)->explode('|')[0] ?? '-'  }}</td>
                        <td>{{ Str::of($dl->person)->explode('|')[1] ?? '-'  }}</td>
                        <td>{{ Str::of($dl->person)->explode('|')[2] ?? '-'  }}</td>
                        <td>
                            <img src="/storage/{{ $dl->foto }}" width="50px" class="gbAbsen">
                        </td>
                        <td>{{ $dl->konfirmasi }}</td>
                    </tr>
                    @endif
                    @endforeach
                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </section>

    </div>
</div>

@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
    $('.gbAbsen').on('click', function(){
        var src = $(this).attr('src');
              Swal.fire({
			  text: 'Dinas Luar',
			  imageUrl: src,
			  imageWidth: 480,
			  imageHeight: 540,
			  imageAlt: 'Custom image',
			})
    });


</script>

@endpush