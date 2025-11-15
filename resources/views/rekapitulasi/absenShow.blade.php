@extends('templates.standard.main')
@section('css')


@endsection
@section('content')
<style>
    html,
    body {
        position: relative;

    }

    body {

        font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
        font-size: 14px;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .swiper-container {
        width: 100%;
        height: auto;
        border: 1px solid rgb(43, 49, 231);
        padding: 5px;

    }

    .swiper-slide {
        text-align: center;
        font-size: 13px;
        background: #fff;

        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        max-height: 300px;
        margin: 0 auto;

    }

    .swiper-slide p {
        margin-top: 1vw;
    }
</style>

<h2 align="center" class="alert alert-primary">ABSEN SHOW {{ $nama_lain }}</h2>

<p class="text-center">{{ now()->translatedFormat('l, d-m-Y') }}</p>
<div class="d-flex justify-content-center">
    <a href="{{ url()->full() }}&kategori=masuk"
        class="btn btn-sm my-2 {{ $kategori=='masuk' ? 'btn-primary' : 'btn-secondary' }}">Absen
        Masuk</a>
    <a href="{{ url()->full() }}&kategori=pulang"
        class="btn btn-sm my-2 ml-3  {{ $kategori=='pulang' ? 'btn-primary' : 'btn-secondary' }}">Absen Pulang</a>
</div>
<content>
    <!-- Swiper -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach ($daftarAbsen as $absen)
            <div class="swiper-slide">
                @if($absen->foto)
                <a href=""> <img src="{{ asset('storage/'.$absen->foto) }}" width="75px">
                    <p>
                        {{ $loop->iteration.'. '.$absen->nama }}<br />
                        {{ $absen->jabatan }}<br />
                        {{ $absen->keterangan.' | '.$absen->waktu }}<br />
                        {{ $absen->konfirmasi }}
                    </p>
                </a>
                @else
                <a href=""> <img src="/img/no_image.jpg">
                    <p>
                        {{ $loop->iteration.'. '.$absen->nama }}<br />
                        {{ $absen->jabatan }}<br />
                        {{ $absen->keterangan.' | '.$absen->waktu }}<br />
                        {{ $absen->konfirmasi }}
                    </p>
                    @endif
            </div>
            @endforeach

        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>


    <div class="d-flex justify-content-center">
        <a href="?jenis_jbt=Struktural&kategori={{ $kategori }}"
            class="btn btn-sm mt-4 mr-1 {{ $jenis_jbt=='Struktural' ? 'btn-primary' : 'btn-secondary' }}">Struktural</a>
        <a href="?jenis_jbt=Fungsional&kategori={{ $kategori }}"
            class="btn  btn-sm mt-4 mr-1 {{ $jenis_jbt=='Fungsional' ? 'btn-primary' : 'btn-secondary' }}">Fungsional</a>
        <a href="?jenis_jbt=Staf PNS&kategori={{ $kategori }}"
            class="btn btn-sm mt-4 mr-1 {{ $jenis_jbt=='Staf PNS' ? 'btn-primary' : 'btn-secondary' }}">Staf PNS</a>
        <a href="?jenis_jbt=Non-PNS&kategori={{ $kategori }}"
            class="btn  btn-sm mt-4 {{ $jenis_jbt=='Non-PNS' ? 'btn-primary' : 'btn-secondary' }}">Non-PNS</a>
    </div>
    <div class="d-flex justify-content-center mb-4 mt-3">
        <a href="/rekapPerorangan" class="btn btn-sm btn-success">
            <i class="fas fa-step-backward"></i> Kembali</a>
        <a href="{{ url()->full() }}" class=" btn btn-sm btn-info ml-2">
            <i class="fas fa-sync-alt"></i> Refresh</a>
    </div>

</content>

@endsection
@push('script')
<!-- Swiper JS -->
<script src="/js/swiper-bundle.min.js"></script>
<script type="text/javascript" src="/light_slider/js/lightslider.js"></script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
      spaceBetween: 30,
      centeredSlides: true,
      autoplay: {
        delay: 2000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
 
</script>

@endpush