@extends('templates.main')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')

<table class="table table-bordered table-striped mb-0 p-0">
    <thead>
        <tr class="table-success">
            <th width="4%">No</th>
            <th width="10%">Nama/NIP</th>

            <th width="10%">Tanggal</th>
            <th width="6%">Jam_M</th>
            <th width="10%">Keterangan <br>Catatan</th>
            <th width="10%">-TPP_M</th>
            <th width="8%">Foto</th>
            <th width="8%">Foto_b</th>
            <th width="8%">Status</th>
            <th width="8%">Validasi</th>

        </tr>
    </thead>
    <tbody>
        @foreach($absensi as $abs)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $abs->nama }} </br> {{ $abs->nip }}</td>
            <td>{{ $abs->tanggal }}-{{ $abs->bulan }}-{{ $abs->tahun }}</td>
            <td>{{ $abs->waktu }}</td>
            <td>{{ $abs->keterangan }}</td>
            <td>{{ $abs->pengurangan }}</td>
            <td><a class="gb_absen" href="{{ $situs }}"><img src="{{ $situs }}" width="75"></a></td>
            <td>{{ $abs->foto_b }}</td>
            <td>{{ $abs->konfirmasi }}</td>
            <td>{{ $abs->validasi }}</td>
        </tr>
        @endforeach
    </tbody>

</table>
@endsection

@push('script')
@endpush