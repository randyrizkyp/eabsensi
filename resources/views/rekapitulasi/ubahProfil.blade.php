@extends('templates.main')
@section('content')
<style>
    .wfo {
        background: linear-gradient(to right, #D6B7B7, #FAFAD6);
    }

    .wfh {
        background: linear-gradient(to right, #D1F9F5, #FAFAD6);
    }

    .tmk {
        background: linear-gradient(to right, #F6DA8E, #FAFAD6);
    }

    .gbab {
        width: 100px;
        height: 75px;
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
<div class="row">
    <div class="col-md-12">
        <form action="/updateProfil" method="post" enctype="multipart/form-data">
            @csrf
            <table class="table table-striped table-bordered">
                <tr>
                    <th colspan="2" class="text-center bg-info">Ubah Foto</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-center">
                        <img src="{{ '/storage/foto_pegawai/'.$dapeg->foto }}" width="200px" id="gbprofil">
                    </th>
                </tr>
                <tr>
                    <th>
                        <input type="hidden" name="id" value="{{ $dapeg->id }}">
                        <input type="hidden" name="oldFoto" value="{{ $dapeg->foto }}">
                        <input type="file" name="ubahFoto" class="form-control" id="ubahFoto" required>
                    </th>
                    <th class="text-center">
                        <button class="btn btn-sm btn-primary">Submit</button>
                    </th>
                </tr>
            </table>
        </form>
        <form action="/updateProfil" method="post">
            @csrf
            <table class="table table-bordered">
                <tr class="bg-info text-center">
                    <th>Ubah Password</th>
                </tr>
                <tr>
                    <th>
                        <div class="form-group">
                            <label for="oldPass">Password Lama</label>
                            <input type="password" class="form-control" id="oldPass" name="passlama" required>
                            <div class="form-group form-check float-right">
                                <input type="checkbox" class="form-check-input" id="showOld">
                                <label class="form-check-label" for="showOld">show password</label>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>
                        <div class="form-group">
                            <label for="passbaru">Password Baru</label>
                            <input type="password" minlength="6" maxlength="10" class="form-control" id="passbaru"
                                name="passbaru" required>
                            <div class="form-group form-check float-right">
                                <input type="checkbox" class="form-check-input" id="showBaru">
                                <label class="form-check-label" for="showBaru">show password</label>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th class="text-center">
                        <button class="btn btn-primary btn-sm">Submit</button>
                    </th>
                </tr>
            </table>
        </form>
    </div>
</div>
{{-- notifikasi --}}
@if(session()->has('gantiPass'))
<script>
    Swal.fire({
   position: 'center',
   icon: 'success',
   title: '{{ session("gantiPass") }}',
   showConfirmButton: true
}).then(function(){
    document.location.href="/logout";
});
</script>

@endif

{{-- notifikasi --}}
@if(session()->has('gagal'))
<script>
    Swal.fire({
   position: 'center',
   icon: 'error',
   title: '{{ session("gagal") }}',
   showConfirmButton: true
})
</script>

@endif

{{-- notifikasi --}}
@if(session()->has('success'))
<script>
    Swal.fire({
   position: 'center',
   icon: 'success',
   title: '{{ session("success") }}',
   showConfirmButton: false,
   timer: 1000
})
</script>

@endif

@endsection
@push('script')
{{-- <script src="/js/filter.js"></script> --}}
<script>
    $('#showOld').click(function(){
           if($(this).is(':checked')){
            $('#oldPass').attr('type', 'text');
           }else{
            $('#oldPass').attr('type', 'password');
           }
        });
        $('#showBaru').click(function(){
           if($(this).is(':checked')){
            $('#passbaru').attr('type', 'text');
           }else{
            $('#passbaru').attr('type', 'password');
           }
        });


    $('#menu').on('click', function(){
        $('#submenu').toggle('slow');
    })
    $('#wrap').on('click', function(){
        $('#submenu').hide();
    });
    
    $("#ubahFoto").change(function(event) {
            var filename = $(this).val();
            getURL2(this, filename);
    });


                    function getURL2(input, nfile) {

                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            var filename = nfile;
                            filename = filename.substring(filename.lastIndexOf('\\') + 1);
                            var cekgb = filename.substring(filename.lastIndexOf('.') + 1);
                            if (cekgb == 'jpg' || cekgb == 'JPG' || cekgb == 'png' || cekgb == 'jpeg' || cekgb == 'jfif' || cekgb == 'PNG') {
                                
                                if(input.files[0]['size'] > 204800){
                                    alert('ukuran file lebih dari 200 Kb');
                                    $('#ubahFoto').val("");
                                }else{

                                    reader.onload = function(e) {
                                    debugger;
                                    $('#gbprofil').attr('src', e.target.result);
                                    $('#gbprofil').hide();
                                    $('#gbprofil').fadeIn(500);
                                    }
                                reader.readAsDataURL(input.files[0]);
                                }
                                
                        }else {
                                alert ("file harus berjenis 'jpg' , 'jpeg', 'png', atau 'jfif'");
                                $('#ubahFoto').val("");
                                // $('#gbprofil').attr('src', '../img/foto_pegawai/'+{{ $dapeg->foto }}+'');

                                
                            }
                            
                            // reader.readAsDataURL(input.files[0]);
                        }

                        
                            
                        
                    }

</script>
@endpush