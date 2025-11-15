
function registrasi(){



var username = $('#username').val();
var password = $('#password').val();
var ingat = $('#remember').val()
    ingat = $('#remember').is(":checked") ? "ingat" : "lupa";



if (ingat == "ingat") {

var storage = window.localStorage;
storage.setItem("usernameabsen", username);
storage.setItem("passwordabsen", password);
var user = storage.getItem("usernameabsen");
var pass = storage.getItem("passwordabsen");



}else{
	var storage = window.localStorage;
	storage.removeItem("usernameabsen");
	storage.removeItem("passwordabsen");
	
}


}

