@extends('templates.main')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')

<div>database : BKPSDM Kab.LU</div>
<h3 class="text-center">DATABASE KEPEGAWAIAN (PPPK) TAHUN {{ now()->format('Y') }} <br>KABUPATEN LAMPUNG UTARA
</h3>
<div>
    <table class="table table-bordered border-dark table-sm " id="table_pppk" style="font-size: .75rem">
        <thead class="table-bordered border-dark table-info">
            <tr>
                <th style="vertical-align: middle">No</th>
                <th style="vertical-align: middle">NIP</th>
                <th style="vertical-align: middle">Nama</th>
                <th style="vertical-align: middle">Tempat_l</th>
                <th style="vertical-align: middle">Tgl_l</th>
                <th style="vertical-align: middle">Jenkel</th>
                <th style="vertical-align: middle">Agama</th>
                <th style="vertical-align: middle">Alamat</th>
                <th style="vertical-align: middle">Status</th>
                <th style="vertical-align: middle">No_SK</th>
                <th style="vertical-align: middle">Tgl_SK</th>
                <th style="vertical-align: middle">Gol_awal</th>
                <th style="vertical-align: middle">TMT_CPNS</th>
                {{-- <th style="vertical-align: middle">TMT_PNS</th> --}}
                {{-- <th style="vertical-align: middle">Gol_akhir</th> --}}
                {{-- <th style="vertical-align: middle">TMT_Gol</th> --}}
                <th style="vertical-align: middle">M_tahun</th>
                <th style="vertical-align: middle">M_bulan</th>
                <th style="vertical-align: middle">Gaji_pokok</th>
                <th style="vertical-align: middle">Tk_pendidikan</th>
                <th style="vertical-align: middle">Pendidikan</th>
                <th style="vertical-align: middle">No_Ijazah</th>
                <th style="vertical-align: middle">Thn_lulus</th>
                <th style="vertical-align: middle">Nama_Sklh/PT</th>
                <th style="vertical-align: middle">Jabatan</th>
                <th style="vertical-align: middle">Unit_organisasi</th>
                <th style="vertical-align: middle">No_usul</th>
                <th style="vertical-align: middle">Tgl_usul</th>


            </tr>

        </thead>
        <tbody class="table-group-divider">
        </tbody>

    </table>
    <br><br><br>
</div>

@endsection

@push('script')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script>
    $('#table_pppk').DataTable({
                scrollX : true,
                processing : true,
                serverSide : true,
                ajax:'{{ route('data_pppk') }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data: 'nip', name: 'nip'},
                    {data: 'nama', name: 'nama'},
                    {data: 'tempat', name: 'tempat'},
                    {data: 'tanggal', name: 'tanggal'},
                    {data: 'jenkel', name: 'jenkel'},
                    {data: 'agama', name: 'agama'},
                    {data: 'alamat', name: 'alamat'},
                    {data: 'status', name: 'status'},
                    {data: 'nomor_sk', name: 'nomor_sk'},
                    {data: 'tgl_sk', name: 'tgl_sk'},
                    {data: 'gol_awal', name: 'gol_awal'},
                    {data: 'tmt_cpns', name: 'tmt_cpns'},
                   
                   
                    {data: 'm_tahun', name: 'm_tahun'},
                    {data: 'm_bulan', name: 'm_bulan'},
                    {data: 'gaji_pokok', name: 'gaji_pokok'},
                    {data: 'tingkat_pendidikan', name: 'tingkat_pendidikan'},
                    {data: 'pendidikan', name: 'pendidikan'},
                    {data: 'nomor_ijazah', name: 'nomor_ijazah'},
                    {data: 'tahun_lulus', name: 'tahun_lulus'},
                    {data: 'nama_pt', name: 'nama_pt'},
                    {data: 'jabatan', name: 'jabatan'},
                    {data: 'unit_organisasi', name: 'unit_organisasi'},
                    {data: 'no_usul', name: 'no_usul'},
                    {data: 'tgl_usul', name: 'tgl_usul'},

                ]
            });
</script>
@endpush