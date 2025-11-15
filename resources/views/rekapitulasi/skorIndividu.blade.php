@extends('templates.main')
@section('content')
<style>
    .wfo {
        background: linear-gradient(to right, #D6B7B7, #FAFAD6);
    }

    .wfh {
        background: linear-gradient(to right, #D1F9F5, #FAFAD6);
    }

    .tmk {
        background: linear-gradient(to right, #F6DA8E, #FAFAD6);
    }

    .gbab {
        width: 100px;
        height: 75px;
    }

    #submenu {
        background-color: rgb(52, 64, 74);
    }

    a {
        text-decoration: none;
    }

    a:hover {
        background-color: #8CD486;
        text-decoration: none;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <section>
            <h3 align="center" id="menu" class="alert alert-success mb-0" style="position: relative"><i
                    class="fas fa-bars float-left"></i>Skor Kedisiplinan
            </h3>
            <div id="submenu" class="pt-4 pr-4 mt-0"
                style="min-width: 300px; position: absolute; z-index:999 ; display: none">
                <ul style=" list-style-type: none;">
                    <li class="mb-2 pr-4"><a href="/absensi/pilihKeterangan" class="text-white"><i
                                class="fas fa-list-alt"></i>&emsp;Pilih Keterangan</a>
                    </li>
                    <li class="mb-2 pr-4"><a href="/rekapPerorangan" class="text-white"><i
                                class="fas fa-list-alt"></i>&emsp;Rekap Individu</a>
                    </li>
                    <li class="mb-2 pr-4"><a href="/metodeSkoring" class="text-white"><i
                                class="fas fa-chart-line"></i>&emsp;Metode Skoring</a>
                    </li>
                    <li class="mb-2 pr-4"><a href="/logout" class="text-white"><i
                                class="fas fa-sign-out-alt"></i>&emsp;Log
                            Out</a>
                    </li>
                </ul>
            </div>
        </section>
    </div>
    <div class="col mt-3" id="wrap">
        <center>
            <div class="card">

                <span id="skorP" class="d-none">{{ $totalSkor }}</span>
                <p class="mb-0">Rata-rata skor kedisiplinan </p>
                <p class="mb-1">Bulan {{ $bulan }} Tahun {{ $tahun }}</p>
                <p class="mb-1">Nama : {{ $nama }} || NIP.{{ $nip }} </p>

                <div class="gauge-container mb-0 pb-2">
                    <div class="gauge"></div>
                </div>
                <svg width="0" height="0" version="1.1" class="gradient-mask" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="gradientGauge">
                            <stop class="color-red" offset="0%" />
                            <stop class="color-orange" offset="30%" />
                            <stop class="color-yellow" offset="55%" />
                            <stop class="color-yellow" offset="75%" />
                            <stop class="color-green" offset="90%" />
                            <stop class="color-blue" offset="100%" />
                        </linearGradient>
                    </defs>
                </svg>
                <p>Tingkat Kedisiplinan : {{ $predikat }}</p>

            </div>
            <div class="d-flex justify-content-center">
                <form class="form-inline" action="/cekSkor/individu" method="post">
                    @csrf
                    <div class="form-group mb-2 ml-2">
                        <select class="custom-select mr-sm-2" id="carbul" name="bulan">
                            @foreach($namaBulan as $nambul)
                            <option value="{{ $nambul['angka'] }}" {{ $bulan==$nambul['angka'] ? 'selected' : '' }}>
                                {{ $nambul['nama'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2 ml-2">
                        <select class="custom-select mr-sm-2" id="carbul" name="tahun">
                            <option {{ $tahun==now()->translatedFormat('Y')-2 ? 'selected' : '' }}>
                                {{ now()->translatedFormat('Y')-2 }}</option>
                            <option {{ $tahun==now()->translatedFormat('Y')-1 ? 'selected' : '' }}>
                                {{ now()->translatedFormat('Y')-1 }}</option>
                            <option {{ $tahun==now()->translatedFormat('Y') ? 'selected' : '' }}>{{
                                now()->translatedFormat('Y') }}</option>
                        </select>
                    </div>
                    <button type="submit" name="carsenrang" class="btn btn-primary mb-2 ml-3">Cek
                        Skor</button>
                </form>
            </div>
        </center>

    </div>
</div>
<hr class="my-1">
<p class="text-center mb-1 mt-2">Tabel Rincian Skor</p>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Skor_M</th>
                    <th>Skor_P</th>
                    <th>Rata2</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $dt)
                <tr>
                    <td> {{$loop->iteration}} </td>
                    <td> {{$dt->tanggal.'-'.$dt->bulan.'-'.$dt->tahun}} </td>
                    <td class="text-center"> {{$dt->skor}} </td>
                    <td class="text-center"> {{$dt->skor_p}} </td>
                    <td class="text-center"> {{ ($dt->skor+$dt->skor_p)/2 }} </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        Total Skor`
                    </td>
                    <td class="text-center">{{ $total=($data->sum('skor')+$data->sum('skor_p'))/2 }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center">
                        Rata-rata Skor`
                    </td>
                    <td class="text-center">{{ round($total/count($data),2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div>


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
        @if(session()->has('gagal'))
        <script>
            Swal.fire({
        position: 'center',
        icon: 'error',
        title: '{{ session("gagal") }}',
        showConfirmButton: true
        })
        </script>

        @endif

        @endsection
        @push('script')
        <script src='https://cdn3.devexpress.com/jslib/17.1.6/js/dx.all.js'></script>
        <script src="/js/gauge.js"></script>
        <script>
            $(".circle_percent").each(function() {
    var $this = $(this),
		$dataV = $this.data("percent"),
		$dataDeg = $dataV * 3.6,
		$round = $this.find(".round_per");
	$round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");	
	$this.append('<div class="circle_inbox"><span class="percent_text"></span></div>');
	$this.prop('Counter', 0).animate({Counter: $dataV},
	{
		duration: 2000, 
		easing: 'swing', 
		step: function (now) {
            var text = $this.find(".percent_text");
            $this.find(".percent_text").text("Nilai"+ "\n"+Math.round(now));
        }
    });
	if($dataV >= 51){
		$round.css("transform", "rotate(" + 360 + "deg)");
		setTimeout(function(){
			$this.addClass("percent_more");
		},1000);
		setTimeout(function(){
			$round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");
		},1000);
	} 
});
        </script>
        <script src="/js/filter.js"></script>
        <script>
            $('#menu').on('click', function(){
                $('#submenu').toggle('slow');
                })
                $('#wrap').on('click', function(){
                    $('#submenu').hide();
                });
                
        </script>
        @endpush