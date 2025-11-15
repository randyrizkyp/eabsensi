var myLat = "";
var myLong = "";
var myLocation;
var statusMyLocation = false;
var directionsService = new google.maps.DirectionsService();

var onSuccess = function (position) {
	myLat = position.coords.latitude;
	myLong = position.coords.longitude;
	myLocation = { lat: myLat, lng: myLong };
	statusMyLocation = true;
};

// onError Callback receives a PositionError object
//
function onError(error) {
	alert('code: ' + error.code + '\n' +
		'message: ' + error.message + '\n');
}

var options = {
	enableHighAccuracy: true,
	timeout: 5000,
	maximumAge: 0
};

navigator.geolocation.getCurrentPosition(onSuccess, onError, options);

checkMyLocation();

function checkMyLocation() {
	if (statusMyLocation == false) {
		setTimeout(checkMyLocation, 500);
	} else {
		document.location.href = '#imgLoading';
		document.getElementById("imgLoading").innerHTML = "";

	}


}

function getRoute() {
	// var tujuan = document.getElementById("tujuan").value;
	var tujuan = { lat: -4.8283984, lng: 104.887452 };

	var mapOptions = {
		zoom: 15,
		center: myLocation
	};
	map = new google.maps.Map(document.getElementById('divMap'), mapOptions);
	directionsDisplay.setMap(map);

	asal = myLocation;
	// tujuan = document.getElementById("tujuan").value;
	var tujuan = { lat: -4.8283984, lng: 104.887452 };

	var selisih1 = myLat - (-4.8283984);
	var selisih2 = myLong - 104.887452;
	var jarak1 = Math.pow((selisih1 * 111322), 2);
	var jarak2 = Math.pow((selisih2 * 111322), 2);
	var jarto = Math.sqrt(jarak1 + jarak2);


	var request = {
		origin: asal,
		destination: tujuan,
		travelMode: google.maps.TravelMode.DRIVING
	};

	directionsService.route(request, function (response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
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
			waktuTempuh.innerHTML = "Jarak Tempuh Anda : " + jarak + " , Radius Anda " + Math.floor(jarto) + " meter";
			var radius = Math.floor(jarto);
			if (radius > 50) {
				ShowCam();
			} else if (radius < 50) {
				diLuar();
			}

		} else {
			alert("Unable to find the distance via road.");
		}
	});
}