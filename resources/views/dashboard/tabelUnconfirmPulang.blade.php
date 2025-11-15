<table class="table table-bordered table-striped mb-0 p-0" id="absenPulang" width="100%" style="font-size: 11px">
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
                Rekap Unconfirmed Absensi Pulang ASN
                @else
                Rekap Absensi Pulang Non-ASN
                @endif
            </th>
            <th class="text-center">
                @if($status == 'asn')
                <button class="btn btn-sm btn-success" id="konfall_pns" bulan="{{ $bulan }}"
                    tahun="{{ $tahun }}">Confirm All</button>
                @else
                <button class="btn btn-sm btn-success" id="konfall_non" bulan="{{ $bulan }}"
                    tahun="{{ $tahun }}">Confirm All</button>
                @endif
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataAbsensiP as $dp)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $dp->nama }}</td>
            <td>{{ $dp->nip }}</td>
            <td>
                <span class="tanggal">{{ $dp->tanggal }}</span>-{{ $dp->bulan }}-{{ $dp->tahun }}
            </td>
            <td>{{ $dp->pulang}}</td>
            <td class="keterangan_p">{{ $dp->keterangan_p }}</td>
            <td class="minus">{{ $dp->pengurangan_p }}</td>
            <td>

                <img src="{{ asset('storage/'.$dp->foto_p) }}" width="75px" class="foto_p"
                    onclick="gbabsen({{ $dp->nama }}|{{ $dp->foto_p }})">
            </td>
            <td>
                <img src="{{ asset('storage/'.$dp->foto_pb) }}" width="75px" class="foto_pb"
                    onclick="gbabsen({{ $dp->nama }}|{{ $dp->foto_pb }})">
            </td>
            <td class="status">
                {{ $dp->konfirmasi_p }}
            </td>
            <td>
                <span class="d-flex justify-content-around">
                    <button class="btn btn-sm btn-info btn_{{ $dp->id }}" onclick="konfirmasi({{ $dp->id }})"
                        title="confirm"><i class="fas fa-check-circle"></i></button>
                    <button class="btn btn-sm btn-danger btn_{{ $dp->id }}" onclick="reject({{ $dp->id }})"
                        title="reject"><i class="fas fa-times-circle"></i></button>
                    <button class="btn btn-sm btn-danger btn_{{ $dp->id }}" onclick="hapus({{ $dp->id }})"
                        title="delete" tanggal="{{ $dp->tanggal }}" bulan="{{ $dp->bulan }}" tahun="{{ $dp->tahun }}"
                        nip="{{ $dp->nip }}"><i class="far fa-trash-alt"></i></button>
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
@push('script')
<script type="text/javascript">
    $('#konfall_pns').click(function(){
	var tanggal = $('.tanggal').eq(0).html();
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

});

    // $('#absenPulang').DataTable({
    //   "responsive": true, "lengthChange": false, "autoWidth": false,
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#absenPulang_wrapper .col-md-6:eq(0)');



    function konfirmasi(id){
        var id = id;
        var button = $('.btn_'+id);
        var tr = button.parents('tr');
       
        var pengurangan = tr.find('.minus');
        $.get( "/absenPulang/konfirmasi", { idkonf: id } )
            .done(function( data ) {
                pengurangan.html(data);
                tr.find('.status').html('confirmed');
            });

    }
    function reject(id){
        var id = id;
        var button = $('.btn_'+id);
        var tr = button.parents('tr');
       
        var pengurangan = tr.find('.minus');
        $.get( "/absenPulang/reject", { idtol: id } )
            .done(function( data ) {
                pengurangan.html(2);
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
                        location.reload();
                        // tr.find('.keterangan_p').html('belum absen');
                        // tr.find('.foto_p').remove();
                        // tr.find('.foto_pb').remove();
                        // tr.find('.minus').html('');
                        // tr.find('.pulang').html('');
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