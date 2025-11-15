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
            <div class="card-header pt-3 pb-2 d-flex flex-row align-items-center justify-content-start position-fixed bg-light"
                style="width:100%; z-index: 999">
                <div class="pilihan mr-4">
                    <a href="?pegawai=asn"
                        class="btn btn-sm {{ $status=='asn' ? 'btn-primary' : 'btn-secondary' }}">Absen Pulang ASN</a>
                    <a href="?pegawai=non"
                        class="btn btn-sm {{ $status=='non' ? 'btn-primary' : 'btn-secondary' }}">Absen Pulang
                        Non-ASN</a>
                </div>

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
                        <form class="form-inline" action="/dashboard/absenPulang/carsen" method="get">
                            @csrf
                            <input name="tglcari" id="tglcari" class="form-control mr-sm-2" type="text"
                                placeholder="masukkan tgl" required="" autocomplete="off">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"
                                name="carsentag">Search</button>
                        </form>
                        <div>
                            @if(Request('pegawai')=='non' || session()->get('statusPegawai') == 'non')
                            <a href="/dashboard/absenPulang?pegawai=non" class="btn btn-primary ml-4 btn-sm harni"
                                name="harni">Hari
                                Ini</a>
                            @else
                            <a href="/dashboard/absenPulang" class="btn btn-primary ml-4 btn-sm harni" name="harni">Hari
                                Ini</a>
                            @endif
                        </div>
                    </nav>
                </div>
                <div class="d-flex ml-4">
                    <form action="/dashboard/absenPulang/carsen" method="get" class="mr-3">
                        @csrf
                        <input type="hidden" name="awal" value="{{ $tanggal.'-'.$bulan.'-'.$tahun }}">
                        <input type="hidden" name="kurang" value=true>
                        <button class="btn btn-sm btn-primary" id="kurtang"><span
                                class="fa fa-arrow-left text-white"></span></button>
                    </form>
                    <form action="/dashboard/absenPulang/carsen" method="get">
                        @csrf

                        <input type="hidden" name="awal" value="{{ $tanggal.'-'.$bulan.'-'.$tahun }}">
                        <input type="hidden" name="tambah" value=true>
                        <button class="btn btn-sm btn-primary" id="tamtang"><span
                                class="fa fa-arrow-right text-white"></span></button>
                    </form>

                </div>
            </div>

            <!-- Card Body -->
            <br><br>
            <div class="card-body mt-4">

                <table class="table table-bordered table-striped mb-0 p-0" id="absenPulang" width="100%"
                    style="font-size: 11px">
                    <thead>
                        <tr class="table-success">
                            <th width="4%">No</th>
                            <th width="10%">Nama</th>
                            <th width="10%">NIP</th>
                            <th width="10%">Tanggal</th>
                            <th width="6%">Jam_P</th>
                            <th width="10%">Keterangan_P <br>Catatan</th>
                            <th width="10%">-TPP_P</th>
                            <th width="8%">Foto_P</th>
                            <th width="8%">Foto_Pb</th>
                            <th width="8%">Status_P/ <br>Validasi_P</th>
                            <th width="16%" class="text-center">Ubah Status</th>
                        </tr>
                        <tr class="bg-orange" style="font-size: 14px">
                            <th colspan="10">
                                @if($status == 'asn')
                                Rekap Absensi Pulang ASN
                                @else
                                Rekap Absensi Pulang Non-ASN
                                @endif
                            </th>
                            <th class="text-center">
                                @if($status == 'asn')
                                <button class="btn btn-sm btn-success" id="konfall_pns" tanggal="{{ $tanggal }}"
                                    bulan="{{ $bulan }}" tahun="{{ $tahun }}">Confirm All</button>
                                @else
                                <button class="btn btn-sm btn-success" id="konfall_non" tanggal="{{ $tanggal }}"
                                    bulan="{{ $bulan }}" tahun="{{ $tahun }}">Confirm All</button>
                                @endif
                            </th>
                        </tr>
                    </thead>


                </table>
                @if($status=='non')
                <span class="badge bg-secondary mb-2">Rekap Absensi Pulang Non-ASN : {{ $hari }}, {{
                    $tanggal.'-'.$bulan.'-'.$tahun }}
                </span>
                @else
                <span class="badge bg-info mb-2">Rekap Absensi Pulang ASN : {{ $hari }}, {{
                    $tanggal.'-'.$bulan.'-'.$tahun }}
                </span>
                @endif
                <table border="1" style="font-size: .8rem; width='70%'" width="60%">
                    <tr style="background: linear-gradient(to right, #D24DCA, #F9EDED);">
                        <th class="pl-4">Keterangan</th>
                        <th class="text-center">Belum Absen</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">DL</th>
                        <th class="text-center">Izin</th>
                        <th class="text-center">Sakit</th>
                        <th class="text-center">Cuti</th>
                        <th class="text-center">Tanpa Ket</th>
                        <th class="text-center">Rejected</th>
                        <th class="text-center">Tidak Absen</th>
                        <th class="text-center">Un_Confirmed</th>
                        <th class="text-center">Total</th>

                    </tr>
                    <tr>
                        <th class="pl-4">Jumlah</th>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan_p', 'belum absen')->count()
                            }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan_p', 'hadir')->where('konfirmasi_p','confirmed')->count()
                            }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan_p', 'dinas luar')->count();
                            }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan_p', 'izin')->where('konfirmasi_p','confirmed')->count()
                            }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan_p', 'sakit')->where('konfirmasi_p','confirmed')->count()
                            }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan_p', 'cuti')->where('konfirmasi_p','confirmed')->count()
                            }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan_p', 'tanpa keterangan')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('konfirmasi_p', 'rejected')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('keterangan_p', 'tidak absen')->count() }}
                        </td>
                        <td class="text-center">
                            {{ $dataAbsensi->where('konfirmasi_p', 'un_confirmed')->count() }}
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



@endsection
@push('script')
<script type="text/javascript">
    $.get("/absenPulang/ajaxBelumAbsen", {data:'oke'}).done(function(hasil){
        console.log(hasil);
    });


    $("#tglcari").datepicker({
             format: 'dd-mm-yyyy',
             autoclose: true,
             todayHighlight: true,
         });
    

    $('#konfall_pns').click(function(){
	var tanggal = $(this).attr('tanggal');
	var bulan = $(this).attr('bulan');
	var tahun = $(this).attr('tahun');
	
    $.get( "/absenPulang/konfirmasiAll", { tanggal : tanggal, bulan : bulan , tahun : tahun } )
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
                location.href="#absenPulang";
            });


})

$('#konfall_non').click(function(){
	var tanggal = $(this).attr('tanggal');
	var bulan = $(this).attr('bulan');
	var tahun = $(this).attr('tahun');
	
    $.get( "/absenPulang/konfirmasiAllNon", { tanggal : tanggal, bulan : bulan , tahun : tahun } )
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
                location.href="#absenPulangNon";
            });


})

    $('.konfirmasi').click(function(){

        var idkonf = $(this).attr('idkonf');

        var tr = $(this).parents('tr');
        var status = tr.find('.status').html("<i>confirmed</i>");
        var pengurangan = tr.find('.minus');
        $.get( "/absenPulang/konfirmasi", { idkonf: idkonf } )
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

			url : '/absenPulang/reject',
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
    $.get( "/absenMasuk/konfirmasi", { idkonf: id } )
    .done(function( data ) {
        if(data >= 0){
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
                        url : '/absenPulang/hapus',
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
        }else{
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Absen telah di reject tim kabupaten!',
                showConfirmButton: false,
                timer: 1500
                });
        }

    });

});

    // $('#absenPulang').DataTable({
    //   "responsive": true, "lengthChange": false, "autoWidth": false,
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#absenPulang_wrapper .col-md-6:eq(0)');

    $('#absenPulang').DataTable({
            scrollX : true,
                    processing : true,
                    responsive: true,
                    serverSide : true,
                    ajax:'{{ route('absenPulang') }}',
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        {data: 'nama', name: 'nama'},
                        {data: 'nip', name: 'nip'},
                        {data: 'tanggal', name: 'tanggal', orderable: false, searchable: false},
                        {data: 'pulang', name: 'pulang'},
                        {data: 'keterangan_p', name: 'keterangan_p', orderable: false, searchable: false},
                        {data: 'pengurangan_p', name: 'pengurangan_p'},
                        {data: 'foto_p', name: 'foto_p', orderable: false, searchable: false},
                        {data: 'foto_pb', name: 'foto_pb', orderable: false, searchable: false},
                        {data: 'konfirmasi_p', name: 'konfirmasi_p', orderable: false, searchable: false},
                        {data: 'ubahstatus', name: 'ubahstatus', orderable: false, searchable: false},

              
                    ],
                search: {
                    "regex": true
                }
        });

    function konfirmasi(id){
        var id = id;
        var button = $('.btn_'+id);
        var tr = button.parents('tr');
       
        var pengurangan = tr.find('.minus');
        $.get( "/absenPulang/konfirmasi", { idkonf: id } )
            .done(function( data ) {
                if(data >= 0){
                    pengurangan.html(data);
                    tr.find('.status').html('confirmed');
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Absen Telah di Reject Oleh Sistem!',
                        showConfirmButton: false,
                        timer: 1500
                        });
                }
               
            });

    }
    function reject(id){
        var id = id;
        var button = $('.btn_'+id);
        var tr = button.parents('tr');
       
        var pengurangan = tr.find('.minus');
        $.get( "/absenPulang/reject", { idtol: id } )
            .done(function( data ) {
                pengurangan.html(data);
                tr.find('.status').html('rejected');
            });

    }

    function hapus(id){
        var id = id;
        var button = $('.btn_'+id);
        var tr = button.parents('tr');
        var tanggal = button.attr('tanggal');
        var bulan = button.attr('bulan');
        var tahun = button.attr('tahun');
        var nip = button.attr('nip');

        $.get( "/absenPulang/konfirmasi", { idkonf: id } )
            .done(function( data ) {
                if(data >= 0){
                    Swal.fire({
                        title: 'Anda Yakin Hapus?',
                        text: "satu absen pulang akan terhapus!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                            if (result.isConfirmed){
                                $.get( "/absenPulang/hapus", { 
                                    idhap: id,
                                    tanggal: tanggal,
                                    bulan: bulan,
                                    tahun: tahun,
                                    nip: nip 
                                }).done(function( data ) {
                                    tr.find('.keterangan_p').html('belum absen');
                                    tr.find('.foto_p').remove();
                                    tr.find('.minus').html('');
                                    tr.find('.pulang').html('');
                                });
                                        
                            }
                    });
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Absen telah di reject tim kabupaten!',
                        showConfirmButton: false,
                        timer: 1500
                        });
                }               
            });
    }

    function gbabsen(nama){
        var data = nama.split('|');
        var nm = data[0];
        var gb = data[1];
        Swal.fire({
			  text: nm,
			  imageUrl: '/storage/'+gb,
			  imageWidth: 480,
			  imageHeight: 540,
			  imageAlt: 'Custom image',
			})
    }
</script>
@endpush