@extends('templates.main')

@section('content')
<h4 class="mb-4">JUMLAH PEGAWAI BERDASARKAN DATABASE INDUK (SAPK) TAHUN {{ now()->format('Y') }}</h4>
<!-- top tiles -->
<style>
    .users {
        box-shadow: 5px 5px 5px rgb(78, 76, 76);
    }
</style>
<div class="row d-flex">
    <div class="col-md-2 col-sm-6 border-4 border-dark  ">
        <div class="card bg-success users" style="width: 100%;">
            <div class="card-body text-dark">
                <h1 class="text-center"><i class="fa fa-users"></i></h1>
                <h5 class="card-title text-center fs-6"> Total ASN</h5>
                <div class="fs-3 text-center">{{ number_format($asn) }}</div>

            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 border-4 border-dark  ">
        <div class="card bg-warning users" style="width: 100%;">
            <div class="card-body">
                <h1 class="text-center"><i class="fa fa-user"></i></h1>
                <h5 class="card-title text-center fs-6"> Total PNS</h5>
                <div class="fs-3 text-center">{{ number_format($dbpns) }}</div>

            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 border-4 border-dark ">
        <div class="card bg-secondary text-dark users" style="width: 100%;">
            <div class="card-body">
                <h1 class="text-center"><i class="fa fa-user"></i></h1>
                <h5 class="card-title text-center fs-6"> Total P3K </h5>
                <div class="fs-3 text-center">{{ number_format($dbpppk) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 border-4 border-dark ">
        <div class="card bg-success text-white bg-opacity-75 users" style="width: 100%;">
            <div class="card-body">
                <h1 class="text-center"><i class="fa fa-user"></i></h1>
                <h5 class="card-title text-center fs-6"> Total CPNS</h5>
                <div class="fs-3 text-center">{{ $cpns }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 border-4 border-dark ">
        <div class="card bg-primary text-white users" style="width: 100%;">
            <div class="card-body">
                <h1 class="text-center"><i class="fa fa-user"></i></h1>
                <h5 class="card-title text-center fs-6"> Total Non-ASN</h5>
                <div class="fs-3 text-center">{{ $totalnon }}</div>

            </div>
        </div>
    </div>
</div>
<div>
    <hr>
</div>
<!-- /top tiles -->

<h4 class="mb-4">JUMLAH PEGAWAI SELAKU USERS EABSENSI {{ now()->format('Y') }} </h4>
<div class="row d-flex">
    <div class="col-md-2 col-sm-6 border-4 border-dark  ">
        <div class="card bg-success users" style="width: 100%;">
            <div class="card-body text-dark">
                <h1 class="text-center"><i class="fa fa-users"></i></h1>
                <h5 class="card-title text-center fs-6"> Total Users</h5>
                <div class="fs-3 text-center">{{ number_format($pegawais) }}</div>

            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 border-4 border-dark  ">
        <div class="card bg-warning users" style="width: 100%;">
            <div class="card-body">
                <h1 class="text-center"><i class="fa fa-user"></i></h1>
                <h5 class="card-title text-center fs-6"> Users PNS</h5>
                <div class="fs-3 text-center">{{ number_format($pns) }}</div>

            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 border-4 border-dark ">
        <div class="card bg-secondary text-dark users" style="width: 100%;">
            <div class="card-body">
                <h1 class="text-center"><i class="fa fa-male"></i></h1>
                <h5 class="card-title text-center fs-6"> Users PNS (L)</h5>
                <div class="fs-3 text-center">{{ number_format($pria) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 border-4 border-dark ">
        <div class="card bg-success text-white bg-opacity-75 users" style="width: 100%;">
            <div class="card-body">
                <h1 class="text-center"><i class="fa fa-female"></i></h1>
                <h5 class="card-title text-center fs-6"> Users PNS (P)</h5>
                <div class="fs-3 text-center">{{ number_format($wanita) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 border-4 border-dark ">
        <div class="card bg-primary text-white users" style="width: 100%;">
            <div class="card-body">
                <h1 class="text-center"><i class="fa fa-user"></i></h1>
                <h5 class="card-title text-center fs-6"> Users Non-PNS</h5>
                <div class="fs-3 text-center">{{ number_format($non) }}</div>

            </div>
        </div>
    </div>
</div>
<div>
    <hr>
</div>
<div class="mt-4 mb-4">

    <h4>DAFTAR ALAMAT WEB E-ABSENSI PERANGKAT DAERAH</h4>
    <table id="tableaja" class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Perangkat Daerah</th>
                <th>Alamat e-absensi</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($alamats as $alm)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $alm->nama_pd }}</td>
                <td><a href="/masukAdmin?alamat={{ $alm->situs }}" target="_blank">{{ $alm->situs }}</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br>
<br>
<br>
@endsection