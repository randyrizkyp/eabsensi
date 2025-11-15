<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/jpg" href="../img/logolampura2.ico">
    <title>Absen ></title>
    <link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.min.css') }}" />
    <!-- Custom styles for this template -->

    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
    {{-- <script src="js/jquery.countdown360.js" type="text/javascript" charset="utf-8"></script> --}}
    <script type="text/javascript" src="{{ asset('js/webcam.js') }}"></script>
    {{-- <script type="text/javascript" src="webcam2.js"></script> --}}
    {{-- <script src="../package/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../package/dist/sweetalert2.min.css"> --}}

    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map {
            width: 98%;
            height: 40%;
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
    </style>



</head>

<body>
    <!-- oncontextmenu="return false" onkeydown="return false;" -->



    <content style="display :">
        <section id="muatan">

            <div class="row mt-4 ml-1" id="rowcek">
                <span class="badge badge-dark ceklok">1</span>
                <button class="btn btn-info custom-map-control-button tomb ml-4" onclick="cekLokasi()"
                    id="locationButton">Cek Lokasi&emsp;<img src="point.png" width="30px" /></button>
            </div>

        </section>

        <div id="map"></div>
        <div class="row mt-0 ml-1 ketpos">

            <div class="diluar">
                <h1 class="badge badge-danger mt-1" style="font-size: 4vw;"><i>Anda Berada di Luar Radius 50 m dari
                        Kantor</i></h1>
            </div>
            <div class="dktr "><span>
                    <h4 class="badge badge-success mt-1 " style="font-size: 4vw;">Anda Berada di Lingkungan Kantor</h4>
                </span></div>
            <div><img src="../img/konfirmasi.gif" width="40" class="dktr ml-3"></div>
        </div>


        <br>

        <div class="row mt-4 mb-4" id="isket">

            <div class="col-12">
                <span class="badge badge-dark ceklok">2</span>
                <a href="pilih_keterangan.php" class="btn btn-info tomb ml-4">Kembali</a>
            </div>

        </div>

        <div class="nomorcam" style="display: none;"><span class="badge badge-dark ml-1 ceklok">2</span><span
                class="alert alert-success ml-4" id="ambilgambar">Ambil Gambar &emsp;<img src="dibawah.png"
                    width="20px" /></span>
            <div>
                <center>

                    <section style="position: relative;" class="mt-4 ">
                        <div class="d-flex justify-content-center">
                            <div id="my_camera" style="overflow: hidden; width: 360px; height: 480px;  "></div>
                        </div>

                        <div id="bingkai"></div>

                        <button type="buton" class="btn btn-info tpic" onclick="take_snapshot();"><svg
                                xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                class="bi bi-camera" viewBox="0 0 16 16">
                                <path
                                    d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z" />
                                <path
                                    d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
                            </svg>&emsp;Take Pic
                        </button>
                        <div id="countdown" style="position: absolute; top: 10%; left: 30%;"></div>
                        <div style="position : absolute; width:100%; top:0; left:0; color:darkgrey;">

                            <p class="alert alert-success">Buktikan Bahwa Anda di Kantor<br />Silahkan Foto Tanpa
                                Menggunakan Masker</p>



                        </div>
                    </section>

                </center>

                <div id="tokir" style="display: none;">
                    <form action="upload.php" method="post" id="formmasuk">

                        <input type="hidden" name="kirim" value="oke">

                        <div class="row mt-4 ml-1">
                            <span class="badge badge-dark ceklok">3</span><button type="submit" name="kirim"
                                class="btn btn-info ml-4 tomb" id="kirim">Kirim Absen&emsp;<svg
                                    xmlns="http://www.w3.org/2000/svg" width="27" height="27" fill="currentColor"
                                    class="bi bi-telegram" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z" />
                                </svg></button>
                        </div>

                    </form>
                </div>

                <div id="loading"></div>
                <audio id="myAudio">
                    <source src="mixkit-camera-shutter-hard-click-1430.mp3" type="audio/mpeg" id="suara">
                    Your browser does not support the audio element.
                </audio>
                <br><br><br>
    </content>

    <script type="text/javascript" src="{{ asset('bootstrap4/js/bootstrap.js') }}"></script>

    <script type="text/javascript">
        var lat = -4.084555;
      
   var lot = 103.108655;


// Note: This example requires that you consent to location sharing when
// prompted by your browser. If you see the error "The Geolocation service
// failed.", it means you probably did not give permission for the browser to
// locate you.
let map, infoWindow;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: lat, lng: lot },
    zoom: 15,
  });
  infoWindow = new google.maps.InfoWindow();
  const locationButton = document.createElement("button");
  // locationButton.textContent = "CEK LOKASI ANDA";
  // locationButton.classList.add("custom-map-control-button");
  
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
  // locationBuEventtton.addListener("click", () => {
    // Try HTML5 geolocation.

}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}

    function cekLokasi(){
      
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          var jumlat =  position.coords.latitude;
              jumlat = String(jumlat);
              jumlat = jumlat.length;
              if(jumlat > 10 && perangkat != 'iphone'){
                alert("Anda terindikasi menggunakan GPS fake!, silahkan gunakan real gps");
                document.location.href="../index.php";
              }


          infoWindow.setPosition(pos);
          var selisih1 = position.coords.latitude - (lat);
          var selisih2 = position.coords.longitude - lot;
          var jarak1 = Math.pow((selisih1 * 111322),2);
          var jarak2 = Math.pow((selisih2 * 111322),2);
          var jarto = Math.sqrt(jarak1+jarak2);
          if(lat == 0 && lot == 0){
            jarto = 0;
          }
          

          infoWindow.setContent("<p>Jarak anda dari kantor : "+Math.floor(jarto)+" meter</p>");
          infoWindow.open(map);
          map.setCenter(pos);
          if (jarto > 100) {
              
              
              $('.diluar').show();
              $('.dktr').hide();
              
              $('#isket').show();
            
              
              
          }else{
            $('.dktr').show();
            $('.diluar').hide();
            $('.nomorcam').show();
             $('#isket').hide();
            ShowCam();
            document.location.href="#ambilgambar";
          }

        },
        () => {
          handleLocationError(true, infoWindow, map.getCenter());
        },
        {
            enableHighAccuracy: true,
            timeout: 3000,
            maximumAge: 0
        }
      );
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }
  }

    </script>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
        src="https://maps.googleapis.com/maps/api/js??key=AIzaSyATQ2SO4PsF1BOBhwXMmFSEootqliBm0Nc&callback=initMap&libraries=&v=weekly"
        async></script>


    <script type="text/javascript">
        let aud = document.getElementById("myAudio"); 
    function playAudio() { 
      aud.play(); 
     } 

    function mundur(){
       
      var countdown =  $("#countdown").countdown360({
          radius      : 60,
          seconds     : 3,
          fontColor   : '#708090',
          autostart   : false,
          onComplete  : function () { playAudio(); }
       });
      countdown.start();
    }
    
    </script>

    <script language="JavaScript">
        function take_snapshot() {
   
    Webcam.snap(function(data_uri) {
    $('#my_camera').html("");
    document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri+'" />';
    });
    
    // Webcam2.snap(function(data_uri) {
    // $('#my_camera2').html("");
    // document.getElementById('my_camera2').innerHTML = '<img id="base64image2" src="'+data_uri+'" />';
    // });
    $('.tpic').hide();
    SaveSnap();
    mundur();
     
  
}


function take_snapshot2(image_return) {

     Webcam.snap(function(data_uri2) {
    $('#my_camera').html("");
    document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri2+'" />';
    });
    
    // Webcam2.snap(function(data_uri) {
    // $('#my_camera2').html("");
    // document.getElementById('my_camera2').innerHTML = '<img id="base64image2" src="'+data_uri+'" />';
    // });

    SaveSnap2(image_return);
     
  
}


function isket(){
  $('.pilket').on('change', function(){

    $('.tomb_isket').show();
      


  })

}

function ShowCam2(image_return){
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
    var i=0;
    var interval = setInterval(function(){
        if(Webcam.live == true){
          clearInterval(interval);
          console.log(Webcam.live);
          take_snapshot2(image_return);
           
        }
        i++;
    }, 1000);
    
}

function ShowCam(){

Webcam.set({

image_format: 'jpeg',
jpeg_quality: 70
});
Webcam.attach('#my_camera');

}

function SaveSnap(){
    
    var file =  document.getElementById("base64image").src;
    
    
    var formdata = new FormData();
    formdata.append("base64image", file);
    var ajax = new XMLHttpRequest();
    ajax.addEventListener("load", function(event) { uploadcomplete(event);}, false);
    ajax.open("POST", "upload.php");
    ajax.send(formdata);
}
function uploadcomplete(event){
  
  var image_return=event.target.responseText;
  //alert(image_return);
  if (image_return == "uploads/no_image.png") {
   alert("gagal simpan foto, silahkan ulangi kembali");
    history.go(-1);

  }else{

    document.getElementById("fgb").value = image_return;
   
    Webcam.reset();
    ShowCam2(image_return);
  }

    //document.getElementById("loading").innerHTML="";
    //var image_return=event.target.responseText;
    //document.getElementById("loading").innerHTML=image_return;
    // var showup=document.getElementById("uploaded").src=image_return;
    // alert(image_return);
}



function SaveSnap2(image){
    
    var file =  document.getElementById("base64image").src;
    
    
    var formdata = new FormData();
    formdata.append("base64image2", file);
    var ajax = new XMLHttpRequest();
    ajax.addEventListener("load", function(event) { uploadcomplete2(event, image);}, false);
    ajax.open("POST", "upload.php");
    ajax.send(formdata);
}
function uploadcomplete2(event, image){
  
  var image_return2=event.target.responseText;
  //alert(image_return);
  if (image_return2 == "uploads/no_image.png") {
   alert("gagal simpan foto, silahkan ulangi kembali");
    history.go(-1);

  }else{

    document.getElementById("fgb_b").value = image_return2;
    document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+image+'" />';
    var siap = $('#fgb_b').val();
    if(siap == image_return2)
    {
      kirimAbsen();
    }
    
  }

    //document.getElementById("loading").innerHTML="";
    //var image_return=event.target.responseText;
    //document.getElementById("loading").innerHTML=image_return;
    // var showup=document.getElementById("uploaded").src=image_return;
    // alert(image_return);
}


$('#kirim').on('click', function(e){
  e.preventDefault();
  kirimAbsen();

});

function reload(){
  $('#ulang').hide();
  document.location.reload();

}

function kirimAbsen(){

  $.ajax({
          type : 'post',
          url : 'upload.php',
          data : $('#formmasuk').serialize(),
          beforeSend : function(){
            $('#loading').show();
          },
          success : function(katanya){
            
            var hasil = katanya.trim();
           
            if (hasil == "sudah_absen") {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Anda Sudah Absen Masuk Sebelumya!',
                    showConfirmButton: false,
                    timer: 1000
                    }).then(function(){

                    document.location.href='rekap_perorangan.php';
                  });

            }else if (hasil == "berhasil") {
                  Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: 'Berhasil Absen Masuk',
                      showConfirmButton: false,
                      timer: 1000
                      }).then(function(){

                      document.location.href='rekap_perorangan.php';
                    })


            }else{
                  Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'Oops..GAGAL Absen Masuk!',
                      showConfirmButton: false,
                      timer: 1000
                      }).then(function(){

                      document.location.href='pilih_keterangan.php';
                    })

            }



          }
  });

}






$('.pilket').on('change', function(){

     $('.tomb_isket').css('display', 'flex');


})

    </script>
</body>

</html>