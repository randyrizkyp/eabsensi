<?php
use Illuminate\Support\Carbon;
?>
@extends('templates.standard.main')
@section('content')
    <style>
        th {
            text-align: center;
            vertical-align: middle;

        }

        .gampeg {
            padding: 3px;
            box-shadow: 5px 5px 10px #5F5959;
            margin-top: 6px;
        }

        .rekapitulasi {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        #tabel_rekap1 {
            border-collapse: collapse;
            border: 1px solid black;
        }

        #tabel_rekap1 th,
        #tabel_rekap1 td {
            text-align: center;
            font-size: 12px;
            padding: 5px;
            border: 1px solid grey;
        }

        .sekarang {
            background-color: aqua;
        }

        .wadga {
            display: block;
            width: 190px;
            height: auto;
            /*z-index: 9999;*/
            position: relative;

        }

        .wadga:hover {
            background-color: #8CD486;
        }

        table * {
            font-size: 12px;
        }

        .disp-wfo {
            width: 100%;

            overflow-x: scroll;

        }

        .disp-wfo::-webkit-scrollbar {
            width: 5px;
            height: 8px;
            border-top: 1px solid #302A2A;

            background-color: lightgray;

        }

        .disp-wfo::-webkit-scrollbar-thumb {
            width: 20px;
            background: #0A4C95;

        }

        .disp-wfo::-moz-scrollbar-track-piece {
            background-color: #C2D2E4;
        }

        .disp-wfo::-moz-scrollbar-thumb:horizontal {

            background-color: #0A4C95;
        }

        #submenu {
            background-color: rgb(52, 64, 74);
        }

        a {
            text-decoration: none;
        }

        a:hover {
            background-color: #8CD486;
            text-decoration: none;
        }
    </style>

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
    {{-- notifikasi --}}
    {{-- notifikasi --}}
    @if (session()->has('skipDL'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('skipDL') }}',
                showConfirmButton: true,
                timer: 3000
            })
        </script>
    @endif
    {{-- notifikasi --}}

    @if (session()->has('warning'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: '{{ session('warning') }}',
                showConfirmButton: true
            })
        </script>
    @endif

    @if (session()->has('fail'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '{{ session('fail') }}',
                showConfirmButton: true
            })
        </script>
    @endif

    <div class="px-2">
        <section>
            <h4 align="center" id="menu" class="alert alert-success mb-0" style="position: relative"><i
                    class="fas fa-bars float-left"></i>REKAP ABSENSI INDIVIDU
            </h4>
            <div id="submenu" class="pt-4 pr-4 mt-0 bg-info"
                style="min-width: 300px; position: absolute; z-index:999 ; display: none">
                <ul style="list-style-type: none;">
                    <li class="mb-2 pr-4"><a href="/ubahProfil" class="text-white"><i class="fas fa-user"></i>&emsp;Ubah
                            Profil</a>
                    </li>
                    <li class="mb-2 pr-4"><a href="/absensi/pilihKeterangan" class="text-white"><i
                                class="fas fa-list-alt"></i>&emsp;Pilih
                            Keterangan</a>
                    </li>
                    <li class="mb-2 pr-4"><a href="/cekSkor/individu" class="text-white"><i
                                class="fas fa-chart-line"></i>&emsp;Cek Skor</a>
                    </li>
                    <li class="mb-2 pr-4"><a href="/logout" class="text-white"><i class="fas fa-sign-out-alt"></i>&emsp;Log
                            Out</a>
                    </li>
                </ul>
            </div>
        </section>

        <p align="center" class="mt-2">
            {{ $nama_lain }} </br> Lampura, Bulan
            {{ $bulan }} Tahun
            {{ $tahun }}
        </p>
        <div class="card mb-3 wrap" style="max-width: 100%;">

            <center>
                <div class="row no-gutters">
                    <div class="col-sm-4" id="pilgab">
                        <form action="ganti_profil.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="nipga" value="{{ $nip }}">
                            <input type="file" name="gapro" id="imgInp" style="display:none" />

                            <div class="wadga"><img src="{{ asset('storage/foto_pegawai/' . $foto) }}" width="180"
                                    id="blah" class="gampeg"></div>
                            <button type="submit" class="btn btn-info mt-2 mb-2" id="simga"
                                style="display: none;">Simpan</button>
                        </form>
                    </div>

                    <div class="col-sm-8">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $nama }}
                            </h5>
                            <p class="card-text">
                                {{ 'NIP.' . $nip }}
                            </p>
                            <p class="card-text mb-0"><small class="text-muted mb-0">
                                    {{ $jabatan }}
                                </small></p>
                            <p class="card-text mb-0"><small class="text-muted mb-0">
                                    {{ $uker }}
                                </small></p>
                            <p class="card-text"><small class="text-muted">
                                    {{ $uorg }}
                                </small></p>
                        </div>
                    </div>
                </div>
            </center>
        </div>
        <!-- Lihat daftar hadir hari ini -->

        <!-- === cek absensi === -->
        <div class="card wrap">
            <div class="card-body">
                <form method="get" action="/rekapPerorangan">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="tahunabsen">Tahun</label>
                                <input type="text" class="form-control" name="tahun" value="{{ $tahun }}"
                                    required>
                                <input type="hidden" name="nipindividu" value="{{ $nip }}">

                            </div>
                        </div>
                        <div class="col-4">

                            <label for="bulanabsen">Pilih Bulan</label>
                            <select class="custom-select" name="bulan" required>
                                @foreach ($namaBulan as $nambul)
                                    <option value="{{ $nambul['angka'] }}"
                                        {{ $bulan == $nambul['angka'] ? 'selected' : '' }}>
                                        {{ $nambul['nama'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 d-flex align-items-center">

                            <input type="submit" class="btn btn-info mt-3" id="ceka" value="Cek Absen">
                        </div>

                    </div>
                </form>
            </div>

        </div>
        <div class="card">
            <h5><span class="badge badge-success">Absen Masuk & Apel Pagi</span></h5>
        </div>
        <div class="disp-wfo mb-2 wrap">
            <table class="table table-bordered mb-0" width="100%">
                <thead>
                    <tr class="table-success">
                        <th width="">No</th>
                        <th class="text-center">Hari/tanggal</th>
                        <th class="text-center">Ket_Apel</th>
                        <th class="text-center" width="">Ket_Masuk</th>
                        <th class="text-center" width="">Jam_M</th>
                        <th class="text-center" width="">Keterlambatan</th>
                        <th class="text-center" width="">Foto</th>
                        <th class="text-center" width="">Foto_b</th>
                        <th class="text-center" width="">Status</th>
                        <th class="text-center" width="">Validasi</th>
                        <th class="text-center" width="">-TPP(%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekapOrang as $data)
                        <tr class="{{ $data->tanggal == now()->translatedFormat('d') ? 'sekarang' : '' }}">

                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td class="text-center">
                                {{ Carbon::parse($data->tanggal . '-' . $data->bulan . '-' . $data->tahun)->translatedFormat('l') }}
                                <br>
                                {{ $data->tanggal . '-' . $data->bulan . '-' . $data->tahun }}
                            </td>
                            <td class="text-center keterangan">
                                {{ $data->apel_pagi }}
                            </td>
                            <td class="text-center keterangan">
                                {{ $data->keterangan }}
                            </td>
                            <td class="text-center">
                                @if ($data->keterangan == 'hadir')
                                    {{ $data->waktu }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($data->selisih == -500)
                                    -
                                @elseif($data->selisih <= 0 && $data->selisih > -500)
                                    tepat waktu
                                @else
                                    {{ $data->selisih . ' menit' }}
                                @endif

                            </td>
                            <td class="text-center">
                                @if (substr($data->foto, 0, 1) == 'h')
                                    <a target="_blank" href="{{ $data->foto }}"><img src="{{ asset('img/pdf.png') }}"
                                            width="50"></a>
                                @else
                                    <img src="{{ asset('storage/' . $data->foto) }}" width="50" class="gbabsen">
                                @endif
                            </td>
                            <td class="text-center">
                                @if (substr($data->foto_b, 0, 1) == 'h')
                                    <a target="_blank" href="{{ $data->foto_b }}"><img src="{{ asset('img/pdf.png') }}"
                                            width="50"></a>
                                @else
                                    <img src="{{ asset('storage/' . $data->foto_b) }}" width="50" class="gbabsen">
                                @endif
                            </td>
                            <td class="text-center">
                                <i>{{ $data->konfirmasi }}</i>
                            </td>
                            <td class="text-center">
                                @if ($data && $data->validasi)
                                    {{ $data->validasi }} </br>
                                    <small> {{ $data->alasan }} </small>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $data->pengurangan }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
        <div class="card mt-2">
            <h5><span class="badge badge-success">Absen Pulang</span></h5>
        </div>
        <div class="disp-wfo mb-2 wrap">
            <table class="table table-bordered mb-0" width="100%">
                <thead>
                    <tr class="table-success">
                        <th width="">No</th>
                        <th class="text-center">Hari/tanggal</th>
                        <th class="text-center" width="">Ket</th>
                        <th class="text-center" width="">Jam_P</th>
                        <th class="text-center" width="">PSW</th>
                        <th class="text-center" width="">Foto</th>
                        <th class="text-center" width="">Foto_b</th>
                        <th class="text-center" width="">Status</th>
                        <th class="text-center" width="">Validasi</th>
                        <th class="text-center" width="">-TPP(%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekapOrang as $datap)
                        <tr class="{{ $datap->tanggal == now()->translatedFormat('d') ? 'sekarang' : '' }}">

                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td class="text-center">
                                {{ Carbon::parse($datap->tanggal . '-' . $datap->bulan . '-' . $datap->tahun)->translatedFormat('l') }}
                                <br>
                                {{ $datap->tanggal . '-' . $datap->bulan . '-' . $datap->tahun }}
                            </td>
                            <td class="text-center keterangan">
                                {{ $datap->keterangan_p }}
                            </td>
                            <td class="text-center">
                                @if ($datap->keterangan_p == 'hadir')
                                    {{ $datap->pulang }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if (!is_null($datap->selisih_p))
                                    @if ($datap->keterangan_p == 'hadir')
                                        @if ($datap->selisih_p >= 0)
                                            tepat waktu
                                        @else
                                            psw {{ $datap->selisih_p }} menit
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">

                                @if (substr($datap->foto_p, 0, 1) == 'h')
                                    <a target="_blank" href="{{ $datap->foto_p }}"><img
                                            src="{{ asset('img/pdf.png') }}" width="50"></a>
                                @else
                                    <img src="{{ asset('storage/' . $datap->foto_p) }}" width="50" class="gbabsen">
                                @endif

                            </td>
                            <td class="text-center">
                                @if (substr($datap->foto_p, 0, 1) == 'h')
                                    <a target="_blank" href="{{ $datap->foto_pb }}"><img
                                            src="{{ asset('img/pdf.png') }}" width="50"></a>
                                @else
                                    <img src="{{ asset('storage/' . $datap->foto_pb) }}" width="50" class="gbabsen">
                                @endif
                            </td>
                            <td class="text-center">
                                <i>{{ $datap->konfirmasi_p }}</i>
                            </td>
                            <td class="text-center">
                                @if ($datap && $datap->validasi_p)
                                    {{ $datap->validasi_p }} </br>
                                    <small> {{ $datap->alasan_p }} </small>
                                @endif

                            </td>
                            <td class="text-center">
                                {{ $datap->pengurangan_p }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
        <div class="rebul">
            <h5><span class="badge badge-dark">Rekap Absensi bulan {{ $bulan }} Tahun {{ $tahun }}</span>
            </h5>
        </div>
        <div class="disp-wfo mb-2 wrap">
            <p>Jumlah Hari Kerja : {{ $jumlahHariKerja }}</p>
            <table id="tabel_rekap1">
                <tr style="background: linear-gradient(to right, #D24DCA, #F9EDED);">
                    <th>Keterangan</th>
                    <th>Hadir</th>
                    {{-- <th>WFH</th> --}}
                    <th>DL</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Cuti</th>
                    <th>Tanpa Ket</th>
                    <th>Tdk Absen Pulg</th>
                    <th>Rejected</th>
                    <th>Un_confirmed</th>
                    <th>Terlambat</th>
                    <th>PSW</th>
                    <th>-TPP(%) </th>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td align="center">
                        {{ $hadir = $rekapOrang->where('keterangan', 'hadir')->count() }}
                    </td>
                    {{-- <td>

                </td> --}}
                    <td>
                        {{ $dl = $rekapOrang->where('keterangan', 'dinas luar')->count() }}
                    </td>
                    <td>
                        {{ $izin = $rekapOrang->where('keterangan', 'izin')->count() }}
                    </td>
                    <td>
                        {{ $sakit = $rekapOrang->where('keterangan', 'sakit')->count() }}
                    </td>
                    <td>
                        {{ $cuti = $rekapOrang->where('keterangan', 'cuti')->count() }}
                    </td>
                    <td>
                        {{ $tk = $rekapOrang->where('keterangan', 'tanpa keterangan')->count() }}
                    </td>
                    <td>
                        {{ $tap = $rekapOrang->where('keterangan_p', 'tidak absen')->count() }}
                    </td>
                    <td>
                        {{ $rekapOrang->where('keterangan', 'rejected')->count() }} +
                        {{ $rekapOrang->where('keterangan_p', 'rejected')->count() }}
                    </td>
                    <td>
                        {{ $rekapOrang->where('konfirmasi', 'un_confirmed')->count() }} +
                        {{ $rekapOrang->where('konfirmasi_p', 'un_confirmed')->count() }}
                    </td>
                    <td>
                        {{ $rekapOrang->where('keterangan', 'hadir')->where('selisih', '>', 0)->pluck('selisih')->sum() }}
                        menit
                    </td>
                    <td>
                        {{ $rekapOrang->where('keterangan_p', 'hadir')->where('selisih_p', '<', 0)->pluck('selisih_p')->sum() }}
                        menit
                    </td>
                    <td>
                        @if (
                            $tk == $jumlahHariKerja ||
                                $cutiBesar == $jumlahHariKerja ||
                                $cutiML4 == $jumlahHariKerja ||
                                $cutiDTN == $jumlahHariKerja)
                            100
                        @else
                            {{ $rekapOrang->where('pengurangan', '!=', null)->pluck('pengurangan')->sum() +
                                $rekapOrang->where('pengurangan_p', '!=', null)->pluck('pengurangan_p')->sum() }}
                        @endif
                    </td>

                </tr>

            </table>
            <br>
            <small class="text-muted"><i>note: jika jumlah tanpa keterangan atau cuti besar, cuti melahirkan anak ke-4 dst,
                    cuti diluar tanggungan negara sama dengan jumlah hari kerja (satu bulan penuh) maka pengurangan tpp
                    kehadiran pada
                    bulan tersebut sebesar 100% </i>
            </small>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('#menu').on('click', function() {
            $('#submenu').toggle('slow');
        })
        $('.wrap').on('click', function() {
            $('#submenu').hide();
        });

        $('.gbabsen').click(function(e) {
            var path = $(this).attr('src');
            var tr = $(this).parents('tr');
            var keterangan = tr.find('.keterangan').text();

            e.preventDefault();
            Swal.fire({
                text: keterangan,
                imageUrl: path,
                imageWidth: 480,
                imageHeight: 540,
                imageAlt: 'Custom image',
            })
        });
    </script>
@endpush
