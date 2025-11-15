@extends('templates.standard.main')
@section('content')
<style>
    .disp-wfo {
        width: 100%;

        overflow-x: scroll;

    }

    .disp-wfo::-webkit-scrollbar {
        width: 5px;
        height: 8px;
        border-top: 1px solid #302A2A;

        background-color: lightgray;

    }

    .disp-wfo::-webkit-scrollbar-thumb {
        width: 20px;
        background: #0A4C95;

    }

    .disp-wfo::-moz-scrollbar-track-piece {
        background-color: #C2D2E4;
    }

    .disp-wfo::-moz-scrollbar-thumb:horizontal {

        background-color: #0A4C95;
    }
</style>
<div class="px-1">

    <div class="row mt-2">

        <div class="col-md-12">
            <div class="bg-info">
                <h5 class="text-center pt-3">DAFTAR HADIR PEGAWAI

                </h5>
                <p class="text-center pb-2">
                    {{ config('global.nama_lain') }}: {{ $tanggal.'-'.$bulan.'-'.$tahun }}
                </p>
            </div>

            <div class="d-flex justify-content-center mb-2">
                <a href="/rekapPerorangan" class="btn btn-success">
                    <i class="fas fa-step-backward"></i> Kembali</a>
                <a href="/daftarHadir" class="btn btn-primary ml-2">
                    <i class="fas fa-sync-alt"></i> Refresh</a>
            </div>

            <div class="disp-wfo mb-2">
                <table id="tabel_pegawai" class="table table-bordered table-striped " width="100%"
                    style="font-size: 11px">
                    <thead>
                        <tr class="table-success">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Keterangan_M</th>
                            <th>Jam_M</th>
                            <th>Keterangan_p</th>
                            <th>Jam_P</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dapeg as $peg)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $peg->nama }}</td>
                            <td>{{ $peg->jabatan }}</td>
                            <td>
                                {{ $daftarHadir->where('nip',$peg->nip)->pluck('keterangan')->first() ?? '-' }}
                            </td>
                            <td>
                                {{ $daftarHadir->where('nip',$peg->nip)->where('waktu','!=',
                                '00:00:00')->pluck('waktu')->first() ?? '-' }}
                            </td>
                            <td>
                                {{ $daftarHadir->where('nip',$peg->nip)->pluck('keterangan_p')->first() ?? '-'
                                }}
                            </td>
                            <td>

                                {{ $daftarHadir->where('nip',$peg->nip)->where('pulang','!=',
                                '00:00:00')->pluck('pulang')->first() ?? '-' }}
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
@endsection
@push('script')
<script>
    $('#tabel_pegawai').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
</script>
@endpush