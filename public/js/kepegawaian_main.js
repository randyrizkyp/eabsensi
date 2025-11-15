
function gbAbsen(foto){
        var path = '/storage/foto_pegawai/'+foto;
                               
        Swal.fire({
          imageUrl: path,
          imageWidth: 480,
          imageHeight: 540,
          imageAlt: 'Custom image',
        })
};

 function updatePegawai(id){
                 
    $.ajax({
        'type' : 'get',
        'url' : '/cobaPegawai',
        'data' : {
            'nama' : 'redho',
            'istri' : 'yesi',
            'id' : id
        },
        success : function(hasil){
            $('#updateAsn').modal('show');
            $('#isiUpdate').html(hasil);
        }
    });
 }

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
    var size = input.files[0]['size'];
    
    if (cekgb == 'jpg' || cekgb == 'JPG' || cekgb == 'png' || cekgb == 'jpeg' || cekgb == 'jfif') {
       if(input.files[0]['size'] > 204800){
            alert('ukuran file lebih dari 200 Kb');
            $('#foto_pegawai').val("");
            
       }else{

        reader.onload = function(e) {
            debugger;
            $('#gb_pegawai').attr('src', e.target.result);
            $('#gb_pegawai').hide();
            $('#gb_pegawai').fadeIn(500);

                }
        reader.readAsDataURL(input.files[0]);
        
       } 

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
                                
                                if(input.files[0]['size'] > 204800){
                                    alert('ukuran file lebih dari 200 Kb');
                                    $('.edfoto_pegawai').val("");
                                }else{

                                    reader.onload = function(e) {
                                    debugger;
                                    edgb.attr('src', e.target.result);
                                    edgb.hide();
                                    edgb.fadeIn(500);
                                    }
                                reader.readAsDataURL(input.files[0]);
                                }
                                
                        }else {
                                alert ("file harus berjenis 'jpg' , 'jpeg', 'png', atau 'jfif'");
                                $('.edfoto_pegawai').val("");
                                $('.edgb').attr('src', '../img/foto_pegawai/no_image.png');

                                
                            }
                            
                            // reader.readAsDataURL(input.files[0]);
                        }

                        
                            
                        
                    }

                    
function hapusPegawai(id){
        var idpeg = id;
        var cls = $('.hapus_'+id);
        var tr = cls.parents('tr');
    
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
}
