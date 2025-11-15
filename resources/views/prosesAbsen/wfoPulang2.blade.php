<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/jpg" href="/img/logolampura2.ico">
    <title>e-absensi | {{ config('global.nama_lain') }}</title>
    <link rel="apple-touch-icon" href="apple-touch-icon.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144x144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="apple-touch-icon-57x57-precomposed.png" />
    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/css/style_baru.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- 		<script src="jquery/jquery-1.11.3.min.js"></script> -->
    <!-- <script src="js/mainUrl.js"></script> -->
    <!-- 		<link rel="stylesheet" href="jquery/jquery.mobile-1.4.5.min.css"> -->
    <!-- 		<script src="jquery/jquery-1.11.3.min.js"></script>
		<script src="jquery/jquery.mobile-1.4.5.min.js"></script> -->

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/webcam.js"></script>
    <script src="/js/jquery.countdown360.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="/package/dist/sweetalert2.min.css">
    <script src="/package/dist/sweetalert2.min.js"></script>


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
    </style>

</head>

<body>
    <?php 
  // if ($jam > 11 OR $jam < 6) {
  //  echo "<script>Swal.fire(
  //                   'Anda mengakses diluar jam absen!',
  //                   'Waktu Absensi Masuk adalah 06.00 - 12.00 WIB',
  //                   'error'
  //                 ).then(function(){
  //                   document.location.href='rekap_perorangan.php';

  //                   })
  //  </script>";
  //  exit();
  //  die();
    
  // }


	 ?>

    <div id="imgLoading"></div>

    <div class="row">
        <div class="col">
            <h3 class="alert alert-success text-center">WFO PULANG</h3>
        </div>
    </div>

    <div class="row  d-flex justify-content-center align-items-center">
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
            @if($tpt_lain != '')
            <div class="form-group">
                <label for="tptLain">Tempat Tugas Lain</label>
                <select class="form-control" id="tptLain">
                    <option value="{{ $lat }},{{ $lot }}">== Pilih ==</option>
                    <option value="{{ $latlain }},{{ $lotlain }}">{{ $tpt_lain }}</option>
                </select>
            </div>
            @endif
            <button type="button" class="btn btn-primary btn-lg btn-block" onclick="getRoute()">Cek Lokasi</button>
        </div>
    </div>
    <div class="row  d-flex justify-content-center align-items-center">
        <div class="col-11 ">
            <div>
                <p class="mt-2 mb-0">Loc anda : <span id="latAnda"></span>, <span id="lotAnda"></span> </p>
                <p class="mt-0 mb-0">Loc Kantor : <span id="latKantor">{{ $lat }}</span>, <span id="lotKantor">{{ $lot
                        }}</span>
                </p>
            </div>

            <div id="divMap" style="width: 100%; height: 300px"></div>
            <div id="waktuTempuh"></div>
        </div>
    </div>
    <div class="row mt-0 ketpos d-flex justify-content-center align-items-center">
        <div class="col-10 ">
            <div class="diluar">
                <h1 class="badge badge-danger mt-1" style="font-size: 3vw;"><i>Anda Berada di Luar Radius 100 m dari
                        Kantor</i>
                </h1>
            </div>
            <div class="dktr">
                <h1 class="badge badge-success mt-1 " style="font-size: 3vw;">Anda Berada di Lingkungan Kantor</h1>
            </div>
        </div>
    </div>

    <div class="row mt-4 ketpos justify-content-center align-items-center" id="tobalik" style="display: none;">
        <div class="col-11 mb-4 ">
            <a href="/absensi/pilihKeterangan" class="btn btn-warning btn-block">Kembali ke Pilih Keterangan</a>
        </div>
    </div>

    <div id="nomorcam" style="display: none;">
        <section style="position: relative;" class="mt-4 ">
            <div class="d-flex justify-content-center">
                <div id="my_camera" style="overflow: hidden; width: 360px; height: 480px;  "></div>
            </div>

            <div id="bingkai"></div>

            <div class="d-flex justify-content-center">
                <form action="/uploadFotoPulang" method="post" enctype="multipart/form-data" id="upfotoPulang">
                    @csrf
                    <input type="hidden" name="gambarWfoPulang" id="gambarWfoPulang" value="">
                    <input type="hidden" name="gambarWfoPulang_b" id="gambarWfoPulang_b" value="">
                    <button type="submit" class="btn btn-info tpic mt-1"><i class="fas fa-camera-retro"></i>&emsp;Take
                        Pic
                    </button>
                </form>
            </div>

            <div id="countdown" style="position: absolute; top: 10%; left: 50%; transform: translateX(-50%)"></div>
            <div style="position : absolute; width:100%; top:0; left:0; color:darkgrey;">

                <p class="alert alert-success text-center">Buktikan Bahwa Anda di Kantor<br />Silahkan Foto Tanpa
                    Menggunakan
                    Masker</p>



            </div>
        </section>

    </div>




    <div class="row justify-content-center mb-4" id="tokir" style="display: none">
        <div class="col-10 mt-4">
            <form action="/uploadWfoPulang" method="post" id="formPulang">
                @csrf
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

    <script>
        $('#tptLain').on('change', function(){
      var latLot = $(this).val();
      latLot = latLot.split(',');
      var latAnda = $('#latAnda').html();
      var lotAnda = $('#lotAnda').html();
      var lat = Number(latLot[0]);
      var lot = Number(latLot[1]);
      if(lat == 0){
        lat = latAnda;
        lot = lotAnda;
      }
      $('#latKantor').html(lat);
      $('#lotKantor').html(lot);
    })
    </script>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKZWNryVL_RFWBbDAEmdD3WYwq3RJSEzU&libraries=places">
    </script>
    <script>
        var myLat = "";
    var myLong = "";
    var myLocation;
    var statusMyLocation = false;
    var directionsService = new google.maps.DirectionsService();

    var onSuccess = function(position) {
      myLat = position.coords.latitude;
      myLong = position.coords.longitude;	
      myLocation = { lat: myLat, lng: myLong };
      statusMyLocation = true;
      // alert('lat :'+myLat+' - '+'lot : '+myLong);
      $('#latAnda').html(myLat);
      $('#lotAnda').html(myLong);
    };

    // onError Callback receives a PositionError object
    //
    function onError(error) {
      alert('code: '    + error.code    + '\n' +
          'message: ' + error.message + '\n');
    }

    var options = {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
              };

    navigator.geolocation.getCurrentPosition(onSuccess, onError, options);

    checkMyLocation();

    function checkMyLocation()
    {
      if ( statusMyLocation == false )
      {
        setTimeout(checkMyLocation,500);
      }else
      {
        document.location.href = '#imgLoading';
        document.getElementById("imgLoading").innerHTML = "";
        
      }


    }

    function getRoute()
    {
      // var tujuan = document.getElementById("tujuan").value;
      // var tujuan = { lat: -4.812826505954065, lng: 104.88160734412553  };
      
      var mapOptions = {
        zoom: 15,
        center: myLocation
      };
      map = new google.maps.Map(document.getElementById('divMap'), mapOptions);
      directionsDisplay.setMap(map);

      asal = myLocation;
      var latKantor =$('#latKantor').html();
      var lotKantor = $('#lotKantor').html();
        latKantor = Number(latKantor);
        lotKantor = Number(lotKantor);
        
      // tujuan = document.getElementById("tujuan").value;
      var tujuan = { lat: latKantor, lng: lotKantor  };

            var selisih1 = myLat - (tujuan.lat);
              var selisih2 = myLong - tujuan.lng;
              var jarak1 = Math.pow((selisih1 * 111322),2);
              var jarak2 = Math.pow((selisih2 * 111322),2);
              var jarto = Math.sqrt(jarak1+jarak2);
              

      var request = {
        origin: asal,
        destination: tujuan,
        travelMode: google.maps.TravelMode.DRIVING
      };
      
      directionsService.route(request, function (response, status) {
        if ( status == google.maps.DirectionsStatus.OK) {
          directionsDisplay.setDirections(response);
        }
      });

      var service = new google.maps.DistanceMatrixService();
      service.getDistanceMatrix({
        origins: [asal],
        destinations: [tujuan],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false
      }, function (response, status) {
        if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
          var jarak = response.rows[0].elements[0].distance.text;	
          var waktu = response.rows[0].elements[0].duration.text;
          var waktuTempuh = document.getElementById("waktuTempuh");
          waktuTempuh.innerHTML = "Jarak Tempuh Anda : " + jarak + " , Radius Anda " + Math.floor(jarto)+" meter";
          var radius =  Math.floor(jarto);
          if (radius > 100) {
            ShowCam();
          }else if(radius < 100){
            $('#tokem').show();
           location.href="#tokem";
          }	
          
        } else {
          alert("Unable to find the distance via road.");
        }
      });
    }
    </script>
    <script>
        google.maps.event.addDomListener(window, 'load', function () {
				// new google.maps.places.SearchBox(document.getElementById('tujuan'));
				directionsDisplay = new google.maps.DirectionsRenderer({ 'draggable': true });
			});
			
			document.location.href = '#imgLoading';
			document.getElementById("imgLoading").innerHTML = "<div class=\"loading\">Loading&#8230;</div>";
    </script>


    <script language="JavaScript">
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

    function ShowCam(){
      $('#nomorcam').show();
      $('.dktr').show();
      $('.diluar').hide();
      document.location.href='#nomorcam';
      
      Webcam.set({

      image_format: 'jpeg',
      jpeg_quality: 70
      });
      Webcam.attach('#my_camera');

    }


    $('#upfotoPulang').on('submit', function(e){
      e.preventDefault();
      Webcam.snap(function(data_uri) {
      $('#my_camera').html("");
      document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri+'" />';
      $('#gambarWfoPulang').val(data_uri);
      });
      
      $.ajax({
        url : '/uploadFotoPulang',
        type : 'post',
        data : new FormData(this),
        contentType : false,
        processData : false,
        success : function(image_return){
          if(image_return){
            Webcam.reset();
            ShowCam2(image_return);
            mundur();
            $('.fotoPulang').val(image_return);
          }
        }

       });
    });

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

function take_snapshot2(image_return) {

   Webcam.snap(function(data_uri2) {
   $('#my_camera').html("");
   document.getElementById('my_camera').innerHTML = '<img id="base64image" src="'+data_uri2+'" />';
   $('#gambarWfoPulang_b').val(data_uri2); 
   
   $.ajax({
        url : '/uploadFotoPulang_b',
        type : 'post',
        data : {
          _token : "{{ csrf_token() }}",
          gambarWfoPulang_b : data_uri2
        },
        cache : false,
        success : function(image_return2){
          if(image_return2){
            document.getElementById('my_camera').innerHTML = '<img id="base64image" src="/storage/'+image_return+'" />';
            $('.fotoPulang_b').val(image_return2);
            $('#tokir').show();
          }
        }

    });



  });
   
   
    
 
}


function isket(){
 $('.pilket').on('change', function(){

   $('.tomb_isket').show();
     


 })

}







$('#formPulang').on('submit', function(e){
 e.preventDefault();
    $.ajax({
            type : 'post',
            url : $(this).attr('action'),
            data : $(this).serialize(),
            beforeSend : function(){
              $('#loading').show();
            },
            success : function(hasil){
                if(hasil==1){
                  Swal.fire({
                      position: 'center',
                      icon: 'success',
                      title: 'Berhasil Absen Pulang',
                      showConfirmButton: false,
                      timer: 1000
                      }).then(function(){

                      document.location.href='/rekapPerorangan';
                    })

                  }else if(hasil==2){
                    Swal.fire({
                      position: 'center',
                      icon: 'error',
                      title: 'sudah ada absen pulang hari ini!',
                      showConfirmButton: true
                    }).then(function(){
                       document.location.href='/rekapPerorangan';
                    })
                  }
                       
            }
    });

});

function reload(){
 $('#ulang').hide();
 document.location.reload();

}


function diLuar(){

	$('#nomorcam').hide();
	$('.dktr').hide();
	$('.diluar').show();
	$('#tokir').hide();
	$('#tobalik').show();


}

    </script>
</body>

</html>