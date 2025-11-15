@extends('templates.main')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

@endsection

@section('content')
<br><br>
<h4 class="mb-3">DAFTAR PEGAWAI YANG BERULANG TAHUN HARI INI</h4>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Edit Ucapan Ultah
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Ucapan Ulang Tahun</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="/img/bupati.jpg" class="img-responsive img-thumbnail" alt="">
                <img src="/img/wabup.png" class="img-responsive img-thumbnail ml-2" alt="">
                <p>Bupati dan Wakil Bupati Lampung Utara</p>
                <p>Mengucapkan Selamat Ulang Tahun, Semoga panjang umur, sehat selalu, dan senantiasa dalam lindungan
                    Allah Tuhan Yang Maha Kuasa, Amiin</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10">

        <table class="table table-stripped mb-4 " id="tableaja">
            <thead class="bg-primary text-white">
                <tr>
                    <th>No</th>
                    <th>Nama / NIP</th>
                    <th>Tanggal-lahir</th>
                    <th>Unit_Kerja</th>
                    <th>Ultah ke</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; ?>
                @foreach($pegawais as $pg)
                <?php $tgllahir = str_replace(' ','', $pg->tanggal );
                      $tgl = explode('-', $tgllahir);
                      $ultah = $tgl[0]."-".$tgl[1];
                      
                ?>
                @if($ultah == now()->format('d-m'))
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $pg->nama }}<br>NIP.{{ $pg->id }}</td>
                    <td>{{ $pg->tanggal }}</td>
                    <td>{{ $pg->unit_kerja }}</td>
                    <td>{{ str_replace('setelahnya','', now()->diffForHumans($tgllahir)) }}</td>
                </tr>
                <?php $i++; ?>
                @endif

                @endforeach

            </tbody>
    </div>
</div>
</table>
<br>
<br>
<br>
<br>
@endsection
@push('script')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
</script>
@endpush