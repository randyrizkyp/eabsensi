@extends('templatesLTE.main')
@section('content')
    <?php
    use App\Http\Controllers\RekapitulasiController;
    use Illuminate\Support\Carbon;
    $rekap = new RekapitulasiController();
    ?>
    <div class="row">
        <div class="col-md-12 p-0 mx-0">
            <div class="mt-4" style="background : linear-gradient(to left ,#8f9103, #f4fad8, #8f9103);">
                <h4 class="text-center pt-2 adopd2">REKAP APEL PER BULAN </h4>

                <div class="d-flex justify-content-center">
                    <form class="form-inline" action="/rekapitulasi/tpp" method="post">
                        @csrf
                        <div class="form-group mb-2 ml-2">
                            <select class="custom-select mr-sm-2" id="carbul" name="bulan">
                                @foreach ($namaBulan as $nambul)
                                    <option value="{{ $nambul['angka'] }}"
                                        {{ $bulan == $nambul['angka'] ? 'selected' : '' }}>
                                        {{ $nambul['nama'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2 ml-2">
                            <select class="custom-select mr-sm-2" id="carbul" name="tahun">
                                <option {{ $tahun == now()->translatedFormat('Y') - 2 ? 'selected' : '' }}>
                                    {{ now()->translatedFormat('Y') - 2 }}</option>
                                <option {{ $tahun == now()->translatedFormat('Y') - 1 ? 'selected' : '' }}>
                                    {{ now()->translatedFormat('Y') - 1 }}</option>
                                <option {{ $tahun == now()->translatedFormat('Y') ? 'selected' : '' }}>
                                    {{ now()->translatedFormat('Y') }}</option>
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
            <div class="row">
                <div class="col">
                    <p>REKAPITULASI APEL <b>BULAN {{ $bulan }}
                            TAHUN {{ $tahun }}</b></p>
                </div>
                <div class="d-flex mb-2">
                    <form action="/cetakApel" method="post" target="_blank">
                        @csrf
                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                        <button type="submit" class="btn btn-info"><img src="/img/pdf.png"
                                width="25px">&ensp;Download</button>

                    </form>
                </div>

            </div>

            <section>
                <table class="table table-bordered" style="font-size: .7rem" id="rekapBulan" width = "100%">
                    <thead>
                        <tr class="bg-info">
                            <th>No</th>
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
                                <td>
                                    {{ $dts->first()->nama }} <br> {{ $dts->first()->nip }} <br>
                                    {{ $dts->first()->pangkat }}
                                    <br> {{ $dts->first()->jabatan }}
                                </td>
                            
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
                               


                        @endforeach

                    </tbody>
      

                </table>
            </section>

        </div>
    </div>
    {{-- notifikasi --}}
    @if (session()->has('success'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: true
            })
        </script>
    @endif
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    {{-- <script>
        var jumK = $('.tppKehadiran').length;
        var totalK = 0;
        for (let i = 0; i < jumK; i++) {
            var jum = Number($('.tppKehadiran').eq(i).html());
            totalK += jum;
        }
        $('.jumTPK').html(totalK);

        var jumtppKur = $('.tppKur').length;
        var totalKur = 0;
        for (let i = 0; i < jumtppKur; i++) {
            var jumkur = Number($('.tppKur').eq(i).html());
            totalKur += jumkur;
        }
        $('.jumTPPKUR').html(totalKur);
        $('.jumPOT').html(totalK - totalKur);
        $('.jumTPK').mask('000.000.000.000.000', {
            reverse: true
        });
        $('.jumTPPKUR').mask('000.000.000.000.000', {
            reverse: true
        });
        $('.jumPOT').mask('000.000.000.000.000', {
            reverse: true
        });

        $('.tpp_update').mask('000.000.000.000.000', {
            reverse: true
        });

        $('#rekapBulan').dataTable();
    </script> --}}
    <script>
        $('#rekapBulan').dataTable();
    </script>
@endpush
