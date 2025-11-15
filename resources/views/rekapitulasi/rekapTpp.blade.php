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
                <h4 class="text-center pt-2 adopd2">REKAP TPP KEHADIRAN PER BULAN </h4>

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
                    <p>DAFTAR PENERIMA TPP (KOMPONEN KEHADIRAN) BERDASARKAN DATA ABSENSI <b>BULAN {{ $bulan }}
                            TAHUN {{ $tahun }}</b></p>
                </div>
                <div class="d-flex mb-2">
                    <form action="/cetakRekapTPP" method="post" target="_blank">
                        @csrf
                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                        <button type="submit" class="btn btn-info"><img src="/img/pdf.png"
                                width="25px">&ensp;Download</button>

                    </form>
                </div>

            </div>

            <section>
                <table class="table table-bordered" style="font-size: .7rem" id="rekapBulan">
                    <thead>
                        <tr class="bg-info">
                            <th>No</th>
                            <th>Nama / NIP</th>
                            <th>Hadir</th>
                            <th>DL</th>
                            <th>Sakit</th>
                            <th>Cuti</th>
                            <th>Izin</th>
                            <th>TK</th>
                            <th class="text-center bg-warning">TAP</th>
                            <th class="text-center bg-warning">Rej</th>
                            <th class="text-center">TPP (40%)</th>
                            <th class="text-center">-TPP</th>
                            <th>TPP Kehadiran</th>
                            <th class="text-center">Opsi</th>


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
                                <?php $dts = collect($dts);
                                $dts = $dts->sortBy(['tanggal', 'desc']); ?>
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
                                <td class="text-center">
                                    {{ $dts->where('keterangan_p', 'tidak absen')->count() }}
                                </td>
                                <td class="text-center">
                                    {{ $dts->where('validasi', 'rejected')->count() + $dts->where('validasi_p', 'rejected')->count() }}
                                </td>
                                <td class="text-center">
                                    <?php
                                    $tpp = str_replace('.', '', $dts->first()->tpp);
                                    $tpph = $tpp * 0.4;
                                    $pengur = $dts->where('pengurangan', '!=', '')->pluck('pengurangan')->sum() + $dts->where('pengurangan_p', '!=', '')->pluck('pengurangan_p')->sum();
                                    $tppkur = $tpph - ($pengur / 100) * $tpph;
                                    $tpppengur = ($pengur / 100) * $tpph;
                                    
                                    ?>
                                    <span class="tppKehadiran" style="display: none">{{ $tpph }}</span>
                                    <span class="tppKur" style="display: none">{{ $tppkur }}</span>
                                    Rp.{{ $dts->first()->tpp }} X 40% <br> = Rp.{{ Number_format($tpph, 0, ',', '.') }}
                                </td>
                                <td class="text-center">

                                    {{ $pengur }}% X Rp.{{ Number_format($tpph, 0, ',', '.') }} <br>
                                    = Rp.{{ Number_format($tpppengur, 0, ',', '.') }}
                                </td>
                                <td>
                                    Rp.{{ Number_format($tppkur, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <?php $id = $dts->first()->nip; ?>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-warning btn-sm mr-2" data-toggle="modal"
                                            data-target="#edit_{{ $id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#hapus_{{ $id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>


                                <!-- Modal -->
                                <div class="modal fade" id="edit_{{ $id }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title text-dark" id="staticBackdropLabel">Ubah Jumlah TPP
                                                    Bulan
                                                    {{ $bulan }} Tahun {{ $tahun }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/rekapitulasi/ubahTPP" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                                                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="nama">Nama</label>
                                                                <input type="text" class="form-control" name="nama"
                                                                    value="{{ $dts->first()->nama }}" readonly>

                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="nip">NIP</label>
                                                                <input type="text" class="form-control" name="nip"
                                                                    value="{{ $dts->first()->nip }}" readonly>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="tpp_sebelumnya">Jumlah TPP</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">Rp.</div>
                                                                    </div>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $dts->first()->tpp }}" readonly>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="tpp_sebelumnya">Jumlah TPP (Update)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">Rp.</div>
                                                                    </div>
                                                                    <input type="text" class="form-control tpp_update"
                                                                        name="tpp_update" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal -->
                                <div class="modal fade" id="hapus_{{ $id }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title">Hapus dari Daftar Penerima TPP Bulan
                                                    {{ $bulan }} Tahun
                                                    {{ $tahun }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/rekapitulasi/hapusPenerima" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                                                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="nama">Nama</label>
                                                                <input type="text" class="form-control" name="nama"
                                                                    value="{{ $dts->first()->nama }}" readonly>

                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="nip">NIP</label>
                                                                <input type="text" class="form-control" name="nip"
                                                                    value="{{ $dts->first()->nip }}" readonly>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="tpp_sebelumnya">Jumlah TPP</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">Rp.</div>
                                                                    </div>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $dts->first()->tpp }}" readonly>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                        </div>
                                                    </div>
                                                    <p>Apakah Anda Yakin Hapus Ybs dari Daftar Penerima TPP Bulan
                                                        {{ $bulan }}
                                                        Tahun {{ $tahun }} ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot style="background-color: rgb(163, 160, 160); font-size: 15px; font-weight: bolder">
                        <tr>
                            <td colspan="10" class="text-center">JUMLAH TOTAL</td>
                            <td class="text-right">Rp.<span
                                    class="jumTPK">{{ Number_format($jumlahTPPKehadiran, 0, ',', '.') }}</span></td>
                            <td class="text-right">Rp.<span
                                    class="jumPOT">{{ Number_format($jumlahTotalPot, 0, ',', '.') }}</span></td>
                            <td class="text-right">Rp.<span
                                    class="jumTPPKUR">{{ Number_format($jumlahTPPBersih, 0, ',', '.') }}</span></td>
                            <td></td>

                        </tr>
                    </tfoot>

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
