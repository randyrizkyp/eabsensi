@extends('templates.main')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container mb-4">
    <span>database : bkpsdm-eabsensi-2022</span>
    <h4 class="text-center mt-3">DATABASE SELURUH {{ $kriteria }} PENGGUNA EABSENSI KABUPATEN LAMPUNG UTARA TAHUN {{
        now()->format('Y')
        }}</h4>

    <table class="table table-stripped mb-4" id="myTable">
        <thead>

            <tr class="table-primary">
                <th width="5%">id</th>
                <th width="15%">nama</th>
                <th>NIP</th>
                <th>Pangkat</th>
                <th>Jabatan</th>
                <th>Unit_organisasi</th>
                <th>Tpp</th>
                <th>TMT_Absen</th>

            </tr>

        </thead>

        <tbody>

        </tbody>
    </table>
    <br><br>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
</script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $('#myTable').DataTable({
                processing : true,
                serverSide : true,
                ajax:'{{ route('data_eabsen_non') }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data: 'nama', name: 'nama'},
                    {data: 'dbpegawai_id', name: 'dbpegawai_id'},
                    {data: 'pangkat', name: 'pangkat'},
                    {data: 'jabatan', name: 'jabatan'},
                    {data: 'unit_organisasi', name: 'unit_organisasi'},
                    {data: 'tpp', name: 'tpp'},
                    {data: 'tmt_absen', name: 'tmt_absen'},
                    
                ]
            });
</script>

@endpush