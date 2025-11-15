<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/jpg" href="/img/logolampura2.ico">
    <title>e-absensi | {{ config('global.nama_lain') }}</title>
    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/package/dist/sweetalert2.min.css">
    <script src="/package/dist/sweetalert2.min.js"></script>
    {{-- plugin leaflet-peta --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        body {
            height: 150vh;
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
    <div id="imgLoading">
        <img src="/img/spinner.gif">
    </div>

    <div class="row">
        <div class="col">
            <h3 class="alert alert-success text-center">WFO MASUK</h3>
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
                <input type="text" class="form-control" id="unor" name="unor" value="{{ $unor }}" readonly>
            </div>
            @if($tpt_lain != '')
            <div class="form-group">
                <label for="tptLain">Tempat Tugas Lain</label>
                <select class="form-control" id="tptLain">
                    <option value="{{ $lat }},{{ $lot }}">== Pilih ==</option>
                    <option value="{{ $latlain }},{{ $lotlain }},{{ $tpt_lain }}">{{ $tpt_lain }}</option>
                </select>
            </div>
            @endif
            <button type="button" id="cekLok" class="btn btn-primary btn-lg btn-block">Cek Lokasi</button>
        </div>
    </div>
    <div class="row  d-flex justify-content-center align-items-center">
        <div class="col-11">
            <div>
                <p class="mt-2 mb-0">Loc anda : <span id="latAnda"></span>, <span id="lotAnda"></span> </p>
                <p class="mt-0 mb-0">Loc Kantor : <span id="latKantor">{{ $lat }}</span>, <span id="lotKantor">{{ $lot
                        }}</span>
                </p>
                <input type="hidden" id="tpt_lain" value="">
            </div>
            <div id="bungkus">
                <div id="map" class="mt-2"></div>
            </div>
            <p id="jarto"></p>
        </div>
    </div>
    <div class="row  d-flex justify-content-center align-items-center mb-3" id="posisi">

        <p class="badge badge-info text-center" id="didalam" style="display: none;">
            Anda Berada di Lingkungan Kantor
        </p>
        <p class="badge badge-danger text-center" id="diluar" style="display: none;">
            Anda Berada diluar radius kantor (100 m)
        </p>

    </div>
    <div class="row d-flex justify-content-center align-items-center" id="feedback">
        <div class="col-6">
            <form action="/uploadWfoMasukNon" method="post">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg btn-block" id="kirim" style="display: none;">Kirim
                    Absen &ensp;<i class="fas fa-paper-plane"></i></button>
            </form>

            <a href="/absensi/pilihKeterangan" class="btn btn-warning btn-block" id="kembali"
                style="display: none;">Kembali</a>


        </div>
    </div>

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

    {{-- notifikasi --}}
    @if(session()->has('success'))
    <script>
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: '{{ session("success") }}',
          showConfirmButton: false,
          timer : 1000
        }).then(function(){
            document.location.href='/rekapPerorangan';
        })
    </script>

    @endif



    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
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

        })
    </script>
    <script type="text/javascript">
        getMap();
    function getMap()
    {

        var latKantor = $('#latKantor').html();
        var lotKantor = $('#lotKantor').html();
        var uker = $('#tujuan').val();
        var unor = $('#unor').val();
        var tpt_lain = " -  "+$('#tpt_lain').val();
            
        var maps = L.map('map', {
            center: [latKantor, lotKantor],
            zoom: 15
        });
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(maps);
            L.Control.geocoder().addTo(maps);
            var marker = L.marker([latKantor, lotKantor]).addTo(maps);
            marker.bindPopup("<b>"+uker+"</b> </br>"+unor+tpt_lain).openPopup();
            var circle = L.circle([latKantor, lotKantor], {
                color: 'green',
                fillColor: 'green',
                fillOpacity: 0.2,
                radius: 100
            }).addTo(maps);
           

            

                     

    }
   
    $('#cekLok').on('click', function(){
                map.remove();
                $('#bungkus').append('<div id="map"></div>');
                // map.invalidateSize();
                myLat = $('#latAnda').html();
                myLong =$('#lotAnda').html();

                var latKantor = $('#latKantor').html();
                var lotKantor = $('#lotKantor').html();
                var uker = $('#tujuan').val();
                var unor = $('#unor').val();
                var tpt_lain = " -  "+$('#tpt_lain').val();
                    
                var maps = L.map('map', {
                    center: [myLat, myLong],
                    zoom: 15
                });
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(maps);
                    L.Control.geocoder().addTo(maps);
                  

                    var marker = L.marker([latKantor, lotKantor]).addTo(maps);
                    marker.bindPopup("<b>"+uker+"</b> </br>"+unor+tpt_lain).openPopup();
                    var circle = L.circle([latKantor, lotKantor], {
                        color: 'green',
                        fillColor: 'green',
                        fillOpacity: 0.2,
                        radius: 100
                    }).addTo(maps);

                var jarak = "{{$jarak}}"
                var latKantor = $('#latKantor').html();
                var lotKantor = $('#lotKantor').html();
                var marker2 = L.marker([myLat, myLong]).addTo(maps);
                    marker2.bindPopup("<b>Posisi Anda</b>").openPopup();
                var selisih1 = myLat - (latKantor);
                var selisih2 = myLong - lotKantor;
                var jarak1 = Math.pow((selisih1 * 111322),2);
                var jarak2 = Math.pow((selisih2 * 111322),2);
                var jarto = Math.sqrt(jarak1+jarak2);
                    jarto = Math.round(jarto);
                $('#jarto').html('Radius Anda '+jarto+' meter');
                if(jarak == 1){
                    $('#diluar').hide();
                    $('#didalam').show();
                    $('#kembali').hide();
                    $('#kirim').show();
                    location.href='#feedback';
                }else if(jarto > 150) {
                    $('#diluar').show();
                    $('#didalam').hide();
                    $('#kembali').show();
                    $('#kirim').hide();
                    location.href='#feedback';
                    
                }else{
                    $('#diluar').hide();
                    $('#didalam').show();
                    $('#kembali').hide();
                    $('#kirim').show();
                    location.href='#feedback';
                   
                }
    });



        var myLat = "";
        var myLong = "";
        var myLocation;
        var statusMyLocation = false;
     
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
            document.location.reload();
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
                    // document.location.href = '#imgLoading';
                    // document.getElementById("imgLoading").innerHTML = "<div class=\"loading\">Loading&#8230;</div>";
                    setTimeout(checkMyLocation,500);
                }else
                {
                    // document.location.href = '#imgLoading';
                    document.getElementById("imgLoading").innerHTML = "";
            
                }


        }


    </script>

    {{-- <script>
        var map = L.map('map').setView([-4.868835, 104.7065839], 16);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);
        L.Control.geocoder().addTo(map);
        var marker = L.marker([-4.868835, 104.7065839]).addTo(map);
        marker.bindPopup("<b>Kantor Kecamatan Abung Barat</b>").openPopup();
        var circle = L.circle([-4.868835, 104.7065839], {
            color: 'green',
            fillColor: 'green',
            fillOpacity: 0.2,
            radius: 100
        }).addTo(map);
        circle.bindPopup("<b>Kantor Kecamatan Abung Barat</b>").openPopup();
        if(!navigator.geolocation){
            alert("Your browser doesn't support geolocation feature!");
        }else{
            navigator.geolocation.getCurrentPosition(onSuccess);
        }
        function onSuccess(position){
           // alert(position.coords.latitude);
        }

        var marker2 = L.marker([-4.86988, 104.7065839]).addTo(map);
        marker2.bindPopup("<b>Posisi Anda</b>").openPopup();
        var selisih1 = -4.868835 - (-4.86988);
          var selisih2 = 104.7065839 - 104.7065839;
          var jarak1 = Math.pow((selisih1 * 111322),2);
          var jarak2 = Math.pow((selisih2 * 111322),2);
          var jarto = Math.sqrt(jarak1+jarak2);
          alert(jarto);

    </script> --}}

    {{-- <script>
        var map_init = L.map('map', {
            center: [9.0820, 8.6753],
            zoom: 8
        });
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map_init);
        L.Control.geocoder().addTo(map_init);
        if (!navigator.geolocation) {
            console.log("Your browser doesn't support geolocation feature!")
        } else {
            setInterval(() => {
                navigator.geolocation.getCurrentPosition(getPosition)
            }, 5000);
        };
        var marker, circle, lat, long, accuracy;

        function getPosition(position) {
            // console.log(position)
            lat = position.coords.latitude
            long = position.coords.longitude
            accuracy = position.coords.accuracy
            // alert(accuracy);

            if (marker) {
                map_init.removeLayer(marker)
            }

            if (circle) {
                map_init.removeLayer(circle)
            }

            marker = L.marker([lat, long])
            circle = L.circle([lat, long], {
                radius: 200
            })

            var featureGroup = L.featureGroup([marker, circle]).addTo(map_init)

            map_init.fitBounds(featureGroup.getBounds())

            console.log("Your coordinate is: Lat: " + lat + " Long: " + long + " Accuracy: " + accuracy)
        }
    </script> --}}

</body>

</html>