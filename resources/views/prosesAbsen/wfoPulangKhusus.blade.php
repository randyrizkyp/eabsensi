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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/jpg" href="/img/logolampura2.ico">
    <title>e-absensi | {{ config('global.nama_lain') }}</title>

    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/css/style_baru.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/webcam.js"></script>
    <script src="/js/jquery.countdown360.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="/package/dist/sweetalert2.min.css">
    <script src="/package/dist/sweetalert2.min.js"></script>
    {{-- plugin leaflet-peta --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/light_slider/css/lightslider.css">

    <style>
        .dktr,
        .diluar {
            display: none;
        }

        #my_camera {
            /*width: 320px;
				    height: 240px;*/
            background-color: black;

        }

        #base64image {
            object-fit: contain;
            object-position: center;

            background-color: black;
        }

        td {
            direction: ltr;
            margin: auto;
            text-align: justify;
            word-break: break-word;
            white-space: pre-line;
            overflow-wrap: break-word;
            -ms-word-break: break-word;
            word-break: break-word;
            word-break: break-word;
            -ms-hyphens: auto;
            -moz-hyphens: auto;
            -webkit-hyphens: auto;
            hyphens: auto;
        }

        table {
            table-layout: fixed;
            width: 100%;
        }

        #waktuTempuh {
            font-size: 12px;
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

        .tpic {
            border: 1px solid black;

        }

        #bingkai {

            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 480px;
            border-top: 120px solid black;
            border-left: 40px solid black;
            border-right: 40px solid black;
            border-bottom: 40px solid black;
        }


        .swiper-container {
            width: 100%;
            height: auto;
            border: 1px solid rgb(43, 49, 231);
            padding: 5px;

        }

        .swiper-slide {
            text-align: center;
            font-size: 13px;
            background: #fff;

            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            max-height: 480px;
            margin: 0 auto;

        }

        .swiper-slide p {
            margin-top: 1vw;
        }

        #latKantor,
        #lotKantor {
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

        #map {
            width: 100%;
            height: 300px;
        }

        #imgLoading {
            position: absolute;
            top: 35%;
            left: 45%;
            z-index: 999;
        }
    </style>

</head>

<body>

    <div id="imgLoading"></div>

    <div class="row">
        <div class="col">
            <section>
                <h3 align="center" id="menu" class="alert alert-warning mb-0" style="position: relative"><i
                        class="fas fa-bars float-left"></i>WFO PULANG
                </h3>
                <div id="submenu" class="pt-4 px-4 mt-0"
                    style="min-width: 300px; position: absolute; z-index:999 ; display: none">
                    <ul style=" list-style-type: none;">
                        <li class="mb-2 pr-4"><a href="/absensi/pilihKeterangan" class="text-white"><i
                                    class="fas fa-list-alt"></i>&emsp;Pilih Keterangan</a>
                        </li>
                        <li class="mb-2 pr-4"><a href="/rekapPerorangan" class="text-white"><i
                                    class="fas fa-list-alt"></i>&emsp;Rekap Individu</a>
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

    <div class="row wrap d-flex justify-content-center align-items-center mt-3">
        <div class="col-11">
            <div class="form-group">
                <label for="asal">Dari :</label>
                <input type="text" class="form-control" id="asal" name="asal" value="Lokasi Anda saat ini" readonly>
            </div>
            <div class="form-group">
                <label for="tujuan">Ke Unit Kerja :</label>
                <input type="text" class="form-control" id="tujuan" name="tujuan"
                    value="Kantor {{ config('global.nama_lain') }} Kab.Lampung Utara" readonly>
            </div>
            <div class="form-group">
                <label for="tujuan">Unit Organisasi :</label>
                <input type="text" class="form-control" id="tujuan" name="tujuan" value="{{ $unor }}" readonly>
            </div>
            @if($tpt_lain != '' || $opdlain)
            <div class="form-group">
                <label for="tptLain">Sub Unit / Plt.OPD Lain</label>
                <select class="form-control" id="tptLain">
                    <option value="{{ $lat }},{{ $lot }},{{ config('global.nama_lain').'-'.$unor }}">== Pilih ==
                    </option>
                    @if($tpt_lain != '')
                    <option value="{{ $latlain }},{{ $lotlain }},{{ $tpt_lain }}">{{ $tpt_lain }}</option>
                    @endif
                    @if($opdlain)
                    <option value="{{ $latopd_lain }},{{ $lotopd_lain }},{{ $opdlain->nama_lain }}">{{
                        $opdlain->nama_lain }}
                    </option>
                    @endif
                </select>
            </div>
            @endif

            {{-- <button type="button" id="cekLok" class="btn btn-primary btn-lg btn-block">Cek Lokasi</button> --}}
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-11">
            <div class="alert alert-info text-center">
                Pilih Metode Ambil Gambar
            </div>
            <div>
                <button type="button" class="btn btn-block btn-secondary text-left" id="tokam">1. Dari
                    Kamera</button>
                <button type="button" class="btn btn-block btn-secondary text-left" id="togal">2. Dari
                    Gallery</button>
            </div>
        </div>
    </div>
    <div id="dariGallery" style="display: none">
        <form action="/uploadWfoPulangKhusus" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center mt-3">
                <div class="col-11">
                    <div class="form-group">
                        <label for="pulang">Update Jam Pulang :</label>
                        <input type="text" class="form-control" id="pulang" name="pulang"
                            value="{{ now()->format('H:i:s') }}">
                    </div>
                    <div class="input-group mb-3 mt-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Foto_depan</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="fotoGallery" accept="image/*" class="custom-file-input"
                                id="inputfile" required>
                            <label class="custom-file-label" id="label1" for="inputfile">Choose file</label>
                        </div>
                    </div>
                    <div class="input-group mb-3 mt-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Foto_blkng</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="fotoGallery_b" accept="image/*" class="custom-file-input"
                                id="inputfile2" required>
                            <label class="custom-file-label" id="label2" for="inputfile2">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="col-11">
                    <div class="row">
                        <div class="col-6 border d-flex justify-content-center">
                            <img src="/img/no_image.jpg" id="imgView1"
                                style="width : 90%; height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-6 border d-flex justify-content-center">
                            <img src="/img/no_image.jpg" id="imgView2"
                                style="width : 90%; height: 200px; object-fit: cover;">
                        </div>
                    </div>
                </div>
                <div class="col-11">
                    <div class="row justify-content-center mt-3">
                        <div class="col-8">
                            <button type="submit" id="kirimGallery" class="btn btn-primary btn-block"
                                style="display: none">Kirim Absen &emsp;<i class="fab fa-telegram"></i></button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div id="nomorcam" style="display: none;" class="wrap">
        <section style="position: relative;" class="mt-4" id="sectionCamera">
            <div class="d-flex justify-content-center">
                <div id="my_camera" style="overflow: hidden; width: 360px; height: 480px;  ">
                </div>
            </div>

            {{-- <div id="bingkai"></div> --}}

            <div class="d-flex justify-content-center">

                <button type="button" class="btn btn-primary mt-1" id="tpic"><i
                        class="fas fa-camera-retro"></i>&emsp;Take
                    Pic
                </button>

            </div>

            <div id="countdown" style="position: absolute; top: 10%; left: 50%; transform: translateX(-50%)"></div>
            <div style="position : absolute; width:100%; top:0; left:0; color:darkgrey;">

                <p class="alert alert-success text-center">Buktikan Bahwa Anda di Kantor<br />Silahkan Foto Tanpa
                    Menggunakan
                    Masker</p>



            </div>
        </section>
        <section id="sectionSlide" style="display: none;" class="wrap">
            <!-- Swiper -->
            <div class="swiper-container">
                <div class="swiper-wrapper">

                    <div class="swiper-slide">

                        <a href="#"> <img src="/img/no_image.jpg" id="gbDepan" width="360px" height="480px">
                            <p>
                                Gambar Depan
                            </p>
                        </a>

                    </div>
                    <div class="swiper-slide">

                        <a href="#"><img src="/img/no_image.jpg" id="gbBelakang" width="360px" height="480px">
                            <p>
                                Gambar Belakang
                            </p>
                        </a>

                    </div>


                </div>
                <div class="text-center mb-2">
                    <button type="button" class="btn btn-primary" onclick="retakePic();"><i
                            class="fas fa-redo"></i>&ensp;Retake
                        Pic (<span class="waktuAbsen"></span>)</button>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

        </section>

    </div>




    <div class="row justify-content-center mb-4" id="tokir" style="display: none">
        <div class="col-10 mt-4">
            <form action="/uploadWfoPulang" method="post" id="formPulang">
                @csrf
                <div class="form-group">
                    <label for="pulang2">Update Jam Pulang :</label>
                    <input type="text" class="form-control" id="pulang2" name="pulang"
                        value="{{ now()->format('H:i:s') }}">
                </div>
                <input type="hidden" name="fotoPulang" class="fotoPulang" value="">
                <input type="hidden" name="fotoPulang_b" class="fotoPulang_b" value="">
                <button type="submit" name="kirim" class="btn btn-primary btn-block" id="kirim">Kirim Absen&emsp;<i
                        class="fab fa-telegram"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="row justify-content-center mb-4" id="tokem" style="display: none">
        <div class="col-10 mt-4 text-center">
            <p class="badge badge-danger text-center">Anda berada diluar radius kantor !</p>
            <a href="/absensi/pilihKeterangan" class="btn btn-warning btn-block" id="tombolKembali"><i
                    class="fas fa-arrow-alt-circle-left"></i>&emsp;Kembali
            </a>

        </div>
    </div>

    <audio id="myAudio">
        <source src="{{ asset('img/mixkit-camera-shutter-hard-click-1430.mp3') }}" type="audio/mpeg" id="suara">
        Your browser does not support the audio element.
    </audio>
    <div id="perangkat" style="visibility: hidden">{{ $perangkat }}</div>

    <br><br><br><br><br>
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

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <!-- Swiper JS -->
    <script src="/js/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="/light_slider/js/lightslider.js"></script>
    <script src="/js/filter.js"></script>
    <script>
        $('#togal').on('click', function(){
            $('#dariGallery').show('slow');
            $('.btn-info').removeClass('btn-info');
            $('#togal').toggleClass('btn-info');
            Webcam.reset();
            $('#sectionCamera').hide();
            $('#sectionSlide').hide();
        })
        $('#tokam').on('click', function(){
            $('#dariGallery').hide();
            $('.btn-info').removeClass('btn-info');
            $('#tokam').addClass('btn-info');
            $('#sectionCamera').show();
            ShowCam();
        })



        $('#inputfile').on('change', function(event){

        var inputFile = $('#inputfile').val();
        var pathFile = inputFile.value;
        var ekstensiOk = /(\.jpg|\.jpeg|\.png|\.jfif)$/i;
        var eks = ekstensiOk.exec(inputFile);

            if (!ekstensiOk.exec(inputFile)) {
                alert('silahkan upload file .jpg/.jpeg/.png/.jfif');
                $('#inputfile').val("");
                $('#label1').html("");
                $('#imgView1').src("/img/no_image.jpg");
                return false;
            }else{
                var nmfile = $(this).val();
                $('#label1').html("");
                $('#label1').html(nmfile);
            
                getURL(this);

            }
        })



        function getURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var filename = $("#inputfile").val();
                filename = filename.substring(filename.lastIndexOf('\\') + 1);
                if(input.files[0]['size'] > 1024000 ){
                    alert('ukuran file tidak boleh > 1MB');
                    $('#inputfile').val("");
                    $('#label1').html("");
                    $('#imgView1').src("/img/no_image.jpg");
                    return false;
                    }else{
                    
                        reader.onload = function(e) {
                            debugger;
                            $('#imgView1').attr('src', e.target.result);
                            $('#imgView1').hide();
                            $('#imgView1').fadeIn(500);

                        }
                        reader.readAsDataURL(input.files[0]);
                        
                    }
            
            }

        }

        $('#inputfile2').on('change', function(event){

        var inputFile = $('#inputfile2').val();
        var pathFile = inputFile.value;
        var ekstensiOk = /(\.jpg|\.jpeg|\.png|\.jfif)$/i;
        var eks = ekstensiOk.exec(inputFile);

            if (!ekstensiOk.exec(inputFile)) {
                alert('silahkan upload file .jpg/.jpeg/.png/.jfif');
                $('#inputfile2').val("");
                $('#label2').html("");
                $('#imgView2').src("/img/no_image.jpg");
                return false;
            }else{
                var nmfile = $(this).val();
                $('#label2').html("");
                $('#label2').html(nmfile);
            
                getURL2(this);

            }
        })


        function getURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var filename = $("#inputfile2").val();
                filename = filename.substring(filename.lastIndexOf('\\') + 1);
                if(input.files[0]['size'] > 1024000 ){
                    alert('ukuran file tidak boleh > 1MB');
                    $('#inputfile2').val("");
                    $('#label2').html("");
                    $('#imgView2').src("/img/no_image.jpg");
                    return false;
                    }else{
                    
                        reader.onload = function(e) {
                            debugger;
                            $('#imgView2').attr('src', e.target.result);
                            $('#imgView2').hide();
                            $('#imgView2').fadeIn(500);

                        }
                        reader.readAsDataURL(input.files[0]);
                        $('#kirimGallery').show();


                    }
            
            }

        }


        // submenu
    $('#menu').on('click', function () {
    $('#submenu').toggle('slow');
    })
    $('.wrap').on('click', function () {
    $('#submenu').hide();
    });


    function slide() {
    var swiper = new Swiper('.swiper-container', {
        spaceBetween: 10,
        centeredSlides: true,
        autoplay: true,
        pagination: {
        el: '',
        clickable: true,
        },
        navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
        },
    });
    }


// TPT_LAIN
$('#tptLain').on('change', function(){
          var latLot = $(this).val();
          latLot = latLot.split(',');
          var latAnda = $('#latAnda').html();
          var lotAnda = $('#lotAnda').html();
          var lat = Number(latLot[0]);
          var lot = Number(latLot[1]);
          var tpt_lain = latLot[2];
          $('#tpt_lain').val(tpt_lain);
          if(lat == 0){
            lat = latAnda;
            lot = lotAnda;
          }
          $('#latKantor').html(lat);
          $('#lotKantor').html(lot);
          
          map.remove();
          $('#bungkus').append('<div id="map"></div>');
          getMap();

});



var wakab;
function retakePic() {
  clearInterval(wakab);
  $('#tokir').hide();
  $('#sectionCamera').show();
  $('#sectionSlide').hide();
  $('#countdown360').html("");
  Webcam.reset();
  Webcam.init();
  Webcam.params.constraints = null;
  ShowCam();

}

function waktuAbsen() {
  var i = 60;
  wakab = setInterval(function () {
    $('.waktuAbsen').html(i + 's');
    if (i == 1) {
      $('#tokir').hide();    
    }else if(i<=0){
      clearInterval(wakab);
      $('.waktuAbsen').html("");
      location.reload();
      alert('waktu absen habis, silahkan ulangi!');
    }
    i--;
  }, 1000);
}


let aud = document.getElementById("myAudio");
function playAudio() {
  aud.play();
}

function mundur() {

  var countdown = $("#countdown").countdown360({
    radius: 60,
    seconds: 3,
    fontColor: '#708090',
    autostart: false,
    onComplete: function () {
      playAudio();
      $('#sectionCamera').hide();
      $('#sectionSlide').show();
      countdown.stop();
      $("#countdown").html("");
      countdown._init();
      slide();
      waktuAbsen();
      $('#tokir').show();
    }
  });
  countdown.start();
}

function ShowCam() {
  $('#nomorcam').show();

  document.location.href = '#nomorcam';

  Webcam.set({

    image_format: 'jpeg',
    jpeg_quality: 70
  });
  Webcam.attach('#my_camera');

}


$('#tpic').on('click', function () {
  Webcam.snap(function (data_uri) {
    $('#my_camera').html("");
    document.getElementById('my_camera').innerHTML = '<img id="base64image" src="' + data_uri + '" />';
    $('.fotoPulang').val(data_uri);
    $('#gbDepan').attr('src', data_uri);
    Webcam.reset();
    ShowCam2();
    mundur();
  });

});

function ShowCam2() {
  Webcam.init();
  Webcam.params.constraints = {
    video: true,
    facingMode: "environment"
  };
  Webcam.set({

    image_format: 'jpeg',
    jpeg_quality: 70
  });
  Webcam.attach('#my_camera');
  var i = 0;
  var interval = setInterval(function () {
    if (Webcam.live == true) {
      clearInterval(interval);
      console.log(Webcam.live);
      take_snapshot2();

    }
    i++;
  }, 1000);

}

function take_snapshot2() {

  Webcam.snap(function (data_uri2) {
    // $('#my_camera').html("");
    // document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri2+'" />';
    // $('#gambarWfo_b').val(data_uri2); 
    $('.fotoPulang_b').val(data_uri2);
    $('#gbBelakang').attr('src', data_uri2);
  });


}


function isket() {
  $('.pilket').on('change', function () {

    $('.tomb_isket').show();



  })

}

$('#formPulang').on('submit', function (e) {
  e.preventDefault();
  clearInterval(wakab);
  $.ajax({
    type: 'post',
    url: $(this).attr('action'),
    data: $(this).serialize(),
    beforeSend: function () {
      $('#loading').show();
    },
    success: function (hasil) {
      if (hasil == 1) {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Berhasil Absen Pulang',
          showConfirmButton: false,
          timer: 1000
        }).then(function () {

          document.location.href = '/rekapPerorangan';
        })

      } else if (hasil == 2) {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'sudah ada absen pulang hari ini!',
          showConfirmButton: true
        }).then(function () {
          document.location.href = '/rekapPerorangan';
        })
      }

    }
  });

});



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