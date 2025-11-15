<?php 
use App\Http\Controllers\BpkaController;

?>

@extends('templates.main')


@section('content')
<?php 
$data = new BpkaController;

?>

<h3>REKAP ABSENSI BPKA BULAN {{ $bulan }}, TAHUN {{ $tahun }}</h3>
<div class="row mt-4 mb-4 d-inline-block">
    <div class="col">
        <a href="/dashboard?opd={{ $perangkat }}" class="btn btn-secondary">Daftar Pegawai</a>
        <a href="" class="btn btn-primary">Rekap Absensi</a>
    </div>
</div>
<table class="table table-bordered table-striped" id="table1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama/NIP</th>
            <th>Jabatan/Pangkat</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Hadir</th>
            <th>DL</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Cuti</th>
            <th>TAP</th>
            <th>Rej</th>
            <th>TK</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pegawais as $peg)
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th>{{ $peg->nama }} <br> NIP.{{ $peg->nip }} </th>
            <th>{{ $peg->jabatan }} <br> {{ $peg->pangkat }}</th>
            <th>{{ $peg->bulan }}</th>
            <th>{{ $peg->tahun }}</th>
            {!! $data->coba($peg->nip,$peg->bulan,$peg->tahun) !!}
        </tr>
        @endforeach
    </tbody>

</table>


@endsection