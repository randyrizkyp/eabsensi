<!DOCTYPE html>
<html>
<noscript>
    <style>
        body,
        html {
            overflow: hidden
        }

        /* Noscript Popup by igniel.com */
        #ignielNoscript {
            background: rgba(0, 0, 0, 0.85);
            padding: 0;
            position: fixed;
            bottom: 0;
            left: 0;
            top: -100px;
            right: 0;
            z-index: 1000;
            opacity: 1;
            visibility: visible;
            height: auto;
        }

        #ignielNoscript svg {
            width: 100px;
            height: 100px
        }

        #ignielNoscript svg path {
            fill: #fff
        }

        #ignielNoscript .isiNoscript {
            background: #008c5f;
            color: #fff;
            position: absolute;
            text-align: center;
            padding: 0 30px 30px 30px;
            margin: auto;
            top: 30%;
            left: 0;
            right: 0;
            font-size: 1.5rem;
            font-weight: 400;
            line-height: 1.5em;
            font-family: monospace;
            max-width: 670px;
            box-shadow: 0 20px 10px -10px rgba(0, 0, 0, 0.15);
            border: 15px solid rgba(0, 0, 0, .07);
            overflow: hidden;
            transition: all .6s cubic-bezier(.25, .8, .25, 1);
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            backface-visibility: visible;
            transition: all .2s ease-in-out, visibility 0s;
            transform-origin: bottom center;
            pointer-events: auto;
            transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(1);
            opacity: 1;
            animation: ignielWobble .5s;
            -moz-animation: ignielWobble .5s;
            -webkit-animation: ignielWobble .5s;
            -o-animation: ignielWobble .5s
        }

        #ignielNoscript .isiNoscript:hover {
            box-shadow: 0 20px 10px -10px rgba(0, 0, 0, 0.2);
        }

        #ignielNoscript .isiNoscript h4,
        #ignielNoscript .isiNoscript .judul {
            display: inline-block;
            background: rgba(0, 0, 0, .07);
            padding: 5px 25px 15px 25px;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 20px
        }
    </style>
</noscript>

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


        .tomb {
            font-size: 1.2em;

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
            display: none;
            justify-content: center;
            z-index: 999;
            margin-top: 10px;



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

        .gbab {
            width: 45px;
            height: 35px;
        }
    </style>

</head>

<body>

    <section id="muatan">

        <div class="row">
            <div class="col-md-12">
                <section>
                    <h3 align="center" id="menu" class="alert alert-info mb-0" style="position: relative"><i
                            class="fas fa-bars float-left"></i>

                        <img class="gbab mr-2" src="/img/sakit.png">IZIN SAKIT
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

        <div class="row mt-4 wrap">
            <div class="col">
                <center>
                    <div class="mt-4 d-flex justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor"
                            class="bi bi-calendar-week mr-1" viewBox="0 0 16 16">
                            <path
                                d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                            <path
                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                        </svg>
                        <input type="text" name="tglawal" id="tglawal" placeholder="Tgl mulai izin sakit" size="25">
                    </div>

                </center>
            </div>
        </div>

        <div class="col mt-4 d-flex justify-content-center"><span class="badge badge-dark ml-2 mr-2">s/d</span></div>

        <div class="row wrap">
            <div class="col">
                <div class="mt-4 d-flex justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor"
                        class="bi bi-calendar-week mr-1" viewBox="0 0 16 16">
                        <path
                            d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                    </svg>
                    <input type="text" name="tglkembali" id="tglkembali" placeholder="Tgl akhir izin sakit" size="25">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3"><button class="btn btn-primary" name="subtgl"
                id="subtgl">Submit</button></div>



    </section>

    <center>

        <section id="fotspt" style="display: none;" class="wrap">


            <div id="muatan_gb">
                <div><span class="badge badge-info mt-2 mb-1 ">
                        <h5>Silahkan Foto Surat Keterangan Dokter</h5>
                    </span></div>
                <div id="my_camera" style="overflow: hidden; width: 90%; height: 480px; margin: auto; ">

                </div>
                <button onclick="take_snapshot()" class="btn btn-primary agam mt-1"><svg
                        xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-camera" viewBox="0 0 16 16">
                        <path
                            d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z" />
                        <path
                            d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
                    </svg>&emsp;Ambil Gambar</button>

            </div>



            <div id="loading" style="display: none;">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>


            <form action="/absensi/uploadSakit" method="post">
                @csrf
                <input type="hidden" name="berangkat" id="berangkat" value="">
                <input type="hidden" name="kembali" id="kembali" value="">
                <input type="hidden" name="data_uri" id="data_uri" value="">

                <div id="tokir">
                    <button type="submit" name="kirim" class="btn btn-info mt-2 tomb" id="kirim">&emsp;Kirim
                        Absen&emsp;<i class="fab fa-telegram"></i></button>
                </div>

            </form>


        </section>
    </center>
    <br><br><br>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script>
        $('#menu').on('click', function(){
            $('#submenu').toggle('slow');
        })
        $('.wrap').on('click', function(){
            $('#submenu').hide();
        });
        
    </script>
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

              if (sel > 0) {
                    if(sel > 3 ){
                            Swal.fire({
                            title: 'Izin sakit maksimal 3 hari!',
                            text: '',
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'reload'
                            }).then((result) => {
                            if (result.isConfirmed){
                                location.reload();                      
                            }

                            });
                    }else{
                        Swal.fire({
                        title: 'Anda Izin sakit selama '+sel+' hari, siapkan bukti Surat Keterangan Dokter!',
                        text: '',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Lanjutkan'
                        }).then((result) => {
                        if (result.isConfirmed){

                            $('#fotspt').show();
                            $('#berangkat').val(tglawal);
                            $('#kembali').val(tglkembali) ;
                            
                            document.location.href='#fotspt';
                            ShowCam();
                        }

                        });

                    }

              }else if (sel <= 0) {
                  Swal.fire(
                    'uupss!',
                    'Jangan tanggal mundur donk boss!',
                    'error'
                  ).then(function(){
                    document.location.reload();

                    })
                
              }
              
          }

          
      })


function ShowCam(){
    Webcam.init();
    Webcam.params.constraints = {
        video: true,
        facingMode: "environment"
    };
   Webcam.attach('#my_camera');
      Webcam.set({
      image_format: 'jpeg',
      jpeg_quality: 100
      });
     

}

  
function take_snapshot() {
    var audio = new Audio("/img/mixkit-camera-shutter-hard-click-1430.mp3");
    audio.play();
    Webcam.snap(function(data_uri) {
    $('#my_camera').html("");
    document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri+'" />';
    $('#data_uri').val(data_uri);
    });
    
    $('#tokir').css('display', 'flex');
    location.href="#tokir";
    $('.agam').css('display', 'none');
    // SaveSnap();
    // $('#loading').show(); 
  
}



function SaveSnap(){
    
    
    var file =  document.getElementById("base64image").src;
    
    
    var formdata = new FormData();
    formdata.append("base64image", file);
    var ajax = new XMLHttpRequest();
    ajax.addEventListener("load", function(event) { uploadcomplete(event);}, false);
    ajax.open("POST", "upload_dl.php");
    ajax.send(formdata);
}
function uploadcomplete(event){
  
  var image_return=event.target.responseText;
  if (image_return == "uploads/no_image.png") {
   alert("gagal simpan foto, silahkan ulangi kembali");
    history.go(-1);
    $('#loading').hide();

  }else{

    document.getElementById("fgb").value = image_return;
    $('#loading').hide();
    $('#tokir').css('display', 'flex');

     location.href="#tokir";
    $('.agam').css('display', 'none');
    
  }

}


    </script>

</body>
<noscript>
    <div id='ignielNoscript'>
        <div class='isiNoscript'><span class='judul'>Aktifkan Javascript</span><br /><svg viewBox='0 0 24 24'>
                <path
                    d='M3,3H21V21H3V3M7.73,18.04C8.13,18.89 8.92,19.59 10.27,19.59C11.77,19.59 12.8,18.79 12.8,17.04V11.26H11.1V17C11.1,17.86 10.75,18.08 10.2,18.08C9.62,18.08 9.38,17.68 9.11,17.21L7.73,18.04M13.71,17.86C14.21,18.84 15.22,19.59 16.8,19.59C18.4,19.59 19.6,18.76 19.6,17.23C19.6,15.82 18.79,15.19 17.35,14.57L16.93,14.39C16.2,14.08 15.89,13.87 15.89,13.37C15.89,12.96 16.2,12.64 16.7,12.64C17.18,12.64 17.5,12.85 17.79,13.37L19.1,12.5C18.55,11.54 17.77,11.17 16.7,11.17C15.19,11.17 14.22,12.13 14.22,13.4C14.22,14.78 15.03,15.43 16.25,15.95L16.67,16.13C17.45,16.47 17.91,16.68 17.91,17.26C17.91,17.74 17.46,18.09 16.76,18.09C15.93,18.09 15.45,17.66 15.09,17.06L13.71,17.86Z' />
            </svg><br />Untuk mengakses web ini, hidupkan Javascript di dalam pengaturan browser.</div>
    </div>
</noscript>

</html>