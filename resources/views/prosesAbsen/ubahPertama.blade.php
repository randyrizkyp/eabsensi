<!DOCTYPE html>
<html>

<head>
    <title>Login_pertama</title>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/jpg" href="../img/logolampura2.ico">
    <title>Login Form BKPSDM</title>
    <meta name="description" content="Custom Login Form Styling with CSS3" />
    <meta name="keywords" content="css3, login, form, custom, input, submit, button, html5, placeholder" />
    <meta name="author" content="Codrops" />
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <script src="/js/modernizr.custom.63321.js"></script>


    <script src="/package/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="/package/dist/sweetalert2.min.css">
    <!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
    <style>
        body {
            background: #e1c192 url(images/bg.jpg);
            background-repeat: no-repeat;
            background-size: cover;
        }

        header #reg {

            color: black;
        }

        #pertama {
            width: 300px;
            height: 300px;
        }
    </style>












</head>

<body>


    <div class="container">
        <header>
            <center>
                <h3>Terimakasih Telah Bergabung Aplikasi Digital Lampung Utara</h3>
                <h3>Anda terdaftar sebagai </h3>
            </center>


        </header>
        <section class="main">
            <h3 align="center">Untuk Keamanan Silahkan Ganti Password Anda !</h3>
            <form id="pertama" class="form-2" method="post" action="">
                <p>
                    <label for="userbaru"><i class="icon-user"></i>Username Baru (max 16 chars):</label>
                    <input type="text" name="userbaru" id="userbaru" maxlength="16" value="" disabled>
                </p>
                <p>
                    <label for="passbaru"><i class="icon-lock"></i>Password Baru (min 6 chars & max 16 chars) :</label>
                    <input type="password" name="passbaru" id="passbaru" minlength="6" maxlength="10" required=""
                        class="showpassword">
                </p>
                <p>
                    <label for="konfirmasi"><i class="icon-lock"></i>Konfirmasi Password :</label>
                    <input type="password" name="konfirmasi" id="konfirmasi" maxlength="10" required=""
                        class="showpassword">
                </p>
                <p>
                    <input class="s_pertama" type="submit" name="s_pertama" value="Submit">
                </p>


    </div>




    <section>
        <!-- Proses Pertama Login -->




    </section>


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
			                    .attr('maxlength',$input.attr('maxlength'))
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


</body>

</html>