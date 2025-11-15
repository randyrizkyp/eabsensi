@extends('templatesLTE.main')
@section('content')
<style>
    .blink {
        animation: blink .5s linear infinite;
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
        font-size: .8rem;
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
        content: '';
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
        content: '';
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
            <div class="card-header pt-3 pb-2 d-flex flex-row align-items-center justify-content-start position-fixed bg-white"
                style="z-index: 999">

                <h6 class="m-0 pl-0 mr-2 font-weight-bold text-primary">Absensi Masuk </h6>

                @if($hariini)
                <h6 class="m-0 pl-0 mr-2 font-weight-bold text-primary bg-warning p-1">Hari ini : {{ $hari }},</h6>
                @else
                <h6 class="m-0 pl-0 mr-0 font-weight-bold text-primary ">Hari : {{ $hari }}, &ensp;</h6>
                @endif
                <h6
                    class="m-0 pl-0 mr-4 font-weight-bold {{ $cekLibur ? 'text-danger' : 'text-primary' }} {{ $hariini ? '' : 'blink' }}">
                    Tanggal : &ensp; {{ $tanggal.'-'.$bulan.'-'.$tahun }}
                </h6>
                <div class="float-right">
                    <nav class="navbar">
                        <form class="form-inline" action="/dashboard/absenMasuk/carsen" method="post">
                            @csrf
                            <input name="tglcari" id="tglcari" class="form-control mr-sm-2" type="text"
                                placeholder="masukkan tgl" required="" autocomplete="off">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"
                                name="carsentag">Search</button>
                        </form>
                        <form class="form-inline" action="" method="post">
                            <a href="/dashboard/absenMasuk" class="btn btn-primary ml-4 btn-sm harni" type="submit"
                                name="harni">Hari
                                Ini</a>
                        </form>
                    </nav>
                </div>
                <div class="d-flex ml-4">
                    <form action="/dashboard/absenMasuk/carsen" method="post" class="mr-3">
                        @csrf
                        <input type="hidden" name="awal" value="{{ $tanggal.'-'.$bulan.'-'.$tahun }}">
                        <input type="hidden" name="kurang" value=true>
                        <button class="btn btn-sm btn-primary" id="kurtang"><span
                                class="fa fa-arrow-left text-white"></span></button>
                    </form>
                    <form action="/dashboard/absenMasuk/carsen" method="post">
                        @csrf

                        <input type="hidden" name="awal" value="{{ $tanggal.'-'.$bulan.'-'.$tahun }}">
                        <input type="hidden" name="tambah" value=true>
                        <button class="btn btn-sm btn-primary" id="tamtang"><span
                                class="fa fa-arrow-right text-white"></span></button>
                    </form>

                </div>
            </div>
        </div>
        <!-- Card Body -->
        <br><br>
        <div class="card-body">

            <table class="table table-bordered table-striped mb-0 p-0" id="absenMasuk" width="100%">
                <thead>
                    <tr class="table-success">
                        <th width="4%">No</th>
                        <th width="10%">Nama/NIP</th>

                        <th width="10%">Tanggal</th>
                        <th width="6%">Jam_M</th>
                        <th width="10%">Keterangan <br>Catatan</th>
                        <th width="10%">-TPP_M</th>
                        <th width="8%">Foto</th>
                        <th width="8%">Foto_b</th>
                        <th width="8%">Status</th>
                        <th width="8%">Validasi</th>
                        <th width="16%" class="text-center">Ubah Status</th>
                    </tr>
                    <tr class="bg-orange">
                        <th colspan="10">Rekap Absensi Masuk ASN</th>
                        <th class="text-center">
                            <button class="btn btn-sm btn-success" id="konfall_pns" tanggal="{{ $tanggal }}"
                                bulan="{{ $bulan }}" tahun="{{ $tahun }}">Confirm All</button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataAbsensi as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="nampeg">{{ $data->nama." " }} <br> {{ $data->nip }}</td>
                        <td>{{ $data->tanggal . '-'.$data->bulan.'-'.$data->tahun }}</td>
                        <td>{{ $data->waktu }}</td>
                        <td>{{ $data->keterangan }}
                            <br>
                            @if($data->selisih <= 0 && $data->keterangan == 'hadir')
                                tepat waktu
                                @elseif($data->selisih > 0 && $data->keterangan == 'hadir')
                                telat {{ $data->selisih }} menit
                                @endif
                        </td>
                        <td class="minus">{{ $data->pengurangan }}</td>
                        <td>
                            @if($data->foto)
                            <a class="gb_absen " href="{{  asset('storage/'.$data->foto)  }}">
                                <img src="{{ asset('storage/'.$data->foto) }}" width="75px">
                            </a>
                            @endif
                        </td>
                        <td>
                            @if($data->foto_b)
                            <a class="gb_absen img-fluid" href="{{  asset('storage/'.$data->foto_b)  }}">
                                <img src="{{ asset('storage/'.$data->foto_b) }}" width="75px">
                            </a>
                            @endif
                        </td>
                        <td class="status">{{ $data->konfirmasi }}</td>
                        <td>{{ $data->validasi }}</td>
                        <td>
                            <span class="d-flex">
                                <button idkonf="{{ $data->id }}" class="btn btn-success btn-sm konfirmasi">Konfirmasi
                                </button>
                                <button idtol="{{ $data->id }}" class="btn btn-danger ml-2 btn-sm tolak">Tolak</button>
                                <button idhap="{{ $data->id }}" tanggal="{{ $data->tanggal }}"
                                    bulan="{{ $data->bulan }}" tahun="{{ $data->tahun }}" nip="{{ $data->nip }}"
                                    class="btn btn-warning ml-2 btn-sm hapus">Hapus</button>
                            </span>

                        </td>
                    </tr>
                    @endforeach


                </tbody>

            </table>
            <span class="badge bg-secondary mb-2">Rekap Absensi Masuk ASN, {{ $hari }}, {{
                $tanggal.'-'.$bulan.'-'.$tahun }}
            </span>
            <table border="1" style="font-size: .8rem; width='70%'" width="60%">
                <tr style="background: linear-gradient(to right, #D24DCA, #F9EDED);">
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
                        {{ $dataAbsensi->where('keterangan', 'dinas luar')->where('konfirmasi','confirmed')->count() }}
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

            <table class="table table-bordered table-striped mb-0 p-0" id="absenMasukNon" width="100%">
                <thead>
                    <tr class="table-success">
                        <th width="5%">No</th>
                        <th>Nama/No_Absen</th>
                        <th>Tanggal</th>
                        <th>Jam_M</th>
                        <th>Keterangan</th>
                        <th>Catatan</th>
                        <th>Status</th>
                        <th>Ubah Status</th>
                    </tr>
                    <tr class="bg-primary">
                        <th colspan="7">Rekap Absensi Masuk Non-ASN</th>
                        <th class="text-center">
                            <button class="btn btn-sm btn-success" id="konfall_non" tanggal="{{ $tanggal }}"
                                bulan="{{ $bulan }}" tahun="{{ $tahun }}">Confirm All</button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataAbsensiNon as $danon)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="nampeg">{{ $danon->nama." " }} <br> {{ $danon->nip }}</td>
                        <td>{{ $danon->tanggal . '-'.$danon->bulan.'-'.$danon->tahun }}</td>
                        <td>{{ $danon->waktu }}</td>
                        <td>{{ $danon->keterangan }}
                        </td>
                        <td>
                            @if($danon->selisih <= 0 && $danon->keterangan == 'hadir')
                                tepat waktu
                                @elseif($danon->selisih > 0 && $danon->keterangan == 'hadir')
                                telat {{ $danon->selisih }} menit
                                @endif
                        </td>

                        <td class="status">{{ $danon->konfirmasi }}</td>
                        <td>
                            <span class="d-flex">
                                <button idkonf="{{ $danon->id }}" class="btn btn-success btn-sm konfirmasi">Konfirmasi
                                </button>
                                <button idtol="{{ $danon->id }}" class="btn btn-danger ml-2 btn-sm tolak">Tolak</button>
                                <button idhap="{{ $danon->id }}" tanggal="{{ $danon->tanggal }}"
                                    bulan="{{ $danon->bulan }}" tahun="{{ $danon->tahun }}" nip="{{ $danon->nip }}"
                                    class="btn btn-warning ml-2 btn-sm hapus">Hapus</button>
                            </span>

                        </td>
                    </tr>
                    @endforeach


                </tbody>

            </table>
            <span class="badge bg-info mb-2">Rekap Absensi Masuk Non-ASN, {{ $hari }}, {{
                $tanggal.'-'.$bulan.'-'.$tahun }}
            </span>
            <table border="1" style="font-size: .8rem; width='70%'" width="60%">
                <tr style="background: linear-gradient(to right, #D24DCA, #F9EDED);">
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
                        {{ $dataAbsensiNon->where('keterangan', 'hadir')->where('konfirmasi','confirmed')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dataAbsensiNon->where('keterangan', 'dinas luar')->where('konfirmasi','confirmed')->count()
                        }}
                    </td>
                    <td class="text-center">
                        {{ $dataAbsensiNon->where('keterangan', 'izin')->where('konfirmasi','confirmed')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dataAbsensiNon->where('keterangan', 'sakit')->where('konfirmasi','confirmed')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dataAbsensiNon->where('keterangan', 'cuti')->where('konfirmasi','confirmed')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dataAbsensiNon->where('keterangan', 'tanpa keterangan')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dataAbsensiNon->where('konfirmasi', 'rejected')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dataAbsensiNon->where('konfirmasi', 'un_confirmed')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $dataAbsensiNon->count() }}
                    </td>



                </tr>

            </table>


        </div>
    </div>
</div>




@endsection
@push('script')
<script type="text/javascript">
    $("#tglcari").datepicker({
             format: 'dd-mm-yyyy',
             autoclose: true,
             todayHighlight: true,
         });
    
     $('.gb_absen').click(function(e){
			var path = $(this).attr('href');
			var tr = $(this).parents('tr');
			var nampeg = tr.find('.nampeg').text();
			   
			e.preventDefault();
				Swal.fire({
			  text: nampeg,
			  imageUrl: path,
			  imageWidth: 480,
			  imageHeight: 540,
			  imageAlt: 'Custom image',
			})
	});

    $('#konfall_pns').click(function(){
	var tanggal = $(this).attr('tanggal');
	var bulan = $(this).attr('bulan');
	var tahun = $(this).attr('tahun');
	
    $.get( "/absenMasuk/konfirmasiAll", { tanggal : tanggal, bulan : bulan , tahun : tahun } )
            .done(function( data ) {
                if(data==true){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Semua Absen PNS Telah Dikonfirmasi',
                        showConfirmButton: false,
                        timer: 1500
                        }).then(function(){
                        location.reload();})
                }
                location.href="#absenMasuk";
            });


})

$('#konfall_non').click(function(){
	var tanggal = $(this).attr('tanggal');
	var bulan = $(this).attr('bulan');
	var tahun = $(this).attr('tahun');
	
    $.get( "/absenMasuk/konfirmasiAllNon", { tanggal : tanggal, bulan : bulan , tahun : tahun } )
            .done(function( data ) {
                if(data==true){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Semua Absen Non-ASN Telah Dikonfirmasi',
                        showConfirmButton: false,
                        timer: 1500
                        }).then(function(){
                        
                        location.reload();})
                }
                location.href="#absenMasukNon";
            });


})

    $('.konfirmasi').click(function(){

        var idkonf = $(this).attr('idkonf');

        var tr = $(this).parents('tr');
        var status = tr.find('.status').html("<i>confirmed</i>");
        var pengurangan = tr.find('.minus');
        $.get( "/absenMasuk/konfirmasi", { idkonf: idkonf } )
            .done(function( data ) {
                pengurangan.html(data);
            });

    });

    $('.tolak').click(function(){
		var idtol = $(this).attr('idtol');
		var tr = $(this).parents('tr');
		tr.find('.status').html("<i>rejected</i>");
		var minus = tr.find('.minus').html("2");
	

		$.ajax({

			url : '/absenMasuk/reject',
			type : 'get',
			data : 'idtol='+idtol,
			success : function(respons){
                console.log(respons);
			}


		});

	});

$('.hapus').click(function(){

    var idhap = $(this).attr('idhap');
    var tanggal = $(this).attr('tanggal');
    var bulan = $(this).attr('bulan');
    var tahun = $(this).attr('tahun');
    var nip = $(this).attr('nip');
    var tr = $(this).parents('tr');
        Swal.fire({
            title: 'Anda Yakin Hapus?',
            text: "Satu Absen akan terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                if (result.isConfirmed){
                  $.ajax({

                            url : '/absenMasuk/hapus',
                            type : 'get',
                            data : 'idhap='+idhap+'&tanggal='+tanggal+'&bulan='+bulan+'&tahun='+tahun+'&nip='+nip,
                            success : function(){

                                    Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Satu Absen Telah Terhapus',
                                    showConfirmButton: false,
                                    timer: 1500
                                    }).then(function(){
                                
                                        tr.remove();
                                        
                                    })
                            }

                    })
                 }



    });

});

    // $('#absenMasuk').DataTable({
    //   "responsive": true, "lengthChange": false, "autoWidth": false,
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#absenMasuk_wrapper .col-md-6:eq(0)');

    $('#absenMasuk').DataTable();
    $('#absenMasukNon').DataTable();
</script>
@endpush