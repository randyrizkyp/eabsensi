<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-control" content="public">
    <link rel="shortcut icon" type="image/jpg" href="/img/logolampura2.ico">
    <title>E-Absensi | BPKAD</title>

    <script src="/package/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="/package/dist/sweetalert2.min.css">


    <link rel="stylesheet" type="text/css" href="/css/login.css" />

    <style>
        body {
            /* background: url('/img/tr2.jpg');


            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            background: rgba(rgb(241, 236, 236), rgb(234, 241, 234), rgb(201, 201, 239), .5) */
            background: linear-gradient(to right, rgb(173, 228, 255), rgb(157, 157, 223), white);
        }
    </style>
</head>

<body>

    @yield('content')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <script type="text/javascript" src="/js/username.js"></script>
    <script type="text/javascript" src="/js/registrasi.js"></script>


    <script type="text/javascript">
        $(function(){
			    $(".showpassword").each(function(index,input) {
			        var $input = $(input);
			        $("<p class='opt'/>").append(
			            $("<input type='checkbox' class='showpasswordcheckbox' id='showPassword' />").click(function() {
			                var change = $(this).is(":checked") ? "text" : "password";
			                var rep = $("<input placeholder='Password' type='" + change + "' />")
			                    .attr("id", $input.attr("id"))
			                    .attr("name", $input.attr("name"))
			                    .attr('class', $input.attr('class'))
			                    .val($input.val())
			                    .insertBefore($input);
			                $input.remove();
			                $input = rep;
			             })
			        ).append($("<label for='showPassword'/>").text("Show password")).insertAfter($input.parent());
			    });

			    $('#showPassword').click(function(){
					if($("#showPassword").is(":checked")) {
						$('.icon-lock').addClass('icon-unlock');
						$('.icon-unlock').removeClass('icon-lock');    
					} else {
						$('.icon-unlock').addClass('icon-lock');
						$('.icon-lock').removeClass('icon-unlock');
					}
			    });

			

			});


    </script>
    @stack('script')


</body>

</html>