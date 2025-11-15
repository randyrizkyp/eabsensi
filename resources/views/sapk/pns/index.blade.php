@extends('templates.main')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
@endsection
@section('content')
<br>
<h3 class="text-center">DATABASE INDUK KEPEGAWAIAN (PNS) TAHUN {{ now()->format('Y') }} <br>KABUPATEN LAMPUNG UTARA</h3>
<div>
    <table class="table table-bordered border-dark table-sm " id="myTable" style="font-size: .75rem">
        <thead class="table-bordered border-dark table-info">
            <tr>
                {{-- <th rowspan=" 2" style="vertical-align: middle" align="center">Aksi</th> --}}
                <th style="vertical-align: middle">No</th>
                <th style="vertical-align: middle">NIP</th>
                <th align="center" style="vertical-align: middle">Glr_dpn</th>
                <th style="vertical-align: middle">Nama</th>
                <th align="center" style="vertical-align: middle">Glr_blkg</th>
                <th style="vertical-align: middle">Tempat_L</th>
                <th>Tgl_L</th>
                <th>L/P</th>
                <th style="vertical-align: middle" align="center">Gol_awal</th>
                <th style="vertical-align: middle">TMT_CPNS</th>
                <th style="vertical-align: middle">TMT_PNS</th>

                <th style="vertical-align: middle">Gol_akhir</th>
                <th style="vertical-align: middle">TMT_gol</th>
                <th class="text-center" style="vertical-align: middle">M_Tahun</th>
                <th class="text-center" style="vertical-align: middle">M_Bulan</th>
                <th>Esselon</th>
                <th style="text-align: center">Jabatan Struktural</th>
                <th>TMT_struk</th>
                <th align="center" class="text-center">Fung_tertentu</th>
                <th>Fung_umum</th>
                <th>TMT_Fung</th>

                <th style="vertical-align: middle">Unit_Kerja</th>
                <th style="vertical-align: middle">Unit_Kerja_Induk</th>
                <th style="vertical-align: middle">Pendidikan</th>
                <th style="vertical-align: middle">Tahun_lulus</th>
                <th style="vertical-align: middle">Stat_Huk</th>

            </tr>
            {{-- <tr>


                <th>Tahun</th>
                <th>Bulan</th>
                <th>Esselon</th>
                <th>TMT_struk</th>
                <th>Nama_Jab_Struk</th>
                <th>TMT_Fung</th>
                <th>Fungsional_tertentu</th>
                <th>Fungsional_umum</th>


            </tr> --}}
        </thead>
        <tbody>

            {{-- @foreach ($pegawais as $peg)

            <tr>
                <th>
                    <span class="d-flex">
                        <a href="" class="badge badge-primary"><i class="fa fa-pencil-square-o fs-6 "></i></a>
                        <a href="" class="badge badge-danger ml-2"><i class="fa fa-trash fs-6 "></i></a>
                    </span>
                </th>
                <th align="center">{{ $loop->iteration }}</th>
                <th align="center">{{ $peg->nip }}</th>
                <th>{{ $peg->glr_dpn . $peg->nama .','. $peg->glr_blkg }}</th>
                <th>{{ $peg->tempat }}, {{ $peg->tanggal }}</th>
                <th>{{ $peg->gol_awal }}</th>
                <th>{{ $peg->tmt_cpns }}</th>
                <th>{{ $peg->tmt_pns }}</th>
                <th>{{ $peg->lp }}</th>
                <th>{{ $peg->gol_akhir }}</th>
                <th>{{ $peg->tmt_gol }}</th>
                <th>{{ $peg->m_tahun }}</th>
                <th>{{ $peg->m_bulan }}</th>
                <th>{{ $peg->esselon }}</th>
                <th>{{ $peg->tmt_struk }}</th>
                <th>{{ $peg->nama_jab_struk }}</th>
                <th>{{ $peg->tmt_fung }}</th>
                <th>{{ $peg->nama_jab_fungt }}</th>
                <th>{{ $peg->nama_jab_fungu }}</th>
                <th>{{ $peg->unit_kerja }}</th>
                <th>{{ $peg->unit_induk }}</th>
                <th>{{ $peg->pendidikan }}</th>
                <th>{{ $peg->tahun_lulus }}</th>
                <th>{{ $peg->stat_huk }}</th>

            </tr>
            @endforeach --}}
        </tbody>

    </table>
    <br><br><br>
</div>
@endsection
@push('script')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script>
    $('#myTable').DataTable({
                scrollX : true,
                processing : true,
                serverSide : true,
                ajax:'{{ route('data') }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data: 'nip', name: 'nip'},
                    {data: 'glr_dpn', name: 'glr_dpn'},
                    {data: 'nama', name: 'nama'},
                    {data: 'glr_blkg', name: 'glr_blkg'},
                    {data: 'tempat', name: 'tempat'},
                    {data: 'tanggal', name: 'tanggal'},
                    {data: 'lp', name: 'lp'},
                    {data: 'gol_awal', name: 'gol_awal'},
                    {data: 'tmt_cpns', name: 'tmt_cpns'},
                    {data: 'tmt_pns', name: 'tmt_pns'},
                    {data: 'gol_akhir', name: 'gol_akhir'},
                    {data: 'tmt_gol', name: 'tmt_gol'},
                    {data: 'm_tahun', name: 'm_tahun'},
                    {data: 'm_bulan', name: 'm_bulan'},
                    {data: 'esselon', name: 'esselon'},
                    {data: 'nama_jab_struk', name: 'nama_jab_struk'},
                    {data: 'tmt_struk', name: 'tmt_struk'},
                    {data: 'nama_jab_fungt', name: 'nama_jab_fungt'},
                    {data: 'nama_jab_fungu', name: 'nama_jab_fungu'},
                    {data: 'tmt_fung', name: 'tmt_fung'},
                    {data: 'unit_kerja', name: 'unit_kerja'},
                    {data: 'unit_induk', name: 'unit_induk'},
                    {data: 'pendidikan', name: 'pendidikan'},
                    {data: 'tahun_lulus', name: 'tahun_lulus'},
                    {data: 'stat_huk', name: 'stat_huk'},

                ]
            });
</script>
@endpush