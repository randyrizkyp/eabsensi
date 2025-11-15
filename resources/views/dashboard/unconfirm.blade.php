@extends('templatesLTE.main')
@section('content')
<div class="row mt-4">
    <div class="col mt-4">
        <div class="alert alert-danger mt-2">
            <h6 class="text-center">Daftar Absen Un_Confirmed</h6>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col mt-2 mb-2">
        <a href="?kriteria=absen_masuk"
            class="btn btn-sm mr-3 {{ $kriteria=='absen_masuk' ? 'btn-primary' : 'btn-secondary' }}">Absen Masuk</a>
        <a href="?kriteria=absen_pulang"
            class="btn btn-sm {{ $kriteria=='absen_pulang' ? 'btn-primary' : 'btn-secondary' }}">Absen Pulang</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12 p-0 mx-0">
        @if($kriteria=='absen_masuk')
        @include('dashboard.tabelUnconfirmMasuk')
        @else
        @include('dashboard.tabelUnconfirmPulang')

        @endif

    </div>
</div>



@endsection