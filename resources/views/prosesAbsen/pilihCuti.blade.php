<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/jpg" href="/img/logolampura2.ico">
    <title>Absen Sakit</title>
    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/webcam.js"></script>
    <link rel="stylesheet" href="/package/dist/sweetalert2.min.css">
    <script src="/package/dist/sweetalert2.min.js"></script>


    <!-- Daterange picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>




    <style>
        html,
        body {
            width: 100%;
            height: 100%;

            margin: 0;
            padding: 0;
        }

        #map {
            width: 80%;
            height: 50%;
            margin: 15px auto;
        }

        #cam {
            width: 80%;
            height: 500px;
        }

        .ceklok {
            font-size: 5vw;
            height: 50px;
            vertical-align: middle;
            line-height: 50px;
        }

        .tomb {
            width: 50vw;
            font-size: 5vw;
            vertical-align: middle;
            height: 50px;

        }

        #rowcek {
            display: flex;
            box-sizing: border-box;


        }

        .ketpos {
            margin-left: 12vw;
        }

        .dktr,
        .diluar {
            display: none;
        }

        .pilket {
            margin-left: 11vw;
            margin-top: 5vw;
            width: 70vw;
            font-size: 5vw;
        }

        #base64image {
            width: 80%;
        }

        #wadahcam {
            display: flex;
            flex-direction: column;
            position: relative;
            left: 0;

        }

        #my_camera {
            /*width: 320px;
    height: 240px;*/
            background-color: black;

        }

        #base64image {
            object-fit: cover;
            object-position: center;
            width: 100%;
            height: 100%;
            background-color: black;
        }

        #isket {
            display: none;
        }

        #tokir {
            display: none;
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

</head>

<body>

    <section id="muatan">

        <div class="row">
            <div class="col-md-12">
                <section>
                    <h3 align="center" id="menu" class="alert alert-warning mb-0" style="position: relative"><i
                            class="fas fa-bars float-left"></i>
                        Pilih Jenis Cuti ASN
                    </h3>
                    <div id="submenu" class="pt-4 pr-4 mt-0"
                        style="min-width: 300px; position: absolute; z-index:999 ; display: none">
                        <ul style=" list-style-type: none;">
                            <li class="mb-2 pr-4"><a href="/absensi/pilihKeterangan" class="text-white"><i
                                        class="fas fa-list-alt"></i>&emsp;Pilih Keterangan</a>
                            </li>
                            <li class="mb-2 pr-4"><a href="/logout" class="text-white"><i
                                        class="fas fa-sign-out-alt"></i>&emsp;Log
                                    Out</a>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>

        <form action="/absensi/prosesCuti" method="post">
            @csrf
            <div class="row wrap">

                <div class="col-10 ml-3 mt-3">
                    <div class="form-group">


                        <select class="form-control" id="pilcut" name="pilcut">
                            <option value="cuti1">Cuti Tahunan</option>
                            <option value="cuti2">Cuti Besar</option>
                            <option value="cuti3">Cuti Sakit</option>
                            <option value="cuti4">Cuti Melahirkan (anak ke-1 sd ke-3)</option>
                            <option value="cuti5">Cuti Melahirkan (anak ke-4 dst)</option>
                            <option value="cuti6">Cuti Alasan Penting</option>
                            <option value="cuti7">Cuti Diluar Tanggungan Negara</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>

        </form>





    </section>





    <script type="text/javascript" src="../bootstrap4/js/bootstrap.js"></script>
    <script>
        $('#menu').on('click', function(){
            $('#submenu').toggle('slow');
        })
        $('.wrap').on('click', function(){
            $('#submenu').hide();
        });
        
    </script>
    <script type="text/javascript">
        $("#tglawal").datepicker({
                format: 'dd M yyyy',
                autoclose: true,
                todayHighlight: true,
            });
      $("#tglkembali").datepicker({
                format: 'dd M yyyy',
                autoclose: true,
                todayHighlight: true,
            });

      $('#subtgl').on('click', function(){
          
          var tglawal = $('#tglawal').val();
              tgl1 = new Date(tglawal);

          var tglkembali = $('#tglkembali').val();
              tgl2 = new Date(tglkembali);
          var sel = tgl2-tgl1;
              sel = sel/(24*60*60*1000) ;
              sel = sel + 1 ;
              
          

          if (tglawal == "" || tglkembali == "") {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Oops..isi tanggal secara lengkap!',
                showConfirmButton: false,
                timer: 1500
                }).then(function(){

                document.location.href="cuti.php";})
            
          }else{

              if (sel > 0) {

                      Swal.fire({
                      title: 'Anda cuti selama '+sel+' hari, Siapkan Bukti Surat Cuti Anda!',
                      text: '',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Lanjutkan'
                    }).then((result) => {
                    if (result.isConfirmed){

                        $('#berangkat').val(tglawal);
                        $('#kembali').val(tglkembali) ;
                        $('#fotspt').css('display', 'flex');
                        ShowCam();
                     }

                    });


              }else if (sel <= 0) {
                  Swal.fire(
                    'uupss!',
                    'Jangan tanggal mundur donk boss!',
                    'error'
                  ).then(function(){
                    document.location.href="cuti.php";

                    })
                
              }
                

       



          }

          
      })

    </script>



    <script language="JavaScript">
        function take_snapshot() {
    var audio = new Audio("mixkit-camera-shutter-hard-click-1430.wav");
    audio.play();
    Webcam.snap(function(data_uri) {
    $('#my_camera').html("");
    document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri+'" />';
    });
    
    SaveSnap();
     
  
}




function ShowCam(){
   Webcam.attach('#my_camera');
      Webcam.set({
      image_format: 'jpeg',
      jpeg_quality: 90
      });
     

}





function SaveSnap(){
    
    var file =  document.getElementById("base64image").src;
    
    
    var formdata = new FormData();
    formdata.append("base64image", file);
    var ajax = new XMLHttpRequest();
    ajax.addEventListener("load", function(event) { uploadcomplete(event);}, false);
    ajax.open("POST", "upload_cuti.php");
    ajax.send(formdata);
}
function uploadcomplete(event){
  
  var image_return=event.target.responseText;
  if (image_return == "uploads/no_image.png") {
   alert("gagal simpan foto, silahkan ulangi kembali");
    history.go(-1);

  }else{

    document.getElementById("fgb").value = image_return;
   $('#tokir').css('display', 'flex');
     location.href="#tokir";
    
  }

}



    </script>
</body>

</html>