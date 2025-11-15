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
    <title>Perjalanan Dinas</title>
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


        #rowcek {
            display: flex;
            box-sizing: border-box;


        }

        .ketpos {
            margin-left: 12vw;
        }


        .pilket {
            margin-left: 11vw;
            margin-top: 5vw;
            width: 70vw;
            font-size: 5vw;
        }


        #isket {
            display: none;
        }

        #fotspt {

            position: relative;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;


        }

        #loading {
            position: absolute;
            left: 49%;
            bottom: 59%;
            z-index: 999;

        }

        #muatan_gb {}

        .agam {
            font-size: 1.2em;
            /*  margin-top: -13vw;*/
        }

        #tokir {
            justify-content: center;
            z-index: 999;
            margin-top: 10px;



        }
    </style>

</head>

<body>

    <section id="muatan">

        <div class="row">

            <div class="col"><span class="badge badge-success d-flex justify-content-center">
                    <h3>Tanggal Perjalanan Dinas</h3>
                </span></div>
        </div>


        <div class="row mt-4">
            <div class="col">
                <center>
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor"
                        class="bi bi-calendar-week mr-1" viewBox="0 0 16 16">
                        <path
                            d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                    </svg>
                    <input type="text" name="tglawal" id="tglawal" placeholder="Tgl Berangkat" size="25">
                </center>
            </div>
        </div>

        <div class="col mt-4 d-flex justify-content-center"><span class="badge badge-dark ml-2 mr-2">s/d</span></div>

        <div class="row">
            <div class="col">
                <div class="mt-4 d-flex justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor"
                        class="bi bi-calendar-week mr-1" viewBox="0 0 16 16">
                        <path
                            d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                    </svg>
                    <input type="text" name="tglkembali" id="tglkembali" placeholder="Tgl Kembali" size="25">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3"><button class="btn btn-primary" name="subtgl"
                id="subtgl">Submit</button></div>


    </section>

    <center>

        <form action="/absensi/uploadDinasLuar" method="post" enctype="multipart/form-data">
            @csrf
            <section id="pilfile" style="display: none;">
                <style type="text/css">
                    .image_upload>input {
                        display: none;
                    }
                </style>
                <div class="card"
                    style="width: 80%; background-color: #F8EDED; margin: 10px auto; display: flex; align-items: center; justify-content: center;">
                    <div style="width: 100%; background-color: #A0F9DF">
                        <h3 align="center">Siapkan SPT/SPD Anda</h3>
                    </div>

                    <div>
                        <button type="button" class="btn btn-info my-2" onclick="ShowCam();"><i
                                class="fas fa-camera-retro"></i>&emsp;Ambil Foto</button>
                    </div>
                    <div id="pilgal">

                        <p class="image_upload">
                            <label for="imeg">
                                <a class="btn btn-warning" rel="nofollow"><i class="far fa-images"></i>&emsp;Pilih dari
                                    Gallery</a>
                            </label>
                            <input type="file" name="imeg" id="imeg">
                        </p>
                        <input type="hidden" name="berangkat" class="berangkat" value="">
                        <input type="hidden" name="kembali" class="kembali" value="">
                        <input type="hidden" name="jenisdl" value="{{ $jenisdl }}">
                        <input type="hidden" name="tujuan" value="{{ $tujuan }}">
                        <input type="hidden" name="maksud" value="{{ $maksud }}">
                        <input type="hidden" name="pengikut" value="{{ $pengikut }}">

                    </div>


                </div>

            </section>


            <div id="gbsurat" class="row my-3 " style="display: none; width: 250px; margin: 10px auto;">
                <figure style="display: flex; flex-direction: column; align-items: center;">
                    <span class="nmfile"></span>
                    <img src="" width="250px" height="300px" id="imgView">
                    <button class="btn btn-primary mt-3" type="submit" name="kirim">&emsp;Kirim Absen&emsp;&emsp;<i
                            class="fab fa-telegram"></i></button>
                </figure>

            </div>
        </form>


        <section id="fotspt" style="display: none;">
            <div id="muatan_gb">
                <div><span class="badge badge-info mt-2 mb-1 ">
                        <h5>Silahkan Foto SPT/SPPD anda</h5>
                    </span></div>
                <div id="my_camera" style="overflow: hidden; width: 90%; height: 480px; margin: auto; ">

                </div>
                <button onclick="take_snapshot()" class="btn btn-primary agam mt-1"><i
                        class="fas fa-camera-retro"></i>&emsp;Ambil Gambar</button>
            </div>

            <div id="loading" style="display: none;">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>


            <form action="/absensi/uploadDinasLuar" method="post">
                @csrf
                <input type="hidden" name="berangkat" class="berangkat" value="">
                <input type="hidden" name="kembali" class="kembali" value="">
                <input type="hidden" name="jenisdl" value="{{ $jenisdl }}">
                <input type="hidden" name="tujuan" value="{{ $tujuan }}">
                <input type="hidden" name="maksud" value="{{ $maksud }}">
                <input type="hidden" name="pengikut" value="{{ $pengikut }}">
                <input type="hidden" name="data_uri" id="data_uri" value="">


                <div class="row justify-content-center mb-4" id="tokir" style="display: none">
                    <div class="col-md-6">
                        <button type="submit" name="kirim" class="btn btn-info btn-block tomb" id="kirim">Kirim
                            Absen&emsp;<i class="fab fa-telegram"></i></button>
                    </div>
                </div>

            </form>


        </section>
    </center>









    <br><br>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    {{-- <script src="/datepicker/js/bootstrap-datepicker.js"></script> --}}
    <script type="text/javascript">
        $( "#tglawal" ).datepicker({
                dateFormat: 'dd M yy',
                autoclose: true,
                todayHighlight: true,
            });
      $("#tglkembali").datepicker({
                dateFormat: 'dd M yy',
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

                document.location.reload();})
            
          }else{

              if (sel > 0 && sel <= 30) {

                      Swal.fire({
                      title: 'Anda akan DL selama '+sel+' hari, siapkan bukti SPT/SPPD anda!',
                      text: '',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Lanjutkan'
                    }).then((result) => {
                    if (result.isConfirmed){
                        // $('#fotspt').show();
                        $('.berangkat').val(tglawal);
                        $('.kembali').val(tglkembali) ;
                        $('#pilfile').show();
                        
                        
                     }

                    });


              }else if (sel <= 0) {
                  Swal.fire(
                    'uupss!',
                    'Jangan tanggal mundur donk boss!',
                    'error'
                  ).then(function(){
                    document.location.reload();

                    });
                
              }else{

                Swal.fire(
                    'uupss!',
                    'input dinas luar maksimal 30 hari',
                    'error'
                  ).then(function(){
                    $('#tglawal').val("");
                    $('#tglkembali').val("");
                    });


              }
        



        }
          
      })



function ShowCam(){
    Webcam.init();
    Webcam.params.constraints = {
        video: true,
        facingMode: "environment"
    };
  $('#fotspt').show();
  $('#pilfile').hide();
   Webcam.attach('#my_camera');
      Webcam.set({
        image_format: 'jpeg',
        jpeg_quality: 100
     });   
    location.href='#fotspt';
}

  
function take_snapshot() {
    var audio = new Audio("/img/mixkit-camera-shutter-hard-click-1430.mp3");
    audio.play();
    Webcam.snap(function(data_uri) {
    $('#my_camera').html("");
    $('#loading').show();
    document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri+'" />';
    $('#data_uri').val(data_uri);
                    $('#loading').hide();
                    $('#tokir').show();
                    $('.agam').css('display', 'none');
                    location.href='#fotspt';

    });
    
    

  
}




$('#imeg').on('change', function(event){

  var inputFile = $('#imeg').val();
  var pathFile = inputFile.value;
  var ekstensiOk = /(\.jpg|\.jpeg|\.png|\.jfif)$/i;
  var eks = ekstensiOk.exec(inputFile);

    if (!ekstensiOk.exec(inputFile)) {
      alert('silahkan upload file .jpg/.jpeg/.png/.jfif');
      return false;
    }else{
      var nmfile = $(this).val();
       $('#pilfile').hide();
       $('#subtgl').hide();
      getURL(this);

    }




})



    function getURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var filename = $("#imeg").val();
            filename = filename.substring(filename.lastIndexOf('\\') + 1);
            var size = input.files[0]['size'];
            if(size > 204800){
                alert('ukuran file melebihi 200 kb!');
                location.reload();

            }else{
                reader.onload = function(e) {
                debugger;
                $('.nmfile').html('');
                $('.nmfile').html(filename);
                $('#gbsurat').show();
                $('#imgView').attr('src', e.target.result);
                $('#imgView').hide();
                $('#imgView').fadeIn(500);

            }
            reader.readAsDataURL(input.files[0]);
            }
           
           
        }

    }


    </script>

</body>

</html>