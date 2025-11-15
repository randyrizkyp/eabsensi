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
    <title>Absen Cuti</title>
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
                    <h3 align="center" id="menu" class="alert alert-success mb-0" style="position: relative"><i
                            class="fas fa-bars float-left"></i>
                        Cuti ASN
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
                <table class="table table-bordered">
                    <tr>
                        <th>Jenis Cuti</th>
                        <th>Maksimal Cuti</th>
                        <th>Pengurangan TPP</th>

                    </tr>
                    <tr>
                        <td>
                            <span id="ketcut">{{ $ketcut }}</span>
                        </td>
                        <td>
                            <span id="makscuti"> {{ $makscuti }} </span> {{ $hari }}
                        </td>
                        <td>
                            <span id="pengurangan">{{ $pengurangan }}</span>% perhari, {{ $max }}
                        </td>

                    </tr>
                </table>

            </div>
        </div>

        <div class="row mt-4 wrap">
            <div class="col">
                <center>
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor"
                        class="bi bi-calendar-week mr-1" viewBox="0 0 16 16">
                        <path
                            d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                    </svg>
                    <input type="text" name="tglawal" id="tglawal" placeholder="Tgl mulai cuti" size="25">
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
                    <input type="text" name="tglkembali" id="tglkembali" placeholder="Tgl akhir cuti" size="25">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3"><button class="btn btn-primary" name="subtgl"
                id="subtgl">Submit</button></div>



    </section>

    <form action="/absensi/uploadCuti" method="post" enctype="multipart/form-data">
        @csrf
        <section id="pilfile" style="display: none;" class="wrap">
            <style type="text/css">
                .image_upload>input {
                    display: none;
                }
            </style>
            <div class="card"
                style="width: 80%; background-color: #F8EDED; margin: 10px auto; display: flex; align-items: center; justify-content: center;">
                <div style="width: 100%; background-color: #A0F9DF">
                    <h5 align="center">Siapkan Surat Cuti Anda</h5>
                </div>

                <div>
                    <button type="button" class="btn btn-info my-2" onclick="ShowCam();"><svg
                            xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-camera" viewBox="0 0 16 16">
                            <path
                                d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z" />
                            <path
                                d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
                        </svg>&emsp;Ambil Foto</button>
                </div>
                <div id="pilgal">

                    <p class="image_upload">
                        <label for="imeg">
                            <a class="btn btn-warning" rel="nofollow"><svg xmlns="http://www.w3.org/2000/svg" width="25"
                                    height="25" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                    <path
                                        d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z" />
                                </svg>&emsp;Pilih dari Gallery</a>
                        </label>
                        <input type="file" name="imeg" id="imeg">
                    </p>


                    <input type="hidden" name="berangkat" class="berangkat" value="">
                    <input type="hidden" name="kembali" class="kembali" value="">
                    <input type="hidden" name="jeniscuti" value="{{ $jeniscuti }}">
                    <input type="hidden" name="makscuti" value={{ $makscuti }}>
                    <input type="hidden" name="pengurangan" value={{ $pengurangan }}>
                    <input type="hidden" name="hari" value="{{ $hari }}">
                    <input type="hidden" name="lamanya" value="" class="lamanya">




                </div>


            </div>

        </section>


        <section id="gbsurat" class="my-2 wrap" style="display: none; width: 250px; margin: 10px auto;">
            <figure style="display: flex; flex-direction: column; align-items: center;">
                <span class="nmfile"></span>
                <img src="" width="250px" height="300px" id="imgView">
                <button class="btn btn-primary mt-3" type="submit" name="kirim">KIRIM&emsp;<i
                        class="fab fa-telegram"></i></button>
            </figure>


        </section>
    </form>

    <section id="fotspt" class="wrap"
        style="display: none; flex-direction: column; justify-content: center; align-items: center; margin-top: 20px;">
        <div><span class="badge badge-success">Silahkan Foto Surat Cuti Anda</span></div>
        <div id="my_camera" style="overflow: hidden; width: 90%; height: 480px; margin: auto;">

        </div>

        <div>
            <button type="buton" class="btn btn-info mt-3 takePic" onclick="take_snapshot();"><i
                    class="fas fa-camera-retro"></i>&emsp;Take Pic</button>
        </div>


    </section>

    </center>




    <form action="/absensi/uploadCuti" method="post" style="display: none;" id="tokir" class="wrap">
        @csrf
        <input type="hidden" name="fgb" value="" class="fgb">
        <input type="hidden" name="berangkat" class="berangkat" value="">
        <input type="hidden" name="kembali" class="kembali" value="">
        <input type="hidden" name="jeniscuti" value="{{ $jeniscuti }}">
        <input type="hidden" name="makscuti" value={{ $makscuti }}>
        <input type="hidden" name="pengurangan" value={{ $pengurangan }}>
        <input type="hidden" name="hari" value="{{ $hari }}">
        <input type="hidden" name="lamanya" value="" class="lamanya">


        <div class="row justify-content-center mt-4">
            <div class="col-6 text-center">
                <button type="submit" name="kirim2" class="btn btn-info btn-block" id="kirim">Kirim Absen&emsp;<i
                        class="fab fa-telegram"></i></button>
            </div>

        </div>

    </form>


    <div id="loading"></div>
    <audio id="myAudio">
        <source src="{{ asset('img/mixkit-camera-shutter-hard-click-1430.mp3') }}" type="audio/mpeg" id="suara">
        Your browser does not support the audio element.
    </audio>
    <br><br><br>


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
        // $.get("/hitungHariKerja", {tglawal:tglawal, tglkembali:tglkembali, selisih:sel}).done(function(hasil){
        //     console.log(hasil);
        // });
              

          if (tglawal == "" || tglkembali == "") {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Oops..isi tanggal secara lengkap!',
                showConfirmButton: false,
                timer: 1500
                }).then(function(){

                location.href="#";})
            
          }else{

              if (sel > 0) {
                  var makscuti = $('#makscuti').html();                                 
                  var ketcut = $('#ketcut').html();
                  
                 // cek dulu record absen di tanggal kedepan
                              Swal.fire({
                              title: 'Anda akan melaksanakan '+ketcut+', selama '+sel+' hari',
                              text: 'siapkan bukti Surat cuti anda!',
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Lanjutkan'
                            }).then((result) => {
                            if (result.isConfirmed){

                                $('.berangkat').val(tglawal);
                                $('.kembali').val(tglkembali);
                                $('.lamanya').val(sel);
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
                    })
               
              }
          }         
      })

    </script>

    <script language="JavaScript">
        let aud = document.getElementById("myAudio");
        function playAudio() {
            aud.play();
        }
        function take_snapshot() {
            playAudio();
            Webcam.snap(function(data_uri) {
            $('#my_camera').html("");
            document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri+'" />';
            $('.fgb').val(data_uri);
            $('.takePic').hide();
            $('#tokir').show();
            });
    
        }


        function ShowCam(){
            Webcam.init();
            Webcam.params.constraints = {
                video: true,
                facingMode: "environment"
            };
            $('#fotspt').css('display', 'flex');
            $('#pilfile').hide();
            location.href='#fotspt';

            Webcam.attach('#my_camera');
                Webcam.set({
                image_format: 'jpeg',
                jpeg_quality: 90
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



    </script>
</body>

</html>