@extends('templatesLTE.main')
@section('content')



<style>
    .blink {
        animation: blink 0.5s linear infinite;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 1;
        }

        50.01% {
            opacity: 0;
        }

        100% {
            opacity: 0;
        }
    }

    td {
        font-size: 0.8rem;
        color: black;
    }

    .divider {
        position: relative;
        border-bottom: 2px solid #211212;
        margin-bottom: 30px;
        margin-top: 30px;
    }

    .divider:before {
        position: absolute;
        content: "";
        width: 30px;
        height: 30px;
        border: 1px solid #f0f0f0;
        left: 50%;
        margin-left: -15px;
        top: 50%;
        background: #fff;
        margin-top: -15px;
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .divider:after {
        position: absolute;
        content: "";
        width: 20px;
        height: 20px;
        border: 1px solid #2ca5b9;
        left: 50%;
        margin-left: -10px;
        top: 50%;
        background: #2ca5b9;
        margin-top: -10px;
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>

<div class="row">
    <div class="col-md-12 p-0 mx-0">
        <div class="card shadow mb-4 p-0" width="100%">
            <!-- Card Header - Dropdown -->
            <div class="card-header pt-3 pb-2 d-flex flex-row align-items-center justify-content-start position-fixed bg-light" style="width: 100%; z-index: 999">
                <div class="pilihan mr-4">
                    <a href="?pegawai=asn" class="btn btn-sm {{
                            $status == 'asn' ? 'btn-primary' : 'btn-secondary'
                        }}">Rekap Harian ASN</a>
                    <a href="?pegawai=non" class="btn btn-sm {{
                            $status == 'non' ? 'btn-primary' : 'btn-secondary'
                        }}">Rekap Harian Non-ASN
                    </a>
                </div>

                @if($hariini)
                <h6 class="m-0 pl-0 mr-2 font-weight-bold text-primary bg-warning p-1">
                    Hari ini : {{ $hari }},
                </h6>
                @else
                <h6 class="m-0 pl-0 mr-0 font-weight-bold text-primary">
                    Hari : {{ $hari }}, &ensp;
                </h6>
                @endif
                <h6 class="m-0 pl-0 mr-4 font-weight-bold {{
                        $cekLibur ? 'text-danger' : 'text-primary'
                    }} {{ $hariini ? '' : 'blink' }}">
                    Tanggal : &ensp; {{ $tanggal.'-'.$bulan.'-'.$tahun }}
                </h6>
                <div class="float-right">
                    <nav class="navbar">
                        <form class="form-inline" action="/dashboard/absenHarian" method="get">
                            @if(Request('pegawai'))
                            <input type="hidden" name="pegawai" value="{{ Request('pegawai') }}">
                            @endif
                            <input name="tglcari" id="tglcari" class="form-control mr-sm-2" type="text" placeholder="masukkan tgl" required="" autocomplete="off" />
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="carsentag">
                                Search
                            </button>
                        </form>
                        <div>

                            <form class="form-inline" action="/dashboard/absenHarian" method="get">
                                @if(Request('pegawai'))
                                <input type="hidden" name="pegawai" value="{{ Request('pegawai') }}">
                                @endif
                                <button class="btn btn-info my-2 my-sm-0 ml-3" type="submit" name="hariIni" value="true">
                                    Hari Ini
                                </button>
                            </form>


                        </div>
                    </nav>
                </div>

                <div class="d-flex ml-4">
                    <form action="/dashboard/absenHarian" method="get" class="mr-3">
                        @if(Request('pegawai'))
                        <input type="hidden" name="pegawai" value="{{ Request('pegawai') }}">
                        @endif
                        <input type="hidden" name="awal" value="{{ $tanggal.'-'.$bulan.'-'.$tahun }}" />
                        <input type="hidden" name="kurang" value="true" />
                        <input type="hidden" name="lalu" value="true" />
                        
                        <button class="btn btn-sm btn-info" id="kurtang">
                            <span class="fa fa-arrow-left text-white"></span>
                        </button>
                    </form>
                    
                    <form action="/dashboard/absenHarian" method="get">
                        @if(Request('pegawai'))
                        <input type="hidden" name="pegawai" value="{{ Request('pegawai') }}">
                        @endif
                        <input type="hidden" name="awal" value="{{ $tanggal.'-'.$bulan.'-'.$tahun }}" />
                        <input type="hidden" name="tambah" value="true" />
                        <button class="btn btn-sm btn-info" id="tamtang">
                            <span class="fa fa-arrow-right text-white"></span>
                        </button>
                    </form>
                    
                </div>
            </div>

            <!-- Card Body -->
            <br /><br />
            <div class="card-body mt-4">
                <div class="d-flex mb-2">
                    <div>
                        <h5>Rekap Absensi Harian {{ $nama_lain }}, Hari : {{ $hari }}, Tanggal : {{ $tanggal.'-'.$bulan.'-'.$tahun }} </h5>
                    </div>
                    <div class="ml-auto">
                        <form action="/dashboard/absenHarian" method="get" target="_blank">
                            @if(Request('pegawai'))
                            <input type="hidden" name="pegawai" value="{{ Request('pegawai') }}">
                            @endif
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <button type="submit" name="print" value="true" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Print</button>
                        </form>

                    </div>
                </div>

                @if($kedepan)
                <table class="table table-bordered table-striped mb-0 p-0" width="100%" style="font-size: 11px">
                    <thead class="table-success" style="font-size: 13px">
                        <tr style="vertical-align: middle">
                            <th rowspan="2" width="4%" style="vertical-align: middle">
                                No
                            </th>
                            <th rowspan="2" width="25%" style="vertical-align: middle">
                                Nama / NIP / Jabatan
                            </th>
                            <th colspan="3" class="text-center">Absen Masuk</th>
                            <th colspan="3" class="text-center">
                                Absen Pulang
                            </th>
                        </tr>
                        <tr style="vertical-align: middle">
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-center">Konf/Val</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-center">Konf/Val</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pegawais as $peg )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $peg->nama }} <br />
                                NIP.{{ $peg->nip }} <br />
                                <i>{{ $peg->jabatan }}</i>
                            </td>
                            <td class="text-center">
                                <?php $dahar = $dataAbsensi->where('nip', $peg->nip)->first();
                                ?>
                                {{ $dahar->keterangan ?? '-'}}
                            </td>
                            <td class="text-center">
                                {{ $dahar->waktu ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ $dahar->konfirmasi ?? '-' }} <br />
                                {{ $dahar->validasi ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ $dahar->keterangan_p  ?? '-'}}
                            </td>
                            <td class="text-center">
                                {{ $dahar->pulang ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ $dahar->konfirmasi_p  ?? '-'}} <br />
                                {{ $dahar->validasi ?? '-'}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <table class="table table-bordered table-striped mb-0 p-0" width="100%" style="font-size: 11px">
                    <thead class="table-success" style="font-size: 13px">
                        <tr style="vertical-align: middle">
                            <th rowspan="2" width="4%" style="vertical-align: middle">
                                No
                            </th>
                            <th rowspan="2" width="25%" style="vertical-align: middle">
                                Nama / NIP / Jabatan
                            </th>
                            <th colspan="3" class="text-center">Absen Masuk</th>
                            <th colspan="3" class="text-center">
                                Absen Pulang
                            </th>
                        </tr>
                        <tr style="vertical-align: middle">
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-center">Konf/Val</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-center">Konf/Val</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataAbsensi as $absen )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $absen->nama }} <br />
                                NIP.{{ $absen->nip }} <br />
                                <i>{{ $absen->jabatan }}</i>
                            </td>
                            <td class="text-center">
                                
                                {{ $absen->keterangan ?? '-'}}
                            </td>
                            <td class="text-center">
                                {{ $absen->waktu ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ $absen->konfirmasi ?? '-' }} <br />
                                {{ $absen->validasi ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ $absen->keterangan_p  ?? '-'}}
                            </td>
                            <td class="text-center">
                                {{ $absen->pulang ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ $absen->konfirmasi_p  ?? '-'}} <br />
                                {{ $absen->validasi ?? '-'}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                <br>
                @if($status=='non')
                <span class="badge bg-secondary mb-2">Rekap Absensi Masuk Non-ASN : {{ $hari }},
                    {{
                    $tanggal.'-'.$bulan.'-'.$tahun }}
                </span>
                @else
                <span class="badge bg-info mb-2">Rekap Absensi Masuk ASN : {{ $hari }},
                    {{
                    $tanggal.'-'.$bulan.'-'.$tahun }}
                </span>
                @endif
                <br>
                <table border="1" style="font-size: .8rem; width='70%'" width="60%">
                    <tr style="
                            background: linear-gradient(
                                to right,
                                #d24dca,
                                #f9eded
                            );
                        ">
                        <th class="pl-4">Keterangan</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">DL</th>
                        <th class="text-center">Izin</th>
                        <th class="text-center">Sakit</th>
                        <th class="text-center">Cuti</th>
                        <th class="text-center">Tanpa Ket</th>
                        <th class="text-center">Rejected</th>
                        <th class="text-center">Un_Confirmed</th>
                        <th class="text-center">Total</th>
                    </tr>
                    <tr>
                        <th class="pl-4">Jumlah</th>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan', 'hadir')->where('konfirmasi','confirmed')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan', 'dinas luar')->where('konfirmasi','confirmed')->count()
                            }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan', 'izin')->where('konfirmasi','confirmed')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan', 'sakit')->where('konfirmasi','confirmed')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan', 'cuti')->where('konfirmasi','confirmed')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan', 'tanpa keterangan')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('konfirmasi', 'rejected')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('konfirmasi', 'un_confirmed')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->count() }}
                        </td>
                    </tr>
                </table>

                <div class="divider"></div>
            </div>
        </div>
    </div>
</div>

@endsection @push('script')
<script type="text/javascript">
    $("#tglcari").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true,
    });

    //  $('.gb_absen').click(function(e){
    // 		var path = $(this).attr('href');
    // 		var tr = $(this).parents('tr');
    // 		var nampeg = tr.find('.nampeg').text();

    // 		e.preventDefault();
    // 			Swal.fire({
    // 		  text: nampeg,
    // 		  imageUrl: path,
    // 		  imageWidth: 480,
    // 		  imageHeight: 540,
    // 		  imageAlt: 'Custom image',
    // 		})
    // });

    $("#konfall_pns").click(function() {
        var tanggal = $(this).attr("tanggal");
        var bulan = $(this).attr("bulan");
        var tahun = $(this).attr("tahun");

        $.get("/absenMasuk/konfirmasiAll", {
            tanggal: tanggal,
            bulan: bulan,
            tahun: tahun,
        }).done(function(data) {
            if (data == true) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Semua Absen PNS Telah Dikonfirmasi",
                    showConfirmButton: false,
                    timer: 1500,
                }).then(function() {
                    location.reload();
                });
            }
            location.href = "#absenMasuk";
        });
    });

    $("#konfall_non").click(function() {
        var tanggal = $(this).attr("tanggal");
        var bulan = $(this).attr("bulan");
        var tahun = $(this).attr("tahun");

        $.get("/absenMasuk/konfirmasiAllNon", {
            tanggal: tanggal,
            bulan: bulan,
            tahun: tahun,
        }).done(function(data) {
            if (data == true) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Semua Absen Non-ASN Telah Dikonfirmasi",
                    showConfirmButton: false,
                    timer: 1500,
                }).then(function() {
                    location.reload();
                });
            }
            location.href = "#absenMasukNon";
        });
    });

    $(".konfirmasi").click(function() {
        var idkonf = $(this).attr("idkonf");

        var tr = $(this).parents("tr");
        var status = tr.find(".status").html("<i>confirmed</i>");
        var pengurangan = tr.find(".minus");
        $.get("/absenMasuk/konfirmasi", {
            idkonf: idkonf,
        }).done(function(data) {
            pengurangan.html(data);
        });
    });

    $(".tolak").click(function() {
        var idtol = $(this).attr("idtol");
        var tr = $(this).parents("tr");
        tr.find(".status").html("<i>rejected</i>");
        var minus = tr.find(".minus").html("2");

        $.ajax({
            url: "/absenMasuk/reject",
            type: "get",
            data: "idtol=" + idtol,
            success: function(respons) {
                console.log(respons);
            },
        });
    });

    $(".hapus").click(function() {
        alert("hapus");

        var idhap = $(this).attr("idhap");
        var tanggal = $(this).attr("tanggal");
        var bulan = $(this).attr("bulan");
        var tahun = $(this).attr("tahun");
        var nip = $(this).attr("nip");
        var tr = $(this).parents("tr");
        Swal.fire({
            title: "Anda Yakin Hapus?",
            text: "Satu Absen akan terhapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/absenMasuk/hapus",
                    type: "get",
                    data: "idhap=" +
                        idhap +
                        "&tanggal=" +
                        tanggal +
                        "&bulan=" +
                        bulan +
                        "&tahun=" +
                        tahun +
                        "&nip=" +
                        nip,
                    success: function(hasil) {
                        console.log(hasil);

                        // Swal.fire({
                        // position: 'center',
                        // icon: 'success',
                        // title: 'Satu Absen Telah Terhapus',
                        // showConfirmButton: false,
                        // timer: 1500
                        // }).then(function(){

                        //     tr.remove();

                        // })
                    },
                });
            }
        });
    });

    // $('#absenMasuk').DataTable({
    //   "responsive": true, "lengthChange": false, "autoWidth": false,
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#absenMasuk_wrapper .col-md-6:eq(0)');

    function konfirmasi(id) {
        var id = id;
        var button = $(".btn_" + id);
        var tr = button.parents("tr");

        var pengurangan = tr.find(".minus");
        $.get("/absenMasuk/konfirmasi", {
            idkonf: id,
        }).done(function(data) {
            if (data >= 0) {
                pengurangan.html(data);
                tr.find(".status").html("confirmed");
            } else {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Absen telah di reject tim kabupaten!",
                    showConfirmButton: false,
                    timer: 1500,
                });
            }
        });
    }

    function reject(id) {
        var id = id;
        var button = $(".btn_" + id);
        var tr = button.parents("tr");

        var pengurangan = tr.find(".minus");
        $.get("/absenMasuk/reject", {
            idtol: id,
        }).done(function(data) {
            pengurangan.html(1.5);
            tr.find(".status").html("rejected");
        });
    }

    function hapus(id) {
        var id = id;
        var button = $(".btn_" + id);
        var tr = button.parents("tr");

        Swal.fire({
            title: "Anda Yakin Hapus?",
            text: "Satu Absen akan terhapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.get("/absenMasuk/hapus", {
                    idhap: id,
                }).done(function(data) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Satu Absen Telah Terhapus",
                        showConfirmButton: false,
                        timer: 1000,
                    }).then(function() {
                        tr.remove();
                    });
                });
            }
        });
    }

    function gbabsen(nama) {
        var data = nama.split("|");
        var nm = data[0];
        var gb = data[1];
        Swal.fire({
            text: nm,
            imageUrl: "/storage/" + gb,
            imageWidth: 480,
            imageHeight: 540,
            imageAlt: "Custom image",
        });
    }
</script>
@endpush