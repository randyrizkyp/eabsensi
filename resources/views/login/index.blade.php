@extends('login.main')

@section('content')

{{-- notifikasi --}}
@if(session()->has('success'))
<script>
    Swal.fire({
      position: 'center',
      icon: 'success',
      title: '{{ session("success") }}',
      showConfirmButton: true
    })
</script>

@endif

<div style="display: flex; flex-direction:column; align-items: center; margin-top: 7vw;">
    <img src="/img/logo.png" alt="">
    <h2 style="margin-top: 10px">E-absensi</h2>
    <h2>{{ config('global.nama_lain')}}</h2>

</div>

<div class="row justify-content-center ">
    <section class="main">
        <form class="form-2" method="post" action="/masuk">
            @csrf
            <h1><span class="log-in">Silahkan Login</span></h1>
            <p class="float">
                <label for="username"><i class="icon-user"></i>Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required autofocus>
                <input type="hidden" id="bayangan">
            </p>
            <p class=" float">
                <label for="password"><i class="icon-lock"></i>Password :</label>
                <input type="password" name="password" id="password" placeholder="Password" class="showpassword"
                    autocomplete="" value="{{ old('password') }}" required>
                <input type="hidden" id="bayangan2">
            </p>
            <p class="clearfix">

                <input type="submit" name="s_login" class="s_login" value="Login" onclick="registrasi();">

            </p>
            <br>
            <span id="ingatan"><input type="checkbox" name="ingat" id="remember" value="ingat" checked>Ingat
                saya</span>

            <br>
        </form>
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

@if(session()->has('success'))
<script>
    Swal.fire({
    position: 'center',
    icon: 'success',
    title: '{{ session("success") }}',
    showConfirmButton: true,
    
    });
</script>
@endif

@endsection
@push('script')
<script src="/js/filter.js">
</script>
@endpush