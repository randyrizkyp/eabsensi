@extends('templatesLTE.main')
@section('content')
{{-- notifikasi --}}
@if(session()->has('success'))
<script>
    Swal.fire({
   position: 'center',
   icon: 'success',
   title: '{{ session("success") }}',
   showConfirmButton: false,
   timer : 1000
})
</script>

@endif

{{-- notifikasi --}}
@if(session()->has('fail'))
<script>
    Swal.fire({
   position: 'center',
   icon: 'error',
   title: '{{ session("fail") }}',
   showConfirmButton: true
})
</script>

@endif
{{-- End Notifikasi --}}

<div class="row">
    <div class="col-md-12 p-0 mx-0">
        <!-- Card Body -->
        <div class="card-body bg-white">
            <div class="alert alert-success mb-2 text-center">
                <h3>DAFTAR NON-ASN, TAHUN {{ $tahun }}</h3>
            </div>
           
            <!-- Modal Tambah Pegawai-->
            <div class="modal fade" id="tambagPegawai" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title text-center" id="staticBackdropLabel">Form Tambah Data Pegawai
                                Non-ASN </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body pr-4">
                            <form action="/kepegawaian/tambahAsn" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="col ">
                                        <div class="form-group row">
                                            <label for="nama" class="col-sm-3 col-form-label text-right">Nama</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="nama" name="nama" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="nip" class="col-sm-3 col-form-label text-right">No_Absen</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="nip" name="nip" required>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="col ">
                                        <div class="form-group row">
                                            <label for="jenkel" class="col-sm-3 col-form-label text-right">Jenis
                                                Kel</label>
                                            <div class="col-sm-9">
                                                <select class="custom-select" id="jenkel" name="jenkel" required>
                                                    <option value="" selected disabled>--pilih--</option>
                                                    <option>Laki-laki</option>
                                                    <option>Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="pangkat"
                                                class="col-sm-3 col-form-label text-right">Pangkat</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="pangkat" value="Non-PNS"
                                                    readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="jabatan"
                                                class="col-sm-3 col-form-label text-right">Jabatan</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="jabatan" name="jabatan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="jenjab" class="col-sm-3 col-form-label text-right">Jenis
                                                Jbt</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="jenjab" value="Staf"
                                                    readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="uker" class="col-sm-3 col-form-label text-right">Unit
                                                Kerja</label>
                                            <div class="col-sm-9">
                                                <input type="hidden" name="kode_pd"
                                                    value="{{ config('global.kode_pd') }}">
                                                <input type="text" class="form-control" id="uker" name="uker"
                                                    value="{{ config('global.nama_pd') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="uorg" class="col-sm-3 col-form-label text-right">Unit
                                                Organisasi</label>
                                            <div class="col-sm-9">
                                                <select class="custom-select" id="uorg" name="uorg" required>
                                                    <option value="" selected disabled>-- pilih --</option>
                                                    @foreach($unors as $unor)
                                                    <option
                                                        value="{{ $unor->kode_unit }}|{{ $unor->unit_organisasi }}|{{ $unor->lat }}|{{ $unor->lot }}">
                                                        {{ $unor->unit_organisasi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="tpt_lain" class="col-sm-3 col-form-label text-right">Sub
                                                Unit</label>
                                            <div class="col-sm-9">
                                                <select class="custom-select" id="tpt_lain" name="tpt_lain">
                                                    <option value="|">Tidak Ada</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="opd_lain"
                                                class="col-sm-3 col-form-label text-right">Plt.OPD_Lain</label>
                                            <div class="col-sm-9">
                                                <select class="custom-select" id="opd_lain" name="opd_lain" readonly>
                                                    <option value="|" selected>Tidak Ada</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="tmt_absen"
                                                class="col-sm-3 col-form-label text-right">TMT_Absen</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="tmt_absen" name="tmt_absen"
                                                    autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="tpp" class="col-sm-3 col-form-label text-right">TPP</label>
                                            <div class="col-sm-9">
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">Rp.</div>
                                                    </div>
                                                    <input type="text" class="form-control" id="tpp" name="tpp"
                                                        required>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="norut" class="col-sm-3 col-form-label text-right">No
                                                Urut</label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" id="norut" name="norut"
                                                    value="{{ $pegawaiNon->pluck('norut')->max() + 1 }}" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="foto" class="col-sm-3 col-form-label text-right">foto</label>
                                            <div class="col-sm-9">
                                                <input type="file" class="form-control" id="foto_pegawai"
                                                    placeholder="nominal rupiah" name="foto_pegawai">
                                                <img src="/img/no_image.jpg" width="150"
                                                    class="mt-2 float-right" id="gb_pegawai">
                                            </div>

                                        </div>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="tambo" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>


            <table class="table table-bordered" style="font-size: .8rem;" id="daftarNon">
                <thead style="background-color: darkseagreen">
                    <tr>
                        <th>Norut</th>
                        <th>Nama / No_Absen</th>
                        <th>Pangkat</th>
                        <th>Jabatan</th>
                        <th>Unit Organisasi <br>Tempat Tugas Lain</th>
                        <th>Foto</th>
                        <th>TMT_Absen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pegawaiNon as $non)
                    <tr>
                        <td>{{ $non->norut}}</td>
                        <td>{{ $non->nama }} <br>No.{{ $non->nip }}</td>
                        <td>{{ $non->pangkat }}</td>
                        <td>{{ $non->jabatan }}</td>
                        <td>
                            <ul class="pl-2">
                                <li>{{ $non->unit_organisasi }}</li>
                                @if($non->kode_tpt_lain)
                                <li>{{ $non->tpt_lain }}</li>
                                @endif
                                @if($non->kode_opd_lain)
                                <li>Plt. {{ $non->opd_lain }}</li>
                                @endif
                            </ul>

                        </td>
                        <td align="center">
                            <button class="btn btn-warning btn-sm" onclick="showFoto(`{{ $non->foto }}`)"><i
                                    class="far fa-eye"></i></button>
                        </td>
                        <td>
                            {{ $non->tmt_absen }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                    data-target="#update{{ $non->id }}" title="update">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-success ml-1 mr-1" title="mutasi"><i
                                        class="fas fa-user-minus"></i></button>
                                <button class="btn btn-sm btn-danger hapusPegawai" idpeg="{{ $non->id }}"
                                    title="hapus"><i class="fas fa-trash-alt"></i></button>
                            </div>

                        </td>
                        <!-- Modal Update -->
                        <div class="modal fade" id="update{{ $non->id }}" data-backdrop="static" data-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title text-center" id="staticBackdropLabel">Form Update Data
                                            Pegawai Non-ASN </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body pr-4">
                                        <form action="/kepegawaian/updateAsn" method="post"
                                            enctype="multipart/form-data" class="formUpdate">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $non->id }}">
                                            <div class="form-row">
                                                <div class="col ">
                                                    <div class="form-group row">
                                                        <label for="nama"
                                                            class="col-sm-3 col-form-label text-right">Nama</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="nama"
                                                                name="nama" value="{{ $non->nama }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="nip"
                                                            class="col-sm-3 col-form-label text-right">No_Absen</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="nip" name="nip"
                                                                value="{{ $non->nip }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-row">
                                                <div class="col ">
                                                    <div class="form-group row">
                                                        <label for="jenkel"
                                                            class="col-sm-3 col-form-label text-right">Jenis
                                                            Kel</label>
                                                        <div class="col-sm-9">
                                                            <select class="custom-select" id="jenkel" name="jenkel"
                                                                required>
                                                                <option value="" selected disabled>--pilih--</option>
                                                                <option {{ $non->jenkel == 'Laki-laki' ? 'selected' : ''
                                                                    }}>Laki-laki</option>
                                                                <option {{ $non->jenkel == 'Perempuan' ? 'selected' : ''
                                                                    }}>Perempuan</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="pangkat"
                                                            class="col-sm-3 col-form-label text-right">Pangkat</label>
                                                        <div class="col-sm-9">
                                                            <select class="custom-select" id="pangkat" name="pangkat"
                                                                required readonly>
                                                                <option value="Non-PNS" selected>Non-PNS</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="jabatan"
                                                            class="col-sm-3 col-form-label text-right">Jabatan</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="jabatan"
                                                                name="jabatan" value="{{ $non->jabatan }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="jenjab"
                                                            class="col-sm-3 col-form-label text-right">Jenis
                                                            Jbt</label>
                                                        <div class="col-sm-9">
                                                            <select class="custom-select" id="jenjab" name="jenjab"
                                                                required readonly>
                                                                <option value="Staf Non-PNS" selected>Staf Non-PNS
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="uker"
                                                            class="col-sm-3 col-form-label text-right">Unit
                                                            Kerja</label>
                                                        <div class="col-sm-9">
                                                            <input type="hidden" name="kode_pd"
                                                                value="{{ config('global.kode_pd') }}">
                                                            <input type="text" class="form-control" name="uker"
                                                                value="{{ config('global.nama_pd') }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="uorg"
                                                            class="col-sm-3 col-form-label text-right">Unit
                                                            Organisasi</label>
                                                        <div class="col-sm-9">
                                                            <select class="custom-select uorg" name="uorg">
                                                                @foreach($unors as $unor)
                                                                <option
                                                                    value="{{ $unor->kode_unit }}|{{ $unor->unit_organisasi }}|{{ $unor->lat }}|{{ $unor->lot }}"
                                                                    {{ $non->kode_unit == $unor->kode_unit ? 'selected'
                                                                    : '' }}
                                                                    >
                                                                    {{ $unor->unit_organisasi }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row rowSub">
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="tpt_lain"
                                                            class="col-sm-3 col-form-label text-right">Sub
                                                            Unit</label>
                                                        <div class="col-sm-9">
                                                            <select class="custom-select tpt_lain" name="tpt_lain">
                                                                <option value="|">Tidak Ada</option>

                                                                @foreach ($subunit->where('kode_unit', $non->kode_unit)
                                                                as $sub)
                                                                <option
                                                                    value="{{ $sub->kode_tpt_lain }}|{{ $sub->tpt_lain }}"
                                                                    {{ $non->kode_tpt_lain == $sub->kode_tpt_lain ?
                                                                    'selected' : '' }}>
                                                                    {{
                                                                    $sub->tpt_lain }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="opd_lain"
                                                            class="col-sm-3 col-form-label text-right">Plt.OPD_Lain</label>
                                                        <div class="col-sm-9">
                                                            <select class="custom-select" id="opd_lain" name="opd_lain">
                                                                <option value="|" selected>Tidak Ada</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="tmt_absen"
                                                            class="col-sm-3 col-form-label text-right">TMT_Absen</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control edtmt_absen"
                                                                name="tmt_absen" autocomplete="off"
                                                                value="{{ $non->tmt_absen }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="tpp"
                                                            class="col-sm-3 col-form-label text-right">TPP</label>
                                                        <div class="col-sm-9">
                                                            <div class="input-group mb-2">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">Rp.</div>
                                                                </div>
                                                                <input type="text" class="form-control tpp" name="tpp"
                                                                    value="{{ $non->tpp }}" required>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="norut" class="col-sm-3 col-form-label text-right">No
                                                            Urut</label>
                                                        <div class="col-sm-3">
                                                            <input type="number" class="form-control" id="norut"
                                                                name="norut" value="{{ $non->norut }}" required>

                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="norut" class="col-sm-3 col-form-label text-right">Reset Password</label>
                                                        <div class="col-sm-6">
                                                            <input type="checkbox" name="resetpass" value="1" {{ $non->pertama == 'true' ? 'checked' : '' }}> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group row">
                                                        <label for="foto"
                                                            class="col-sm-3 col-form-label text-right">foto</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" class="form-control edfoto_pegawai"
                                                                name="foto_pegawai">
                                                            <input type="hidden" name="old_foto"
                                                                value="{{ $non->foto }}">

                                                            @if($non->foto)
                                                            <img class="img-fluid mt-2 edgb"
                                                                src="{{ asset('storage/foto_pegawai/'.$non->foto) }}"
                                                                width="150px">
                                                            @else
                                                            <img class="mt-2 float-right edgb"
                                                                src="../img/foto_pegawai/no_image.png" width="150">
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">CLOSE</button>
                                        <button type="submit" name="tambo" class="btn btn-primary">UPDATE</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>


                    </tr>
                    @endforeach
                </tbody>
            </table>




        </div>
    </div>
</div>

@endsection

@push('script')
<script type="text/javascript" src="/js/jquery.mask.js"></script>
<script>
    function showFoto(foto){
        var path = '/storage/foto_pegawai/'+foto;
                               
        Swal.fire({
          imageUrl: path,
          imageWidth: 480,
          imageHeight: 540,
          imageAlt: 'Custom image',
        })
};


    $('#uorg').on('change', function(){
        var uorg = $(this).val();
        var kode_uorg = uorg.split('|');
        $.get("/kepegawaian/ajaxTpt", {'kode_uorg': kode_uorg[0]}).done(function(hasil){
            $('#tpt_lain').html(hasil);
            
        });
    });

    $('.uorg').on('change', function(){
        
        var uorg = $(this).val();
        var kode_uorg = uorg.split('|');
        var form = $(this).parents('.form-row');
        var rowSub = form.siblings('.rowSub');
        var tpt = rowSub.find('.tpt_lain');
       
        $.get("/kepegawaian/ajaxTpt", {'kode_uorg': kode_uorg[0]}).done(function(hasil) {
            tpt.html("");
            tpt.html(hasil);
            
        });
    });



    function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57))
 
      return false;
    return true;
  }
    $('#tpp').mask('000.000.000', {reverse: true});
    $('.tpp').mask('000.000.000', {reverse: true});
	$('.edtpp').mask('000.000.000', {reverse: true});
    $('#tmt_absen').datepicker({
                      format: 'dd-mm-yyyy',
                      autoclose: true,
                      todayHighlight: true,
    });

              $('.edtmt_absen').datepicker({
                      format: 'dd-mm-yyyy',
                      autoclose: true,
                      todayHighlight: true,
                  });

    $("#foto_pegawai").change(function(event) {
        getURL(this);
    });


    function getURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var filename = $("#foto_pegawai").val();
        filename = filename.substring(filename.lastIndexOf('\\') + 1);
        var cekgb = filename.substring(filename.lastIndexOf('.') + 1);
        if (cekgb == 'jpg' || cekgb == 'JPG' || cekgb == 'png' || cekgb == 'jpeg' || cekgb == 'jfif') {
            

            reader.onload = function(e) {
                debugger;
                $('#gb_pegawai').attr('src', e.target.result);
                $('#gb_pegawai').hide();
                $('#gb_pegawai').fadeIn(500);

                    }
        reader.readAsDataURL(input.files[0]);


        }else {
            alert ("file harus berjenis 'jpg' , 'jpeg', 'png', atau 'jfif'");
            $('#foto_pegawai').val("");
            $('#gb_pegawai').attr('src', '../img/foto_pegawai/no_image.png');

            
        }
        
        // reader.readAsDataURL(input.files[0]);
    }

}

                        $(".edfoto_pegawai").change(function(event) {
					   			var edgb = $(this).siblings('.edgb');
					   			var filename = $(this).val();
					        getURL2(this, filename, edgb);
					    });


					    function getURL2(input, nfile,edgb) {

					        if (input.files && input.files[0]) {
					            var reader = new FileReader();
					            var filename = nfile;
					            filename = filename.substring(filename.lastIndexOf('\\') + 1);
					            var cekgb = filename.substring(filename.lastIndexOf('.') + 1);
					            if (cekgb == 'jpg' || cekgb == 'JPG' || cekgb == 'png' || cekgb == 'jpeg' || cekgb == 'jfif' || cekgb == 'PNG') {
					            	

						        	reader.onload = function(e) {
						                debugger;
						                edgb.attr('src', e.target.result);
						                edgb.hide();
						                edgb.fadeIn(500);

						            		}
						        reader.readAsDataURL(input.files[0]);


					        	}else {
					        		alert ("file harus berjenis 'jpg' , 'jpeg', 'png', atau 'jfif'");
					        		$('.edfoto_pegawai').val("");
					        		$('.edgb').attr('src', '../img/foto_pegawai/no_image.png');

					        		
					        	}
					            
					            // reader.readAsDataURL(input.files[0]);
					        }

					        
					        	
					        
					    }

    $('.hapusPegawai').on('click', function(){
        var idpeg = $(this).attr('idpeg');
        var tr = $(this).parents('tr');
       
        var yakin = confirm('apakah anda yakin?');
        if (yakin){
            $.ajax({
                'type' : 'get',
                'url' : '/kepegawaian/hapusAsn',
                'data' : 'idpeg='+idpeg,
                success : function(hasil){
                    if(hasil == 1){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'data berhasil dihapus',
                            showConfirmButton: false,
                            timer : 1000
                            });
                        tr.remove();
                    }
                }
            });
        }
    });

    $('#daftarNon').DataTable();
</script>
@endpush