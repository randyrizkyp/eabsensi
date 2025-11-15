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
                    class="fas fa-bars float-left"></i>Metode Skoring
            </h3>
            <div id="submenu" class="pt-4 pr-4 mt-0"
                style="min-width: 300px; position: absolute; z-index:999 ; display: none">
                <ul style=" list-style-type: none;">
                    <li class="mb-2 pr-4"><a href="/absensi/pilihKeterangan" class="text-white"><i
                                class="fas fa-list-alt"></i>&emsp;Pilih Keterangan</a>
                    </li>
                    <li class="mb-2 pr-4"><a href="/cekSkor/individu" class="text-white"><i
                                class="fas fa-chart-line"></i>&emsp;Cek Skor</a>
                    </li>
                    <li class="mb-2 pr-4"><a href="/rekapPerorangan" class="text-white"><i
                                class="fas fa-list-alt"></i>&emsp;Rekap Individu</a>
                    </li>

                    <li class="mb-2 pr-4"><a href="/logout" class="text-white"><i
                                class="fas fa-sign-out-alt"></i>&emsp;Log
                            Out</a>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</div>
<p class="text-center mt-3 mb-1">Metode Skoring Absen Masuk dan Pulang</p>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-striped">
            <tr>
                <th>No</th>
                <th>Keterangan</th>
                <th>Kriteria (Tl/PSW)</th>
                <th>Skor</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Hadir</td>
                <td>Tepat Waktu</td>
                <td>100</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Hadir</td>
                <td>
                    1 s.d < 31 </td>
                <td>75</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Hadir</td>
                <td>
                    31 s.d < 61 </td>
                <td>50</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Hadir</td>
                <td>
                    61 s.d < 91 </td>
                <td>38</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Hadir</td>
                <td>
                    > 91 </td>
                <td>25</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Dinas Luar</td>
                <td class="text-center">
                </td>
                <td>90</td>
            </tr>
            <tr>
                <td>7</td>
                <td>Cuti</td>
                <td class="text-center">
                </td>
                <td>50</td>
            </tr>
            <tr>
                <td>8</td>
                <td>Sakit</td>
                <td class="text-center">
                </td>
                <td>25</td>
            </tr>
            <tr>
                <td>9</td>
                <td>Izin</td>
                <td class="text-center">
                </td>
                <td>25</td>
            </tr>
            <tr>
                <td>10</td>
                <td>TK / Tdk Absen</td>
                <td class="text-center">
                </td>
                <td>0</td>
            </tr>
            <tr>
                <td>11</td>
                <td>(Rejected)</td>
                <td class="text-center">
                </td>
                <td>-100</td>
            </tr>
        </table>
    </div>
</div>
<hr>
<p class="text-center">Tingkat Kedisiplinan</p>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Rata-rata Skor</th>
                    <th>Predikat</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <= 30 </td>
                    <td>Sangat Rendah</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td> 31 - 55 </td>
                    <td>Rendah</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td> 56 - 75 </td>
                    <td>Cukup</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td> 76 - 90 </td>
                    <td>Tinggi</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td> 91 - 100 </td>
                    <td>Sangat Tinggi</td>
                </tr>
            </tbody>
        </table>
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