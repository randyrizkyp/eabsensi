@extends('login.main')

@section('content')

<style>
    body {
        background: #e1c192 url(/img/tugu.jpg);
        background-repeat: no-repeat;
        background-size: cover;
    }

    header #reg {

        color: rgb(195, 226, 42);
    }

    h3 {
        color: aliceblue;
        background-color: black;
    }

    #pertama {
        width: 300px;
        height: 300px;
    }
</style>
<div class="container">
    <header>
        <center>
            <h3>Terimakasih Telah Bergabung Aplikasi Digital Lampung Utara</h3>
            <h3>Anda terdaftar sebagai {{ $sebagai }}, {{ $nama_lain }}

            </h3>
        </center>


    </header>
    <section class="main">
        <h3 align="center">Untuk Keamanan Silahkan Ganti Password Anda !</h3>
        <form id="pertama" class="form-2" method="post" action="/ubahPassLogin">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">
            <p>
                <label for="userbaru"><i class="icon-user"></i>Username :</label>
                <input type="text" name="username" id="userbaru" maxlength="16" value="{{ $username }}" readonly>
            </p>
            <p>
                <label for="passbaru"><i class="icon-lock"></i>Password Baru (min 6 chars & max 10 chars) :</label>
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
    </section>
</div>





@error('username')
<script>
    Swal.fire({
    position: 'center',
    icon: 'error',
    title:  'username salah',
    showConfirmButton: false,
    timer: 1000
    }).then(function(){
            document.location.href='/';

        });
</script>
@enderror
@error('password')
<script>
    Swal.fire({
    position: 'center',
    icon: 'error',
    title:  'password salah',
    showConfirmButton: false,
    timer: 1000
    }).then(function(){
            document.location.href='/';

        });
</script>
@enderror
@if(session()->has('loginerror'))
<script>
    Swal.fire({
    position: 'center',
    icon: 'error',
    title: 'Login Gagal silahkan coba lagi!',
    showConfirmButton: true,
    
    });
</script>
@endif

@if(session()->has('fail'))
<script>
    Swal.fire({
    position: 'center',
    icon: 'error',
    title: '{{ session("fail") }}',
    showConfirmButton: true,
    
    });
</script>
@endif

@endsection