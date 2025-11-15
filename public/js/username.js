
var storage = window.localStorage;
var user = storage.getItem("usernameabsen");
var pass = storage.getItem("passwordabsen");
if (user != null) {
 		$('#username').remove();
 		$('#password').remove();
 		var baru = $('#bayangan').before(`<input type='text' id='username' placeholder='Username' name='username' value='`+user+`'/>`);
 		var baru2 = $('#bayangan2').before(`<input type="password" name="password" id="password" placeholder="Password" class="showpassword" autocomplete="" value="`+pass+`"/>`);
}else{
	

}
