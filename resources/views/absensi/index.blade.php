@php
use App\Http\Controllers\AbsensiController;
$data = new AbsensiController;
@endphp

@extends('templates.main')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<span>
    <a href="{{ $situs }}" class="text-primary">{{ $situs }}</a>

</span>
<h3 class="text-dark">REKAP ABSENSI {{ $perangkat }} BULAN {{ $bulan }}, TAHUN {{ $tahun }}</h3>
<div class="row mt-4 mb-4 d-inline-block">
    <div class="col">
        <a href="/dashboard?opd={{ $kode_pd }}" class="btn btn-secondary"><i class="fa fa-users"></i> Daftar Pegawai</a>
        <a href="" class="btn btn-primary"><i class="fa fa-table"></i> Rekap Absensi</a>
    </div>
</div>
<form action="/absensi">
    <div class="row mb-2">

        <div class="col-md-2">
            <input type="hidden" name="opd" value="{{ $kode_pd }}">
            <label class="sr-only" for="inlineFormInputGroupUsername">Username</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Bulan</div>
                </div>
                <select class="form-control" id="pilbul" name="pilbul">
                    <option value="01" {{ $bulan=='01' ? 'selected' :'' }}>Januari</option>
                    <option value="02" {{ $bulan=='02' ? 'selected' :'' }}>Februari</option>
                    <option value="03" {{ $bulan=='03' ? 'selected' :'' }}>Maret</option>
                    <option value="04" {{ $bulan=='04' ? 'selected' :'' }}>April</option>
                    <option value="05" {{ $bulan=='05' ? 'selected' :'' }}>Mei</option>
                    <option value="06" {{ $bulan=='06' ? 'selected' :'' }}>Juni</option>
                    <option value="07" {{ $bulan=='07' ? 'selected' :'' }}>Juli</option>
                    <option value="08" {{ $bulan=='08' ? 'selected' :'' }}>Agustus</option>
                    <option value="09" {{ $bulan=='09' ? 'selected' :'' }}>September</option>
                    <option value="10" {{ $bulan=='10' ? 'selected' :'' }}>Oktober</option>
                    <option value="11" {{ $bulan=='11' ? 'selected' :'' }}>November</option>
                    <option value="12" {{ $bulan=='12' ? 'selected' :'' }}>Desember</option>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Tahun</div>
                </div>
                <select class="form-control" id="piltah" name="piltah">
                    <option value="{{ now()->format('Y') }}" {{ $tahun==now()->format('Y') ? 'selected' :'' }}>
                        {{ now()->format('Y') }}
                    </option>
                    <option value="{{ now()->addYear(-1)->format('Y') }}" {{ $tahun==now()->addYear(-1)->format('Y') ?
                        'selected' :'' }}>
                        {{ now()->addYear(-1)->format('Y') }}
                    </option>
                    <option value="{{ now()->addYear(-2)->format('Y') }}" {{ $tahun==now()->addYear(-2)->format('Y') ?
                        'selected' :'' }}>
                        {{ now()->addYear(-2)->format('Y') }}
                    </option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Cari</button>
        </div>


    </div>
</form>
<table class="table table-bordered table stripped" id="tableaja">
    <thead class="table-primary">
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
            {{-- {!! $data->coba($peg->nip,$peg->bulan,$peg->tahun,$database) !!} --}}
            <th>
                {{ $absensi->where('nip', $peg->nip)->where('keterangan', 'hadir')->count()
                }}
            </th>
            <th>
                {{ $absensi->where('nip', $peg->nip)->where('keterangan', 'dinas luar')->count()
                }}
            </th>
            <th>
                {{ $absensi->where('nip', $peg->nip)->where('keterangan', 'sakit')->count()
                }}
            </th>
            <th>
                {{ $absensi->where('nip', $peg->nip)->where('keterangan', 'izin')->count()
                }}
            </th>
            <th>
                {{ $absensi->where('nip', $peg->nip)->where('keterangan', 'cuti')->count()
                }}
            </th>
            <th>
                {{ $absensi->where('nip', $peg->nip)->where('keterangan_p', 'tidak absen')->count()
                }}
            </th>
            <th>
                {{ $absensi->where('nip', $peg->nip)->where('keterangan', 'rejected')->count() +
                $absensi->where('nip', $peg->nip)->where('keterangan_p', 'rejected')->count()
                }}
            </th>
            <th>
                {{ $absensi->where('nip', $peg->nip)->where('keterangan', 'tanpa keterangan')->count()
                }}
            </th>
        </tr>
        @endforeach
    </tbody>

</table>


@endsection
@push('script')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready( function () {
        $('#tableaja').DataTable();

} );
</script>
@endpush